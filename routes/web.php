<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

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