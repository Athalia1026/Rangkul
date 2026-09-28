<?php

namespace App\Http\Controllers\Donors;

use App\Http\Controllers\Controller;
use App\Services\PremiumService;
use Illuminate\Http\Request;

class PremiumController extends Controller
{
    public function __construct(private PremiumService $premiumService) {}

    public function register(Request $request)
    {
        $data = $request->validate([
            'nama_pic' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nomor_pic' => 'required|string|max:30',
            'email_korporat' => 'required|email|max:255',
            'NPWP' => 'required|string|max:255',
            'kota' => 'nullable|string|max:100',
        ]);

        $result = $this->premiumService->createRegistration($data, $request->user());

        return response()->json([
            'status' => 'success',
            'message' => 'Pendaftaran premium berhasil dibuat. Silakan selesaikan pembayaran QRIS.',
            'data' => $result,
        ], 201);
    }

    public function status(Request $request)
    {
        $company = $request->user()->companyPremium()->with(['subscriptions' => fn ($query) => $query->latest()])->first();

        return response()->json([
            'status' => 'success',
            'data' => $company,
        ]);
    }
}
