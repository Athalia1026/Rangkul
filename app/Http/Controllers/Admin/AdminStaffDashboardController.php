<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\FundDisbursement;
use App\Models\ProofVerification;
use App\Models\PurchaseProof;

class AdminStaffDashboardController extends Controller
{
    // Data beranda staf: ringkasan antrian, pendaftaran organisasi, dan bukti penyaluran yang menunggu staf
    public function index()
    {
        $organizations = Organization::query()
            ->where('verification_status', 'menunggu')
            ->orderBy('created_at')
            ->get();

        $proofs = PurchaseProof::with('fundDisbursement.campaign.organization')
            ->menungguStaf()
            ->orderBy('uploaded_at')
            ->get();

        $disbursements = FundDisbursement::with(['campaign.organization'])
            ->where('status', 'menunggu')
            ->where('review_stage', FundDisbursement::REVIEW_STAGE_STAFF)
            ->latest()
            ->get();

        $pendingTransfers = FundDisbursement::with('campaign.organization')
            ->where('status', 'diterima')
            ->where('review_stage', FundDisbursement::REVIEW_STAGE_TRANSFER)
            ->where('transfer_status', 'menunggu_transfer_manual')
            ->latest('verified_at')
            ->get();

        $completedVerifications = Organization::whereIn('verification_status', ['disetujui', 'ditolak'])->count()
            + ProofVerification::whereNotNull('staff_id')->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'pending_organizations' => $organizations->count(),
                    'pending_proofs' => $proofs->count(),
                    'pending_disbursements' => $disbursements->count(),
                    'pending_transfers' => $pendingTransfers->count(),
                    'completed_verifications' => $completedVerifications,
                ],
                'organizations' => $organizations->map(fn (Organization $organization) => [
                    'id' => $organization->id,
                    'name' => $organization->nama_lembaga,
                    'type' => $organization->tipe,
                    'city' => $organization->kota,
                    'registered_at' => optional($organization->created_at)->format('d/m/Y'),
                ])->values(),
                'proofs' => $proofs->map(fn (PurchaseProof $proof) => [
                    'id' => $proof->id,
                    'organization_name' => $proof->fundDisbursement?->campaign?->organization?->nama_lembaga ?? '-',
                    'description' => $proof->deskripsi ?: ($proof->fundDisbursement?->alokasi_dana ?? '-'),
                    'amount' => (float) $proof->nominal,
                ])->values(),
                'disbursements' => $disbursements->map(fn (FundDisbursement $disbursement) => [
                    'id' => $disbursement->id,
                    'organization_name' => $disbursement->campaign?->organization?->nama_lembaga ?? '-',
                    'campaign_title' => $disbursement->campaign?->judul ?? '-',
                    'amount' => (float) $disbursement->nominal_diajukan,
                    'submitted_at' => optional($disbursement->created_at)->format('d/m/Y'),
                ])->values(),
                'pending_transfers' => $pendingTransfers->map(fn (FundDisbursement $disbursement) => [
                    'id' => $disbursement->id,
                    'organization_name' => $disbursement->campaign?->organization?->nama_lembaga ?? '-',
                    'campaign_title' => $disbursement->campaign?->judul ?? '-',
                    'amount' => (float) ($disbursement->nominal_dicairkan ?? $disbursement->nominal_diajukan),
                    'approved_at' => optional($disbursement->verified_at)->format('d/m/Y'),
                ])->values(),
            ],
        ]);
    }
}
