<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Donors\SearchController;
use App\Http\Controllers\Donors\CampaignController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Donors\ProfileController;
use App\Http\Controllers\Donors\VisitController;
use App\Http\Controllers\Organizations\CampaignController as OrganizationCampaignController;
use App\Http\Controllers\Organizations\OrganizationDashboardController;
use App\Http\Controllers\Organizations\OrganizationDisbursementController;
use App\Http\Controllers\Organizations\OrganizationDonationController;
use App\Http\Controllers\Organizations\OrganizationGalleryController;
use App\Http\Controllers\Organizations\OrganizationNotificationController;
use App\Http\Controllers\Organizations\OrganizationProfileController;
use App\Http\Controllers\Organizations\OrganizationReportController;
use App\Http\Controllers\Organizations\OrganizationVisitController;
use App\Http\Middleware\EnsureOrganizationAccount;

Route::view('/donatur/riwayat', 'donatur.riwayat')->name('donatur.riwayat');
Route::view('/donatur/kunjungan/jadwalkan/{organization}', 'donatur.kunjungan', ['mode' => 'create'])->name('donatur.kunjungan.create');
Route::view('/donatur/kunjungan/{id}/edit', 'donatur.kunjungan', ['mode' => 'edit'])->name('donatur.kunjungan.edit');
Route::view('/donatur/kunjungan/{id}', 'donatur.kunjungan', ['mode' => 'detail'])->name('donatur.kunjungan.detail');
Route::view('/donatur/profil', 'donatur.profil')->name('donatur.profil');
Route::view('/donatur/profil/ubah-password', 'donatur.ubah-password')->name('donatur.password');
Route::view('/donatur/premium/daftar', 'donatur.premium-daftar')->name('donatur.premium.daftar');
Route::view('/donatur/premium/berhasil/{id}', 'donatur.premium-berhasil')->name('donatur.premium.berhasil');
Route::view('/donatur/dashboard', 'donatur.dashboard')->name('donatur.dashboard');
Route::view('/donatur/dashboard/laporan', 'donatur.laporan')->name('donatur.laporan');
Route::view('/donatur/notifikasi', 'donatur.notifikasi')->name('donatur.notifikasi');
Route::view('/donatur/penyaluran/{id}', 'donatur.penyaluran')->name('donatur.penyaluran');
Route::get('/donatur/donasi/{campaignId}', [\App\Http\Controllers\Donors\DonationController::class, 'checkout'])->name('donatur.donasi');
Route::view('/donatur/pembayaran/{id}', 'donatur.donasi-pembayaran')->name('donatur.pembayaran');
Route::view('/donatur/donasi-berhasil/{id}', 'donatur.donasi-berhasil')->name('donatur.donasi.berhasil');

Route::get('/donatur/panti/{organizationId}', [OrganizationProfileController::class, 'page'])->name('donatur.panti.show');


/*
|--------------------------------------------------------------------------
| PUBLIC / COMPANY PROFILE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/manager/home');
    }

    return view('company-profile');
});


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('auth.login');
})->name('login');


Route::post('/login', [AuthController::class, 'loginWeb'])
    ->middleware('throttle:5,1')
    ->name('login.store');


// Lupa & atur ulang password: halaman saja, proses dikirim ke /api/forgot-password dan /api/reset-password.
Route::view('/lupa-password', 'auth.forgot-password')->name('password.request');
Route::view('/lupa-password/cek-email', 'auth.forgot-password-sent')->name('password.sent');

Route::get('/reset-password', function (\Illuminate\Http\Request $request) {
    return view('auth.reset-password', [
        'token' => (string) $request->query('token', ''),
        'email' => (string) $request->query('email', ''),
    ]);
})->name('password.reset');


Route::post('/logout', [AuthController::class, 'logoutWeb'])
    ->name('logout');


Route::get('/register', function () {
    return view('auth.pick-role');
})->name('register');


/*
|--------------------------------------------------------------------------
| REGISTER DONOR
|--------------------------------------------------------------------------
*/

Route::get('/register-donors', function () {
    return view('auth.register-donors');
})->name('register-donors');


Route::post('/register-donors', [AuthController::class, 'registerWeb'])
    ->middleware('throttle:5,1')
    ->name('register.store');


/*
|--------------------------------------------------------------------------
| REGISTER ORGANIZATION
|--------------------------------------------------------------------------
*/

Route::get('/register-organizations', function () {
    return view('auth.register-organizations');
})->name('register-organizations');


Route::post('/register-organizations', [AuthController::class, 'registerOrganizationWeb'])
    ->middleware('throttle:3,1')
    ->name('register.organization.store');


/*
|--------------------------------------------------------------------------
| ORGANIZATION REGISTRATION STATUS
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

Route::get('/search', [SearchController::class, 'page'])
    ->name('search');


Route::get('/search-results', [SearchController::class, 'results'])
    ->name('search.results');


Route::get('/search_result', [SearchController::class, 'results'])
    ->name('search.result');


/*
|--------------------------------------------------------------------------
| PUBLIC CAMPAIGN
|--------------------------------------------------------------------------
*/

