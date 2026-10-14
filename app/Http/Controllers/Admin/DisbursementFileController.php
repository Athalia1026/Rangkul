<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FundDisbursement;
use Illuminate\Support\Facades\Storage;

class DisbursementFileController extends Controller
{
    // Menyajikan lampiran pengajuan / bukti transfer manual. Akses dibatasi middleware 'signed' pada route.
    public function show(string $disbursementId, string $type)
    {
        $column = FundDisbursement::FILE_COLUMNS[$type] ?? abort(404);
        $path = FundDisbursement::findOrFail($disbursementId)->{$column};
        $disk = Storage::disk(FundDisbursement::DISK);

        abort_unless($path && $disk->exists($path), 404);

        return $disk->response($path, basename($path), [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
