<?php

namespace App\Http\Controllers\Donors;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Models\VisitDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class VisitController extends Controller
{
    // 1. Donatur Mengajukan Kunjungan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_organisasi' => [
                'required',
                Rule::exists('organizations', 'id')->where(function ($query) {
                    $query->where('verification_status', 'disetujui');
                }),
            ],
            'tanggal_kunjungan' => 'required|date|after_or_equal:today',
            'waktu_kunjungan'   => 'required',
            'pengunjung'        => 'required|integer|min:1',
            'pesan_donatur'     => 'nullable|string|max:255',
        ], [
            'id_organisasi.exists' => 'Organisasi tidak ditemukan atau belum disetujui oleh admin.'
        ]);

       $donorId = auth()->user()->donor?->id ?? auth()->id();

        $visit = Visit::create([
            'id_organisasi'     => $validated['id_organisasi'],
            'id_donatur'        => $donorId,
            'tanggal_kunjungan' => $validated['tanggal_kunjungan'],
            'waktu_kunjungan'   => $validated['waktu_kunjungan'],
            'pengunjung'        => $validated['pengunjung'],
            'pesan_donatur'     => $validated['pesan_donatur'] ?? null,
            'status'            => 'terkirim',
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Pengajuan kunjungan berhasil dikirim.',
            'data'    => $visit
        ], 201);
    }

    // 2. Organisasi Mengonfirmasi atau Menolak Kunjungan
    public function respondVisit(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:dikonfirmasi,ditolak',
            'pesan_organisasi' => 'required|string|max:255',
        ]);

        $organizationId = auth()->user()->organization?->id;

        $visit = Visit::where('id', $id)
            ->where('id_organisasi', auth()->user()->organization?->id)
            ->firstOrFail();

        $visit->update([
            'status' => $request->status,
            'pesan_organisasi' => $request->pesan_organisasi,
            'confirmed_at' => $request->status === 'dikonfirmasi' ? now() : null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "Kunjungan berhasil {$request->status}.",
            'data' => $visit
        ]);
    }

    // 3. Donatur Upload Dokumentasi Kunjungan (Status -> Selesai)
    public function uploadDocumentation(Request $request, $id)
    {
        $request->validate([
            'dokumentasi'   => 'required|array|min:1',
            'dokumentasi.*' => 'image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $donorId = auth()->user()->donor?->id ?? auth()->id();

        $visit = Visit::where('id', $id)
            ->where('id_donatur', $donorId)
            ->firstOrFail();

        if ($visit->status !== 'dikonfirmasi') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Dokumentasi hanya dapat diunggah untuk kunjungan yang sudah dikonfirmasi.'
            ], 422);
        }

        return DB::transaction(function () use ($request, $visit) {
            foreach ($request->file('dokumentasi') as $file) {
                $path = $file->store('visit_documentations', 'public');

                VisitDocument::create([
                    'id_kunjungan' => $visit->id,
                    'lokasi_file'  => $path,
                ]);
            }

            $visit->update(['status' => 'selesai']);

            return response()->json([
                'status'  => 'success',
                'message' => 'Dokumentasi kunjungan berhasil diunggah dan kunjungan diselesaikan.',
                'data'    => $visit->load('documents')
            ]);
        });
    }

    // 4. Get List Kunjungan (Bisa di-filter berdasarkan role)
    public function index(Request $request)
    {
        $user = auth()->user();

        $visits = Visit::with(['documents'])
            ->when($user->role === 'donatur', function ($q) use ($user) {
                $donorId = $user->donor?->id ?? $user->id;
                $q->where('id_donatur', $donorId);
            })
            ->when($user->role === 'organisasi', function ($q) use ($user) {
                $orgId = $user->organization?->id ?? $user->id;
                $q->where('id_organisasi', $orgId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $visits
        ]);
    }

    // Donatur Mengedit Data Kunjungan (Hanya Jika Status Masih 'terkirim')
public function update(Request $request, $id)
{
    $validated = $request->validate([
        'tanggal_kunjungan' => 'sometimes|required|date|after_or_equal:today',
        'waktu_kunjungan'   => 'sometimes|required',
        'pengunjung'        => 'sometimes|required|integer|min:1',
        'pesan_donatur'     => 'nullable|string|max:255',
    ]);

    $donorId = auth()->user()->donor?->id ?? auth()->id();

    // Cari kunjungan milik donatur yang sedang login
    $visit = Visit::where('id', $id)
        ->where('id_donatur', $donorId)
        ->firstOrFail();

    // Proteksi: Hanya bisa diedit jika status masih 'terkirim'
    if ($visit->status !== 'terkirim') {
        return response()->json([
            'status'  => 'error',
            'message' => 'Kunjungan tidak dapat diubah karena sudah ' . $visit->status . '.'
        ], 422);
    }

    // Update data kunjungan
    $visit->update($validated);

    return response()->json([
        'status'  => 'success',
        'message' => 'Data kunjungan berhasil diperbarui.',
        'data'    => $visit
    ], 200);
}
}