@use('App\Support\OrgFormat')

@extends('layouts.organization', [
    'title' => 'Detail Kunjungan',
    'activeNav' => 'kunjungan'
])

@section('content')

@php
    $labelClass = 'text-[20px] text-gray-500';
    $valueClass = 'mt-2 text-[26px] font-semibold text-gray-950 leading-snug break-words';

    $fields = [
        ['label' => 'Nama Pengunjung', 'value' => $visit->donor?->user?->nama ?? '-'],
        ['label' => 'Nama PIC', 'value' => $visit->donor?->companyPremium?->nama_pic ?? $visit->donor?->user?->nama ?? '-'],
        ['label' => 'Email', 'value' => $visit->donor?->user?->email ?? '-'],
        ['label' => 'Tanggal & Waktu', 'value' => OrgFormat::longDate($visit->tanggal_kunjungan) . ', pukul ' . \Illuminate\Support\Str::substr($visit->waktu_kunjungan, 0, 5)],
        ['label' => 'Jumlah Pengunjung', 'value' => $visit->pengunjung . ' Orang'],
        ['label' => 'Diajukan Pada', 'value' => OrgFormat::longDate($visit->created_at)],
    ];
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
        href="{{ route('organisasi.kunjungan') }}"
        class="inline-flex items-center gap-3
               text-[22px]
               text-gray-600
               hover:text-[#08703F]
               transition"
    >
        <i class="fa-solid fa-arrow-left text-[16px]"></i>
        Kembali ke Kunjungan
    </a>



    {{-- =====================================================
        TITLE
    ===================================================== --}}
    <section class="mt-9">

        <div class="flex flex-wrap items-center gap-6">

            <h1
                class="text-[35px]
                       lg:text-[45px]
                       xl:text-[50px]
                       font-bold
                       text-[#08703F]
                       leading-tight"
            >
                Detail Kunjungan
            </h1>

            <span
                class="{{ OrgFormat::statusBadge('visit', $visit->status) }}
                       min-w-[120px]
                       text-center
                       px-7 py-3
                       rounded-full
                       text-[21px]
                       font-semibold"
            >
                {{ OrgFormat::statusLabel('visit', $visit->status) }}
            </span>

        </div>


        <p
            class="mt-5
                   text-[15px]
                   lg:text-[25px]
                   text-gray-500
                   font-medium"
        >
            Informasi lengkap mengenai permintaan kunjungan dari donatur.
        </p>

    </section>



    {{-- =====================================================
        DETAIL CARD
    ===================================================== --}}
    <section class="mt-14">

        <div
            class="bg-white
                   rounded-[28px]
                   border border-gray-100
                   shadow-md
                   px-10
                   lg:px-14
                   py-12"
        >

            {{-- =================================================
                DATA KUNJUNGAN
            ================================================= --}}
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-9">

                @foreach ($fields as $field)
                    <div>
                        <dt class="{{ $labelClass }}">{{ $field['label'] }}</dt>
                        <dd class="{{ $valueClass }}">{{ $field['value'] }}</dd>
                    </div>
                @endforeach


                {{-- TUJUAN KUNJUNGAN --}}
                <div class="sm:col-span-2">
                    <dt class="{{ $labelClass }}">Tujuan Kunjungan</dt>
                    <dd class="mt-2 text-[24px] font-medium text-gray-950 leading-relaxed break-words">
                        {{ $visit->pesan_donatur ?: 'Tidak ada pesan.' }}
                    </dd>
                </div>


                {{-- CATATAN (read-only setelah kunjungan dijawab) --}}
                @if ($visit->status !== 'terkirim')
                    <div class="sm:col-span-2">
                        <dt class="{{ $labelClass }}">Catatan untuk Pengunjung</dt>
                        <dd class="mt-2 text-[24px] font-medium text-gray-950 leading-relaxed break-words">
                            {{ $visit->pesan_organisasi ?: 'Tidak ada catatan.' }}
                        </dd>
                    </div>
                @endif

            </dl>



            @if ($visit->status === 'terkirim')

                {{-- =================================================
                    CATATAN (SATU-SATUNYA INPUT)
                ================================================= --}}
                <div class="mt-10">

                    <label for="pesanOrganisasi" class="{{ $labelClass }} block">
                        Catatan untuk Pengunjung
                    </label>

                    <textarea
                        id="pesanOrganisasi"
                        maxlength="255"
                        rows="3"
                        placeholder="Tulis pesan untuk donatur, misalnya informasi titik temu"
                        class="mt-3
                               w-full
                               min-h-[130px]
                               bg-white
                               border border-gray-300
                               rounded-[16px]
                               px-7 py-5
                               text-[23px]
                               text-gray-900
                               leading-relaxed
                               placeholder:text-gray-400
                               outline-none
                               resize-none
                               focus:border-[#08703F]
                               focus:ring-2
                               focus:ring-[#08703F]/10
                               transition"
                    >{{ $visit->pesan_organisasi }}</textarea>

                    <p class="mt-3 text-[18px] text-gray-500">
                        Catatan ini akan dikirim ke donatur saat Anda menerima atau menolak kunjungan.
                    </p>

                </div>

            @endif



            {{-- =================================================
                BUTTON
            ================================================= --}}
            <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-5 mt-12">

                @if ($visit->status === 'terkirim')

                    {{-- TOLAK --}}
                    <button
                        type="button"
                        data-visit-status="ditolak"
                        data-respond-url="{{ route('organisasi.kunjungan.respond', $visit->id) }}"
                        data-visitor="{{ $visit->donor?->user?->nama ?? 'donatur' }}"
                        data-visit-date="{{ OrgFormat::longDate($visit->tanggal_kunjungan) }}"
                        class="min-w-[160px]
                               inline-flex
                               items-center
                               justify-center
                               bg-white
                               border-2 border-red-500
                               text-red-500
                               px-8 py-4
                               rounded-[16px]
                               text-[23px]
                               font-semibold
                               hover:bg-red-50
                               transition"
                    >
                        Tolak
                    </button>


                    {{-- TERIMA --}}
                    <button
                        type="button"
                        data-visit-status="dikonfirmasi"
                        data-respond-url="{{ route('organisasi.kunjungan.respond', $visit->id) }}"
                        data-visitor="{{ $visit->donor?->user?->nama ?? 'donatur' }}"
                        data-visit-date="{{ OrgFormat::longDate($visit->tanggal_kunjungan) }}"
                        class="min-w-[280px]
                               inline-flex
                               items-center
                               justify-center
                               gap-3
                               bg-[#08703F]
                               hover:bg-[#065D35]
                               text-white
                               px-8 py-4
                               rounded-[16px]
                               text-[23px]
                               font-semibold
                               shadow-sm
                               transition"
                    >
                        <i class="fa-solid fa-check text-[20px]"></i>
                        Terima Kunjungan
                    </button>

                @else

                    <a
                        href="{{ route('organisasi.kunjungan') }}"
                        class="min-w-[200px]
                               text-center
                               bg-[#08703F]
                               hover:bg-[#065D35]
                               text-white
                               px-8 py-4
                               rounded-[16px]
                               text-[24px]
                               font-semibold
                               transition"
                    >
                        Kembali
                    </a>

                @endif

            </div>

        </div>

    </section>



    @if ($visit->status === 'terkirim')
        @include('organisasi.partials.visit-confirm-modal')
    @endif

</main>

@endsection
