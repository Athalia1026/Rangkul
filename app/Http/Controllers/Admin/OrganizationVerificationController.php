<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\OrganizationVerificationMail;
use Illuminate\Support\Facades\Mail;

class OrganizationVerificationController extends Controller
{
    // 1. Get List Organisasi yang Menunggu Verifikasi
    public function index(Request $request)
    {
        $organizations = Organization::with('user')
            ->where('verification_status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data'   => $organizations
        ]);
    }

    // 2. Get Detail Organisasi beserta Seluruh Dokumen Fisiknya
    public function show($id)
    {
        $organization = Organization::with(['user', 'documents'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $organization
        ]);
    }

    // 3. Verifikasi (Approve/Reject) Dokumen & Update Status Organisasi Otomatis
    public function verifyDocument(Request $request, $documentId)
    {
        $request->validate([
            'status'           => 'required|in:diterima,ditolak',
            'alasan_penolakan' => 'required_if:status,ditolak|nullable|string',
        ]);

        return DB::transaction(function () use ($request, $documentId) {
            $document = OrganizationDocument::findOrFail($documentId);

            $adminId = auth()->user()->adminProfile?->id;

            // Update status dokumen
            $document->update([
                'status'           => $request->status,
                'alasan_penolakan' => $request->status === 'ditolak' ? $request->alasan_penolakan : null,
                'verified_at'      => now(),
                'verified_by'      => $adminId, // Mengisi ID Admin yang login
            ]);

            // Cek seluruh status dokumen organisasi terkait
            $organizationId = $document->id_organisasi;
            $allDocuments = OrganizationDocument::where('id_organisasi', $organizationId)->get();

            $organization = Organization::findOrFail($organizationId);
            $previousStatus = $organization->verification_status;


            // Logic Otomatis Status Organisasi:
            // - Jika ada 1 saja dokumen ditolak -> Status Organisasi = 'ditolak'
            // - Jika semua dokumen disetujui -> Status Organisasi = 'disetujui'
            // - Jika masih ada yang 'menunggu' -> Status Organisasi = 'menunggu'
            if ($allDocuments->contains('status', 'ditolak')) {
                $organization->update(['verification_status' => 'ditolak']);
            } elseif ($allDocuments->every(fn($doc) => $doc->status === 'diterima')) {
                $organization->update(['verification_status' => 'disetujui']);
            } else {
                $organization->update(['verification_status' => 'menunggu']);
            }

            $organization->refresh();
            if (
            $previousStatus !== $organization->verification_status &&
            in_array($organization->verification_status, ['disetujui', 'ditolak'])
        ) {
            try {
                Mail::to($organization->user->email)
                    ->send(new OrganizationVerificationMail($organization));
            } catch (\Exception $e) {
                // Catat error ke log tanpa memberhentikan proses API
                \Log::error('SMTP Mailtrap Timeout: ' . $e->getMessage());
            }
        }

            return response()->json([
                'status'  => 'success',
                'message' => 'Status dokumen berhasil diperbarui',
                'data'    => $document->fresh()
            ]);
        });
    }
}