<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\FundDisbursement;
use App\Models\PurchaseProof;
use App\Models\BankAccount;

class OrganizationDisbursementController extends Controller
{
    // 1. Organisasi Mengajukan Pencairan Dana
    public function requestDisbursement(Request $request)
    {
        $organization = auth()->user()->organization;

        if (!$organization) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun Anda tidak terhubung ke data organisasi.'
            ], 403);
        }

        $organizationId = $organization->id;

        // Guardrail: mencegah submit baru saat masih ada riwayat yang belum selesai
        $hasPendingReports = FundDisbursement::whereHas('campaign', function ($q) use ($organizationId) {
                $q->where('id_organisasi', $organizationId);
            })
            ->whereIn('status', ['menunggu', 'diterima'])
            ->where(function ($query) {
                $query->doesntHave('purchaseProofs')
                    ->orWhereHas('purchaseProofs', function ($sub) {
                        $sub->where('status', '!=', 'diterima');
                    });
            })->exists();

        if ($hasPendingReports) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengajuan diblokir: Anda masih memiliki pencairan yang belum selesai atau bukti belum diverifikasi.'
            ], 403);
        }

        $request->validate([
            'id_campaign'      => 'required|exists:campaigns,id',
            'alokasi_dana'     => 'required|string',
            'nominal_diajukan' => 'required|numeric|min:10000',
            'alasan'           => 'required|string',
            'lampiran'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $bankAccount = BankAccount::where('id_organisasi', $organizationId)
            ->where('status_verifikasi', 'diterima')
            ->first();

        if (!$bankAccount) {
            return response()->json(['status' => 'error', 'message' => 'Rekening bank tidak valid atau belum diverifikasi.'], 400);
        }

        return DB::transaction(function () use ($request, $organizationId, $bankAccount) {
            $lampiranPath = $request->hasFile('lampiran')
                ? $request->file('lampiran')->store('disbursements/lampiran', 'public')
                : null;

            $disbursement = FundDisbursement::create([
                'id'                 => (string) Str::uuid(),
                'id_campaign'        => $request->id_campaign,
                'id_bank_account'    => $bankAccount->id,
                'alokasi_dana'       => $request->alokasi_dana,
                'nominal_diajukan'   => $request->nominal_diajukan,
                'alasan'             => $request->alasan,
                'lampiran_pendukung' => $lampiranPath,
                'status'             => 'menunggu'
            ]);

            return response()->json(['status' => 'success', 'data' => $disbursement], 201);
        });
    }

    // 2. Organisasi Mengunggah Bukti Pengeluaran (Purchase Proof)
    public function uploadProof(Request $request, $disbursementId)
    {
        $request->validate([
            'bukti_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'nominal'    => 'required|numeric',
            'deskripsi'  => 'required|string'
        ]);

        $disbursement = FundDisbursement::findOrFail($disbursementId);

        if ($disbursement->status !== 'diterima') {
            return response()->json([
                'status' => 'error',
                'message' => 'Bukti pengeluaran hanya dapat diunggah setelah pencairan dana disetujui admin.'
            ], 403);
        }

        return DB::transaction(function () use ($request, $disbursement) {
            $path = $request->file('bukti_file')->store('purchase-proofs', 'public');

            $proof = PurchaseProof::create([
                'id'           => (string) Str::uuid(),
                'id_pencairan' => $disbursement->id,
                'lokasi_file'  => $path,
                'nominal'      => $request->nominal,
                'deskripsi'    => $request->deskripsi,
                'status'       => 'menunggu',
                'uploaded_at'  => now(),
            ]);

            return response()->json(['status' => 'success', 'data' => $proof], 201);
        });
    }
}