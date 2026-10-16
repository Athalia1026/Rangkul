@extends('layouts.public')
@section('title', 'Pembayaran Donasi - Rangkul')
@section('content')
<main class="checkout-wrap" id="checkout-payment" data-id="{{ request()->route('id') }}" data-client-key="{{ config('services.midtrans.client_key') }}" data-snap-url="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}">
    <section class="checkout-card checkout-payment-card">
        <x-back-link :href="route('donatur.riwayat')">Kembali ke Riwayat</x-back-link>
        <h1>Selesaikan Pembayaran Donasi</h1>
        <div class="payment-summary">
            <div class="min-w-0">
                <span>Kampanye</span>
                <strong id="payment-campaign">-</strong>
            </div>
            <div class="payment-summary-total">
                <span>Total Pembayaran</span>
                <strong id="payment-total">-</strong>
            </div>
        </div>
        <p id="payment-message" class="payment-status" role="status">Memuat pembayaran...</p>
        <div class="payment-actions">
            <button type="button" class="checkout-primary" id="payment-open" hidden>Bayar Sekarang</button>
            <button type="button" class="checkout-secondary" id="payment-check">Cek Status Pembayaran</button>
            <a id="payment-retry" class="checkout-primary" hidden>Buat Donasi Baru</a>
        </div>
        <div class="checkout-breakdown checkout-instructions"><h2>Langkah Pembayaran</h2><ol>
            <li>Tekan tombol <strong>Bayar Sekarang</strong> untuk membuka halaman pembayaran Midtrans.</li>
            <li>Pilih metode pembayaran yang tersedia (QRIS atau GoPay), lalu ikuti petunjuk pada halaman tersebut.</li>
            <li>Periksa nama penerima dan total pembayaran sebelum mengonfirmasi.</li>
            <li>Setelah membayar, status akan diperbarui otomatis. Anda juga dapat menekan tombol <strong>Cek Status Pembayaran</strong>.</li>
        </ol></div>
    </section>
</main>
@endsection
