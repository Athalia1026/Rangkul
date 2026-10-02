<header class="donor-header">
    <div class="donor-header-inner {{ request()->routeIs('donatur.search.results') ? 'donor-results-header' : '' }} max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        @if(request()->routeIs('donatur.search.results'))
            <a href="{{ route('donatur.cari') }}" class="flex items-center gap-2 text-gray-800 hover:text-[#05522d]">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5m7-7-7 7 7 7"/></svg>Kembali
            </a>
            <form action="{{ route('donatur.search.results') }}" method="GET" role="search" class="donor-results-search">
                <button type="submit" aria-label="Cari" class="p-2 text-[#05522d]"><svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="10.5" cy="10.5" r="7.5"/><path d="m16 16 5 5" stroke-linecap="round"/></svg></button>
                <input type="search" name="q" value="{{ $query ?? request('q', '') }}" aria-label="Cari kampanye atau organisasi" placeholder="Cari kampanye atau organisasi..." class="min-w-0 w-full bg-transparent py-2 pr-3 outline-none text-sm">
                @foreach(['sort', 'category', 'tab'] as $parameter)
                    @if(request()->filled($parameter))<input type="hidden" name="{{ $parameter }}" value="{{ request($parameter) }}">@endif
                @endforeach
            </form>
        @else
        <a href="{{ route('donatur.beranda') }}" aria-label="Rangkul Beranda"><img class="donor-logo" src="{{ asset('images/logo.png') }}" alt="Rangkul.com"></a>
        <nav class="donor-nav" aria-label="Navigasi donatur">
            <a href="{{ route('donatur.beranda') }}" @if(request()->routeIs('donatur.beranda')) aria-current="page" @endif>Beranda</a>
            <a href="{{ route('donatur.cari') }}" @if(request()->routeIs('donatur.cari')) aria-current="page" @endif>Cari</a>
            <a href="{{ route('donatur.riwayat') }}" @if(request()->routeIs('donatur.riwayat')) aria-current="page" @endif>Riwayat</a>
        </nav>
        @endif
        <div class="donor-account">
            <a href="{{ route('donatur.notifikasi') }}" class="donor-bell" aria-label="Notifikasi" @if(request()->routeIs('donatur.notifikasi')) aria-current="page" @endif>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" /></svg>
            </a>
            <div class="donor-profile-menu">
                <button type="button" id="donor-avatar-button" class="donor-avatar" aria-label="Menu akun donatur" aria-expanded="false" aria-controls="donor-profile-dropdown"><span id="donor-initials">D</span><img id="donor-avatar-image" hidden alt="Foto profil"></button>
                <div id="donor-profile-dropdown" class="donor-dropdown" hidden>
                    <strong id="donor-name">Memuat akun...</strong>
                    <button type="button" data-donor-panel="profile">Profil saya</button>
                    <p id="donor-account-error" role="status"></p>
                </div>
            </div>
            {{-- Keluar: cabut token API donatur lalu akhiri sesi web (lihat donor.js) --}}
            <form id="donor-logout-form" method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="donor-logout" aria-label="Keluar" title="Keluar">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" /></svg>
                </button>
            </form>
        </div>
    </div>
</header>
