@extends('layouts.public')
@section('title', 'Riwayat Aktivitas - Rangkul')
@section('content')
<div id="activity-page" class="activity-page max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8" data-today="{{ now()->toDateString() }}">
    <header class="activity-heading"><h1>Riwayat Aktivitas</h1><p>Pantau donasi dan kunjungan Anda, serta setiap kebaikan yang telah dibagikan.</p></header>
    <dl class="activity-summary">
        <div><dd id="activity-count">—</dd><dt>Donasi Dilakukan</dt></div>
        <div><dd id="activity-total">—</dd><dt>Total Donasi</dt></div>
        <div><dd id="activity-visits">—</dd><dt>Kunjungan Panti Asuhan</dt></div>
    </dl>
    <div id="activity-status" role="status">Memuat riwayat aktivitas...</div>
    <button id="activity-retry" class="activity-action" hidden>Coba Lagi</button>
    <section class="activity-section" aria-labelledby="donation-history-title">
        <h2 id="donation-history-title">Riwayat Donasi</h2>
        <div class="activity-filters" aria-label="Filter riwayat donasi" data-filter="donations">
            @foreach(['all' => 'Semua', 'belum_bayar' => 'Belum Bayar', 'sudah_bayar' => 'Selesai', 'sudah_disalurkan' => 'Tersalurkan'] as $value => $label)
                <button type="button" data-value="{{ $value }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">{{ $label }}</button>
            @endforeach
        </div>
        <div id="activity-donations" class="activity-list" aria-live="polite"></div>
    </section>
    <section class="activity-section" aria-labelledby="visit-history-title">
        <h2 id="visit-history-title">Riwayat Kunjungan</h2>
        <div class="activity-filters" aria-label="Filter riwayat kunjungan" data-filter="visits">
            @foreach(['all' => 'Semua', 'terkirim' => 'Terkirim', 'dikonfirmasi' => 'Dikonfirmasi', 'ditolak' => 'Ditolak', 'selesai' => 'Selesai'] as $value => $label)
                <button type="button" data-value="{{ $value }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">{{ $label }}</button>
            @endforeach
        </div>
        <div id="activity-visit-list" class="activity-list" aria-live="polite"></div>
    </section>
</div>
<dialog id="activity-dialog" class="panti-dialog" aria-labelledby="activity-dialog-title">
    <button type="button" class="panti-dialog-close" aria-label="Tutup">×</button>
    <h2 id="activity-dialog-title"></h2><div id="activity-dialog-content"></div>
</dialog>
@endsection
