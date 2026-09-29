<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use Illuminate\Http\Request;

class OrganizationVisitController extends Controller
{
    public const STATUS_FILTERS = ['terkirim', 'dikonfirmasi', 'ditolak', 'selesai'];

    // Halaman daftar permintaan kunjungan ke organisasi (web)
    public function index(Request $request)
    {
        $organizationId = $request->user()->organization->id;

        $visits = Visit::with('donor.user:id,nama')
            ->where('id_organisasi', $organizationId)
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->whereHas('donor.user', fn ($q) => $q->where('nama', 'like', '%' . $request->q . '%'));
            })
            ->when(
                in_array($request->status, self::STATUS_FILTERS, true),
                fn ($q) => $q->where('status', $request->status)
            )
            ->when($request->filled('tanggal'), fn ($q) => $q->whereDate('tanggal_kunjungan', $request->tanggal))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('organisasi.kunjungan', compact('visits'));
    }

    // Halaman detail satu permintaan kunjungan (web)
    public function show(Request $request, string $visitId)
    {
        $visit = Visit::with(['donor.user:id,nama,email', 'donor.companyPremium'])
            ->where('id_organisasi', $request->user()->organization->id)
            ->findOrFail($visitId);

        return view('organisasi.kunjungan-detail', compact('visit'));
    }
}
