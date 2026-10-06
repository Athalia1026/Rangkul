@extends('layouts.public')
@section('title', 'Pembayaran Berhasil - Rangkul')
@section('content')
<main id="premium-success-page" class="premium-page max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8" data-id="{{ request()->route('id') }}">
    <section class="premium-success-card">
        <p id="premium-success-status" class="premium-success-status" role="status">Memeriksa status pembayaran...</p>

        <div id="premium-success-content" hidden>
            <span class="premium-success-icon" aria-hidden="true">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg>
            </span>
            <h1>Pembayaran Berhasil</h1>
            <p class="premium-success-text">Pembayaran berhasil! Selamat datang di Premium. Nikmati berbagai fitur eksklusif yang kini telah tersedia di akun Anda.</p>

            <div class="premium-success-grid">
                <div class="premium-success-box">
                    <span>Status Akun</span>
                    <strong>
                        <svg width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path fill="#0b5d36" d="m12 1 2.6 2.1 3.3-.4.9 3.2 2.9 1.7-1.2 3.1 1.2 3.1-2.9 1.7-.9 3.2-3.3-.4L12 23l-2.6-2.1-3.3.4-.9-3.2-2.9-1.7 1.2-3.1-1.2-3.1 2.9-1.7.9-3.2 3.3.4Z"/><path d="m8 12.2 2.7 2.7L16 9.6" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Premium
                    </strong>
                </div>
                <div class="premium-success-box">
                    <span>Masa Berlaku</span>
                    <strong>
                        <svg width="18" height="20" viewBox="0 0 20 22" aria-hidden="true"><path fill="#0b5d36" d="M5 0h2v3h6V0h2v3h3a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3V0Zm-3 9v11h16V9H2Z"/></svg>
                        <span id="premium-duration">—</span>
                    </strong>
                </div>
            </div>
            <div class="premium-success-trx">
                <span>ID TRANSAKSI</span>
                <strong id="premium-transaction">—</strong>
            </div>

            <div class="premium-success-actions">
                <a href="{{ route('donatur.dashboard') }}" class="profile-btn primary">
                    Buka Dashboard
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
                </a>
                <button type="button" id="premium-invoice" class="profile-btn secondary">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12m-5-5 5 5 5-5M4 17v3h16v-3"/></svg>
                    Simpan Invoice
                </button>
            </div>
            <p id="premium-invoice-message" class="profile-message premium-success-note" role="status"></p>
        </div>

        <div id="premium-pending" class="premium-pending" hidden>
            <h1>Menunggu Pembayaran</h1>
            <p class="premium-success-text">Pembayaran Anda belum kami terima. Halaman ini akan diperbarui otomatis setelah pembayaran selesai.</p>
            <div class="premium-success-actions">
                <button type="button" id="premium-recheck" class="profile-btn primary">Cek Status Pembayaran</button>
                <a href="{{ route('donatur.premium.daftar') }}" class="profile-btn secondary">Kembali ke Pembayaran</a>
            </div>
        </div>
    </section>
</main>
@endsection
