@extends('layouts.public')
@section('title', 'Pembayaran Donasi - Rangkul')
@section('content')
<main class="checkout-wrap" id="checkout-payment" data-id="{{ request()->route('id') }}" data-client-key="{{ config('services.midtrans.client_key') }}" data-snap-url="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}">
    <section class="checkout-card checkout-payment-card">
        <a class="checkout-back" href="{{ route('donatur.riwayat') }}">← Kembali ke Riwayat</a>
        <h1>Selesaikan Pembayaran Donasi</h1>
        <p id="payment-summary" class="checkout-help"></p>
        <p id="payment-message" role="status">Memuat pembayaran...</p>
        <div id="snap-container"></div>
        <div id="preview-qr" hidden style="text-align:center;margin:24px auto">
            <img src="{{ asset('images/preview-qris.svg') }}" alt="Ilustrasi kode QR untuk preview, bukan kode pembayaran" width="220" height="220" style="margin:auto">
            <a href="{{ asset('images/preview-qris.svg') }}" download="contoh-qris.svg" class="checkout-primary">Unduh QRIS Contoh</a>
        </div>
        <a id="payment-provider-link" class="checkout-secondary" hidden target="_blank" rel="noopener noreferrer">Buka Halaman Pembayaran</a>
        <div class="checkout-breakdown checkout-instructions"><h2>Langkah Pembayaran</h2><ol>
            <li>Pilih QRIS pada panel pembayaran di atas.</li>
            <li>Buka mobile banking atau e-wallet yang mendukung QRIS, lalu pindai kode atau gunakan opsi simpan QR pada panel pembayaran.</li>
            <li>Periksa nama penerima dan total pembayaran sebelum mengonfirmasi.</li>
            <li>Setelah membayar, tunggu verifikasi atau tekan tombol di bawah untuk memeriksa status.</li>
        </ol></div>
        <button type="button" class="checkout-primary" id="payment-check">Saya Sudah Membayar</button>
    </section>
</main>
@endsection
