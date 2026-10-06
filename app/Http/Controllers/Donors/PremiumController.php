<?php

namespace App\Http\Controllers\Donors;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\PremiumService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PremiumController extends Controller
{
    public function __construct(private PremiumService $premiumService) {}

    public function register(Request $request)
    {
        abort_unless($request->user()->account_type === 'donatur', 403);

        $data = $request->validate([
            'nama_pic' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'nomor_pic' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]{8,20}$/'],
            'email_korporat' => 'required|email|max:255',
            'NPWP' => ['required', 'string', 'max:30', 'regex:/^[0-9.\-\s]{15,30}$/'],
            'kota' => 'nullable|string|max:100',
        ], [
            'required' => 'Kolom ini wajib diisi.',
            'max' => 'Maksimal :max karakter.',
            'email_korporat.email' => 'Format email tidak valid.',
            'nomor_pic.regex' => 'Nomor telepon hanya boleh berisi angka, spasi, + atau -.',
            'NPWP.regex' => 'NPWP terdiri dari 15–16 digit angka.',
        ]);

        if ($request->user()->companyPremium?->activeSubscription()->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Akun Anda sudah premium.'], 409);
        }

        $result = $this->premiumService->createRegistration($data, $request->user());

        return response()->json([
            'status' => 'success',
            'message' => 'Pendaftaran premium berhasil dibuat. Silakan selesaikan pembayaran.',
            'data' => $result,
        ], 201);
    }

    public function status(Request $request)
    {
        $company = $request->user()->companyPremium()->with(['subscriptions' => fn ($query) => $query->latest()])->first();
        $pricing = $this->premiumService->pricing();

        return response()->json([
            'status' => 'success',
            'is_premium' => (bool) $company?->activeSubscription()->exists(),
            'price' => $pricing['price'],
            'pricing' => $pricing,
            'data' => $company,
        ]);
    }

    // Status satu langganan; ?refresh=1 menanyakan langsung ke Midtrans.
    public function show(Request $request, string $id)
    {
        $subscription = $this->ownedSubscription($request, $id);
        if ($request->boolean('refresh')) {
            $subscription = $this->premiumService->refreshPayment($subscription);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $subscription->id,
                'status' => $subscription->status,
                'is_active' => $subscription->isActive(),
                'transaction_id' => $subscription->transaction_id,
                'started_at' => $subscription->started_at,
                'expired_at' => $subscription->expired_at,
                'paid_at' => $subscription->paid_at,
                'duration_days' => $subscription->started_at && $subscription->expired_at
                    ? (int) $subscription->started_at->diffInDays($subscription->expired_at)
                    : null,
                'pricing' => $this->premiumService->pricing(),
            ],
        ]);
    }

    public function invoice(Request $request, string $id)
    {
        $subscription = $this->ownedSubscription($request, $id)->load('company.donor.user');
        abort_unless($subscription->paid_at, 404);

        return Pdf::loadView('reports.premium-invoice', [
            'subscription' => $subscription,
            'company' => $subscription->company,
            'pricing' => $this->premiumService->pricing(),
        ])->setPaper('a5', 'portrait')->download('invoice-premium-' . $subscription->transaction_id . '.pdf');
    }

    private function ownedSubscription(Request $request, string $id): Subscription
    {
        $companyId = $request->user()->companyPremium?->id;
        abort_unless($companyId, 404);

        return Subscription::where('id_perusahaan', $companyId)->findOrFail($id);
    }
}
