@extends('layouts.public')
@section('title', 'Rangkul - Platform Donasi Terpercaya | Cari')

@section('content')
@php
    $hasQuery = !empty(trim((string) request('q', $query ?? '')));
    $pilihanRangkul = $campaigns->take(7);
    $otherCampaigns = $hasQuery ? $campaigns : $campaigns->skip(7);
    $isUrgent = ($sort === 'urgent');
@endphp

<div class="w-full flex flex-col">
    <!-- ============================================================ -->
    <!-- 2. HERO SEARCH BANNER                                        -->
    <!-- ============================================================ -->
    <section class="relative w-full h-[250px] sm:h-[290px] overflow-hidden flex items-center justify-center select-none bg-gray-950">
        <!-- Background Grayscale Image -->
        <img
            src="{{ asset('images/search.jpg') }}"
            alt="Campaign Banner"
            class="absolute inset-0 w-full h-full object-cover object-[center_35%] filter grayscale opacity-65 pointer-events-none"
        />
        
        <!-- Dark Vignette Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/55 via-black/40 to-black/60 pointer-events-none"></div>

        <!-- Hero Content -->
        <div class="relative z-10 w-full max-w-[800px] mx-auto px-4 sm:px-6 text-center flex flex-col items-center">
            <h1 class="text-[24px] sm:text-[32px] md:text-[34px] font-extrabold text-white tracking-tight leading-tight drop-shadow-md">
                Temukan Campaign yang Ingin Anda Dukung
            </h1>

            <!-- Search Bar Input -->
            <div class="w-full max-w-[580px] mt-6 relative">
                <form action="{{ route('search.results') }}" method="GET" class="relative flex items-center bg-white/90 backdrop-blur-md hover:bg-white focus-within:bg-white rounded-xl shadow-lg border border-white/40 transition-all">
                    <svg class="w-5 h-5 text-gray-500 ml-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" stroke-width="2"/>
                        <path d="m21 21-4.35-4.35" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Cari organisasi atau campaign..."
                        class="w-full py-3 sm:py-3.5 pl-3 pr-10 text-gray-900 placeholder-gray-500 text-[14px] sm:text-[15px] outline-none bg-transparent"
                    />
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                </form>
            </div>
        </div>
    </section>

    <!-- ============================================================ -->
    <!-- 3. MAIN CONTENT CONTAINER                                    -->
    <!-- ============================================================ -->
    <div class="w-full max-w-[1240px] mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12 space-y-12 sm:space-y-14 flex-1">
        
        @if(!$hasQuery)
            <!-- ========================================================== -->
            <!-- SECTION A: PILIHAN RANGKUL (SLIDABLE - 7 CAMPAIGNS)       -->
            <!-- ========================================================== -->
            <section class="relative">
                <div class="flex items-center justify-between mb-5 sm:mb-6">
                    <div>
                        <h2 class="text-[22px] sm:text-[25px] font-bold text-gray-950 tracking-tight">
                            Pilihan Rangkul
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                            Geser untuk melihat kampanye pilihan lainnya
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            onclick="scrollPilihanRangkul(-360)"
                            class="w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-700 hover:text-[#05522d] hover:border-[#05522d] hover:bg-[#d8f0e2]/40 flex items-center justify-center transition-all cursor-pointer shadow-xs active:scale-95"
                            aria-label="Sebelumnya"
                        >
                            &#10094;
                        </button>
                        <button
                            type="button"
                            onclick="scrollPilihanRangkul(360)"
                            class="w-9 h-9 rounded-full bg-[#05522d] text-white hover:bg-[#044023] flex items-center justify-center transition-all cursor-pointer shadow-xs active:scale-95"
                            aria-label="Berikutnya"
                        >
                            &#10095;
                        </button>
                    </div>
                </div>

                <!-- Slidable Track Container -->
                <div
                    id="pilihanRangkulTrack"
                    class="flex gap-6 overflow-x-auto scroll-smooth pb-4 pt-1 px-1 -mx-1 select-none cursor-grab"
                    style="scrollbar-width: none; -ms-overflow-style: none;"
                >
                    @forelse ($pilihanRangkul as $campaign)
                        <div class="w-[290px] sm:w-[330px] md:w-[360px] shrink-0 bg-white rounded-2xl overflow-hidden border border-gray-100/90 shadow-2xs hover:shadow-md transition-all duration-200 flex flex-col group cursor-pointer">
                            <div class="relative aspect-[16/10] overflow-hidden bg-gray-100">
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
                            <div class="p-5 flex flex-col flex-1 justify-between">
                                <div>
                                    <span class="text-[12px] text-gray-500 font-medium block mb-1">{{ $campaign['nama_organisasi'] }}</span>
                                    <h3 class="text-[15.5px] sm:text-[16px] font-bold text-gray-950 leading-snug line-clamp-2 group-hover:text-[#05522d] transition-colors">{{ $campaign['judul'] }}</h3>
                                </div>
                                <div class="mt-4 pt-1">
                                    <p class="text-[13px] text-gray-600 mb-2">Terkumpul <span class="font-bold text-[#05522d]">Rp {{ number_format($campaign['terkumpul'], 0, ',', '.') }}</span></p>
                                    <div class="w-full h-2 rounded-full bg-[#d8f0e2] overflow-hidden"><div class="h-full bg-[#05522d] rounded-full" style="width: {{ $campaign['persentase'] }}%"></div></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="w-full py-8 text-center text-sm text-gray-500">Belum ada campaign aktif.</p>
                    @endforelse
                </div>
            </section>
        @endif

        <!-- ========================================================== -->
        <!-- SECTION B: PENGGALANGAN DANA LAINNYA                      -->
        <!-- ========================================================== -->
        <section>
            
            <!-- Section Header with Sort Trigger Button -->
            <div class="flex items-center justify-between mb-5 sm:mb-6">
                <h2 class="text-[22px] sm:text-[25px] font-bold text-gray-950 tracking-tight">
                    {{ $hasQuery ? 'Hasil Pencarian: "' . request('q', $query) . '"' : 'Penggalangan Dana Lainnya' }}
                </h2>

                <!-- Sort Filter Trigger Button -->
                <div class="relative" id="sortDropdownContainer">
                    <button
                        type="button"
                        id="sortBtn"
                        onclick="toggleSortDropdown()"
                        class="p-2 rounded-xl text-[#05522d] hover:bg-[#d8f0e2]/50 active:bg-[#d8f0e2] transition-colors cursor-pointer flex items-center justify-center"
                        aria-label="Urutkan Campaign"
                    >
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="7" y1="12" x2="21" y2="12"></line>
                            <line x1="13" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>

                    <!-- SORT DROPDOWN POPOVER -->
                    <div id="sortMenu" class="hidden absolute right-0 top-full mt-2 w-[220px] bg-white rounded-2xl shadow-xl border border-gray-100 py-2.5 z-50">
                        <div class="px-4 py-1.5 text-[15px] font-bold text-gray-950">
                            Urutkan
                        </div>

                        <!-- Option 1: Paling Mendesak -->
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'mendesak']) }}" class="w-full px-4 py-2.5 text-left text-[14px] flex items-center justify-between cursor-pointer transition-colors {{ $isUrgent ? 'bg-[#dcf3e7] text-gray-950 font-semibold' : 'text-gray-800 hover:bg-gray-50 font-medium' }}">
                            <span>Paling Mendesak</span>
                            @if ($isUrgent)
                                <div class="w-5 h-5 rounded-full bg-[#05522d] text-white flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                    </svg>
                                </div>
                            @endif
                        </a>

                        <!-- Option 2: Terbaru -->
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'terbaru']) }}" class="w-full px-4 py-2.5 text-left text-[14px] flex items-center justify-between cursor-pointer transition-colors {{ !$isUrgent ? 'bg-[#dcf3e7] text-gray-950 font-semibold' : 'text-gray-800 hover:bg-gray-50 font-medium' }}">
                            <span>Terbaru</span>
                            @if (!$isUrgent)
                                <div class="w-5 h-5 rounded-full bg-[#05522d] text-white flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                    </svg>
                                </div>
                            @endif
                        </a>
                    </div>
                </div>
            </div>

            <!-- List of Horizontal Campaign Cards -->
            <div class="space-y-4 sm:space-y-5">
                @forelse ($otherCampaigns as $campaign)
                    <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-2xs hover:shadow-md transition-all duration-200 cursor-pointer flex flex-col sm:flex-row group">
                        <div class="w-full sm:w-[260px] md:w-[320px] lg:w-[380px] h-[170px] sm:h-[180px] shrink-0 overflow-hidden bg-gray-100">
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
                        <div class="p-5 sm:p-6 flex flex-col justify-between flex-1">
                            <div>
                                <h3 class="text-[16px] sm:text-[17px] font-bold text-gray-950 group-hover:text-[#05522d] transition-colors leading-snug line-clamp-2">
                                    {{ $campaign['judul'] }}
                                </h3>
                                <p class="text-[12.5px] text-gray-500 font-medium mt-1">{{ $campaign['nama_organisasi'] }}</p>
                                <div class="mt-3.5 w-full h-2.5 rounded-full bg-[#dcf3e7] overflow-hidden">
                                    <div class="h-full bg-[#05522d] rounded-full" style="width: {{ $campaign['persentase'] }}%"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-2 mt-4 pt-2">
                                <div>
                                    <span class="text-[11.5px] text-gray-500 font-medium block">Terkumpul</span>
                                    <span class="text-[13.5px] sm:text-[14.5px] font-bold text-[#05522d] block mt-0.5">Rp {{ number_format($campaign['terkumpul'], 0, ',', '.') }}</span>
                                </div>
                                <div>
                                    <span class="text-[11.5px] text-gray-500 font-medium block">Target</span>
                                    <span class="text-[13.5px] sm:text-[14.5px] font-bold text-gray-900 block mt-0.5">Rp {{ number_format($campaign['target_dana'], 0, ',', '.') }}</span>
                                </div>
                                <div>
                                    <span class="text-[11.5px] text-gray-500 font-medium block">Sisa hari</span>
                                    <span class="text-[13.5px] sm:text-[14.5px] font-bold text-[#05522d] block mt-0.5">{{ $campaign['sisa_hari'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-500">
                        <p class="text-[15px] font-medium">
                            {{ $hasQuery ? 'Tidak ada campaign yang cocok dengan pencarian Anda.' : 'Belum ada penggalangan dana lainnya.' }}
                        </p>
                    </div>
                @endforelse
            </div>
        </section>

    </div>
</div>
@endsection

@push('scripts')
<style>
    #pilihanRangkulTrack::-webkit-scrollbar {
        display: none;
    }
</style>
<script>
    function toggleSortDropdown() {
        const menu = document.getElementById('sortMenu');
        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    function scrollPilihanRangkul(offset) {
        const track = document.getElementById('pilihanRangkulTrack');
        if (track) {
            track.scrollBy({ left: offset, behavior: 'smooth' });
        }
    }

    document.addEventListener('click', function(e) {
        const container = document.getElementById('sortDropdownContainer');
        const menu = document.getElementById('sortMenu');
        if (container && menu && !container.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });

    // Drag-to-scroll interactivity for Section A Track
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('pilihanRangkulTrack');
        if (!slider) return;

        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.remove('cursor-grab');
            slider.classList.add('cursor-grabbing');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('cursor-grabbing');
            slider.classList.add('cursor-grab');
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('cursor-grabbing');
            slider.classList.add('cursor-grab');
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 1.5;
            slider.scrollLeft = scrollLeft - walk;
        });
    });
</script>
@endpush