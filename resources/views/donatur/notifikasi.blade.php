@extends('layouts.public')
@section('title', 'Notifikasi - Rangkul')
@section('content')
<main class="donor-updates-wrap" id="notifications-page">
    <section class="donor-updates-card">
        <header class="updates-header"><a href="{{ route('donatur.beranda') }}">← Kembali</a><h1>Notifikasi</h1></header>
        <div class="updates-toolbar"><p>Informasi terbaru tentang donasi dan kunjungan Anda.</p><button type="button" id="notification-preview">Lihat contoh tampilan</button></div>
        <p id="updates-message" role="status">Memuat notifikasi...</p>
        <div id="notification-list"></div>
    </section>
</main>
@endsection
