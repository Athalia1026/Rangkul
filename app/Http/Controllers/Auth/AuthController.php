<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResubmitRegistrationRequest;
use App\Http\Requests\RegisterDonorRequest;
use App\Http\Requests\RegisterOrganizationRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function registerDonor(RegisterDonorRequest $request)
    {
        $result = $this->authService->registerDonor($request->validated());

        return response()->json([
            'message' => 'Registrasi Donatur berhasil',
            'data' => $result['user'],
            'token' => $result['token'],
        ], 201);
    }

    public function registerWeb(RegisterDonorRequest $request)
    {
        $this->authService->registerDonor($request->validated());

        return Redirect::route('login')->with('success', 'Registrasi berhasil. Silakan masuk dengan akun Anda.');
    }

    public function registerOrganization(RegisterOrganizationRequest $request)
    {
        try {
            $result = $this->authService->registerOrganization($request);

            return response()->json([
                'message' => 'Registrasi Organisasi berhasil (Dokumen Menunggu Verifikasi Admin)',
                'data' => $result['user'],
                'token' => $result['token'],
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Registrasi gagal. Sistem telah membatalkan seluruh proses.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            $result = $this->authService->login($request->only('email', 'password'));

            if (($result['status'] ?? 200) === 403) {
                return response()->json([
                    'message' => $result['message'],
                    'code' => $result['code'],
                    'user' => $result['user'],
                ], 403);
            }

            return response()->json([
                'message' => $result['message'],
                'access_token' => $result['access_token'],
                'token_type' => $result['token_type'],
                'user' => $result['user'],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => $e->validator->errors()->first(),
            ], 401);
        }
    }

    public function me(Request $request)
    {
        $user = $this->authService->me($request->user());

        return response()->json([
            'user' => $user,
        ], 200);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Logout berhasil',
        ], 200);
    }

    public function resubmit(ResubmitRegistrationRequest $request)
    {
        try {
            $result = $this->authService->resubmit($request);

            return response()->json([
                'status' => $result['status'],
                'message' => $result['message'],
                'data' => $result['data'],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => $e->validator->errors()->first(),
            ], 422);
        }
    }
}
