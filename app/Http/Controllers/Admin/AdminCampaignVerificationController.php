<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

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

        $campaign = Campaign::findOrFail($id);

        // Ambil ID dari tabel admins melalui relasi user
        $adminId = auth()->user()->adminProfile?->id;
        
        $campaign->update([
            'status'       => $request->status,
            'alasan_tolak' => $request->status === 'ditolak' ? $request->alasan_tolak : null,
            'verified_by'  => $adminId,
            'verified_at'  => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Status campaign berhasil diperbarui.',
            'data'    => $campaign->fresh()
        ]);
    }
}