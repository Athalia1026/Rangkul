<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PurchaseProofVerificationMail;
use App\Models\PurchaseProof;
use App\Models\ProofVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminProofVerificationController extends Controller
{
    // Verifikasi Berlapis (Staf & Manajer) untuk Bukti Pengeluaran
    public function verifyProof(Request $request, $proofId)
    {
        $request->validate([
            'status'  => 'required|in:diterima,ditolak',
            'catatan' => 'nullable|string'
        ]);

        $admin = auth()->user();
        $adminProfile = $admin?->adminProfile;
        $role = $adminProfile?->tipe;

        return DB::transaction(function () use ($request, $proofId, $adminProfile, $role) {
            $proof = PurchaseProof::with(['fundDisbursement.campaign.organization.user'])->findOrFail($proofId);

            $verification = ProofVerification::firstOrCreate(
                ['id_bukti' => $proofId],
                [
                    'id' => (string) Str::uuid(),
                    'staff_id' => null,
                    'manager_id' => null,
                    'status' => 'menunggu',
                    'catatan' => null,
                ]
            );

            if ($role === 'staff') {
                $verification->update([
                    'staff_id' => $adminProfile->id,
                    'status' => $request->status === 'ditolak' ? 'ditolak' : 'menunggu',
                    'catatan' => $request->catatan,
                ]);

                if ($request->status === 'ditolak') {
                    $proof->update([
                        'status' => 'ditolak',
                        'alasan_tolak' => $request->catatan,
                    ]);
                }

                return response()->json(['status' => 'success', 'message' => 'Verifikasi Staff berhasil disimpan. Menunggu Manajer.']);
            }

            if ($role === 'manager') {
                if (!$verification->staff_id || $verification->status === 'ditolak') {
                    return response()->json(['status' => 'error', 'message' => 'Validasi gagal: Membutuhkan persetujuan Staff terlebih dahulu.'], 403);
                }

                $verification->update([
                    'manager_id' => $adminProfile->id,
                    'status' => $request->status,
                    'catatan' => $request->catatan,
                    'verified_at' => now(),
                ]);

                $proof->update([
                    'status' => $request->status,
                    'alasan_tolak' => $request->status === 'ditolak' ? $request->catatan : null,
                ]);

                $organization = $proof->fundDisbursement?->campaign?->organization;
                if ($organization && $organization->user) {
                    try {
                        Mail::to($organization->user->email)->send(new PurchaseProofVerificationMail($proof));
                    } catch (\Exception $e) {
                        \Log::error('SMTP Mailtrap Proof Verification Failed: ' . $e->getMessage());
                    }
                }

                return response()->json(['status' => 'success', 'message' => 'Verifikasi Final Manager berhasil disimpan.']);
            }

            return response()->json(['status' => 'error', 'message' => 'Role admin tidak dikenali.'], 403);
        });
    }
}