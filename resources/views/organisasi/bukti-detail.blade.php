@use('App\Support\OrgFormat')

@extends('layouts.organization', [
    'title' => 'Detail Bukti',
    'activeNav' => 'kampanye'
])

@section('content')

@php
    $campaign = $proof->fundDisbursement?->campaign;
    $fileUrl = OrgFormat::storageUrl($proof->lokasi_file);
    $isPdf = \Illuminate\Support\Str::endsWith(strtolower($proof->lokasi_file), '.pdf');

    $labelClass = 'text-[20px] text-gray-500';
@endphp

<main
    class="w-full
           org-container px-6 sm:px-8 lg:px-12
           pt-16 pb-24"
>

    {{-- =====================================================
        BACK
    ===================================================== --}}
    <a
        href="{{ route('organisasi.kampanye.detail', ['campaign' => $proof->fundDisbursement?->id_campaign, 'tab' => 'bukti']) }}"
        class="inline-flex items-center gap-3
               text-[22px]
               text-gray-600
               hover:text-[#08703F]
               transition"
    >
        <i class="fa-solid fa-arrow-left text-[16px]"></i>
        Kembali ke Kampanye
    </a>



    {{-- =====================================================
        DETAIL CARD
    ===================================================== --}}
    <section
        class="mt-9
               bg-white
               rounded-[28px]
               shadow-md
               border border-gray-100
               px-10
               lg:px-14
               py-12"
    >

        <h1
            class="text-[38px]
                   lg:text-[44px]
                   font-bold
                   text-gray-950
                   leading-tight"
        >
            Detail Bukti Penyaluran
        </h1>

        @if ($campaign)
            <p class="mt-2 text-[21px] text-gray-500">
                Kampanye: {{ $campaign->judul }}
            </p>
        @endif


        <div
            class="grid grid-cols-1
                   lg:grid-cols-[minmax(0,0.85fr)_minmax(0,1fr)]
                   gap-12
                   mt-10"
        >

            {{-- =================================================
                FOTO BUKTI
            ================================================= --}}
            <div
                class="relative
                       h-[560px]
                       rounded-[24px]
                       overflow-hidden
                       bg-[#F0F8F4]"
            >

                @if ($isPdf)
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-5 text-[#08703F]">
                        <i class="fa-regular fa-file-pdf text-[90px]"></i>
                        <span class="text-[24px] font-semibold">Dokumen PDF</span>
                    </div>
                @else
                    <img
                        src="{{ $fileUrl }}"
                        alt="Bukti {{ $proof->fundDisbursement?->alokasi_dana }}"
                        class="absolute inset-0 w-full h-full object-cover"
                    >
                @endif


                {{-- TOMBOL LIHAT --}}
                <div
                    class="absolute inset-x-0 bottom-0
                           pt-20 pb-7
                           flex justify-center
                           bg-gradient-to-t from-black/70 to-transparent"
                >
                    @if ($isPdf)
                        <a
                            href="{{ $fileUrl }}"
                            target="_blank"
                            rel="noopener"
                            class="inline-flex items-center gap-3
                                   text-white
                                   text-[23px]
                                   font-semibold
                                   hover:underline"
                        >
                            <i class="fa-solid fa-up-right-from-square text-[20px]"></i>
                            Buka Dokumen
                        </a>
                    @else
                        <button
                            type="button"
                            data-open-photo
                            class="inline-flex items-center gap-3
                                   text-white
                                   text-[23px]
                                   font-semibold
                                   hover:underline"
                        >
                            <i class="fa-solid fa-magnifying-glass-plus text-[22px]"></i>
                            Lihat Foto
                        </button>
                    @endif
                </div>

            </div>



            {{-- =================================================
                INFORMASI
            ================================================= --}}
            <div class="lg:pt-2">

                <h2
                    class="text-[40px]
                           lg:text-[46px]
                           font-bold
                           text-[#08703F]
                           leading-tight
                           break-words"
                >
                    {{ $proof->fundDisbursement?->alokasi_dana ?? '-' }}
                </h2>

                <span
                    class="mt-5
                           inline-flex
                           {{ OrgFormat::statusBadge('proof', $proof->status) }}
                           px-6 py-2
                           rounded-full
                           text-[21px]
                           font-semibold"
                >
                    {{ OrgFormat::statusLabel('proof', $proof->status) }}
                </span>


                <dl class="mt-10 space-y-8">

                    <div>
                        <dt class="{{ $labelClass }}">Nominal Penyaluran</dt>
                        <dd class="mt-2 text-[40px] font-bold text-gray-950 leading-none">
                            {{ OrgFormat::rupiah($proof->nominal) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="{{ $labelClass }}">Tanggal Penyaluran</dt>
                        <dd class="mt-2 text-[26px] font-medium text-gray-950">
                            {{ OrgFormat::longDate($proof->uploaded_at) }}
                        </dd>
                    </div>

                    <div>
                        <dt class="{{ $labelClass }}">Catatan</dt>
                        <dd class="mt-2 text-[24px] text-gray-900 leading-relaxed whitespace-pre-line break-words">
                            {{ $proof->deskripsi ?: '-' }}
                        </dd>
                    </div>

                    @if ($proof->status === 'ditolak' && $proof->alasan_tolak)
                        <div class="bg-[#FDF2F2] border border-red-100 rounded-[16px] px-6 py-5">
                            <dt class="text-[20px] font-semibold text-red-600">Alasan Penolakan</dt>
                            <dd class="mt-2 text-[23px] text-red-700 leading-relaxed break-words">
                                {{ $proof->alasan_tolak }}
                            </dd>
                        </div>
                    @endif

                </dl>

            </div>

        </div>

    </section>



    {{-- =====================================================
        POP UP FOTO
    ===================================================== --}}
    @unless ($isPdf)
        <div
            id="photoModal"
            class="hidden
                   fixed inset-0 z-50
                   items-center justify-center
                   bg-black/80
                   p-10"
            role="dialog"
            aria-modal="true"
            aria-label="Foto bukti penyaluran"
        >

            <button
                type="button"
                data-close-photo
                class="absolute top-8 right-10
                       w-[60px] h-[60px]
                       rounded-full
                       bg-white/15
                       text-white
                       text-[28px]
                       hover:bg-white/25
                       transition"
                aria-label="Tutup"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

            <img
                src="{{ $fileUrl }}"
                alt="Bukti {{ $proof->fundDisbursement?->alokasi_dana }}"
                class="max-w-full max-h-full rounded-[16px] object-contain"
            >

        </div>
    @endunless

</main>

@endsection


@unless ($isPdf)
@push('scripts')
<script>
    (function () {
        const modal = document.getElementById('photoModal');

        function toggle(open) {
            modal.classList.toggle('hidden', !open);
            modal.classList.toggle('flex', open);
        }

        document.querySelector('[data-open-photo]').addEventListener('click', function () {
            toggle(true);
        });

        modal.querySelector('[data-close-photo]').addEventListener('click', function () {
            toggle(false);
        });

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                toggle(false);
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                toggle(false);
            }
        });
    })();
</script>
@endpush
@endunless
