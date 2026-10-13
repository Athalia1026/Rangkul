<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
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

        $completedVerifications = Organization::whereIn('verification_status', ['disetujui', 'ditolak'])->count()
            + ProofVerification::whereNotNull('staff_id')->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'pending_organizations' => $organizations->count(),
                    'pending_proofs' => $proofs->count(),
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
            ],
        ]);
    }
}
