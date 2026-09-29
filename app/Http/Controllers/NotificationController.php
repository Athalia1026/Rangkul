<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

/**
 * Endpoint notifikasi untuk semua role (donatur, organisasi, admin).
 * Setiap user hanya dapat mengakses notifikasi miliknya sendiri.
 */
class NotificationController extends Controller
{
    // 1. Daftar notifikasi milik user login (bisa difilter ?status=unread|read)
    public function index(Request $request)
    {
        $request->validate([
            'status'   => 'nullable|in:unread,read',
            'per_page' => 'nullable|integer|min:1|max:50',
        ]);

        $notifications = Notification::where('user_id', $request->user()->id)
            ->when($request->status === 'unread', fn ($q) => $q->where('is_read', false))
            ->when($request->status === 'read', fn ($q) => $q->where('is_read', true))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'status'       => 'success',
            'unread_count' => $this->unreadQuery($request)->count(),
            'data'         => $notifications,
        ]);
    }

    // 2. Jumlah notifikasi belum dibaca (untuk badge lonceng)
    public function unreadCount(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data'   => ['unread_count' => $this->unreadQuery($request)->count()],
        ]);
    }

    // 3. Tandai satu notifikasi sebagai dibaca
    public function markAsRead(Request $request, string $id)
    {
        $notification = $this->findOwned($request, $id);
        $notification->update(['is_read' => true]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Notifikasi ditandai sudah dibaca.',
            'data'    => $notification,
        ]);
    }

    // 4. Tandai semua notifikasi sebagai dibaca
    public function markAllAsRead(Request $request)
    {
        $updated = $this->unreadQuery($request)->update(['is_read' => true]);

        return response()->json([
            'status'  => 'success',
            'message' => "{$updated} notifikasi ditandai sudah dibaca.",
        ]);
    }

    // 5. Hapus satu notifikasi
    public function destroy(Request $request, string $id)
    {
        $this->findOwned($request, $id)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Notifikasi berhasil dihapus.',
        ]);
    }

    private function unreadQuery(Request $request)
    {
        return Notification::where('user_id', $request->user()->id)->where('is_read', false);
    }

    private function findOwned(Request $request, string $id): Notification
    {
        return Notification::where('user_id', $request->user()->id)->findOrFail($id);
    }
}
