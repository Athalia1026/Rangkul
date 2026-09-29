<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Campaign;
use App\Models\PurchaseProof;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public const STATUS_FILTERS = ['aktif', 'menunggu', 'disalurkan', 'selesai', 'ditolak'];

    // Halaman daftar kampanye milik organisasi (web)
    public function index(Request $request)
    {
        $organization = $request->user()->organization;

        $campaigns = Campaign::where('id_organisasi', $organization->id)
            ->withFinancialSummary()
            ->when($request->filled('q'), fn ($q) => $q->where('judul', 'like', '%' . $request->q . '%'))
            ->when(
                in_array($request->status, self::STATUS_FILTERS, true),
                fn ($q) => $q->where('status', $request->status)
            )
            ->latest()
            ->paginate(4)
            ->withQueryString();

        return view('organisasi.kampanye', compact('organization', 'campaigns'));
    }

    // Halaman detail kampanye beserta pencairan dana & bukti penyaluran (web)
    public function show(Request $request, string $campaignId)
    {
        $organization = $request->user()->organization;

        $campaign = Campaign::where('id_organisasi', $organization->id)
            ->withFinancialSummary()
            ->findOrFail($campaignId);

        $jumlahDonatur = $campaign->donations()
            ->where('status', 'sudah_bayar')
            ->distinct()
            ->count('id_donatur');

        $disbursements = $campaign->fundDisbursements()
            ->with('bankAccount')
            ->latest()
            ->get();

        $proofs = PurchaseProof::with('fundDisbursement:id,alokasi_dana')
            ->whereIn('id_pencairan', $disbursements->pluck('id'))
            ->latest('uploaded_at')
            ->get();

        $bankAccount = BankAccount::where('id_organisasi', $organization->id)->first();

        return view('organisasi.kampanye-detail', [
            'organization'       => $organization,
            'campaign'           => $campaign,
            'jumlahDonatur'      => $jumlahDonatur,
            'disbursements'      => $disbursements,
            'latestDisbursement' => $disbursements->first(),
            'proofs'             => $proofs,
            'bankAccount'        => $bankAccount,
        ]);
    }

    // Simpan perubahan deskripsi kampanye dari tab Overview (web)
    public function updateDescription(Request $request, string $campaignId)
    {
        $campaign = Campaign::where('id_organisasi', $request->user()->organization->id)
            ->findOrFail($campaignId);

        $validated = $request->validate([
            'deskripsi' => 'required|string',
        ]);

        $campaign->update($validated);

        return redirect()
            ->route('organisasi.kampanye.detail', $campaign->id)
            ->with('success', 'Deskripsi kampanye berhasil disimpan.');
    }

    public function store(Request $request)
    {
        $organization = $request->user()->organization;

        // Validasi: Organisasi wajib terverifikasi 'disetujui' terlebih dahulu
        if ($organization->verification_status !== 'disetujui') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Organisasi Anda belum terverifikasi oleh Admin.'
            ], 403);
        }

        $request->validate([
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'required|string',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'target_dana'     => 'required|numeric|min:10000',
            'id_categories'   => 'required|exists:categories,id',
            'foto_cover'      => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('foto_cover')->store('campaigns/covers', 'public');

        $campaign = Campaign::create([
            'id_organisasi'   => $organization->id,
            'judul'           => $request->judul,
            'deskripsi'       => $request->deskripsi,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'target_dana'     => $request->target_dana,
            'id_categories'   => $request->id_categories,
            'foto_cover'      => $path,
            'status'          => 'menunggu', // Default menunggu verifikasi admin
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Campaign berhasil dibuat dan sedang menunggu verifikasi admin.',
            'data'    => $campaign
        ], 201);
    }
}