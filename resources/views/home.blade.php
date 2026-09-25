{{-- resources/views/home.blade.php --}}
@extends('layouts.public')

@section('title', 'Rangkul - Platform Donasi Terpercaya | Beranda')

@section('content')
    <main class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-12 bg-[#F5F7F4]">
        @php
            $featuredCampaign = $topCampaigns->first();
            $fallbackImage = 'images/hero/kids_eating_pangan_1789963840194.jpg';
            $campaignImage = function ($campaign) use ($fallbackImage) {
                if (!$campaign || !$campaign->foto_cover) {
                    return asset($fallbackImage);
                }

                return filter_var($campaign->foto_cover, FILTER_VALIDATE_URL)
                    ? $campaign->foto_cover
                    : asset('storage/' . ltrim($campaign->foto_cover, '/'));
            };
            $heroCampaigns = $topCampaigns->values()->map(fn($campaign) => [
                'title' => $campaign->judul,
                'description' => $campaign->deskripsi,
                'image' => $campaignImage($campaign),
            ])->values();
        @endphp

        <!-- HERO CAROUSEL -->
        <section id="beranda" class="relative w-full overflow-hidden pt-2 pb-4">
            <div class="relative flex items-center justify-center gap-4 min-h-[380px] sm:min-h-[420px]">

                @if ($topCampaigns->isNotEmpty())
                    @php $leftCampaign = $topCampaigns->get(1, $featuredCampaign); @endphp
                    <div
                        class="hidden lg:block absolute -left-[280px] xl:-left-[240px] w-[540px] h-[360px] rounded-3xl overflow-hidden shadow-md opacity-70 filter brightness-90 shrink-0 select-none z-10">
                        <img id="heroPreviousImage" src="{{ $campaignImage($leftCampaign) }}" alt="{{ $leftCampaign->judul }}"
                            class="w-full h-full object-cover transition-opacity duration-500">
                        <div class="absolute inset-0 bg-black/40"></div>
                    </div>
                @endif

                <div
                    class="relative w-full max-w-[1000px] h-[380px] sm:h-[420px] rounded-3xl overflow-hidden shadow-xl z-20">
                    <img id="heroActiveImage" src="{{ $campaignImage($featuredCampaign) }}"
                        alt="{{ $featuredCampaign?->judul ?? 'Kampanye donasi' }}"
                        class="w-full h-full object-cover transition-opacity duration-500">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/40 via-transparent to-transparent"></div>

                    <!-- Left Overlay Translucent Box -->
                    <div id="heroActiveContent"
                        class="absolute left-4 sm:left-8 top-1/2 -translate-y-1/2 max-w-[460px] bg-black/55 backdrop-blur-md p-6 sm:p-8 rounded-2xl border border-white/25 text-white shadow-2xl">
                        <h2 class="text-[22px] sm:text-[27px] font-bold leading-[1.25] text-white tracking-tight">
                            <span id="heroActiveTitle">{{ $featuredCampaign?->judul ?? 'Belum ada kampanye aktif' }}</span>
                        </h2>
                        <p class="text-[14px] sm:text-[15px] text-gray-200 mt-3 leading-relaxed">
                            <span
                                id="heroActiveDescription">{{ $featuredCampaign?->deskripsi ?? 'Kampanye donasi akan tampil di halaman ini setelah tersedia.' }}</span>
                        </p>
                        <button
                            class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#05522d] hover:bg-[#044023] text-white text-sm font-semibold transition-all shadow-md">
                            <span>Donasi Sekarang</span>
                            <span>&#9825;</span>
                            <a href="{{ route('campaign.detail', ['id' => $featuredCampaign->id]) }}"
                                class="absolute inset-0"></a>
                        </button>
                    </div>
                </div>

                @if ($topCampaigns->isNotEmpty())
                    @php $rightCampaign = $topCampaigns->get(2, $featuredCampaign); @endphp
                    <div
                        class="hidden lg:block absolute -right-[280px] xl:-right-[240px] w-[540px] h-[360px] rounded-3xl overflow-hidden shadow-md opacity-70 filter brightness-90 shrink-0 select-none z-10">
                        <img id="heroNextImage" src="{{ $campaignImage($rightCampaign) }}" alt="{{ $rightCampaign->judul }}"
                            class="w-full h-full object-cover transition-opacity duration-500">
                        <div class="absolute inset-0 bg-black/30"></div>
                    </div>
                @endif

            </div>
        </section>

        <!-- BANTU MEREKA HARI INI -->
        <section id="bantu-mereka" class="pt-2">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-[24px] sm:text-[26px] font-bold text-[#05522d] tracking-tight">
                        Bantu Mereka Hari Ini
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                        Geser untuk melihat kampanye donasi lainnya
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button"
                        onclick="document.getElementById('bantuTrack').scrollBy({left: -360, behavior: 'smooth'})"
                        class="w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-700 hover:text-[#05522d] hover:border-[#05522d] hover:bg-[#d8f0e2]/40 flex items-center justify-center transition-all cursor-pointer shadow-xs active:scale-95">
                        &#10094;
                    </button>
                    <button type="button"
                        onclick="document.getElementById('bantuTrack').scrollBy({left: 360, behavior: 'smooth'})"
                        class="w-9 h-9 rounded-full bg-[#05522d] text-white hover:bg-[#044023] flex items-center justify-center transition-all cursor-pointer shadow-xs active:scale-95">
                        &#10095;
                    </button>
                </div>
            </div>

            <div id="bantuTrack" class="flex gap-6 overflow-x-auto scroll-smooth pb-4 pt-1 px-1 -mx-1 select-none"
                style="scrollbar-width: none;">
                @forelse ($todayCampaigns as $campaign)
                    <a href="{{ route('campaign.detail', ['id' => $campaign->id]) }}"
                        class="w-[300px] sm:w-[350px] md:w-[365px] shrink-0 bg-white rounded-[20px] border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.06)] overflow-hidden flex flex-col justify-between hover:shadow-lg transition-all">
                        <div class="relative h-44 sm:h-48 w-full overflow-hidden bg-gray-100">
                            <img src="{{ $campaignImage($campaign) }}" alt="{{ $campaign->judul }}"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="p-5 flex flex-col flex-1 justify-between">
                            <div>
                                <span
                                    class="text-xs text-gray-500 font-medium">{{ $campaign->organization?->nama_lembaga ?? 'Organisasi sosial' }}</span>
                                <h3 class="text-[16px] font-bold text-gray-900 mt-1 leading-snug">{{ $campaign->judul }}</h3>
                            </div>
                            <div class="mt-4 pt-2">
                                <div class="flex items-center gap-1.5 text-sm">
                                    <span class="text-gray-500">Terkumpul</span>
                                    <span class="font-bold text-[#05522d]">Rp
                                        {{ number_format($campaign->total_terkumpul, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mt-2.5">
                                    <div class="h-full bg-[#207466] rounded-full" style="width: {{ $campaign->progress }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="w-full py-10 text-center text-gray-500">Belum ada kampanye donasi aktif.</p>
                @endforelse
            </div>
        </section>

        <!-- REKOMENDASI DONASI -->
        <section id="rekomendasi" class="pt-6">
            <h2 class="text-[24px] sm:text-[26px] font-bold text-[#05522d] tracking-tight text-center mb-8">
                Rekomendasi Donasi
            </h2>

            <div
                class="w-full bg-gradient-to-r from-[#175b4f] via-[#207466] to-[#48ab7e] rounded-[24px] overflow-hidden shadow-xl grid grid-cols-1 md:grid-cols-12 items-stretch">
                <div class="md:col-span-7 p-7 sm:p-10 lg:p-12 flex flex-col justify-center text-white space-y-4">
                    <h3 class="text-[22px] sm:text-[26px] lg:text-[28px] font-bold leading-[1.25] text-white">
                        {{ $featuredCampaign?->judul ?? 'Belum ada rekomendasi donasi' }}
                    </h3>
                    <p class="text-[14px] sm:text-[15px] text-white/90 leading-relaxed max-w-lg">
                        {{ $featuredCampaign?->deskripsi ?? 'Rekomendasi donasi akan tampil setelah kampanye tersedia.' }}
                    </p>
                    <div class="pt-4">
                        <a href="{{ route('campaign.detail', ['id' => $featuredCampaign->id]) }}"
                            class="px-7 py-3 rounded-xl bg-[#d87625] hover:bg-[#c2651b] text-white font-bold text-[15px] sm:text-[16px] transition-all shadow-md inline-block text-center">
                            Bantu Mereka Sekarang
                        </a>
                    </div>
                </div>

                <div class="md:col-span-5 relative min-h-[260px] sm:min-h-[320px] bg-black">
                    <img src="{{ $campaignImage($featuredCampaign) }}"
                        alt="{{ $featuredCampaign?->judul ?? 'Rekomendasi donasi' }}" class="w-full h-full object-cover">
                </div>
            </div>
        </section>

        <!-- PENGGALANGAN DANA PRIORITAS -->
        <section id="prioritas" class="pt-6 pb-8">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-[24px] sm:text-[26px] font-bold text-[#05522d] tracking-tight">
                        Penggalangan Dana Prioritas
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                        Kebutuhan mendesak dengan sisa waktu penggalangan terbatas
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button"
                        onclick="document.getElementById('prioritasTrack').scrollBy({left: -360, behavior: 'smooth'})"
                        class="w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-700 hover:text-[#05522d] hover:border-[#05522d] hover:bg-[#d8f0e2]/40 flex items-center justify-center transition-all cursor-pointer shadow-xs active:scale-95">
                        &#10094;
                    </button>
                    <button type="button"
                        onclick="document.getElementById('prioritasTrack').scrollBy({left: 360, behavior: 'smooth'})"
                        class="w-9 h-9 rounded-full bg-[#05522d] text-white hover:bg-[#044023] flex items-center justify-center transition-all cursor-pointer shadow-xs active:scale-95">
                        &#10095;
                    </button>
                </div>
            </div>

            <div id="prioritasTrack" class="flex gap-6 overflow-x-auto scroll-smooth pb-4 pt-1 px-1 -mx-1 select-none"
                style="scrollbar-width: none;">
                @forelse ($priorityCampaigns as $campaign)
                    <a href="{{ route('campaign.detail', ['id' => $campaign->id]) }}"
                        class="w-[300px] sm:w-[350px] md:w-[365px] shrink-0 bg-white rounded-[20px] border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.06)] overflow-hidden flex flex-col justify-between hover:shadow-lg transition-all">
                        <div class="relative h-44 sm:h-48 w-full overflow-hidden bg-gray-100">
                            <img src="{{ $campaignImage($campaign) }}" alt="{{ $campaign->judul }}"
                                class="w-full h-full object-cover">
                            <div
                                class="absolute top-3 right-3 bg-[#fceeed] text-[#b02a37] border border-[#f5c6cb] px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm">
                                <span>SISA {{ $campaign->sisa_hari }} HARI</span>
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-1 justify-between">
                            <div>
                                <span
                                    class="text-xs text-gray-500 font-medium">{{ $campaign->organization?->nama_lembaga ?? 'Organisasi sosial' }}</span>
                                <h3 class="text-[16px] font-bold text-gray-900 mt-1 leading-snug">{{ $campaign->judul }}</h3>
                            </div>
                            <div class="mt-4 pt-2">
                                <div class="flex items-center gap-1.5 text-sm">
                                    <span class="text-gray-500">Terkumpul</span>
                                    <span class="font-bold text-[#05522d]">Rp
                                        {{ number_format($campaign->total_terkumpul, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mt-2.5">
                                    <div class="h-full bg-[#207466] rounded-full" style="width: {{ $campaign->progress }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="w-full py-10 text-center text-gray-500">Belum ada kampanye prioritas.</p>
                @endforelse
                @if (false)
                    <!-- Card 1 (SISA 1 HARI) -->
                    <div
                        class="w-[300px] sm:w-[350px] md:w-[365px] shrink-0 bg-white rounded-[20px] border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.06)] overflow-hidden flex flex-col justify-between hover:shadow-lg transition-all">
                        <div class="relative h-44 sm:h-48 w-full overflow-hidden bg-gray-100">
                            <img src="{{ asset('assets/images/kids_eating_pangan_1789963840194.jpg') }}"
                                class="w-full h-full object-cover">
                            <div
                                class="absolute top-3 right-3 bg-[#fceeed] text-[#b02a37] border border-[#f5c6cb] px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm">
                                <span>⏱ SISA 1 HARI</span>
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-1 justify-between">
                            <div>
                                <span class="text-xs text-gray-500 font-medium">Panti Asuhan Kasih Bunda</span>
                                <h3 class="text-[16px] font-bold text-gray-900 mt-1 leading-snug">Bantuan Kebutuhan Pangan Anak
                                    Panti</h3>
                            </div>
                            <div class="mt-4 pt-2">
                                <div class="flex items-center gap-1.5 text-sm">
                                    <span class="text-gray-500">Terkumpul</span>
                                    <span class="font-bold text-[#05522d]">Rp 2.500.000</span>
                                </div>
                                <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mt-2.5">
                                    <div class="h-full bg-[#207466] rounded-full" style="width: 28%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 (SISA 3 HARI) -->
                    <div
                        class="w-[300px] sm:w-[350px] md:w-[365px] shrink-0 bg-white rounded-[20px] border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.06)] overflow-hidden flex flex-col justify-between hover:shadow-lg transition-all">
                        <div class="relative h-44 sm:h-48 w-full overflow-hidden bg-gray-100">
                            <img src="{{ asset('assets/images/classroom_chalkboard_1789963866200.jpg') }}"
                                class="w-full h-full object-cover">
                            <div
                                class="absolute top-3 right-3 bg-[#fceeed] text-[#b02a37] border border-[#f5c6cb] px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm">
                                <span>⏱ SISA 3 HARI</span>
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-1 justify-between">
                            <div>
                                <span class="text-xs text-gray-500 font-medium">SD Harapan Bangsa</span>
                                <h3 class="text-[16px] font-bold text-gray-900 mt-1 leading-snug">Renovasi Ruang Belajar yang
                                    Layak</h3>
                            </div>
                            <div class="mt-4 pt-2">
                                <div class="flex items-center gap-1.5 text-sm">
                                    <span class="text-gray-500">Terkumpul</span>
                                    <span class="font-bold text-[#05522d]">Rp 11.500.000</span>
                                </div>
                                <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mt-2.5">
                                    <div class="h-full bg-[#207466] rounded-full" style="width: 78%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div
                        class="w-[300px] sm:w-[350px] md:w-[365px] shrink-0 bg-white rounded-[20px] border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.06)] overflow-hidden flex flex-col justify-between hover:shadow-lg transition-all">
                        <div class="relative h-44 sm:h-48 w-full overflow-hidden bg-gray-100">
                            <img src="{{ asset('assets/images/orphanage_beds_1789963879401.jpg') }}"
                                class="w-full h-full object-cover">
                            <div
                                class="absolute top-3 right-3 bg-[#fceeed] text-[#b02a37] border border-[#f5c6cb] px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm">
                                <span>⏱ SISA 5 HARI</span>
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-1 justify-between">
                            <div>
                                <span class="text-xs text-gray-500 font-medium">Panti Asuhan Cahaya Harapan</span>
                                <h3 class="text-[16px] font-bold text-gray-900 mt-1 leading-snug">Bantuan Perlengkapan Tidur
                                    Anak Panti</h3>
                            </div>
                            <div class="mt-4 pt-2">
                                <div class="flex items-center gap-1.5 text-sm">
                                    <span class="text-gray-500">Terkumpul</span>
                                    <span class="font-bold text-[#05522d]">Rp 5.525.000</span>
                                </div>
                                <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mt-2.5">
                                    <div class="h-full bg-[#207466] rounded-full" style="width: 46%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div
                        class="w-[300px] sm:w-[350px] md:w-[365px] shrink-0 bg-white rounded-[20px] border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.06)] overflow-hidden flex flex-col justify-between hover:shadow-lg transition-all">
                        <div class="relative h-44 sm:h-48 w-full overflow-hidden bg-gray-100">
                            <img src="{{ asset('assets/images/bookshelf_library_1789963921279.jpg') }}"
                                class="w-full h-full object-cover">
                            <div
                                class="absolute top-3 right-3 bg-[#fceeed] text-[#b02a37] border border-[#f5c6cb] px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1.5 shadow-sm">
                                <span>⏱ SISA 2 HARI</span>
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-1 justify-between">
                            <div>
                                <span class="text-xs text-gray-500 font-medium">Yayasan Kasih Ananda</span>
                                <h3 class="text-[16px] font-bold text-gray-900 mt-1 leading-snug">Paket Nutrisi & Susu Balita
                                    Panti Asuhan</h3>
                            </div>
                            <div class="mt-4 pt-2">
                                <div class="flex items-center gap-1.5 text-sm">
                                    <span class="text-gray-500">Terkumpul</span>
                                    <span class="font-bold text-[#05522d]">Rp 3.800.000</span>
                                </div>
                                <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden mt-2.5">
                                    <div class="h-full bg-[#207466] rounded-full" style="width: 38%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>

    </main>
@endsection

@push('scripts')
    <script>
        const heroCampaigns = @json($heroCampaigns);
        const heroPreviousImage = document.getElementById('heroPreviousImage');
        const heroActiveImage = document.getElementById('heroActiveImage');
        const heroNextImage = document.getElementById('heroNextImage');
        const heroActiveContent = document.getElementById('heroActiveContent');
        const heroActiveTitle = document.getElementById('heroActiveTitle');
        const heroActiveDescription = document.getElementById('heroActiveDescription');
        let activeHeroIndex = heroCampaigns.length > 1 ? 1 : 0;

        function replayHeroAnimation(element, animationClass) {
            if (!element) return;
            element.classList.remove(animationClass);
            void element.offsetWidth;
            element.classList.add(animationClass);
        }

        function updateHeroCarousel() {
            if (heroCampaigns.length < 2 || !heroActiveImage) return;

            const totalCampaigns = heroCampaigns.length;
            const previousCampaign = heroCampaigns[(activeHeroIndex - 1 + totalCampaigns) % totalCampaigns];
            const activeCampaign = heroCampaigns[activeHeroIndex];
            const nextCampaign = heroCampaigns[(activeHeroIndex + 1) % totalCampaigns];

            heroPreviousImage.src = previousCampaign.image;
            heroPreviousImage.alt = previousCampaign.title;
            heroActiveImage.src = activeCampaign.image;
            heroActiveImage.alt = activeCampaign.title;
            heroNextImage.src = nextCampaign.image;
            heroNextImage.alt = nextCampaign.title;
            heroActiveTitle.textContent = activeCampaign.title;
            heroActiveDescription.textContent = activeCampaign.description;
            replayHeroAnimation(heroPreviousImage, 'hero-slide-previous');
            replayHeroAnimation(heroActiveImage, 'hero-slide-active');
            replayHeroAnimation(heroActiveContent, 'hero-slide-content');
            replayHeroAnimation(heroNextImage, 'hero-slide-next');
            activeHeroIndex = (activeHeroIndex + 1) % totalCampaigns;
        }

        if (heroCampaigns.length > 1 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            window.setInterval(updateHeroCarousel, 5000);
        }

        // Drag to scroll script
        ['bantuTrack', 'prioritasTrack'].forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            let isDown = false;
            let startX, scrollLeft;
            el.addEventListener('mousedown', (e) => {
                isDown = true;
                el.classList.add('cursor-grabbing');
                startX = e.pageX - el.offsetLeft;
                scrollLeft = el.scrollLeft;
            });
            el.addEventListener('mouseleave', () => { isDown = false; el.classList.remove('cursor-grabbing'); });
            el.addEventListener('mouseup', () => { isDown = false; el.classList.remove('cursor-grabbing'); });
            el.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - el.offsetLeft;
                const walk = (x - startX) * 1.5;
                el.scrollLeft = scrollLeft - walk;
            });
        });
    </script>
@endpush