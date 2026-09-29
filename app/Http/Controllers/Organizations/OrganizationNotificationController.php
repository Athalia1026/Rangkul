<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrganizationNotificationController extends Controller
{
    // Halaman notifikasi organisasi, dikelompokkan per hari (web)
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $notifications = Notification::where('user_id', $userId)
            ->latest()
            ->take(50)
            ->get();

        $groups = $notifications->groupBy(function (Notification $notification) {
            $date = Carbon::parse($notification->created_at);

            return match (true) {
                $date->isToday()     => 'Hari Ini',
                $date->isYesterday() => 'Kemarin',
                default              => $date->locale('id')->translatedFormat('j F Y'),
            };
        });

        // Halaman sudah dibuka, tandai semua notifikasi sebagai dibaca.
        Notification::where('user_id', $userId)->where('is_read', false)->update(['is_read' => true]);

        return view('organisasi.notifikasi', compact('groups'));
    }
}
