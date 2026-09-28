<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DisbursementVerificationMail;
use App\Models\FundDisbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminDisbursementVerificationController extends Controller
{
    public function index()
    {
        $disbursements = FundDisbursement::with(['campaign.organization.user', 'bankAccount'])
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $disbursements,
        ]);
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'alasan_tolak' => 'required_if:status,ditolak|nullable|string',
            'nominal_dicairkan' => 'nullable|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $disbursement = FundDisbursement::with(['campaign.organization.user'])->findOrFail($id);
            $adminId = auth()->user()->adminProfile?->id;

            if ($disbursement->status !== 'menunggu') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pencairan dana ini sudah pernah diverifikasi sebelumnya.'
                ], 409);
            }

            $nominalDicairkan = $request->filled('nominal_dicairkan')
                ? $request->nominal_dicairkan
                : $disbursement->nominal_diajukan;

            $disbursement->update([
                'status' => $request->status,
                'alasan_tolak' => $request->status === 'ditolak' ? $request->alasan_tolak : null,
                'verified_by' => $adminId,
                'verified_at' => now(),
                'nominal_dicairkan' => $request->status === 'diterima' ? $nominalDicairkan : null,
                'transfer_method' => $request->status === 'diterima' ? 'manual_bank' : null,
                'transfer_status' => $request->status === 'diterima' ? 'menunggu_transfer_manual' : null,
                'transaction_id' => $request->status === 'diterima' ? ('MANUAL-' . strtoupper(substr(md5((string) now()), 0, 8))) : $disbursement->transaction_id,
            ]);

            $organization = $disbursement->campaign?->organization;
            if ($organization && $organization->user) {
                try {
                    Mail::to($organization->user->email)->send(new DisbursementVerificationMail($disbursement->fresh()));
                } catch (\Exception $e) {
                    \Log::error('SMTP Mailtrap Disbursement Verification Failed: ' . $e->getMessage());
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Status pencairan dana berhasil diperbarui. Proses transfer manual menunggu admin untuk dicatat setelah transfer rekening dilakukan.',
                'data' => $disbursement->fresh(),
            ]);
        });
    }

    public function completeManualTransfer(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan' => 'nullable|string',
            'referensi_transfer' => 'nullable|string|max:255',
        ]);

        $disbursement = FundDisbursement::findOrFail($id);

        if ($disbursement->status !== 'diterima') {
            return response()->json([
                'status' => 'error',
                'message' => 'Transfer manual hanya bisa dicatat untuk pencairan yang sudah disetujui admin.'
            ], 409);
        }

        $proofPath = $request->hasFile('bukti_transfer')
            ? $request->file('bukti_transfer')->store('disbursements/manual-transfer', 'public')
            : $disbursement->manual_transfer_proof;

        $disbursement->update([
            'transfer_method' => 'manual_bank',
            'transfer_status' => 'selesai',
            'manual_transfer_proof' => $proofPath,
            'transaction_id' => $request->filled('referensi_transfer')
                ? $request->referensi_transfer
                : ($disbursement->transaction_id ?? 'MANUAL-' . strtoupper(substr(md5((string) now()), 0, 8))),
            'paid_at' => now(),
            'transfer_note' => $request->catatan ?? $disbursement->transfer_note,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transfer manual ke rekening organisasi telah dicatat dan diselesaikan.',
            'data' => $disbursement->fresh(),
        ]);
    }
}
