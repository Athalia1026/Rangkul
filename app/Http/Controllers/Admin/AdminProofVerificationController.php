<?php

namespace App\Http\Controllers\Admin;

use App\Services\NotificationService;
use App\Http\Controllers\Controller;
use App\Mail\PurchaseProofVerificationMail;
use App\Models\PurchaseProof;
use App\Models\ProofVerification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminProofVerificationController extends Controller
{
    // Detail satu bukti penyaluran untuk halaman detail ajuan staf
    public function show($proofId)
    {
        $proof = PurchaseProof::with(['fundDisbursement.campaign.organization', 'proofVerification'])->findOrFail($proofId);
        $disbursement = $proof->fundDisbursement;
        $verification = $proof->proofVerification;

        // Tahap verifikasi: staf memeriksa lebih dulu, lalu manajer memberi keputusan akhir.
        $stage = match (true) {
            $proof->status !== 'menunggu' => $proof->status,
            (bool) $verification?->staff_id => 'menunggu_manajer',
            default => 'menunggu_staf',
        };

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $proof->id,
                'status' => $proof->status,
                'stage' => $stage,
                'organization_name' => $disbursement?->campaign?->organization?->nama_lembaga ?? '-',
                'campaign_title' => $disbursement?->campaign?->judul,
                'alokasi_dana' => $disbursement?->alokasi_dana,
                'deskripsi' => $proof->deskripsi,
                'amount' => (float) $proof->nominal,
                'alasan_tolak' => $proof->alasan_tolak,
                'uploaded_at' => $proof->uploaded_at ? Carbon::parse($proof->uploaded_at)->format('d/m/Y') : null,
                'attachment' => $proof->lokasi_file ? [
                    'url' => asset('storage/' . ltrim($proof->lokasi_file, '/')),
                    'is_image' => (bool) preg_match('/\.(jpe?g|png|gif|webp)$/i', $proof->lokasi_file),
                ] : null,
            ],
        ]);
    }

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
                if ($proof->status !== 'menunggu' || $verification->staff_id) {
                    return response()->json(['status' => 'error', 'message' => 'Bukti penyaluran ini sudah pernah diverifikasi Staff sebelumnya.'], 409);
                }

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

                    app(NotificationService::class)->proofVerified($proof);
                }

                return response()->json(['status' => 'success', 'message' => 'Verifikasi Staff berhasil disimpan. Menunggu Manajer.']);
            }

            if ($role === 'manager') {
                if ($proof->status !== 'menunggu') {
                    return response()->json(['status' => 'error', 'message' => 'Bukti penyaluran ini sudah diverifikasi final sebelumnya.'], 409);
                }

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

                app(NotificationService::class)->proofVerified($proof);

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