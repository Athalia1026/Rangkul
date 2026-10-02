@extends('layouts.footeronly')

@section('title', $campaign['judul'] ?? 'Detail Campaign')

@section('content')
<div class="campaign-detail-page text-gray-900 antialiased selection:bg-[#dcf3e7] selection:text-[#05522d]">

    <!-- HERO BANNER -->
    <section class="relative w-full h-[240px] sm:h-[300px] lg:h-[340px] overflow-hidden bg-gray-900">
        <img src="{{ $campaign['image_url'] ?? asset('images/hero/hero_children.jpg') }}" alt="{{ $campaign['judul'] ?? 'Campaign Image' }}" class="w-full h-full object-cover object-center" />
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/20 to-transparent pointer-events-none"></div>

        <!-- Top Floating Navigation: ← Kembali (Left) & Share (Right) -->
        <div class="absolute top-0 left-0 right-0 w-full max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pt-5 sm:pt-6 flex items-center justify-between z-10">
            @php
                $backQuery = $searchQuery ?? request('q', session('last_search_query', ''));
                $backUrl = route('search.results', $backQuery !== '' ? ['q' => $backQuery] : []);
            @endphp
            <a id="campaign-back-link" href="{{ $backUrl }}" class="inline-flex items-center gap-2 text-white/95 hover:text-white font-medium text-[15px] sm:text-[16px] drop-shadow-md transition-colors group">
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
        
        <!-- Campaign Title -->
        <h1 class="text-[24px] sm:text-[28px] md:text-[32px] font-bold text-gray-950 mt-3.5 sm:mt-4 tracking-tight leading-tight">
            {{ $campaign['judul'] ?? 'Judul Campaign' }}
        </h1>

        <!-- Progress Bar -->
        <div class="campaign-main-progress mt-6 w-full rounded-xl bg-[#dcf3e7] overflow-hidden">
            <div class="h-full bg-[#05522d] rounded-full" style="width: {{ $campaign['persentase'] ?? 0 }}%"></div>
        </div>

        <!-- Target Row & Action Button -->
        <div class="mt-4 sm:mt-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="text-[15px] sm:text-[16px]">
                <span class="text-[#05522d] font-bold text-[18px] sm:text-[20px]">Rp {{ number_format($campaign['terkumpul'] ?? 0, 0, ',', '.') }}</span>
                <span class="text-gray-700 font-normal">dari target </span>
                <span class="text-gray-950 font-bold text-[16px] sm:text-[17px]">Rp {{ number_format($campaign['target_dana'] ?? 0, 0, ',', '.') }}</span>
            </div>

            <button type="button" onclick="openDonateModal()" class="w-full sm:w-[280px] px-10 sm:px-14 py-2.5 sm:py-3 bg-[#05522d] hover:bg-[#044023] text-white font-semibold text-[15px] sm:text-[16px] rounded-xl shadow-xs transition-colors cursor-pointer text-center">
                Donasi
            </button>
        </div>

        <!-- Metrics Box -->
        <div class="campaign-metrics mt-6 bg-[#dcf3e7] rounded-2xl p-3.5 sm:p-5 grid grid-cols-3 divide-x divide-gray-300/70 text-center">
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
        <a href="{{ route('donatur.panti.show', $organization->id) }}" class="mt-6 sm:mt-7 flex items-center gap-3.5 cursor-pointer group w-fit">
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
        </a>

        <!-- ALOKASI PENGGUNAAN DONASI -->
        <section class="mt-8 sm:mt-10">
            <h2 class="text-[18px] sm:text-[20px] font-bold text-gray-950">Alokasi Penggunaan Donasi</h2>
            <p class="text-[14px] sm:text-[15px] text-gray-700 leading-relaxed mt-2.5">
                {{ $campaign['deskripsi'] ?? 'Deskripsi alokasi penggunaan donasi.' }}
            </p>
            @if(!empty($campaign['kebutuhan']))
                <h3 class="mt-5 mb-3 font-semibold text-[#065e38]">Daftar Kebutuhan</h3>
                <ul class="list-disc pl-6 sm:columns-2 gap-10 text-[15px] leading-7">
                    @foreach($campaign['kebutuhan'] as $kebutuhan)
                        <li class="break-inside-avoid">{{ $kebutuhan }}</li>
                    @endforeach
                </ul>
            @endif
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
                                </div>
                                <p class="mt-1 text-[12px] sm:text-[13px] text-gray-500">Berdonasi Rp {{ number_format($cmt['nominal'] ?? 0, 0, ',', '.') }} &middot; {{ $cmt['detail'] }}</p>
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
            <div class="campaign-related-track" tabindex="0" aria-label="Kampanye lainnya, geser untuk melihat lebih banyak">
                @forelse($otherCampaigns as $rc)
                    <a href="{{ route('campaign.detail', array_filter(['id' => $rc['id'], 'q' => $backQuery])) }}" class="campaign-related-card bg-white rounded-2xl overflow-hidden hover:shadow-md transition-all cursor-pointer group">
                        <div class="w-full h-[155px] rounded-xl overflow-hidden bg-gray-100">
                            <img src="{{ $rc['image_url'] ?? asset('images/hero/hero_children.jpg') }}" alt="{{ $rc['judul'] }}" class="w-full h-full object-cover group-hover:scale-104 transition-transform duration-300" />
                        </div>
                        <div class="p-4">
                            <p class="text-[12px] text-gray-500 font-medium">{{ $rc['nama_organisasi'] ?? '' }}</p>
                            <h3 class="text-[15px] font-bold text-gray-950 group-hover:text-[#05522d] transition-colors line-clamp-1 mt-0.5">{{ $rc['judul'] }}</h3>
                            <p class="flex flex-wrap items-baseline gap-x-2 text-[13px] text-gray-500 mt-2 font-medium">Terkumpul <span class="text-[#207466] text-[18px] font-bold">Rp {{ number_format($rc['terkumpul'] ?? 0, 0, ',', '.') }}</span></p>
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

    <style>
        #donate-modal [hidden] { display: none !important; }
        .campaign-detail-page { background: #f5f7f4; }
        .campaign-related-track { display: flex; gap: 26px; overflow-x: auto; scroll-snap-type: x mandatory; padding: 4px 6px 18px; scrollbar-width: thin; scrollbar-color: #b2d9c6 transparent; }
        .campaign-related-card { flex: 0 0 365px; max-width: 88vw; scroll-snap-align: start; box-shadow: 0 5px 10px #183c2526; }
        .campaign-detail-page .campaign-main-progress { height: 36px; background: #d8f0e2; }
        .campaign-detail-page .campaign-main-progress > div { background: #207466; }
        .campaign-detail-page .campaign-metrics { color: #065e38; background: #d8f0e2; }
        #donate-modal {
            width: min(440px, calc(100% - 32px));
            max-height: calc(100dvh - 32px);
            margin: auto;
            padding: 0;
            border: 1px solid #e5eee9;
            border-radius: 24px;
            background: #fff;
            box-shadow: 0 24px 80px rgba(5, 82, 45, .18);
        }

        #donate-modal::backdrop {
            background: rgba(15, 35, 25, .45);
            backdrop-filter: blur(5px);
        }
    </style>

    <dialog id="donate-modal" aria-labelledby="donate-modal-title" aria-describedby="donate-modal-description">
        <div class="relative p-6 sm:p-8 text-center">
            <button type="button" onclick="closeDonateModal()" aria-label="Tutup informasi donasi" class="absolute right-4 top-4 rounded-full p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#065e38]">
                <svg aria-hidden="true" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="m6 6 12 12M18 6 6 18" /></svg>
            </button>

            <div class="mx-auto mt-2 mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#e8f5ee] text-[#065e38]">
                <svg aria-hidden="true" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z" /></svg>
            </div>
            <h2 id="donate-modal-title" class="text-[22px] font-bold tracking-tight text-gray-950">Masuk untuk Berdonasi</h2>
            <p class="mt-2 text-sm leading-relaxed text-gray-500">Kamu ingin mendukung kampanye</p>
            <div class="mt-4 rounded-xl border border-[#e0eee6] bg-[#f5faf7] px-4 py-4">
                <p class="text-[15px] font-semibold leading-relaxed text-[#05522d] break-words">{{ $campaign['judul'] ?? 'Kampanye donasi' }}</p>
                <p class="mt-1 text-xs text-gray-500">{{ $organization->nama_lembaga ?? 'Organisasi penyelenggara' }}</p>
            </div>
            <p id="donate-modal-description" class="mt-4 text-sm leading-relaxed text-gray-500">Silakan masuk dengan akun donatur terlebih dahulu untuk berdonasi dan mendukung kampanye ini.</p>
            <a id="donate-login-link" href="{{ route('login') }}" autofocus class="mt-6 block w-full rounded-xl bg-[#065e38] px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-[#044a2c] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#065e38]">Masuk sebagai Donatur</a>
            <button id="donate-dismiss" type="button" hidden onclick="closeDonateModal()" class="mt-6 w-full rounded-xl bg-[#065e38] px-5 py-3 text-sm font-semibold text-white">Kembali ke Kampanye</button>
            <p id="donate-register-link" class="mt-4 text-xs leading-relaxed text-gray-500">
                Belum punya akun?
                <a href="{{ route('register-donors') }}" class="font-semibold text-[#065e38] underline underline-offset-2 hover:text-[#044a2c]">Daftar sebagai Donatur</a>
            </p>
        </div>
    </dialog>

    <div id="campaign-share-status" role="status" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-50 max-w-[90vw] rounded-xl bg-[#065e38] px-5 py-3 text-sm text-white shadow-lg"></div>
    <script>
        let shareTimer;
        async function copyLink() {
            const status = document.getElementById('campaign-share-status');
            try {
                await navigator.clipboard.writeText(window.location.href);
                status.textContent = 'Tautan kampanye berhasil disalin.';
            } catch {
                status.textContent = 'Tautan belum dapat disalin. Silakan salin alamat dari browser.';
            }
            status.classList.remove('hidden');
            clearTimeout(shareTimer);
            shareTimer = setTimeout(() => status.classList.add('hidden'), 4000);
        }
        async function openDonateModal() {
            if (donateModal.open) return;
            previousPageOverflow = document.documentElement.style.overflow;
            donateModal.showModal();
            document.documentElement.style.overflow = 'hidden';
            const token = localStorage.getItem('auth_token');
            if (!token) return;
            document.getElementById('donate-modal-title').textContent = 'Memeriksa akun donatur';
            document.getElementById('donate-modal-description').textContent = 'Mohon tunggu sebentar.';
            document.getElementById('donate-login-link').hidden = true;
            document.getElementById('donate-register-link').hidden = true;
            document.getElementById('donate-dismiss').hidden = true;
            try {
                const response = await fetch('/api/me', { headers: { Accept: 'application/json', Authorization: `Bearer ${token}` } });
                if (!response.ok) throw new Error('Silakan masuk kembali dengan akun donatur untuk melanjutkan.');
                const result = await response.json();
                if (result.user?.account_type !== 'donatur') throw new Error('Donasi hanya dapat dilakukan menggunakan akun donatur.');
                window.location.href = {{ \Illuminate\Support\Js::from(route('donatur.donasi', $campaign['id'])) }};
            } catch (error) {
                document.getElementById('donate-modal-title').textContent = 'Masuk untuk Berdonasi';
                document.getElementById('donate-modal-description').textContent = error.message;
                document.getElementById('donate-login-link').hidden = false;
                document.getElementById('donate-register-link').hidden = false;
            }
        }

        function closeDonateModal() {
            donateModal.close();
        }

        const donateModal = document.getElementById('donate-modal');
        let previousPageOverflow = '';

        const backLink = document.getElementById('campaign-back-link');
        if (localStorage.getItem('auth_token')) {
            backLink.href = {{ \Illuminate\Support\Js::from(route('donatur.search.results', ['q' => $backQuery])) }};
        }
        if (document.referrer) {
            const previous = new URL(document.referrer);
            if (previous.origin === window.location.origin && ['/donatur/beranda', '/donatur/cari', '/donatur/hasil-pencarian', '/beranda', '/search', '/search-results'].includes(previous.pathname)) {
                backLink.href = previous.href;
            }
        }

        donateModal.addEventListener('close', () => {
            document.documentElement.style.overflow = previousPageOverflow;
        });

        donateModal.addEventListener('click', (event) => {
            if (event.target !== donateModal) return;
            const bounds = donateModal.getBoundingClientRect();
            if (event.clientX < bounds.left || event.clientX > bounds.right ||
                event.clientY < bounds.top || event.clientY > bounds.bottom) {
                closeDonateModal();
            }
        });
    </script>
</div>
@endsection

