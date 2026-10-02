@extends('layouts.public')
@section('title', 'Detail Donasi - Rangkul')
@section('content')
<main class="checkout-wrap"><section class="checkout-card">
    <a class="checkout-back" href="{{ route('campaign.detail', $campaign->id) }}">← Kembali</a>
    <h1>Detail Donasi</h1>
    <div class="checkout-campaign">
        <img src="{{ $campaign->foto_cover ? (preg_match('~^https?://~', $campaign->foto_cover) ? $campaign->foto_cover : asset('storage/' . $campaign->foto_cover)) : asset('images/hero/hero_children.jpg') }}" alt="">
        <div><h2>{{ $campaign->judul }}</h2><p>{{ $campaign->organization?->nama_lembaga }}</p></div>
    </div>
    <form id="checkout-form" data-title="{{ $campaign->judul }}" data-campaign="{{ $campaign->id }}" data-fee="{{ $fee }}">
        <fieldset><legend>Pilih Nominal Donasi</legend><div class="checkout-presets">
            @foreach([10000, 20000, 50000, 75000, 100000, 250000, 500000, 750000, 1000000] as $amount)
            <button type="button" data-amount="{{ $amount }}" aria-pressed="{{ $amount === 100000 ? 'true' : 'false' }}">Rp {{ number_format($amount, 0, ',', '.') }}</button>
            @endforeach
        </div></fieldset>
        <label for="checkout-amount">Nominal Donasi Lainnya</label>
        <div class="checkout-input"><span>Rp</span><input id="checkout-amount" type="number" inputmode="numeric" min="10000" max="10000000" step="1" value="100000" required aria-describedby="checkout-amount-help"></div>
        <p id="checkout-amount-help" class="checkout-help">Nominal donasi mulai Rp10.000 hingga Rp10.000.000.</p>
        <label for="checkout-note">Doa atau Catatan</label><textarea id="checkout-note" maxlength="255" rows="5" placeholder="Tuliskan doa atau pesan Anda di sini..."></textarea>
        <div class="checkout-breakdown"><h2>Detail Pembayaran</h2><dl>
            <div><dt>Donasi</dt><dd id="checkout-subtotal">Rp100.000</dd></div>
            <div><dt>Biaya layanan</dt><dd>Rp {{ number_format($fee, 0, ',', '.') }}</dd></div>
            <div class="checkout-total"><dt>Total Biaya</dt><dd id="checkout-total">Rp103.000</dd></div>
        </dl></div>
        <label class="checkout-anonymous" for="checkout-anonymous"><span>Donasi Secara Anonim</span><input type="checkbox" id="checkout-anonymous" role="switch"></label>
        <p id="checkout-message" role="status"></p>
        <button type="submit" class="checkout-primary">Lanjut ke Pembayaran</button>
    </form>
</section></main>
@endsection
