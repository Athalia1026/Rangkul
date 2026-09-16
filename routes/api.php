<?php

use App\Http\Controllers\Admin\AdminCampaignVerificationController;
use App\Http\Controllers\Admin\AdminDisbursementVerificationController;
use App\Http\Controllers\Admin\AdminProofVerificationController;
use App\Http\Controllers\Admin\OrganizationVerificationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Donors\DonationController;
use App\Http\Controllers\Donors\ProfileController;
use App\Http\Controllers\Donors\VisitController;
use App\Http\Controllers\Organizations\CampaignController;
use App\Http\Controllers\Organizations\OrganizationDisbursementController;
use App\Http\Controllers\Organizations\OrganizationGalleryController;
use App\Http\Controllers\Organizations\OrganizationProfileController;
use App\Http\Middleware\CheckIsAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Donors\ActivityHistoryController;
use App\Http\Controllers\Donors\SearchController;
use App\Http\Controllers\Donors\PremiumController;
use App\Http\Controllers\Donors\PremiumDashboardController;
use App\Http\Middleware\CheckPremium;

Route::get('/search', [SearchController::class, 'search']);
Route::post('/register/donor', [AuthController::class, 'registerDonor'])
    ->middleware('throttle:5,1');
Route::post('/register/organization', [AuthController::class, 'registerOrganization'])
    ->middleware('throttle:3,1');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');
Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('throttle:3,1');
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])
    ->middleware('throttle:5,1');
Route::post('/registration/resubmit', [AuthController::class, 'resubmit'])
    ->middleware('throttle:3,1');
Route::post('/midtrans/callback', [DonationController::class, 'handleCallback'])
    ->middleware('throttle:10,1');
Route::get('/campaigns/{campaignId}/wishes', [DonationController::class, 'getCampaignWishes']);
Route::get('/organizations/{organizationId}/campaigns', [OrganizationProfileController::class, 'getOrganizationCampaigns']);
Route::get('/organizations/{organizationId}/profile', [OrganizationProfileController::class, 'showPublicProfile']);

// Endpoint Terproteksi (Wajib Token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('throttle:5,1');
    Route::get('/me', [AuthController::class, 'me']); // Untuk mengambil data profil user saat in
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto']);
    Route::put('/profile/change-password', [PasswordController::class, 'update'])
        ->middleware('throttle:5,1');
    Route::prefix('admin/verifications')->group(function () {
        Route::get('/organizations', [OrganizationVerificationController::class, 'index']);
        Route::get('/organizations/{id}', [OrganizationVerificationController::class, 'show']);
        Route::put('/documents/{documentId}', [OrganizationVerificationController::class, 'verifyDocument']);
        Route::put('/bank-accounts/{bankId}', [OrganizationVerificationController::class, 'verifyBankAccount']);
    });
    Route::get('/donors/activities', [ActivityHistoryController::class, 'index']);
    Route::prefix('premium')->group(function () {
        Route::post('/register', [PremiumController::class, 'register']);
        Route::get('/status', [PremiumController::class, 'status']);
    });
    Route::middleware(CheckPremium::class)->prefix('premium/dashboard')->group(function () {
        Route::get('/', [PremiumDashboardController::class, 'index']);
        Route::get('/export', [PremiumDashboardController::class, 'export']);
    });
});

Route::middleware('auth:sanctum')->prefix('organizations')->group(function () {
    Route::put('/profile', [OrganizationProfileController::class, 'update']);
    Route::post('/galleries', [OrganizationGalleryController::class, 'store']);
    Route::delete('/galleries/{id}', [OrganizationGalleryController::class, 'destroy']);
    Route::post('/campaigns', [CampaignController::class, 'store']);
    Route::post('/disbursements', [OrganizationDisbursementController::class, 'requestDisbursement']);
    Route::post('/disbursements/{disbursementId}/proofs', [OrganizationDisbursementController::class, 'uploadProof']);
});

Route::middleware(['auth:sanctum', CheckIsAdmin::class])->prefix('admin')->group(function () {
    Route::get('/campaigns/pending', [AdminCampaignVerificationController::class, 'index']);
    Route::put('/campaigns/{id}/verify', [AdminCampaignVerificationController::class, 'verify'])
        ->middleware('throttle:10,1');
    Route::get('/disbursements/pending', [AdminDisbursementVerificationController::class, 'index']);
    Route::put('/disbursements/{id}/verify', [AdminDisbursementVerificationController::class, 'verify'])
        ->middleware('throttle:10,1');
    Route::post('/disbursements/{id}/manual-transfer', [AdminDisbursementVerificationController::class, 'completeManualTransfer'])
        ->middleware('throttle:10,1');
    Route::put('/proof-verifications/{proofId}/verify', [AdminProofVerificationController::class, 'verifyProof'])
        ->middleware('throttle:10,1');
});

Route::middleware('auth:sanctum')->prefix('visits')->group(function () {
    Route::get('/', [VisitController::class, 'index']);
    Route::post('/', [VisitController::class, 'store']); // Donatur submit
    Route::patch('/{id}/respond', [VisitController::class, 'respondVisit']); // Organisasi confirm/reject
    Route::post('/{id}/documentation', [VisitController::class, 'uploadDocumentation']); // Donatur upload bukti
    Route::put('/{id}', [VisitController::class, 'update']);
});

Route::middleware('auth:sanctum')->prefix('donations')->group(function () {
    Route::post('/', [DonationController::class, 'store']);
});