@extends('layouts.footeronly')
@section('title', 'Rangkul - Platform Donasi Terpercaya | Hasil Pencarian')

@section('content')
<body class="min-h-screen bg-[#fafbfa] text-gray-900 antialiased flex flex-col selection:bg-[#d8f0e2] selection:text-[#05522d]">

    <!-- ============================================================ -->
    <!-- 1. TOP HEADER BAR                                            -->
    <!-- ============================================================ -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-2xs">
        <div class="w-full max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8 h-18 sm:h-20 flex items-center justify-between gap-4">
            
            <!-- Left: Kembali Button -->
            <a href="{{ route('search') }}" class="flex items-center gap-2 text-gray-800 hover:text-[#05522d] font-medium text-[15px] sm:text-[16px] transition-colors shrink-0">
                <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                <span>Kembali</span>
            </a>

            <!-- Center: Search Bar with Green Border -->
            <div class="flex-1 max-w-[560px] mx-2">
                <form action="{{ route('search.results') }}" method="GET" class="relative flex items-center bg-white rounded-xl border-2 border-[#05522d] shadow-xs px-3.5 py-1.5 sm:py-2">
                    <svg class="w-4 sm:w-5 h-4 sm:h-5 text-gray-800 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35" stroke-linecap="round"/>
                    </svg>
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q', $query ?? '') }}"
                        placeholder="Penggalangan Dana"
                        class="w-full pl-3 pr-2 text-gray-900 placeholder-gray-500 text-[13.5px] sm:text-[14.5px] outline-none bg-transparent"
                    />
                </form>
            </div>

@guest
<div class="flex items-center gap-2 sm:gap-3">
    <a href="{{ route('login') }}" class="px-6 py-2 rounded-lg bg-[#05522d] hover:bg-[#044023] text-white font-semibold text-[15px] transition-colors">Masuk</a>
    <a href="{{ route('register') }}" class="px-6 py-2 rounded-lg bg-[#d8f0e2] hover:bg-[#c4ebd3] text-[#05522d] font-semibold text-[15px] transition-colors">Daftar</a>
    </div>
@endguest
@auth
    <button type="button" class="p-1 text-gray-800 hover:text-[#05522d] transition-colors" aria-label="Notifikasi">
        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/>
        </svg>
    </button>
    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full overflow-hidden border border-gray-200 shadow-2xs">
        <img src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=d8f0e2&color=05522d&bold=true' }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
    </div>
