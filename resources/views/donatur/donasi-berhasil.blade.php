@extends('layouts.public')
@section('title', 'Status Donasi - Rangkul')
@section('content')
<main class="checkout-wrap" id="checkout-success" data-id="{{ request()->route('id') }}">
    <section class="checkout-card checkout-success-card">
        <p id="success-message" role="status">Memeriksa status pembayaran...</p>
        <div id="success-content" hidden>
            <div class="checkout-success-icon" aria-hidden="true">✓</div><h1>Pembayaran Berhasil</h1>
            <p>Terima kasih telah berbagi kebaikan. Donasi Anda telah diterima untuk mendukung kampanye ini.</p>
            <h2>Ringkasan Transaksi</h2><dl class="checkout-receipt">
                <div><dt>Program Kampanye</dt><dd id="receipt-campaign"></dd></div>
                <div><dt>Nominal Donasi</dt><dd id="receipt-amount"></dd></div>
                <div><dt>ID Transaksi</dt><dd id="receipt-id"></dd></div>
                <div><dt>Tanggal</dt><dd id="receipt-date"></dd></div>
            </dl>
        </div>
        <div class="checkout-links"><a class="checkout-secondary" href="{{ route('donatur.beranda') }}">Kembali ke Beranda</a><a class="checkout-primary" href="{{ route('donatur.riwayat') }}">Lihat Riwayat Donasi</a></div>
    </section>
</main>
@endsection
