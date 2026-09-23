<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Donors\SearchController;
use App\Http\Controllers\Donors\CampaignController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/search', [SearchController::class, 'page'])->name('search');
Route::get('/search-results', [SearchController::class, 'results'])->name('search.results');
Route::get('/search_result', [SearchController::class, 'results'])->name('search.result');

// Campaign detail route
Route::get('/campaign/{id}', [CampaignController::class, 'show'])->name('campaign.detail');
Route::get('/campaign/{id}/prayers', [CampaignController::class, 'prayers'])->name('campaign.prayers');

Route::get('/register', function () {
    return view('auth.pick-role');
})->name('register');

Route::get('/register-donors', function () {
    return view('auth.register-donors');
})->name('register-donors');

Route::post('/register-donors', [AuthController::class, 'registerWeb'])
    ->middleware('throttle:5,1')
    ->name('register.store');

Route::get('/register-organizations', function () {
    return view('auth.register-organizations');
})->name('register-organizations');

Route::get('/organization/pending', function () {
    return view('auth.organization-pending');
})->name('organization.pending');

Route::get('/organization/rejected', [AuthController::class, 'showOrganizationRejected'])
    ->name('organization.rejected');

Route::get('/organization/resubmit', [AuthController::class, 'showOrganizationResubmit'])
    ->name('organization.resubmit.form');

Route::post('/organization/resubmit', [AuthController::class, 'resubmitOrganizationWeb'])
    ->middleware('throttle:3,1')
    ->name('organization.resubmit');

Route::post('/register-organizations', [AuthController::class, 'registerOrganizationWeb'])
    ->middleware('throttle:3,1')
    ->name('register.organization.store');

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/manager/home');
    }

    return view('company-profile');
});

Route::get('/beranda', [HomeController::class, 'index']);

Route::get('/manager/home', function () {
    return view('manager.home');
});

Route::get('/manager/detail', function () {
    return view('manager.detail');
});

Route::get('/manager/daftaruser', function () {
    return view('manager.daftaruser');
});

Route::get('/manager/detailuser', function () {
    return view('manager.detailuser');
});

Route::get('/manager/detailtransaksi', function () {
    return view('manager.detailtransaksi');
});