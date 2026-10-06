@extends('layouts.public')
@section('title', 'Lengkapi Data Perusahaan - Rangkul')
@section('content')
<main id="premium-register-page" class="premium-page max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8"
    data-client-key="{{ config('services.midtrans.client_key') }}"
    data-snap-url="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}">
    <header class="premium-heading">
        <h1>Lengkapi Data Perusahaan</h1>
        <p>Lengkapi data perusahaan untuk melanjutkan proses upgrade ke Premium.</p>
    </header>

    <form id="premium-form" class="premium-card" novalidate>
        <div class="premium-fields">
            <label class="is-wide">Nama PIC<input name="nama_pic" type="text" autocomplete="name" maxlength="255" placeholder="Masukkan nama penanggung jawab" required></label>
            <label>Jabatan<input name="jabatan" type="text" autocomplete="organization-title" maxlength="255" placeholder="Masukkan jabatan PIC" required></label>
            <label>Nomor Telepon<input name="nomor_pic" type="tel" inputmode="tel" autocomplete="tel" maxlength="20" placeholder="Contoh : 0891000012938" required></label>
            <label>Email Korporat<input name="email_korporat" type="email" autocomplete="email" maxlength="255" placeholder="Masukkan email perusahaan" required></label>
            <label>NPWP<input name="NPWP" type="text" inputmode="numeric" maxlength="30" placeholder="Masukkan nomor NPWP" required></label>
        </div>

        <section class="premium-summary" aria-labelledby="premium-summary-title">
            <h2 id="premium-summary-title">Ringkasan Pemesanan</h2>
            <dl>
                <div class="is-main"><dt>Akun Premium Perusahaan</dt><dd data-price="price">—</dd></div>
                <div><dt>Biaya Layanan</dt><dd data-price="service_fee">—</dd></div>
                <div><dt id="premium-tax-label">Pajak (PPN 11%)</dt><dd data-price="tax">—</dd></div>
                <div class="is-total"><dt>Total Biaya</dt><dd data-price="total">—</dd></div>
            </dl>
        </section>

        <div class="premium-submit">
            <p id="premium-form-message" class="profile-message" role="status"></p>
            <button type="button" id="premium-check" class="profile-btn secondary" hidden>Cek Status Pembayaran</button>
            <button type="submit" id="premium-pay" class="profile-btn primary">Lanjutkan Pembayaran</button>
        </div>
    </form>
</main>
@endsection
