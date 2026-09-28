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
        $validated = $request->validate([
            'id_campaign' => 'required|exists:campaigns,id',
            'nominal' => 'required|numeric|min:10000|max:10000000',
            'note' => 'nullable|string|max:255',
            'anonim' => 'nullable|boolean',
        ], [
            'nominal.min' => 'Nominal donasi minimal adalah Rp 10.000.',
            'nominal.max' => 'Nominal donasi maksimal adalah Rp 10.000.000.',
        ]);

        $result = $this->donationService->createDonationTransaction($validated, auth()->user());

        return response()->json($result['data'], $result['http_status']);
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
