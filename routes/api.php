<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Donors\ProfileController;
use App\Http\Controllers\Admin\OrganizationVerificationController;
use App\Http\Controllers\Admin\AdminCampaignVerificationController;
use App\Http\Controllers\Organizations\CampaignController;
use App\Http\Middleware\CheckIsAdmin;
use App\Http\Controllers\Donors\VisitController;

Route::post('/register/donor', [AuthController::class, 'registerDonor']);
Route::post('/register/organization', [AuthController::class, 'registerOrganization']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
Route::post('/registration/resubmit', [AuthController::class, 'resubmit']);

// Endpoint Terproteksi (Wajib Token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']); // Untuk mengambil data profil user saat in
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto']);
    Route::put('/profile/change-password', [PasswordController::class, 'update']);
    Route::prefix('admin/verifications')->group(function () {
    Route::get('/organizations', [OrganizationVerificationController::class, 'index']);
    Route::get('/organizations/{id}', [OrganizationVerificationController::class, 'show']);
    Route::put('/documents/{documentId}', [OrganizationVerificationController::class, 'verifyDocument']);
});
});

Route::middleware('auth:sanctum')->prefix('organization')->group(function () {
    Route::post('/campaigns', [CampaignController::class, 'store']);
});

Route::middleware(['auth:sanctum', CheckIsAdmin::class])->prefix('admin')->group(function () {
    Route::get('/campaigns/pending', [AdminCampaignVerificationController::class, 'index']);
    Route::put('/campaigns/{id}/verify', [AdminCampaignVerificationController::class, 'verify']);
});

Route::middleware('auth:sanctum')->prefix('visits')->group(function () {
    Route::get('/', [VisitController::class, 'index']);
    Route::post('/', [VisitController::class, 'store']); // Donatur submit
    Route::patch('/{id}/respond', [VisitController::class, 'respondVisit']); // Organisasi confirm/reject
    Route::post('/{id}/documentation', [VisitController::class, 'uploadDocumentation']); // Donatur upload bukti
    Route::put('/{id}', [VisitController::class, 'update']);
});