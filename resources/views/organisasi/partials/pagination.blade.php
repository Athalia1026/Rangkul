{{-- Pagination organisasi. Butuh $paginator (LengthAwarePaginator). --}}
@if ($paginator->hasPages())
    @php
        $start = max(1, $paginator->currentPage() - 1);
        $end = min($paginator->lastPage(), $start + 2);
        $start = max(1, $end - 2);
    @endphp

    <section class="flex justify-center {{ $class ?? 'mt-16' }}">

        <nav class="flex flex-wrap items-center justify-center gap-5">

            {{-- PREVIOUS --}}
            @if ($paginator->onFirstPage())
                <span
                    class="inline-flex
                           items-center
                           gap-3
                           px-8 py-4
                           border-2 border-gray-300
                           rounded-[14px]
                           bg-white
                           text-gray-400
                           text-[22px]
                           font-semibold
                           cursor-not-allowed"
                >
                    <i class="fa-solid fa-chevron-left text-[15px]"></i>
                    Kembali
                </span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    class="inline-flex
                           items-center
                           gap-3
                           px-8 py-4
                           border-2 border-[#08703F]
                           rounded-[14px]
                           bg-white
                           text-[#08703F]
                           text-[22px]
                           font-semibold
                           hover:bg-[#F0F8F4]
                           transition"
                >
                    <i class="fa-solid fa-chevron-left text-[15px]"></i>
                    Kembali
                </a>
            @endif



            {{-- PAGE NUMBERS --}}
            <div
                class="flex items-center
                       gap-3
                       bg-white
                       border border-gray-200
                       rounded-[14px]
                       p-2
                       shadow-sm"
            >

                @foreach (range($start, $end) as $page)
                    <a
                        href="{{ $paginator->url($page) }}"
                        class="w-[58px] h-[58px]
                               rounded-xl
                               flex items-center justify-center
                               text-[21px]
                               font-semibold
                               transition
                               {{ $page === $paginator->currentPage() ? 'bg-[#08703F] text-white' : 'text-[#08703F] hover:bg-[#F0F8F4]' }}"
                    >
                        {{ $page }}
                    </a>
                @endforeach

            </div>



            {{-- NEXT --}}
            @if ($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    class="inline-flex
                           items-center
                           gap-3
                           px-8 py-4
                           rounded-[14px]
                           bg-[#08703F]
                           text-white
                           text-[22px]
                           font-semibold
                           hover:bg-[#065D35]
                           transition"
                >
                    Selanjutnya
                    <i class="fa-solid fa-chevron-right text-[15px]"></i>
                </a>
            @else
                <span
                    class="inline-flex
                           items-center
                           gap-3
                           px-8 py-4
                           rounded-[14px]
                           bg-gray-300
                           text-white
                           text-[22px]
                           font-semibold
                           cursor-not-allowed"
                >
                    Selanjutnya
                    <i class="fa-solid fa-chevron-right text-[15px]"></i>
                </span>
            @endif

        </nav>

    </section>
@endif
