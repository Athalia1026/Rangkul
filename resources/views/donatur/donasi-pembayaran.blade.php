@extends('layouts.public')
@section('title', 'Pembayaran Donasi - Rangkul')
@section('content')
<main class="checkout-wrap" id="checkout-payment" data-id="{{ request()->route('id') }}" data-client-key="{{ config('services.midtrans.client_key') }}" data-snap-url="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}">
    <section class="checkout-card checkout-payment-card">
        <a class="checkout-back" href="{{ route('donatur.riwayat') }}">← Kembali ke Riwayat</a>
        <h1>Selesaikan Pembayaran Donasi</h1>
        <p id="payment-summary" class="checkout-help"></p>
        <p id="payment-message" role="status">Memuat pembayaran...</p>
        <button type="button" class="checkout-primary" id="payment-open" hidden>Bayar Sekarang</button>
        <a id="payment-provider-link" class="checkout-secondary" hidden target="_blank" rel="noopener noreferrer">Buka Halaman Pembayaran di Tab Baru</a>
        <a id="payment-retry" class="checkout-secondary" hidden>Buat Donasi Baru</a>
        <div class="checkout-breakdown checkout-instructions"><h2>Langkah Pembayaran</h2><ol>
            <li>Tekan tombol <strong>Bayar Sekarang</strong> untuk membuka halaman pembayaran Midtrans.</li>
            <li>Pilih metode pembayaran yang tersedia (QRIS atau GoPay), lalu ikuti petunjuk pada halaman tersebut.</li>
            <li>Periksa nama penerima dan total pembayaran sebelum mengonfirmasi.</li>
            <li>Setelah membayar, status akan diperbarui otomatis. Anda juga dapat menekan tombol di bawah untuk memeriksa status.</li>
        </ol></div>
        <button type="button" class="checkout-secondary" id="payment-check">Saya Sudah Membayar</button>
    </section>
</main>
@endsection
