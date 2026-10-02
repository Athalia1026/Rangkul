@extends('layouts.donor')

@section('content')
    @php
        $hero = $topCampaigns->values();
        $featured = $hero->first();
        $cover = fn ($campaign) => $campaign?->foto_cover
            ? (filter_var($campaign->foto_cover, FILTER_VALIDATE_URL) ? $campaign->foto_cover : asset('storage/' . ltrim($campaign->foto_cover, '/')))
            : asset('images/hero/hero_children.jpg');
    @endphp
    <section class="donor-hero" aria-label="Kampanye pilihan" aria-roledescription="carousel">
        @forelse ($hero as $campaign)
            <article class="donor-hero-slide {{ $loop->first ? 'is-active' : ($loop->index === 1 ? 'is-next' : ($loop->last ? 'is-previous' : '')) }}" data-hero-slide aria-hidden="{{ $loop->first ? 'false' : 'true' }}" @if (!$loop->first) inert @endif>
                <img src="{{ $cover($campaign) }}" alt="{{ $campaign->judul }}" @if (!$loop->first) loading="lazy" @endif>
                <div class="donor-hero-copy">
                    <h1>{{ $campaign->judul }}</h1>
                    <p>{{ $campaign->deskripsi }}</p>
                    <a href="{{ route('campaign.detail', $campaign->id) }}" class="donor-button">Donasi Sekarang <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z"/></svg></a>
                </div>
            </article>
        @empty
            <div class="donor-empty">Belum ada kampanye aktif. Kebaikan berikutnya segera hadir.</div>
        @endforelse
        @if ($hero->count() > 1)
            <button type="button" class="donor-hero-control previous" data-hero-step="-1" aria-label="Kampanye sebelumnya"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5m6 6-6-6 6-6" /></svg></button>
            <button type="button" class="donor-hero-control next" data-hero-step="1" aria-label="Kampanye berikutnya"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" /></svg></button>
        @endif
    </section>

    <section class="donor-section donor-campaign-section" aria-labelledby="today-title">
        <div class="donor-section-header">
            <h2 id="today-title" class="donor-heading">Bantu Mereka Hari Ini</h2>
            <div class="donor-track-controls">
                <button type="button" data-track-step="-1" aria-controls="donor-today-track" aria-label="Kampanye hari ini sebelumnya"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5m6 6-6-6 6-6" /></svg></button>
                <button type="button" data-track-step="1" aria-controls="donor-today-track" aria-label="Kampanye hari ini berikutnya"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" /></svg></button>
            </div>
        </div>
        <div id="donor-today-track" class="donor-track" tabindex="0" aria-label="Kampanye hari ini, geser untuk melihat lainnya">
            @forelse ($todayCampaigns as $campaign)
                <x-donor-campaign-card :campaign="$campaign" />
            @empty
                <p class="donor-empty">Belum ada kampanye tersedia.</p>
            @endforelse
        </div>
    </section>

    <section class="donor-recommendation donor-shell" aria-labelledby="recommendation-title">
        <h2 id="recommendation-title" class="donor-heading">Rekomendasi Donasi</h2>
        <div class="donor-banner">
            <div class="donor-banner-copy">
                <h3>{{ $featured?->judul ?? 'Bantu Wujudkan Harapan Mereka Hari Ini!' }}</h3>
                <p>Setiap bantuan Anda berarti. Bersama, kita dapat memenuhi kebutuhan sehari-hari dan menghadirkan harapan bagi anak-anak yang membutuhkan.</p>
                <a class="donor-button donor-button-orange" href="{{ $featured ? route('campaign.detail', $featured->id) : route('search') }}">Bantu Mereka Sekarang</a>
            </div>
            <img src="{{ asset('images/hero/students_peace.jpg') }}" alt="Anak-anak yang membutuhkan dukungan pendidikan" loading="lazy">
        </div>
    </section>

    <section class="donor-section donor-priority" aria-labelledby="priority-title">
        <div class="donor-section-header">
            <h2 id="priority-title" class="donor-heading">Penggalangan Dana Prioritas</h2>
            <div class="donor-track-controls">
                <button type="button" data-track-step="-1" aria-controls="donor-priority-track" aria-label="Kampanye prioritas sebelumnya"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5m6 6-6-6 6-6" /></svg></button>
                <button type="button" data-track-step="1" aria-controls="donor-priority-track" aria-label="Kampanye prioritas berikutnya"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6" /></svg></button>
            </div>
        </div>
        <div id="donor-priority-track" class="donor-track" tabindex="0" aria-label="Kampanye prioritas, geser untuk melihat lainnya">
            @forelse ($priorityCampaigns as $campaign)
                <x-donor-campaign-card :campaign="$campaign" :priority="true" />
            @empty
                <p class="donor-empty">Belum ada kampanye prioritas.</p>
            @endforelse
        </div>
    </section>
@endsection
