@php
    // Gaya mengikuti navbar organisasi (x-organization-navbar). Data akun (nama, foto, badge notifikasi,
    // status premium) diisi donor.js karena donatur login lewat token API, bukan sesi web.
    $navItems = [
        ['label' => 'Beranda', 'route' => 'donatur.beranda', 'active' => ['donatur.beranda']],
        ['label' => 'Cari', 'route' => 'donatur.cari', 'active' => ['donatur.cari']],
        ['label' => 'Riwayat', 'route' => 'donatur.riwayat', 'active' => ['donatur.riwayat']],
        ['label' => 'Dashboard', 'route' => 'donatur.dashboard', 'active' => ['donatur.dashboard', 'donatur.laporan'], 'premium' => true],
    ];
    $profileActive = request()->routeIs('donatur.profil', 'donatur.password');
@endphp

<header class="w-full bg-white border-b border-gray-100 sticky top-0 z-30 shadow-2xs">
    @if(request()->routeIs('donatur.search.results'))
        <div class="donor-header-inner donor-results-header max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
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
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">

            {{-- LOGO --}}
            <a href="{{ route('donatur.beranda') }}" class="flex items-center gap-2.5" aria-label="Rangkul Beranda">
                <div class="flex flex-col leading-tight">
                    <img src="{{ asset('images/logo.png') }}" alt="Rangkul Logo" class="h-11 w-auto">
                </div>
            </a>

            {{-- DESKTOP NAVIGATION --}}
            <nav class="donor-nav hidden md:flex items-center gap-8 text-[15px] font-medium text-gray-700" aria-label="Navigasi donatur">
                @foreach ($navItems as $item)
                    @php $isActive = request()->routeIs(...$item['active']); @endphp
                    <a href="{{ route($item['route']) }}" @if($item['premium'] ?? false) data-premium-only hidden @endif
                        @if($isActive) aria-current="page" @endif
                        class="{{ $isActive ? 'text-[#05522d] font-semibold' : 'text-gray-700 hover:text-[#05522d]' }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>
    @endif

            {{-- RIGHT SIDE --}}
            <div class="flex items-center gap-4">
                {{-- Hanya untuk donatur perusahaan yang belum premium (lihat donor.js) --}}
                <button type="button" id="donor-premium-button" class="donor-premium-button" aria-haspopup="dialog" aria-controls="premium-dialog" hidden>Gabung Premium</button>

                <div class="flex items-center gap-6">

                    {{-- Notifikasi (titik merah diatur donor.js sesuai jumlah belum dibaca) --}}
                    <a href="{{ route('donatur.notifikasi') }}" id="donor-bell" aria-label="Notifikasi"
                        @if(request()->routeIs('donatur.notifikasi')) aria-current="page" @endif
                        class="relative p-1 transition-colors {{ request()->routeIs('donatur.notifikasi') ? 'text-[#05522d]' : 'text-gray-500 hover:text-[#05522d]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                            stroke="currentColor" class="w-[26px] h-[26px]" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <span id="donor-bell-badge" class="absolute top-1 right-1.5 h-2 w-2 overflow-hidden rounded-full bg-red-500 ring-2 ring-white text-[0px]" hidden></span>
                    </a>

                    {{-- Profil --}}
                    <div class="border-l border-gray-200 pl-6">
                        <a href="{{ route('donatur.profil') }}" id="donor-avatar-button" aria-label="Profil saya" title="Profil saya"
                            @if($profileActive) aria-current="page" @endif
                            class="h-10 w-10 rounded-full overflow-hidden flex items-center justify-center bg-[#d8f0e2] text-[#05522d] text-[14px] font-bold border-2 transition-all shadow-sm {{ $profileActive ? 'border-[#05522d]' : 'border-transparent hover:border-[#05522d]' }}">
                            <span id="donor-initials">D</span>
                            <img id="donor-avatar-image" class="h-full w-full object-cover" hidden alt="">
                        </a>
                    </div>

                    {{-- Keluar: cabut token API donatur lalu akhiri sesi web (lihat donor.js) --}}
                    <form id="donor-logout-form" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-1 text-gray-500 hover:text-red-600 transition-colors disabled:opacity-60 disabled:cursor-wait"
                            aria-label="Keluar" title="Keluar">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" class="w-5 h-5" aria-hidden="true">
                                <path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 192 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l210.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128zM160 96c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 32C43 32 0 75 0 128L0 384c0 53 43 96 96 96l64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l64 0z"/>
                            </svg>
                        </button>
                    </form>

                    @unless(request()->routeIs('donatur.search.results'))
                        {{-- MOBILE MENU --}}
                        <details class="relative md:hidden">
                            <summary class="list-none cursor-pointer p-1 text-gray-700 hover:text-[#05522d]" aria-label="Menu">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                                    stroke="currentColor" class="w-[26px] h-[26px]" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                            </summary>

                            <div class="donor-nav absolute right-0 top-12 w-56 bg-white rounded-xl shadow-lg border border-gray-100 p-2 text-[15px] font-medium">
                                @foreach ($navItems as $item)
                                    @php $isActive = request()->routeIs(...$item['active']); @endphp
                                    <a href="{{ route($item['route']) }}" @if($item['premium'] ?? false) data-premium-only hidden @endif
                                        class="block px-4 py-2.5 rounded-lg {{ $isActive ? 'bg-[#d8f0e2] text-[#05522d] font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">{{ $item['label'] }}</a>
                                @endforeach
                            </div>
                        </details>
                    @endunless

                </div>
            </div>
        </div>
</header>
<x-premium-dialog />
