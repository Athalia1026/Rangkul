<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Admin\OrganizationVerificationController;

Route::post('/register/donor', [AuthController::class, 'registerDonor']);
Route::post('/register/organization', [AuthController::class, 'registerOrganization']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);

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
