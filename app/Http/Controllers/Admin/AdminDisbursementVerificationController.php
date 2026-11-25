<?php

namespace App\Http\Controllers\Admin;

use App\Services\NotificationService;
use App\Http\Controllers\Controller;
use App\Mail\DisbursementVerificationMail;
use App\Models\Campaign;
use App\Models\FundDisbursement;
use App\Support\OrgFormat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AdminDisbursementVerificationController extends Controller
{
    public function index()
    {
        $disbursements = FundDisbursement::with(['campaign.organization.user', 'bankAccount'])
            ->where('status', 'menunggu')
            ->where('review_stage', FundDisbursement::REVIEW_STAGE_MANAGER)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $disbursements,
        ]);
    }

    public function pendingManualTransfers()
    {
        $disbursements = FundDisbursement::with('campaign.organization')
            ->where('status', 'diterima')
            ->where('review_stage', FundDisbursement::REVIEW_STAGE_TRANSFER)
            ->where('transfer_status', 'menunggu_transfer_manual')
            ->latest('verified_at')
            ->get()
            ->map(fn (FundDisbursement $disbursement) => [
                'id' => $disbursement->id,
                'organization_name' => $disbursement->campaign?->organization?->nama_lembaga ?? '-',
                'campaign_title' => $disbursement->campaign?->judul ?? '-',
                'amount' => (float) ($disbursement->nominal_dicairkan ?? $disbursement->nominal_diajukan),
                'approved_at' => optional($disbursement->verified_at)->format('d/m/Y'),
            ]);

        return response()->json(['status' => 'success', 'data' => $disbursements]);
    }

    public function managerTransfers()
    {
        $disbursements = FundDisbursement::with('campaign.organization')
            ->where('status', 'diterima')
            ->whereIn('review_stage', [
                FundDisbursement::REVIEW_STAGE_TRANSFER,
                FundDisbursement::REVIEW_STAGE_COMPLETED,
            ])
            ->whereIn('transfer_status', ['menunggu_transfer_manual', 'selesai'])
            ->latest('verified_at')
            ->get()
            ->map(fn (FundDisbursement $disbursement) => [
                'id' => $disbursement->id,
                'organization_name' => $disbursement->campaign?->organization?->nama_lembaga ?? '-',
                'campaign_title' => $disbursement->campaign?->judul ?? '-',
                'amount' => (float) ($disbursement->nominal_dicairkan ?? $disbursement->nominal_diajukan),
                'transfer_status' => $disbursement->transfer_status,
                'approved_at' => optional($disbursement->verified_at)->format('d/m/Y'),
                'paid_at' => optional($disbursement->paid_at)->format('d/m/Y H:i'),
            ]);

        return response()->json(['status' => 'success', 'data' => $disbursements]);
    }

    // Detail satu pengajuan pencairan untuk halaman detail pengajuan manajer
    public function show($id)
    {
        $disbursement = FundDisbursement::with(['campaign.organization', 'verifier.user', 'staffReviewer.user', 'purchaseProofs', 'bankAccount'])
            ->findOrFail($id);
        $campaignBalance = Campaign::withFinancialSummary()->find($disbursement->id_campaign)?->saldoTersisa() ?? 0;

        // Bukti pembelian: lampiran saat pengajuan (privat, lewat link bertanda tangan), atau bukti
        // pembelian terbaru yang diunggah setelah dana cair (publik, juga ditampilkan ke donatur).
        $latestProof = $disbursement->purchaseProofs->sortByDesc('uploaded_at')->first()?->lokasi_file;
        $attachment = $disbursement->lampiran_pendukung
            ? ['path' => $disbursement->lampiran_pendukung, 'url' => $disbursement->fileUrl('lampiran')]
            : ($latestProof ? ['path' => $latestProof, 'url' => asset('storage/' . ltrim($latestProof, '/'))] : null);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $disbursement->id,
                'status' => $disbursement->status,
                'review_stage' => $disbursement->review_stage,
                'transfer_status' => $disbursement->transfer_status,
                'organization_name' => $disbursement->campaign?->organization?->nama_lembaga ?? '-',
                'campaign_title' => $disbursement->campaign?->judul,
                'amount' => (float) $disbursement->nominal_diajukan,
                'approved_amount' => (float) ($disbursement->nominal_dicairkan ?? $disbursement->nominal_diajukan),
                // Saldo kampanye yang belum dicairkan, agar manajer bisa membandingkan dengan nominal diajukan.
                'campaign_balance' => (float) $campaignBalance,
                'destination_account' => $disbursement->destinationAccount() + [
                    'status' => $disbursement->bankAccount?->status_verifikasi,
                ],
                'alokasi_dana' => $disbursement->alokasi_dana,
                'alasan' => $disbursement->alasan,
                'alasan_tolak' => $disbursement->alasan_tolak,
                'verifier_name' => $disbursement->verifier?->user?->nama,
                'staff_reviewer_name' => $disbursement->staffReviewer?->user?->nama,
                'staff_reviewed_at' => optional($disbursement->staff_reviewed_at)->format('d/m/Y H:i'),
                'submitted_at' => optional($disbursement->created_at)->format('d/m/Y'),
                'manual_transfer_proof_url' => $disbursement->manual_transfer_proof
                    ? $disbursement->fileUrl('bukti-transfer')
                    : null,
                'transaction_id' => $disbursement->transaction_id,
                'paid_at' => optional($disbursement->paid_at)->format('d/m/Y H:i'),
                'transfer_note' => $disbursement->transfer_note,
                'attachment' => $attachment ? [
                    'url' => $attachment['url'],
                    'is_image' => (bool) preg_match('/\.(jpe?g|png|gif|webp)$/i', $attachment['path']),
                ] : null,
            ],
        ]);
    }

    public function staffReview(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'alasan_tolak' => 'required_if:status,ditolak|nullable|string',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $disbursement = FundDisbursement::with(['campaign.organization'])->lockForUpdate()->findOrFail($id);

            if ($disbursement->status !== 'menunggu' || $disbursement->review_stage !== FundDisbursement::REVIEW_STAGE_STAFF) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pengajuan ini sudah diproses oleh staff atau bukan dalam tahap verifikasi staff.',
                ], 409);
            }

            if ($request->status === 'diterima' && !$disbursement->lampiran_pendukung) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pengajuan tidak dapat diteruskan ke manager tanpa lampiran pendukung.',
                ], 422);
            }

            $disbursement->update([
                'review_stage' => $request->status === 'diterima'
                    ? FundDisbursement::REVIEW_STAGE_MANAGER
                    : FundDisbursement::REVIEW_STAGE_REJECTED,
                'status' => $request->status === 'ditolak' ? 'ditolak' : 'menunggu',
                'alasan_tolak' => $request->status === 'ditolak' ? $request->alasan_tolak : null,
                'staff_reviewed_by' => auth()->user()->adminProfile?->id,
                'staff_reviewed_at' => now(),
            ]);

            if ($request->status === 'diterima') {
                app(NotificationService::class)->disbursementForwardedToManager($disbursement);
            } else {
                app(NotificationService::class)->disbursementVerified($disbursement);
            }

            return response()->json([
                'status' => 'success',
                'message' => $request->status === 'diterima'
                    ? 'Pengajuan lolos verifikasi staff dan diteruskan ke manager.'
                    : 'Pengajuan pencairan ditolak oleh staff.',
                'data' => $disbursement->fresh(),
            ]);
        });
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'alasan_tolak' => 'required_if:status,ditolak|nullable|string',
            'nominal_dicairkan' => 'nullable|numeric|min:1',
        ]);

        return DB::transaction(function () use ($request, $id) {
            // Dikunci agar dua persetujuan yang dikirim bersamaan tidak sama-sama lolos cek status.
            $disbursement = FundDisbursement::with(['campaign.organization.user', 'bankAccount'])->lockForUpdate()->findOrFail($id);
            $adminId = auth()->user()->adminProfile?->id;

            if ($disbursement->status !== 'menunggu' || $disbursement->review_stage !== FundDisbursement::REVIEW_STAGE_MANAGER) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pengajuan ini belum disetujui staff untuk diteruskan ke manager, atau sudah pernah diverifikasi.'
                ], 409);
            }

            $nominalDicairkan = $request->filled('nominal_dicairkan')
                ? $request->nominal_dicairkan
                : $disbursement->nominal_diajukan;

            if ($request->status === 'diterima') {
                if ($rejection = $this->approvalRejection($disbursement, (float) $nominalDicairkan)) {
                    return $rejection;
                }
            }

            $account = $disbursement->bankAccount;

            $disbursement->update([
                // Salinan rekening saat disetujui: transfer tetap ke rekening ini walau data lembaga berubah.
                'rekening_bank' => $request->status === 'diterima' ? $account?->bank : null,
                'rekening_nomor' => $request->status === 'diterima' ? $account?->no_rekening : null,
                'rekening_pemilik' => $request->status === 'diterima' ? $account?->pemilik_rekening : null,
                'status' => $request->status,
                'review_stage' => $request->status === 'diterima'
                    ? FundDisbursement::REVIEW_STAGE_TRANSFER
                    : FundDisbursement::REVIEW_STAGE_REJECTED,
                'alasan_tolak' => $request->status === 'ditolak' ? $request->alasan_tolak : null,
                'verified_by' => $adminId,
                'verified_at' => now(),
                'nominal_dicairkan' => $request->status === 'diterima' ? $nominalDicairkan : null,
                'transfer_method' => $request->status === 'diterima' ? 'manual_bank' : null,
                'transfer_status' => $request->status === 'diterima' ? 'menunggu_transfer_manual' : null,
                'transaction_id' => $request->status === 'diterima' ? ('MANUAL-' . strtoupper(substr(md5((string) now()), 0, 8))) : $disbursement->transaction_id,
            ]);

            app(NotificationService::class)->disbursementVerified($disbursement);

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
                'message' => 'Pengajuan pencairan disetujui manager. Staff dapat melakukan transfer dan mencatat bukti transfer.',
                'data' => $disbursement->fresh(),
            ]);
        });
    }

    public function completeManualTransfer(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan' => 'nullable|string',
            'referensi_transfer' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('fund_disbursements', 'transaction_id')->ignore($id, 'id'),
            ],
        ]);

        return DB::transaction(function () use ($request, $id) {
            $disbursement = FundDisbursement::lockForUpdate()->findOrFail($id);

            if ($disbursement->status !== 'diterima'
                || $disbursement->review_stage !== FundDisbursement::REVIEW_STAGE_TRANSFER) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transfer manual hanya bisa dicatat oleh staff untuk pencairan yang sudah disetujui manager.'
                ], 409);
            }

            // Transfer yang sudah dicatat tidak boleh ditimpa (tanggal bayar, referensi, dan bukti transfer).
            if ($disbursement->transfer_status === 'selesai') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Transfer manual untuk pencairan ini sudah dicatat sebelumnya.'
                ], 409);
            }

            $proofPath = $request->file('bukti_transfer')->store('disbursements/manual-transfer', FundDisbursement::DISK);

            $disbursement->update([
                'transfer_method' => 'manual_bank',
                'transfer_status' => 'selesai',
                'review_stage' => FundDisbursement::REVIEW_STAGE_COMPLETED,
                'manual_transfer_proof' => $proofPath,
                'transaction_id' => $request->filled('referensi_transfer')
                    ? $request->referensi_transfer
                    : ($disbursement->transaction_id ?? 'MANUAL-' . strtoupper(substr(md5((string) now()), 0, 8))),
                'paid_at' => now(),
                'transfer_note' => $request->catatan ?? $disbursement->transfer_note,
            ]);

            app(NotificationService::class)->disbursementTransferred($disbursement);

            return response()->json([
                'status' => 'success',
                'message' => 'Transfer manual ke rekening organisasi telah dicatat dan diselesaikan.',
                'data' => $disbursement->fresh(),
            ]);
        });
    }

    /**
     * Syarat persetujuan pencairan: nominal tidak melebihi yang diajukan maupun saldo kampanye,
     * dan rekening tujuan masih terverifikasi (rekening yang diganti lewat pengajuan ulang
     * pendaftaran harus diverifikasi lagi sebelum menerima dana).
     */
    private function approvalRejection(FundDisbursement $disbursement, float $nominal): ?JsonResponse
    {
        if ($nominal > (float) $disbursement->nominal_diajukan) {
            return $this->unprocessable('Nominal yang dicairkan tidak boleh melebihi nominal yang diajukan (' . OrgFormat::rupiah($disbursement->nominal_diajukan) . ').');
        }

        $balance = Campaign::withFinancialSummary()->find($disbursement->id_campaign)?->saldoTersisa() ?? 0;
        if ($nominal > $balance) {
            return $this->unprocessable('Nominal pencairan melebihi saldo kampanye yang tersedia (' . OrgFormat::rupiah($balance) . ').');
        }

        if ($disbursement->bankAccount?->status_verifikasi !== 'diterima') {
            return response()->json([
                'status' => 'error',
                'message' => 'Rekening tujuan lembaga belum terverifikasi. Verifikasi rekening terlebih dahulu sebelum menyetujui pencairan.',
            ], 409);
        }

        return null;
    }

    private function unprocessable(string $message): JsonResponse
    {
        return response()->json(['status' => 'error', 'message' => $message], 422);
    }
}
