<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use App\Models\OrganizationDocument;
use Illuminate\Support\Facades\Storage;

class OrganizationDocumentController extends Controller
{
    // Menyajikan dokumen pendaftaran privat. Akses dibatasi middleware 'signed' pada route.
    public function show(string $documentId)
    {
        $document = OrganizationDocument::findOrFail($documentId);
        $disk = Storage::disk(OrganizationDocument::DISK);

        abort_unless($disk->exists($document->lokasi_file), 404);

        return $disk->response($document->lokasi_file, $document->nama_file, [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
