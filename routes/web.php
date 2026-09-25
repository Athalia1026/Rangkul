<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('organisasi.dashboard');
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


        Route::get('/kampanye/pencairan/ajukan', function () {
            return view('organisasi.pencairan-ajukan');
        })->name('kampanye.pencairan.ajukan');


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