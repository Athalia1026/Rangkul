<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Donors\SearchController;
use App\Http\Controllers\Donors\CampaignController;
use App\Http\Controllers\HomeController;


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
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', function () {
            return view('organisasi.dashboard');
        })->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | KAMPANYE
        |--------------------------------------------------------------------------
        */

        Route::get('/kampanye', function () {
            return view('organisasi.kampanye');
        })->name('kampanye');


        Route::get('/kampanye/detail', function () {
            return view('organisasi.kampanye-detail');
        })->name('kampanye.detail');


        /*
        |--------------------------------------------------------------------------
        | PENCAIRAN DANA
        |--------------------------------------------------------------------------
        */

        Route::get('/kampanye/pencairan/ajukan', function () {
            return view('organisasi.pencairan-ajukan');
        })->name('kampanye.pencairan.ajukan');


        /*
        |--------------------------------------------------------------------------
        | BUKTI PENYALURAN
        |--------------------------------------------------------------------------
        */

        Route::get('/kampanye/bukti/upload', function () {
            return view('organisasi.bukti-upload');
        })->name('kampanye.bukti.upload');


        Route::get('/kampanye/bukti/detail', function () {
            return view('organisasi.bukti-detail');
        })->name('kampanye.bukti.detail');


        /*
        |--------------------------------------------------------------------------
        | DONASI
        |--------------------------------------------------------------------------
        */

        Route::get('/donasi', function () {
            return view('organisasi.donasi');
        })->name('donasi');


        Route::get('/donasi/detail', function () {
            return view('organisasi.donasi-detail');
        })->name('donasi.detail');


        /*
        |--------------------------------------------------------------------------
        | KUNJUNGAN
        |--------------------------------------------------------------------------
        */

        Route::get('/kunjungan', function () {
            return view('organisasi.kunjungan');
        })->name('kunjungan');


        Route::get('/kunjungan/detail', function () {
            return view('organisasi.kunjungan-detail');
        })->name('kunjungan.detail');


        /*
        |--------------------------------------------------------------------------
        | LAPORAN
        |--------------------------------------------------------------------------
        */

        Route::get('/laporan', function () {
            return view('organisasi.laporan');
        })->name('laporan');


        Route::get('/laporan/hasil', function () {
            return view('organisasi.laporan-hasil');
        })->name('laporan.hasil');


        /*
        |--------------------------------------------------------------------------
        | PROFIL
        |--------------------------------------------------------------------------
        */

        Route::get('/profil', function () {
            return view('organisasi.profil');
        })->name('profil');


        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI
        |--------------------------------------------------------------------------
        */

        Route::get('/notifikasi', function () {
            return view('organisasi.notifikasi');
        })->name('notifikasi');

    });