<?php

namespace App\Http\Controllers\Donors;

use App\Http\Controllers\Controller;
use App\Services\DonationService;
use App\Services\PremiumService;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function __construct(
        protected DonationService $donationService,
        protected PremiumService $premiumService
    ) {
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->account_type === 'donatur' && $request->user()->donor, 403);
        $validated = $request->validate([
            'id_campaign' => 'required|exists:campaigns,id',
            'nominal' => 'required|integer|min:10000|max:10000000',
            'note' => 'nullable|string|max:255',
            'anonim' => 'nullable|boolean',
        ], [
            'nominal.min' => 'Nominal donasi minimal adalah Rp 10.000.',
            'nominal.max' => 'Nominal donasi maksimal adalah Rp 10.000.000.',
        ]);

        $campaign = \App\Models\Campaign::where('status', 'aktif')->findOrFail($validated['id_campaign']);
        abort_if($campaign->tanggal_selesai && $campaign->tanggal_selesai->endOfDay()->isPast(), 422, 'Periode donasi kampanye ini sudah berakhir.');
        if (!config('services.midtrans.server_key')) {
            return response()->json(['message' => 'Pembayaran belum tersedia. Konfigurasi pembayaran perlu dilengkapi.'], 503);
        }
        try {
            $result = $this->donationService->createDonationTransaction($validated, auth()->user());
        } catch (\Throwable $exception) {
            report($exception);
            return response()->json(['message' => 'Pembayaran belum dapat dibuat. Silakan coba kembali beberapa saat lagi.'], 502);
        }

        return response()->json($result['data'], $result['http_status']);
    }

    public function checkout(string $campaignId)
    {
        $campaign = \App\Models\Campaign::with('organization')->where('status', 'aktif')->findOrFail($campaignId);
        return view('donatur.donasi-detail', ['campaign' => $campaign, 'fee' => DonationService::PAYMENT_FEE]);
    }

    public function status(Request $request, string $id)
    {
        abort_unless($request->user()->account_type === 'donatur' && $request->user()->donor, 403);
        $donation = \App\Models\Donation::with('campaign')->where('id_donatur', $request->user()->donor->id)->findOrFail($id);
        if ($request->boolean('refresh')) $donation = $this->donationService->refreshPayment($donation)->load('campaign');
        return response()->json(['id' => $donation->id, 'campaign' => $donation->campaign?->judul,
            'nominal' => (int) $donation->nominal, 'fee' => $donation->payment_fee,
            'status' => $donation->status, 'paid_at' => $donation->paid_at,
            'created_at' => $donation->created_at, 'snap_token' => $donation->snap_token,
            'payment_url' => $donation->payment_url]);
    }

    public function handleCallback(Request $request)
    {
        if (str_starts_with((string) $request->input('order_id'), 'PREM-')) {
            $result = $this->premiumService->handleCallback($request->all());

            return response()->json($result['response'], $result['statusCode']);
        }

        $result = $this->donationService->handleCallback($request->all());

        return response()->json($result['response'], $result['statusCode']);
    }

    public function getCampaignWishes(string $campaignId)
    {
        $wishes = $this->donationService->getCampaignWishes($campaignId);

        return response()->json([
            'status' => 'success',
            'data' => $wishes,
        ], 200);
    }
}
