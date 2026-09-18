<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('masuk', function () {
    return view('auth.login');
});

Route::get('/manager/daftaruser', function () {
    return view('manager.daftaruser');
});
Route::get('/manager/laporantransaksi', function () {
    return view('manager.laporantransaksi');
});

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

Route::get('/manager/detail', function () {
    return view('manager.detail');
});