Route::get('/campaign/{id}', [CampaignController::class, 'show'])
    ->name('campaign.detail');


Route::get('/campaign/{id}/prayers', [CampaignController::class, 'prayers'])
    ->name('campaign.prayers');


/*
|--------------------------------------------------------------------------
| BERANDA
|--------------------------------------------------------------------------
*/

Route::get('/beranda', [HomeController::class, 'index']);
Route::get('/donatur/beranda', [HomeController::class, 'index'])->name('donatur.beranda');
Route::get('/donatur/cari', [SearchController::class, 'page'])->name('donatur.cari');
Route::get('/donatur/hasil-pencarian', [SearchController::class, 'results'])->name('donatur.search.results');


/*
|--------------------------------------------------------------------------
| MANAGER
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| ORGANISASI
|--------------------------------------------------------------------------
*/

Route::prefix('organisasi')
    ->name('organisasi.')
    ->middleware(['auth', EnsureOrganizationAccount::class])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [OrganizationDashboardController::class, 'index'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | KAMPANYE
        |--------------------------------------------------------------------------
        */

        Route::get('/kampanye', [OrganizationCampaignController::class, 'index'])
            ->name('kampanye');


        Route::get('/kampanye/{campaign}', [OrganizationCampaignController::class, 'show'])
            ->name('kampanye.detail');


        Route::put('/kampanye/{campaign}/deskripsi', [OrganizationCampaignController::class, 'updateDescription'])
            ->name('kampanye.deskripsi.update');


        /*
        |--------------------------------------------------------------------------
        | PENCAIRAN DANA
        |--------------------------------------------------------------------------
        */

        Route::get('/kampanye/{campaign}/pencairan/ajukan', [OrganizationDisbursementController::class, 'create'])
            ->name('kampanye.pencairan.ajukan');


        Route::post('/pencairan', [OrganizationDisbursementController::class, 'requestDisbursement'])
            ->name('kampanye.pencairan.store');


        /*
        |--------------------------------------------------------------------------
        | BUKTI PENYALURAN
        |--------------------------------------------------------------------------
        */

        Route::get('/kampanye/{campaign}/bukti/upload', [OrganizationDisbursementController::class, 'createProof'])
            ->name('kampanye.bukti.upload');


        Route::post('/pencairan/{disbursement}/bukti', [OrganizationDisbursementController::class, 'uploadProof'])
            ->name('kampanye.bukti.store');


        Route::get('/bukti/{proof}', [OrganizationDisbursementController::class, 'showProof'])
            ->name('kampanye.bukti.detail');


        /*
        |--------------------------------------------------------------------------
        | DONASI
        |--------------------------------------------------------------------------
        */

        Route::get('/donasi', [OrganizationDonationController::class, 'index'])
            ->name('donasi');


        Route::get('/donasi/{donation}', [OrganizationDonationController::class, 'show'])
            ->name('donasi.detail');


        /*
        |--------------------------------------------------------------------------
        | KUNJUNGAN
        |--------------------------------------------------------------------------
        */

        Route::get('/kunjungan', [OrganizationVisitController::class, 'index'])
            ->name('kunjungan');


        Route::get('/kunjungan/{visit}', [OrganizationVisitController::class, 'show'])
            ->name('kunjungan.detail');


        Route::patch('/kunjungan/{visit}/respond', [VisitController::class, 'respondVisit'])
            ->name('kunjungan.respond');


        /*
        |--------------------------------------------------------------------------
        | LAPORAN
        |--------------------------------------------------------------------------
        */

        Route::get('/laporan', [OrganizationReportController::class, 'index'])
            ->name('laporan');


        Route::get('/laporan/hasil', [OrganizationReportController::class, 'result'])
            ->name('laporan.hasil');


        Route::get('/laporan/download', [OrganizationReportController::class, 'download'])
            ->name('laporan.download');


        /*
        |--------------------------------------------------------------------------
        | PROFIL
        |--------------------------------------------------------------------------
        */

        Route::get('/profil', [OrganizationProfileController::class, 'edit'])
            ->name('profil');


        Route::put('/profil', [OrganizationProfileController::class, 'update'])
            ->name('profil.update');


        Route::post('/profil/galeri', [OrganizationGalleryController::class, 'store'])
            ->name('profil.galeri.store');


        Route::post('/profil/foto', [ProfileController::class, 'updatePhoto'])
            ->name('profil.foto');


        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI
        |--------------------------------------------------------------------------
        */

        Route::get('/notifikasi', [OrganizationNotificationController::class, 'index'])
            ->name('notifikasi');

    });
Route::get('/manager/laporantransaksi', function () {
    return view('manager.laporantransaksi');
});

Route::get('/manager/detailpengajuan', function () {
    return view('manager.detailpengajuan');
});

Route::get('/manager/detailuser', function () {
    return view('manager.detailuser');
});
