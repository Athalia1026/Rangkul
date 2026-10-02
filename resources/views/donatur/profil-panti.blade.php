@extends('layouts.public')
@section('title', $organization->nama_lembaga . ' - Rangkul')
@section('content')
@php
    $gallery = $organization->galleries;
    $cover = $gallery->first()?->image_url ?: asset('images/hero/hero_children.jpg');
    $initials = collect(preg_split('/\s+/u', trim($organization->nama_lembaga)))->take(2)->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))->implode('');
    $address = trim($organization->alamat . ', ' . $organization->kota, ', ');
    $maps = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address);
    if (preg_match('~^https?://~i', $organization->link_maps ?? '')) $maps = $organization->link_maps;
    $phone = preg_replace('/\D/', '', $organization->no_telp ?? '');
    if (str_starts_with($phone, '0')) $phone = '62' . substr($phone, 1);
@endphp
<div class="panti-profile">
    <div class="panti-banner"><img src="{{ $cover }}" alt="Kegiatan {{ $organization->nama_lembaga }}"></div>
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <header class="panti-identity">
            <div class="panti-logo" aria-hidden="true">{{ $initials }}</div>
            <h1>{{ $organization->nama_lembaga }}</h1>
        </header>
        <section class="panti-section" aria-labelledby="panti-about">
            <h2 id="panti-about">Tentang Kami</h2>
            <p class="panti-description">{{ $organization->deskripsi ?: 'Informasi tentang panti ini akan segera dilengkapi.' }}</p>
        </section>
        <div class="panti-contact-grid panti-section">
            <section aria-labelledby="panti-contact">
                <h2 id="panti-contact">Hubungi Kami</h2>
                @if($phone)
                    <a class="panti-contact-link" href="https://wa.me/{{ $phone }}" target="_blank" rel="noopener noreferrer"><span aria-hidden="true">☎</span>{{ $organization->no_telp }}</a>
                @endif
                @if($organization->user?->email)
                    <a class="panti-contact-link" href="mailto:{{ $organization->user->email }}"><span aria-hidden="true">✉</span>{{ $organization->user->email }}</a>
                @endif
                <a class="panti-primary" href="{{ route('donatur.kunjungan.create', $organization->id) }}">Jadwalkan Kunjungan</a>
            </section>
            <section class="panti-location" aria-labelledby="panti-location">
                <h2 id="panti-location">Lokasi Panti</h2>
                <p>{{ $address ?: 'Alamat belum tersedia' }}</p>
                @if($address)
                    <a class="panti-map" href="{{ $maps }}" target="_blank" rel="noopener noreferrer">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <strong>{{ $organization->kota }}</strong><span>Lihat lokasi di Google Maps ↗</span>
                    </a>
                @endif
            </section>
        </div>
        <section class="panti-section" aria-labelledby="panti-gallery">
            <h2 id="panti-gallery">Galeri Panti Asuhan</h2>
            <div class="panti-gallery">
                @forelse($gallery as $photo)
                    <a href="{{ $photo->image_url }}" target="_blank" rel="noopener noreferrer" aria-label="Lihat foto kegiatan {{ $loop->iteration }}"><img src="{{ $photo->image_url }}" alt="Kegiatan {{ $organization->nama_lembaga }} — foto {{ $loop->iteration }}" loading="lazy"></a>
                @empty
                    <p class="text-gray-500">Panti belum menambahkan foto kegiatan.</p>
                @endforelse
            </div>
        </section>
        <section class="panti-section" aria-labelledby="panti-campaigns">
            <h2 id="panti-campaigns">Daftar Campaign</h2>
            <div class="space-y-5">
                @forelse($campaigns as $campaign)
                    @php
                        $collected = (int) $campaign->collected;
                        $percent = $campaign->target_dana > 0 ? min(100, round($collected / $campaign->target_dana * 100, 1)) : 0;
                        $image = $campaign->foto_cover ? (preg_match('~^https?://~', $campaign->foto_cover) ? $campaign->foto_cover : asset('storage/' . $campaign->foto_cover)) : $cover;
                    @endphp
                    <a class="donor-result-card" href="{{ route('campaign.detail', $campaign->id) }}">
                        <img src="{{ $image }}" alt="{{ $campaign->judul }}" loading="lazy">
                        <div class="donor-result-content">
                            <h2>{{ $campaign->judul }}</h2>
                            <p class="text-sm text-gray-500 mt-1">{{ $organization->nama_lembaga }}</p>
                            <div class="donor-result-progress" role="progressbar" aria-label="Dana terkumpul" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"><span style="width: {{ $percent }}%"></span></div>
                            <dl class="donor-result-stats">
                                <div><dt>Terkumpul</dt><dd>Rp {{ number_format($collected, 0, ',', '.') }}</dd></div>
                                <div><dt>Target</dt><dd>Rp {{ number_format($campaign->target_dana, 0, ',', '.') }}</dd></div>
                                <div><dt>Sisa hari</dt><dd>{{ $campaign->sisa_hari }}</dd></div>
                            </dl>
                        </div>
                    </a>
                @empty
                    <p class="rounded-2xl bg-white p-8 text-gray-500">Belum ada kampanye aktif dari panti ini.</p>
                @endforelse
            </div>
            <div class="mt-6">{{ $campaigns->links() }}</div>
        </section>
    </div>
</div>
<dialog id="panti-visit-dialog" class="panti-dialog" aria-labelledby="panti-visit-title">
    <button type="button" class="panti-dialog-close" aria-label="Tutup formulir">×</button>
    <h2 id="panti-visit-title">Jadwalkan Kunjungan</h2>
    <p>Ajukan waktu kunjungan ke {{ $organization->nama_lembaga }}. Panti akan mengonfirmasi pengajuan Anda.</p>
    <form id="panti-visit-form" data-organization="{{ $organization->id }}" data-endpoint="{{ url('/api/visits') }}">
        <label>Tanggal kunjungan<input type="date" name="tanggal_kunjungan" min="{{ now()->toDateString() }}" required></label>
        <label>Waktu kunjungan<input type="time" name="waktu_kunjungan" required></label>
        <label>Jumlah pengunjung<input type="number" name="pengunjung" min="1" value="1" required></label>
        <label>Pesan untuk panti <span>(opsional)</span><textarea name="pesan_donatur" maxlength="255" rows="3" placeholder="Ceritakan rencana kunjungan Anda"></textarea></label>
        <p id="panti-visit-feedback" role="status" hidden></p>
        <button class="panti-primary" type="submit">Kirim Pengajuan</button>
    </form>
</dialog>
@endsection
