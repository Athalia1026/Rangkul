@extends('layouts.public')
@section('title', 'Pratinjau Laporan - Rangkul')
@section('content')
<main id="report-preview-page" class="premium-page report-page max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
    <a href="{{ route('donatur.dashboard') }}" id="report-back" class="password-back">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
        Kembali
    </a>
    <header class="report-toolbar">
        <h1>Pratinjau Laporan</h1>
        <button type="button" id="report-download" class="profile-btn secondary" disabled>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12m-5-5 5 5 5-5M4 17v3h16v-3"/></svg>
            Unduh Laporan
        </button>
    </header>
    <p id="report-status" class="premium-success-status" role="status">Memuat laporan...</p>
    <p id="report-message" class="profile-message dashboard-message" role="status"></p>

    <article id="report-sheet" class="report-sheet" aria-label="Laporan Dampak CSR" hidden>
        <img class="report-logo" src="{{ asset('images/logo.png') }}" alt="Rangkul.com">
        <div class="report-head">
            <div>
                <h2>Laporan Dampak CSR</h2>
                <p>Periode: <span id="report-period"></span></p>
            </div>
            <div class="report-company">
                <img id="report-company-logo" alt="" hidden>
                <strong id="report-company-name"></strong>
            </div>
        </div>

        <h3 class="report-section-title">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true"><path d="M6 2h8l5 5v15H6Z"/><path d="M14 2v5h5"/></svg>
            Ringkasan Donasi
        </h3>
        <div class="report-panel report-stats">
            <div>
                <div class="dashboard-stat-top">
                    <span class="dashboard-stat-icon green" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="3"/></svg></span>
                    <span id="report-growth" class="dashboard-growth" hidden></span>
                </div>
                <p class="dashboard-stat-label">Total Donasi</p>
                <p class="dashboard-stat-value" id="report-total"></p>
            </div>
            <div>
                <span class="dashboard-stat-icon yellow" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 14h3l4 3h6a2 2 0 0 0 0-4h-4M3 14v6h3l3 1h7l5-4"/><circle cx="16" cy="6" r="3"/></svg></span>
                <p class="dashboard-stat-label">Donasi Tersalurkan</p>
                <p class="dashboard-stat-value" id="report-distributed"></p>
            </div>
            <div>
                <span class="dashboard-stat-icon red" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><path d="M12 21s-8-5.1-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 5.9-8 11-8 11Z"/></svg></span>
                <p class="dashboard-stat-label">Penggalangan Dana</p>
                <p class="dashboard-stat-value"><span id="report-supported"></span><span id="report-campaigns-distributed"></span></p>
            </div>
            <div>
                <span class="dashboard-stat-icon blue" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="7.5" r="4.5"/><path d="M3 21a9 9 0 0 1 18 0Z"/></svg></span>
                <p class="dashboard-stat-label">Total Penerima Manfaat</p>
                <p class="dashboard-stat-value"><span id="report-beneficiaries"></span><span>Anak</span></p>
            </div>
        </div>

        <section class="report-panel">
            <h3 class="report-panel-title">Detail Penyaluran Donasi</h3>
            <div class="dashboard-table-wrap">
                <table class="dashboard-table report-table">
                    <thead><tr><th scope="col">Campaign / Panti Asuhan</th><th scope="col">Tanggal</th><th scope="col">Nominal</th><th scope="col" class="center">Status</th></tr></thead>
                    <tbody id="report-rows"></tbody>
                </table>
            </div>
        </section>

        <h3 class="report-section-title">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="5" width="16" height="15" rx="2"/><path d="M3 17V4a1 1 0 0 1 1-1h13"/><path d="m8 17 4-4 3 3 2-2 3 3"/></svg>
            Dokumentasi &amp; Bukti
        </h3>
        <div id="report-gallery" class="report-gallery"></div>

        <section class="report-panel report-conclusion">
            <h3 class="report-panel-title">Kesimpulan</h3>
            <p id="report-conclusion"></p>
        </section>
    </article>
</main>
@endsection
