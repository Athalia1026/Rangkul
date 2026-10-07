<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Donor;
use App\Models\Organization;

class AdminUserController extends Controller
{
    private const DOCUMENT_LABELS = [
        'sk' => 'SK Pendirian / SK Operasional',
        'ktp' => 'KTP Penanggung Jawab',
        'kegiatan' => 'Foto Kegiatan dan Penerima Manfaat',
        'bangunan' => 'Foto Tampak Depan Bangunan',
    ];

    // Daftar seluruh organisasi dan donatur untuk halaman Daftar Pengguna
    public function index()
    {
        $organizations = Organization::query()->latest()->get();
        $donors = Donor::with('user')->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_organisasi' => $organizations->count(),
                'total_donatur' => $donors->count(),
                'organizations' => $organizations->map(fn (Organization $organization) => [
                    'id' => $organization->id,
                    'registered_at' => optional($organization->created_at)->format('d/m/Y'),
                    'name' => $organization->nama_lembaga,
                    'address' => $organization->alamat,
                    'type' => $this->organizationTypeLabel($organization->tipe),
                    'type_key' => $this->organizationTypeKey($organization->tipe),
                ])->values(),
                'donors' => $donors->map(fn (Donor $donor) => [
                    'id' => $donor->id,
                    'name' => $donor->user?->nama ?? '-',
                    'email' => $donor->user?->email ?? '-',
                    'phone' => $donor->no_telp,
                    'city' => $donor->kota,
                    'type' => ucfirst($donor->tipe),
                ])->values(),
            ],
        ]);
    }

    // Detail satu organisasi beserta dokumen pendaftaran dan rekening lembaga
    public function showOrganization($id)
    {
        $organization = Organization::with(['user', 'documents', 'galleries'])->findOrFail($id);
        $bankAccount = BankAccount::where('id_organisasi', $organization->id)->first();
        $documentOrder = array_flip(array_keys(self::DOCUMENT_LABELS));

        $documents = $organization->documents
            ->map(function ($document) {
                $key = $this->documentKey($document->lokasi_file);

                return [
                    'key' => $key,
                    'label' => self::DOCUMENT_LABELS[$key] ?? 'Dokumen Lainnya',
                    'file_name' => $document->nama_file,
                    'url' => asset('storage/' . ltrim($document->lokasi_file, '/')),
                    'status' => $document->status,
                ];
            })
            ->sortBy(fn ($document) => $documentOrder[$document['key']] ?? PHP_INT_MAX)
            ->values();

        $buildingPhoto = $documents->firstWhere('key', 'bangunan')['url'] ?? null;

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $organization->id,
                'name' => $organization->nama_lembaga,
                'type' => $this->organizationTypeLabel($organization->tipe),
                'verification_status' => $organization->verification_status,
                'alasan_penolakan' => $organization->alasan_penolakan,
                'jumlah_anak' => $organization->jumlah_anak,
                'registered_at' => $organization->created_at?->locale('id')->translatedFormat('j F Y'),
                'address' => $organization->alamat,
                'city' => $organization->kota,
                'phone' => $organization->no_telp,
                'contact_name' => $organization->user?->nama,
                'email' => $organization->user?->email,
                'description' => $organization->deskripsi,
                'photo_url' => $buildingPhoto
                    ?? $organization->galleries->first()?->image_url
                    ?? $organization->user?->profilePhotoUrl(),
                'documents' => $documents,
                'bank_account' => $bankAccount ? [
                    'bank' => $bankAccount->bank,
                    'no_rekening' => $bankAccount->no_rekening,
                    'pemilik_rekening' => $bankAccount->pemilik_rekening,
                    'status' => $bankAccount->status_verifikasi,
                ] : null,
            ],
        ]);
    }

    /** Jenis dokumen diambil dari folder penyimpanan, mis. documents/sk/xxx.pdf -> sk. */
    private function documentKey(string $path): string
    {
        $segments = explode('/', trim($path, '/'));

        return count($segments) >= 2 ? $segments[count($segments) - 2] : 'lainnya';
    }

    private function organizationTypeKey(?string $type): string
    {
        $type = strtolower((string) $type);

        return str_contains($type, 'panti') ? 'panti' : (str_contains($type, 'sekolah') ? 'sekolah' : $type);
    }

    private function organizationTypeLabel(?string $type): string
    {
        return match ($this->organizationTypeKey($type)) {
            'panti' => 'Panti',
            'sekolah' => 'Sekolah',
            default => $type ?: '-',
        };
    }
}