@endauth

        </div>
    </header>

    <!-- ============================================================ -->
    <!-- 2. MAIN RESULTS CONTAINER                                    -->
    <!-- ============================================================ -->
    <main class="w-full max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-10 pb-16 flex-1">
        
        <!-- Title & Subtitle -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-[26px] sm:text-[32px] font-bold text-gray-950 tracking-tight leading-tight">
                Hasil Pencarian
            </h1>
            <p class="text-[14px] sm:text-[15px] text-gray-700 mt-1.5">
                Menampilkan hasil untuk <span class="font-bold text-gray-950">&ldquo;{{ request('q', $query ?: 'Penggalangan Dana') }}&rdquo;</span>
            </p>
        </div>

        <!-- Tab Navigation -->
        <div class="flex items-center gap-8 sm:gap-12 border-b border-gray-200/80 mb-8 sm:mb-9">
            <button
                type="button"
                id="tabBtnPenggalangan"
                onclick="switchTab('penggalangan')"
                class="pb-3 text-[15px] sm:text-[16px] cursor-pointer transition-colors relative text-gray-950 font-bold"
            >
                Penggalangan Dana
                <span id="indicatorPenggalangan" class="absolute bottom-0 left-0 right-0 h-[2.5px] bg-[#05522d] rounded-t-sm"></span>
            </button>

            <button
                type="button"
                id="tabBtnOrganisasi"
                onclick="switchTab('organisasi')"
                class="pb-3 text-[15px] sm:text-[16px] cursor-pointer transition-colors relative text-gray-500 font-medium hover:text-gray-900"
            >
                Organisasi
                <span id="indicatorOrganisasi" class="hidden absolute bottom-0 left-0 right-0 h-[2.5px] bg-[#05522d] rounded-t-sm"></span>
            </button>
        </div>

        <!-- ========================================================== -->
        <!-- TAB 1: PENGGALANGAN DANA                                  -->
        <!-- ========================================================== -->
        <div id="contentPenggalangan" class="space-y-4 sm:space-y-5">
            @forelse ($campaigns as $campaign)
                <a href="{{ route('campaign.detail', $campaign['id']) }}" class="bg-white rounded-2xl p-3 sm:p-3.5 border border-gray-100 shadow-2xs hover:shadow-md transition-all duration-200 cursor-pointer flex flex-col sm:flex-row gap-4 sm:gap-6 group">
                    <div class="w-full sm:w-[320px] md:w-[380px] lg:w-[410px] h-[160px] sm:h-[175px] shrink-0 rounded-xl overflow-hidden bg-gray-100">
                        @if (!empty($campaign['image_url']))
                            <img src="{{ $campaign['image_url'] }}" alt="{{ $campaign['judul'] }}" class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-300">
                        @else
                            <div class="w-full h-full bg-[#d8f0e2] flex items-center justify-center text-[#05522d]/40">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 py-1 sm:py-2 pr-2 sm:pr-4 flex flex-col justify-between">
                        <div>
                            <h2 class="text-[16px] sm:text-[17.5px] font-bold text-gray-950 group-hover:text-[#05522d] transition-colors leading-snug line-clamp-2">
                                {{ $campaign['judul'] }}
                            </h2>
                            <p class="text-[12.5px] sm:text-[13px] text-gray-500 font-medium mt-1">{{ $campaign['nama_organisasi'] }}</p>
                            <div class="mt-3.5 sm:mt-4 w-full h-2 sm:h-2.5 rounded-full bg-[#dcf3e7] overflow-hidden">
                                <div class="h-full bg-[#05522d] rounded-full" style="width: {{ $campaign['persentase'] }}%"></div>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mt-4 pt-1">
                            <div>
                                <span class="text-[11.5px] text-gray-500 font-medium block">Terkumpul</span>
                                <span class="text-[14px] sm:text-[15px] font-bold text-[#05522d] block mt-0.5">Rp {{ number_format($campaign['terkumpul'], 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="text-[11.5px] text-gray-500 font-medium block">Target</span>
                                <span class="text-[14px] sm:text-[15px] font-bold text-gray-900 block mt-0.5">Rp {{ number_format($campaign['target_dana'], 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="text-[11.5px] text-gray-500 font-medium block">Sisa hari</span>
                                <span class="text-[14px] sm:text-[15px] font-bold text-[#05522d] block mt-0.5">{{ $campaign['sisa_hari'] }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="bg-white rounded-2xl border border-gray-100 p-8 sm:p-12 text-center text-gray-500">
                    <p class="text-[15px] font-medium">Tidak ada penggalangan dana yang cocok dengan pencarian Anda.</p>
                </div>
            @endforelse
        </div>

        <!-- ========================================================== -->
        <!-- TAB 2: ORGANISASI                                          -->
        <!-- ========================================================== -->
        <div id="contentOrganisasi" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($organizations as $org)
                <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-2xs hover:shadow-md transition-all flex flex-col group cursor-pointer">
                    <div class="relative h-44 sm:h-48 overflow-hidden bg-gray-100">
                        @if (!empty($org['image_url']))
                            <img src="{{ $org['image_url'] }}" alt="{{ $org['nama_lembaga'] }}" class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-300">
                        @else
                            <div class="w-full h-full bg-[#d8f0e2] flex items-center justify-center text-[#05522d]/40">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="px-5 pb-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="relative -mt-7 mb-3 w-14 h-14 rounded-full bg-white shadow-md flex items-center justify-center border-2 border-white shrink-0">
                                <span class="text-[10px] sm:text-xs font-bold text-gray-700 uppercase tracking-tight text-center px-1">
                                    {{ !empty($org['tipe']) ? \Illuminate\Support\Str::limit($org['tipe'], 8, '') : 'CHARITY' }}
                                </span>
                            </div>
                            <h2 class="text-[16px] sm:text-[17px] font-bold text-gray-950 group-hover:text-[#05522d] transition-colors leading-snug mb-1.5">
                                {{ $org['nama_lembaga'] }}
                            </h2>
                            <p class="text-[12.5px] sm:text-[13px] text-gray-500 leading-relaxed line-clamp-2">
                                {{ $org['deskripsi'] ?? 'Lembaga sosial yang berkomitmen memberikan dampak positif bagi sesama.' }}
                            </p>
                        </div>
                        <div class="mt-5 pt-2 flex items-center gap-1.5 text-gray-800">
                            <svg class="w-4 h-4 text-[#05522d]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                            </svg>
                            <span class="text-[13px] text-gray-700 font-medium">{{ $org['kota'] ?? 'Indonesia' }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl border border-gray-100 p-8 sm:p-12 text-center text-gray-500">
                    <p class="text-[15px] font-medium">Tidak ada organisasi yang cocok dengan pencarian Anda.</p>
                </div>
            @endforelse
        </div>

    </main>
    @endsection

    <script>
        function switchTab(tab) {
            const penggalanganContent = document.getElementById('contentPenggalangan');
            const organisasiContent = document.getElementById('contentOrganisasi');
            const tabBtnP = document.getElementById('tabBtnPenggalangan');
            const tabBtnO = document.getElementById('tabBtnOrganisasi');
            const indP = document.getElementById('indicatorPenggalangan');
            const indO = document.getElementById('indicatorOrganisasi');

            if (tab === 'penggalangan') {
                penggalanganContent.classList.remove('hidden');
                organisasiContent.classList.add('hidden');
                tabBtnP.className = "pb-3 text-[15px] sm:text-[16px] cursor-pointer transition-colors relative text-gray-950 font-bold";
                tabBtnO.className = "pb-3 text-[15px] sm:text-[16px] cursor-pointer transition-colors relative text-gray-500 font-medium hover:text-gray-900";
                indP.classList.remove('hidden');
                indO.classList.add('hidden');
            } else {
                penggalanganContent.classList.add('hidden');
                organisasiContent.classList.remove('hidden');
                tabBtnP.className = "pb-3 text-[15px] sm:text-[16px] cursor-pointer transition-colors relative text-gray-500 font-medium hover:text-gray-900";
                tabBtnO.className = "pb-3 text-[15px] sm:text-[16px] cursor-pointer transition-colors relative text-gray-950 font-bold";
                indP.classList.add('hidden');
                indO.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab === 'organisasi') {
                switchTab('organisasi');
            }
        });
    </script>
</body>
</html>

