@extends('layouts.public')

@section('title', 'Rangkul - Platform Donasi Terpercaya | Doa & Harapan')

@section('content')

    <body
        class="bg-[#f3f4f6] text-gray-900 font-sans min-h-screen flex flex-col antialiased selection:bg-[#dcf3e7] selection:text-[#05522d]">
        <!-- 2. MAIN CARD CONTAINER -->
        <main class="w-full max-w-[1040px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-9 flex-1">
            <div class="bg-white rounded-[24px] sm:rounded-[32px] p-6 sm:p-10 md:p-12 shadow-xs border border-gray-150">

                <!-- Header Row: ← Kembali (Left) & Centered Title -->
                <div class="relative mb-8 sm:mb-10">
                    <!-- Kembali button -->
                    <a href="{{ route('campaign.detail', array_filter(['id' => $campaign->id, 'q' => request('q', session('last_search_query', ''))])) }}"
                        class="sm:absolute sm:left-0 sm:top-1 inline-flex items-center gap-2 text-[15px] sm:text-[16px] text-gray-900 hover:text-[#05522d] font-normal transition-colors mb-3 sm:mb-0 group">
                        <svg class="w-5 h-5 text-gray-900 group-hover:-translate-x-1 transition-transform"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        <span>Kembali</span>
                    </a>

                    <!-- Centered Heading & Subtitle -->
                    <div class="text-center sm:px-24">
                        <h1
                            class="text-[22px] sm:text-[26px] md:text-[28px] font-bold text-gray-950 tracking-tight leading-tight">
                            Doa & Harapan
                        </h1>
                        <p class="text-[13px] sm:text-[14.5px] text-gray-600 mt-2 leading-relaxed">
                            Setiap pesan yang dibagikan menjadi semangat dan harapan bagi mereka yang membutuhkan.
                        </p>
                    </div>
                </div>

                <!-- List of Prayer Entries -->
                <div class="space-y-6 sm:space-y-7">
                    @forelse($prayers as $prayer)
                        <div class="pt-1">
                            <div class="flex items-center gap-3.5">
                                @if($prayer['is_anonim'])
                                    <div
                                        class="w-11 h-11 rounded-full bg-[#d1d5db] text-[#4b5563] flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6 text-gray-600" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z" />
                                        </svg>
                                    </div>
                                @else
                                    <img src="{{ $prayer['avatar'] }}" alt="{{ $prayer['nama'] }}"
                                        class="w-11 h-11 rounded-full object-cover shrink-0 border border-gray-100">
                                @endif

                                <div class="flex flex-col">
                                    <span
                                        class="font-bold text-[15px] sm:text-[15.5px] text-gray-950 leading-tight">{{ $prayer['nama'] }}</span>
                                    <span class="text-[12.5px] sm:text-[13px] text-gray-500 font-normal mt-0.5">
                                        Berdonasi Rp {{ number_format($prayer['nominal'], 0, ',', '.') }} •
                                        {{ $prayer['waktu'] }}
                                    </span>
                                </div>
                            </div>
                            <p class="text-[14px] sm:text-[14.5px] text-gray-800 italic mt-2.5 leading-relaxed font-normal">
                                “{{ $prayer['note'] }}”
                            </p>
                        </div>
                    @empty
                        <div class="py-12 text-center text-gray-500">
                            <p class="text-[14px] sm:text-[15px]">Belum ada doa & harapan untuk penggalangan dana ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
@endsection
</body>

</html>