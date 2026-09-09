<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationDocument;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\OrganizationVerificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrganizationVerificationController extends Controller
{
    // 1. Get List Organisasi yang Menunggu Verifikasi
    public function index(Request $request)
    {
        // Tambahkan 'bankAccount' pada eager loading
        $organizations = Organization::with(['user', 'bankAccount'])
            ->where('verification_status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $organizations
        ]);
    }

    // 2. Get Detail Organisasi beserta Seluruh Dokumen Fisiknya dan Bank
    public function show($id)
    {
        // Tambahkan 'bankAccount' pada eager loading
        $organization = Organization::with(['user', 'documents', 'bankAccount'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $organization
        ]);
    }

    // 3. Verifikasi Dokumen
    public function verifyDocument(Request $request, $documentId)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'alasan_penolakan' => 'required_if:status,ditolak|nullable|string',
        ]);

        return DB::transaction(function () use ($request, $documentId) {
            $document = OrganizationDocument::findOrFail($documentId);
            $adminId = auth()->user()->adminProfile?->id;

            $document->update([
                'status' => $request->status,
                'alasan_penolakan' => $request->status === 'ditolak' ? $request->alasan_penolakan : null,
                'verified_at' => now(),
                'verified_by' => $adminId,
            ]);

            // Panggil fungsi pembantu untuk kalkulasi ulang status organisasi
            $this->checkAndUpdateOrganizationStatus($document->id_organisasi);

            return response()->json([
                'status' => 'success',
                'message' => 'Status dokumen berhasil diperbarui',
                'data' => $document->fresh()
            ]);
        });
    }

    // 4. Verifikasi Rekening Bank (FITUR BARU)
    public function verifyBankAccount(Request $request, $bankId)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            // Jika ada tabel alasan penolakan di bank account, bisa ditambahkan di sini
        ]);

        return DB::transaction(function () use ($request, $bankId) {
            $bankAccount = BankAccount::findOrFail($bankId);

            $bankAccount->update([
                'status_verifikasi' => $request->status,
                // 'verified_at' => now(), // Tambahkan jika ada kolom ini di DB Anda
            ]);

            // Panggil fungsi pembantu untuk kalkulasi ulang status organisasi
            // Asumsi $bankAccount->id_organisasi merujuk pada user_id (sama seperti dokumen)
            $this->checkAndUpdateOrganizationStatus($bankAccount->id_organisasi);

            return response()->json([
                'status' => 'success',
                'message' => 'Status rekening bank berhasil diperbarui',
                'data' => $bankAccount->fresh()
            ]);
        });
    }

    // ==============================================================================
    // PRIVATE HELPER FUNCTION: Menghitung status global organisasi
    // ==============================================================================
    private function checkAndUpdateOrganizationStatus($organizationId)
    {
        $organization = Organization::with('user')->findOrFail($organizationId);
        $previousStatus = $organization->verification_status;

        // Ambil semua data pendukung
        $allDocuments = OrganizationDocument::where('id_organisasi', $organizationId)->get();
        $bankAccount = BankAccount::where('id_organisasi', $organizationId)->first();

        // LOGIKA PENENTUAN:
        // 1. Ditolak jika ADA SATU SAJA dokumen ditolak ATAU bank ditolak
        $isAnyDitolak = $allDocuments->contains('status', 'ditolak') ||
            ($bankAccount && $bankAccount->status_verifikasi === 'ditolak');

        // 2. Disetujui jika SEMUA dokumen diterima DAN bank diterima
        $isAllDiterima = $allDocuments->every(fn($doc) => $doc->status === 'diterima') &&
            ($bankAccount && $bankAccount->status_verifikasi === 'diterima');

        if ($isAnyDitolak) {
            $organization->update(['verification_status' => 'ditolak', 'verified_at' => null]);
        } elseif ($isAllDiterima) {
            $organization->update(['verification_status' => 'disetujui', 'verified_at' => now()]);
        } else {
            $organization->update(['verification_status' => 'menunggu', 'verified_at' => null]);
        }

        $organization->refresh();

        // Kirim Email jika status berubah menjadi final (disetujui/ditolak)
        if ($previousStatus !== $organization->verification_status && in_array($organization->verification_status, ['disetujui', 'ditolak'])) {
            try {
                Mail::to($organization->user->email)->send(new OrganizationVerificationMail($organization));
            } catch (\Exception $e) {
                Log::error('SMTP Mailtrap Timeout: ' . $e->getMessage());
            }
        }
    }
}