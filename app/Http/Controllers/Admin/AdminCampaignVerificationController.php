<?php
namespace App\Http\Controllers\Admin;

use App\Services\NotificationService;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCampaignVerificationController extends Controller
{
    // List campaign pending
    public function index()
    {
        $campaigns = Campaign::with(['organization', 'verifier'])
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['status' => 'success', 'data' => $campaigns]);
    }

    // Approve / Reject Campaign
    public function verify(Request $request, $id)
    {
        $request->validate([
            'status'       => 'required|in:aktif,ditolak',
            'alasan_tolak' => 'required_if:status,ditolak|nullable|string',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $campaign = Campaign::lockForUpdate()->findOrFail($id);

            // Hanya kampanye yang menunggu verifikasi yang bisa diputuskan; kampanye yang sudah ditolak,
            // berjalan, atau selesai tidak boleh diaktifkan / ditolak ulang lewat endpoint ini.
            if ($campaign->status !== 'menunggu') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Kampanye ini sudah pernah diverifikasi sebelumnya.'
                ], 409);
            }

            // Ambil ID dari tabel admins melalui relasi user
            $adminId = auth()->user()->adminProfile?->id;

            $campaign->update([
                'status'       => $request->status,
                'alasan_tolak' => $request->status === 'ditolak' ? $request->alasan_tolak : null,
                'verified_by'  => $adminId,
                'verified_at'  => now(),
            ]);

            app(NotificationService::class)->campaignVerified($campaign);

            return response()->json([
                'status'  => 'success',
                'message' => 'Status campaign berhasil diperbarui.',
                'data'    => $campaign->fresh()
            ]);
        });
    }
}