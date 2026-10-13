<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use App\Models\FundDisbursement;
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

        // Notifikasi pencairan dana diarahkan ke kampanyenya, jadi ambil id kampanye sekaligus.
        $disbursementCampaigns = FundDisbursement::whereIn(
            'id',
            $notifications->where('reference_type', 'fund_disbursement')->pluck('reference_id')
        )->pluck('id_campaign', 'id');

        $notifications->each(function (Notification $notification) use ($disbursementCampaigns) {
            $notification->url = $this->targetUrl($notification, $disbursementCampaigns);
        });

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

    // Halaman tujuan saat notifikasi diklik, sesuai jenis referensinya
    private function targetUrl(Notification $notification, $disbursementCampaigns): ?string
    {
        $id = $notification->reference_id;

        return match ($notification->reference_type) {
            'donation' => route('organisasi.donasi.detail', $id),
            'campaign' => route('organisasi.kampanye.detail', $id),
            'visit' => route('organisasi.kunjungan.detail', $id),
            'purchase_proof' => route('organisasi.kampanye.bukti.detail', $id),
            'fund_disbursement' => isset($disbursementCampaigns[$id])
                ? route('organisasi.kampanye.detail', $disbursementCampaigns[$id])
                : route('organisasi.kampanye'),
            'organization' => route('organisasi.profil'),
            'report' => route('organisasi.laporan'),
            default => null,
        };
    }
}
