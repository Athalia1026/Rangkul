@extends('layouts.public')
@section('title', 'Hasil Pencarian - Rangkul')

@section('content')
@php $organizationTab = request('tab') === 'organisasi'; @endphp
<div class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12 pb-20 sm:pb-24">
    <h1 class="text-[28px] sm:text-[32px] font-bold tracking-tight text-gray-950">Hasil Pencarian</h1>
    <p class="mt-3 text-[15px] text-gray-700">
        @if($query !== '')
            Menampilkan hasil untuk <strong class="text-gray-950">&ldquo;{{ $query }}&rdquo;</strong>
        @else
            Jelajahi penggalangan dana dan organisasi pilihan Rangkul.
        @endif
    </p>

    <nav class="donor-results-tabs" aria-label="Jenis hasil pencarian">
        <a href="{{ request()->fullUrlWithQuery(['tab' => 'penggalangan']) }}" @if(!$organizationTab) aria-current="page" @endif>Penggalangan Dana</a>
        <a href="{{ request()->fullUrlWithQuery(['tab' => 'organisasi']) }}" @if($organizationTab) aria-current="page" @endif>Organisasi</a>
    </nav>

    @if(!$organizationTab)
        <div class="space-y-4 sm:space-y-5">
            @forelse($campaigns as $campaign)
                <a href="{{ route('campaign.detail', ['id' => $campaign['id'], 'q' => $query]) }}" class="donor-result-card">
                    <img src="{{ $campaign['image_url'] ?: asset('images/hero/hero_children.jpg') }}" alt="{{ $campaign['judul'] }}" loading="lazy">
                    <div class="donor-result-content">
                        <h2>{{ $campaign['judul'] }}</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ $campaign['nama_organisasi'] }}</p>
                        <div class="donor-result-progress" role="progressbar" aria-label="Dana terkumpul" aria-valuenow="{{ $campaign['persentase'] }}" aria-valuemin="0" aria-valuemax="100"><span style="width: {{ $campaign['persentase'] }}%"></span></div>
                        <dl class="donor-result-stats">
                            <div><dt>Terkumpul</dt><dd>Rp {{ number_format($campaign['terkumpul'], 0, ',', '.') }}</dd></div>
                            <div><dt>Target</dt><dd>Rp {{ number_format($campaign['target_dana'], 0, ',', '.') }}</dd></div>
                            <div><dt>Sisa hari</dt><dd>{{ $campaign['sisa_hari'] }}</dd></div>
                        </dl>
                    </div>
                </a>
            @empty
                <div class="rounded-2xl bg-white p-10 text-center">
                    <h2 class="font-semibold text-gray-900">Kampanye belum ditemukan</h2>
                    <p class="mt-2 text-sm text-gray-500">Coba kata kunci lain atau ubah filter pencarian.</p>
                    <a href="{{ route('donatur.cari') }}" class="mt-5 inline-block font-semibold text-[#05522d]">Kembali ke Cari</a>
                </div>
            @endforelse
        </div>
    @else
        <div class="donor-organization-grid">
            @forelse($organizations as $organization)
                <a href="{{ route('donatur.panti.show', $organization['id']) }}" class="donor-organization-card">
                    <div class="donor-organization-cover">
                        <img src="{{ $organization['image_url'] ?: asset('images/hero/hero_children.jpg') }}" alt="{{ $organization['nama_lembaga'] }}" loading="lazy">
                        <div class="donor-organization-badge" aria-hidden="true">
                            {{ collect(preg_split('/\s+/u', trim($organization['nama_lembaga'])))->filter()->take(2)->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))->implode('') }}
                        </div>
                    </div>
                    <div class="donor-organization-content">
                        <h2>{{ $organization['nama_lembaga'] }}</h2>
                        <p class="donor-organization-description">{{ $organization['deskripsi'] ?: 'Organisasi sosial yang berkomitmen membantu sesama.' }}</p>
                        <p class="donor-organization-location">
                            <svg width="20" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a8 8 0 0 0-8 8c0 6 8 12 8 12s8-6 8-12a8 8 0 0 0-8-8Zm0 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/></svg>
                            <span>{{ $organization['kota'] ?: 'Lokasi belum tersedia' }}</span>
                        </p>
                    </div>
                </a>
            @empty
                <div class="col-span-full rounded-2xl bg-white p-10 text-center">
                    <h2 class="font-semibold">Organisasi belum ditemukan</h2>
                    <p class="mt-2 text-sm text-gray-500">Coba cari nama organisasi atau kota lainnya.</p>
                </div>
            @endforelse
        </div>
    @endif
</div>
@endsection
