@extends('layouts.public')
@section('title', 'Dashboard Premium - Rangkul')
@section('content')
<main id="premium-dashboard-page" class="premium-page dashboard-page max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
    <header class="dashboard-header">
        <div>
            <h1>Dashboard Premium Perusahaan</h1>
            <p>Pantau donasi dan dampaknya dalam satu dashboard</p>
        </div>
        <div class="dashboard-controls">
            <div class="dashboard-period">
                <button type="button" id="period-button" class="dashboard-period-button" aria-haspopup="listbox" aria-expanded="false" aria-controls="period-list" aria-label="Pilih periode" disabled>
                    <svg width="20" height="20" viewBox="0 0 20 22" aria-hidden="true"><path fill="#0b5d36" d="M5 0h2v3h6V0h2v3h3a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3V0Zm-3 9v11h16V9H2Z"/></svg>
                    <span id="period-label">Memuat periode...</span>
                    <svg class="dashboard-chevron" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <ul id="period-list" class="dashboard-period-list" role="listbox" aria-label="Periode" tabindex="-1" hidden></ul>
            </div>
            <button type="button" id="dashboard-export" class="profile-btn primary">Export Laporan</button>
        </div>
    </header>
    <p id="dashboard-status" class="premium-success-status" role="status">Memuat dashboard...</p>
    <p id="dashboard-message" class="profile-message dashboard-message" role="status"></p>

    <div id="dashboard-content" hidden>
        <section class="dashboard-stats" aria-label="Ringkasan periode">
            <div>
                <div class="dashboard-stat-top">
                    <span class="dashboard-stat-icon green" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="3"/><path d="M6 10v4m12-4v4"/></svg></span>
                    <span id="stat-growth" class="dashboard-growth" hidden></span>
                </div>
                <p class="dashboard-stat-label">Total Donasi</p>
                <p class="dashboard-stat-value" id="stat-total">—</p>
            </div>
            <div>
                <span class="dashboard-stat-icon red" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><path d="M12 21s-8-5.1-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 5.9-8 11-8 11Z"/><path d="m9 12 2 2 4-4"/></svg></span>
                <p class="dashboard-stat-label">Penggalangan Dana Didukung</p>
                <p class="dashboard-stat-value"><span id="stat-campaigns">—</span><span>Penggalangan Dana</span></p>
            </div>
            <div>
                <span class="dashboard-stat-icon yellow" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3 3 10.5V21h6v-6h6v6h6V10.5Z"/></svg></span>
                <p class="dashboard-stat-label">Organisasi Dibantu</p>
                <p class="dashboard-stat-value"><span id="stat-organizations">—</span><span>Organisasi</span></p>
            </div>
            <div>
                <span class="dashboard-stat-icon blue" aria-hidden="true"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="7.5" r="4.5"/><path d="M3 21a9 9 0 0 1 18 0Z"/></svg></span>
                <p class="dashboard-stat-label">Total Penerima Manfaat</p>
                <p class="dashboard-stat-value"><span id="stat-beneficiaries">—</span><span>Anak</span></p>
            </div>
        </section>

        <section class="dashboard-panel" aria-labelledby="trend-title">
            <div class="dashboard-panel-head">
                <h2 id="trend-title">Tren Donasi</h2>
                <div class="dashboard-toggle" role="group" aria-label="Ukuran tren">
                    <button type="button" data-metric="frekuensi" aria-pressed="true">Frekuensi Donasi</button>
                    <button type="button" data-metric="total" aria-pressed="false">Total Donasi</button>
                </div>
            </div>
            <div id="trend-chart" class="dashboard-chart"></div>
            <table id="trend-table" class="sr-only"><caption>Tren donasi per bulan</caption><thead><tr><th>Bulan</th><th>Frekuensi</th><th>Total</th></tr></thead><tbody></tbody></table>
        </section>

        <section class="dashboard-panel" aria-labelledby="recent-title">
            <div class="dashboard-panel-head">
                <h2 id="recent-title" class="is-green">Riwayat Donasi Terkini</h2>
                <a href="{{ route('donatur.riwayat') }}" class="dashboard-link">Lihat Semua</a>
            </div>
            <div class="dashboard-table-wrap">
                <table class="dashboard-table">
                    <thead><tr><th scope="col">Campaign / Panti Asuhan</th><th scope="col">Tanggal</th><th scope="col">Nominal</th><th scope="col">Status</th><th scope="col"><span class="sr-only">Aksi</span></th></tr></thead>
                    <tbody id="recent-body"></tbody>
                </table>
            </div>
        </section>
    </div>
</main>
@endsection
