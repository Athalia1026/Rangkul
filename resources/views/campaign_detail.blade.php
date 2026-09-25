@extends('layouts.footeronly')

@section('title', $campaign['judul'] ?? 'Detail Campaign')

@section('content')
<body class="bg-white text-gray-900 font-sans min-h-screen flex flex-col antialiased selection:bg-[#dcf3e7] selection:text-[#05522d]">

    <!-- HERO BANNER -->
    <section class="relative w-full h-[280px] sm:h-[380px] md:h-[430px] lg:h-[460px] overflow-hidden bg-gray-900">
        <img src="{{ $campaign['image_url'] ?? asset('images/default_campaign.jpg') }}" alt="{{ $campaign['judul'] ?? 'Campaign Image' }}" class="w-full h-full object-cover object-center" />
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/20 to-transparent pointer-events-none"></div>

        <!-- Top Floating Navigation: ← Kembali (Left) & Share (Right) -->
        <div class="absolute top-0 left-0 right-0 w-full max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-5 sm:pt-6 flex items-center justify-between z-10">
            @php
                $backQuery = $searchQuery ?? request('q', session('last_search_query', ''));
                $backUrl = route('search.results', $backQuery !== '' ? ['q' => $backQuery] : []);
            @endphp
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 text-white/95 hover:text-white font-medium text-[15px] sm:text-[16px] drop-shadow-md transition-colors group">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Kembali</span>
            </a>

            <button type="button" onclick="copyLink()" class="text-white/95 hover:text-white p-2 rounded-full hover:bg-white/15 drop-shadow-md transition-colors cursor-pointer" aria-label="Bagikan campaign">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="18" cy="5" r="3"></circle>
                    <circle cx="6" cy="12" r="3"></circle>
                    <circle cx="18" cy="19" r="3"></circle>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                </svg>
            </button>
        </div>
    </section>

    <!-- MAIN DETAIL CONTENT CONTAINER -->
    <main class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 pb-16 flex-1">
        
        <!-- Category Pill Badge -->
        <div class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-[#dcf3e7] text-[#05522d] font-semibold text-[13px] sm:text-[13.5px]">
            <svg class="w-4 h-4 text-[#05522d]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 21a9 9 0 0 0 9-9H3a9 9 0 0 0 9 9Z"></path>
                <path d="M7 21h10"></path>
                <path d="M19.5 12c0-3.8-3.4-7-7.5-7S4.5 8.2 4.5 12"></path>
            </svg>
            <span>{{ $campaign['kategori'] ?? 'Kategori' }}</span>
        </div>

        <!-- Campaign Title -->
        <h1 class="text-[24px] sm:text-[28px] md:text-[32px] font-bold text-gray-950 mt-3.5 sm:mt-4 tracking-tight leading-tight">
            {{ $campaign['judul'] ?? 'Judul Campaign' }}
        </h1>

        <!-- Progress Bar -->
        <div class="mt-4 sm:mt-5 w-full h-5 sm:h-6 rounded-full bg-[#dcf3e7] overflow-hidden">
            <div class="h-full bg-[#05522d] rounded-full" style="width: {{ $campaign['persentase'] ?? 0 }}%"></div>
        </div>

        <!-- Target Row & Action Button -->
        <div class="mt-4 sm:mt-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="text-[15px] sm:text-[16px]">
                <span class="text-[#05522d] font-bold text-[18px] sm:text-[20px]">Rp {{ number_format($campaign['terkumpul'] ?? 0, 0, ',', '.') }}</span>
                <span class="text-gray-700 font-normal">dari target </span>
                <span class="text-gray-950 font-bold text-[16px] sm:text-[17px]">Rp {{ number_format($campaign['target_dana'] ?? 0, 0, ',', '.') }}</span>
            </div>

            <button type="button" onclick="openDonateModal()" class="w-full sm:w-auto px-10 sm:px-14 py-2.5 sm:py-3 bg-[#05522d] hover:bg-[#044023] text-white font-semibold text-[15px] sm:text-[16px] rounded-xl shadow-xs transition-colors cursor-pointer text-center">
                Donasi
            </button>
        </div>

        <!-- Metrics Box -->
        <div class="mt-6 bg-[#dcf3e7] rounded-2xl p-3.5 sm:p-5 grid grid-cols-3 divide-x divide-gray-300/70 text-center">
            <div>
                <span class="text-[16px] sm:text-[18px] font-bold text-gray-950 block mt-0.5">{{ $campaign['persentase'] ?? 0 }}%</span>
                <span class="text-[12px] sm:text-[13.5px] text-gray-700 font-medium block">Terkumpul</span>
            </div>
            <div>
                <span class="text-[16px] sm:text-[18px] font-bold text-gray-950 block">{{ $campaign['sisa_hari'] ?? '-' }}</span>
                <span class="text-[12px] sm:text-[13.5px] text-gray-700 font-medium block mt-0.5">Hari Tersisa</span>
            </div>
            <div>
                <span class="text-[16px] sm:text-[18px] font-bold text-gray-950 block">{{ $campaign['donatur'] ?? '-' }}</span>
                <span class="text-[12px] sm:text-[13.5px] text-gray-700 font-medium block mt-0.5">Donatur</span>
            </div>
        </div>

        <!-- Organizer Header Strip -->
        <div class="mt-6 sm:mt-7 flex items-center gap-3.5 cursor-pointer group w-fit">
            <div class="w-12 h-12 rounded-full border border-gray-200 bg-white flex items-center justify-center p-1 shadow-2xs group-hover:border-[#05522d] transition-colors shrink-0">
                <div class="w-full h-full rounded-full border border-gray-300 flex flex-col items-center justify-center relative p-0.5">
                    <svg viewBox="0 0 32 32" fill="none" class="w-7 h-7 text-gray-700">
                        <circle cx="16" cy="16" r="14" stroke="currentColor" stroke-width="1" stroke-dasharray="2 1.5" />
                        <path d="M10 18C10 14 13 11 16 11C19 11 22 14 22 18" stroke="currentColor" stroke-width="1.2" />
                        <circle cx="16" cy="8" r="2.2" fill="currentColor" />
                        <rect x="7" y="21" width="18" height="5" rx="1.5" fill="#f3f4f6" stroke="currentColor" stroke-width="0.8" />
                        <text x="16" y="24.8" text-anchor="middle" font-size="3.8" font-weight="bold" fill="#374151" font-family="sans-serif">
                            {{ strtoupper(substr($organization->nama_lembaga ?? 'ORG', 0, 7)) }}
                        </text>
                    </svg>
                </div>
            </div>

            <div>
                <h3 class="font-bold text-[15px] sm:text-[16px] text-gray-950 group-hover:text-[#05522d] transition-colors leading-tight">
                    {{ $organization->nama_lembaga ?? 'Nama Organisasi' }}
                </h3>
                <p class="text-[14px] sm:text-[14px] text-gray-500 font-medium mt-0.5">
                    {{ $organization->kota ?? 'Kota' }}
                </p>
            </div>
        </div>

        <!-- ALOKASI PENGGUNAAN DONASI -->
        <section class="mt-8 sm:mt-10">
            <h2 class="text-[18px] sm:text-[20px] font-bold text-gray-950">Alokasi Penggunaan Donasi</h2>
            <p class="text-[14px] sm:text-[15px] text-gray-700 leading-relaxed mt-2.5">
                {{ $campaign['deskripsi'] ?? 'Deskripsi alokasi penggunaan donasi.' }}
            </p>
        </section>

        <!-- DOA & HARAPAN CARD -->
        <section class="mt-9 sm:mt-11">
            <div class="bg-white rounded-2xl border border-gray-200/90 p-5 sm:p-7 shadow-2xs">
                <div class="flex items-start justify-between gap-4 mb-5">
                    <div>
                        <h3 class="text-[17px] sm:text-[18px] font-bold text-gray-950 leading-tight">Doa & Harapan</h3>
                        <p class="text-[14px] sm:text-[15px] text-gray-500 mt-1">Setiap pesan yang dibagikan menjadi semangat dan harapan bagi mereka yang membutuhkan.</p>
                    </div>
                    <a href="{{ route('campaign.prayers', array_filter(['id' => $campaign['id'], 'q' => $backQuery])) }}" class="text-[12px] sm:text-[13px] text-gray-600 hover:text-[#05522d] underline font-medium cursor-pointer shrink-0">Lihat Semua</a>
                </div>

                <div class="space-y-4 sm:space-y-5">
                    @forelse($comments as $cmt)
                        <div class="flex items-start gap-3">
                            <img src="{{ $cmt['avatar'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($cmt['nama']) . '&background=d8f0e2&color=05522d' }}" alt="{{ $cmt['nama'] }}" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full object-cover shrink-0 border border-gray-100" />
                            <div class="flex-1">
                                <div class="flex items-center flex-wrap gap-x-1.5">
                                    <span class="font-semibold text-[13px] sm:text-[14px] text-gray-950">{{ $cmt['nama'] }}</span>
                                    <span class="text-[12px] sm:text-[13px] text-gray-400">{{ $cmt['detail'] }}</span>
                                </div>
                                <p class="text-[14px] sm:text-[15px] text-gray-600 italic mt-0.5 leading-relaxed">&ldquo;{{ $cmt['pesan'] }}&rdquo;</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-[13px] text-gray-500">Belum ada doa & harapan untuk campaign ini.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- CAMPAIGN LAINNYA DARI ORGANISASI INI -->
        <section class="mt-12 sm:mt-14">
            <h2 class="text-[19px] sm:text-[21px] font-bold text-gray-950 mb-4 sm:mb-5">Campaign Lainnya dari {{ $organization->nama_lembaga ?? '' }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($otherCampaigns as $rc)
                    <a href="{{ route('campaign.detail', array_filter(['id' => $rc['id'], 'q' => $backQuery])) }}" class="bg-white rounded-2xl p-3 border border-gray-150 shadow-2xs hover:shadow-md transition-all cursor-pointer group">
                        <div class="w-full h-[155px] rounded-xl overflow-hidden bg-gray-100">
                            <img src="{{ $rc['image_url'] ?? asset('images/default_campaign.jpg') }}" alt="{{ $rc['judul'] }}" class="w-full h-full object-cover group-hover:scale-104 transition-transform duration-300" />
                        </div>
                        <div class="p-1 pt-3">
                            <p class="text-[12px] text-gray-500 font-medium">{{ $rc['nama_organisasi'] ?? '' }}</p>
                            <h3 class="text-[15px] font-bold text-gray-950 group-hover:text-[#05522d] transition-colors line-clamp-1 mt-0.5">{{ $rc['judul'] }}</h3>
                            <p class="text-[12.5px] text-gray-500 mt-2 font-medium">Terkumpul <span class="text-[#05522d] font-bold">Rp {{ number_format($rc['terkumpul'] ?? 0, 0, ',', '.') }}</span></p>
                            <div class="mt-2 w-full h-1.5 rounded-full bg-[#dcf3e7] overflow-hidden">
                                <div class="h-full bg-[#05522d] rounded-full" style="width: {{ $rc['persentase'] ?? 0 }}%"></div>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-gray-500 col-span-full">Tidak ada campaign lain dari organisasi ini.</p>
                @endforelse
            </div>
        </section>
    </main>

    <script>
        function copyLink() {
            navigator.clipboard.writeText(window.location.href);
            alert('Tautan campaign berhasil disalin!');
        }
        function openDonateModal() {
            alert('Membuka formulir donasi untuk {{ $campaign["judul"] ?? "campaign" }}');
        }
    </script>
</body>
@endsection

