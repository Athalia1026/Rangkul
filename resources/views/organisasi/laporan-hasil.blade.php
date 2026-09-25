@extends('layouts.organization', [
    'title' => 'Hasil Laporan',
    'activeNav' => 'laporan'
])

@section('content')

{{-- =========================================================
    HERO
========================================================= --}}
<section class="relative h-[500px] overflow-hidden">

    <img
        src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2400&auto=format&fit=crop"
        alt="Laporan"
        class="absolute inset-0 w-full h-full object-cover"
    >

    <div class="absolute inset-0 bg-black/45"></div>

    <div class="relative z-10 h-full flex items-center">

        <div class="w-full px-8 sm:px-10 lg:px-16 xl:px-20 2xl:px-24">

            <h1
                class="text-white
                       text-[62px]
                       lg:text-[70px]
                       xl:text-[76px]
                       font-bold
                       leading-[1.08]"
            >
                Laporan
            </h1>

            <p
                class="mt-7
                       text-white/95
                       text-[28px]
                       lg:text-[31px]
                       font-medium"
            >
                Lihat ringkasan aktivitas dan unduh laporan campaign.
            </p>

        </div>

    </div>

</section>



{{-- =========================================================
    MAIN CONTENT
========================================================= --}}
<main
    class="w-full
           px-8 sm:px-10 lg:px-16 xl:px-20 2xl:px-24
           py-20
           space-y-16"
>

    {{-- =====================================================
        FILTER HASIL LAPORAN
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[26px]
               shadow-md
               border border-gray-100
               px-10 py-10"
    >

        <div class="flex items-center gap-5">

            <div
                class="w-[62px]
                       h-[62px]
                       rounded-[16px]
                       bg-[#DDF0E9]
                       text-[#08703F]
                       flex items-center justify-center"
            >
                <i class="fa-solid fa-calendar-days text-[28px]"></i>
            </div>

            <h2
                class="text-[38px]
                       lg:text-[42px]
                       font-bold
                       text-gray-950"
            >
                Periode Laporan
            </h2>

        </div>


        <div
            class="grid grid-cols-1
                   lg:grid-cols-3
                   gap-8
                   mt-9"
        >

            {{-- TANGGAL AWAL --}}
            <div>

                <label
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-gray-900"
                >
                    Tanggal Awal
                </label>

                <div
                    class="w-full
                           h-[82px]
                           bg-white
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           flex items-center
                           gap-4
                           text-[23px]
                           text-gray-900"
                >
                    <i class="fa-regular fa-calendar-days text-[26px]"></i>
                    01 / 01 / 2026
                </div>

            </div>



            {{-- TANGGAL AKHIR --}}
            <div>

                <label
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-gray-900"
                >
                    Tanggal Akhir
                </label>

                <div
                    class="w-full
                           h-[82px]
                           bg-white
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           flex items-center
                           gap-4
                           text-[23px]
                           text-gray-900"
                >
                    <i class="fa-regular fa-calendar-days text-[26px]"></i>
                    30 / 06 / 2026
                </div>

            </div>



            {{-- CAMPAIGN --}}
            <div>

                <label
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-gray-900"
                >
                    Campaign
                </label>

                <div
                    class="w-full
                           h-[82px]
                           bg-white
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           flex items-center
                           justify-between
                           text-[23px]
                           text-gray-900"
                >
                    <span>Perlengkapan Kelas</span>

                    <i class="fa-solid fa-chevron-down text-[20px]"></i>
                </div>

            </div>

        </div>


        {{-- BUTTONS --}}
        <div class="flex flex-wrap items-center gap-5 mt-10">

            <a
                href="{{ route('organisasi.laporan') }}"
                class="min-w-[190px]
                       h-[76px]
                       inline-flex
                       items-center
                       justify-center
                       border-2
                       border-[#08703F]
                       bg-white
                       text-[#08703F]
                       rounded-[16px]
                       text-[23px]
                       font-semibold
                       hover:bg-[#F0F8F4]
                       transition"
            >
                Tampilkan
            </a>


            <button
                type="button"
                class="min-w-[190px]
                       h-[76px]
                       bg-[#08703F]
                       text-white
                       rounded-[16px]
                       text-[23px]
                       font-semibold
                       hover:bg-[#065D35]
                       transition"
            >
                Download
            </button>

        </div>

    </section>



    {{-- =====================================================
        STATISTIC CARDS
    ===================================================== --}}
    <section>

        <div
            class="grid grid-cols-1
                   sm:grid-cols-2
                   xl:grid-cols-4
                   gap-8"
        >

            {{-- DONASI UANG --}}
            <div
                class="bg-white
                       rounded-[24px]
                       shadow-md
                       border border-gray-100
                       min-h-[225px]
                       overflow-hidden
                       p-5"
            >

                <div
                    class="bg-[#08703F]
                           text-white
                           min-h-[70px]
                           rounded-[16px]
                           px-6
                           flex items-center
                           gap-4"
                >
                    <i class="fa-solid fa-hand-holding-dollar text-[27px]"></i>

                    <span class="text-[23px] font-semibold">
                        Donasi Uang
                    </span>
                </div>

                <div class="px-2 pt-7">

                    <p
                        class="text-[35px]
                               lg:text-[38px]
                               font-bold
                               text-gray-950"
                    >
                        Rp 1.500.000
                    </p>

                    <p class="mt-3 text-[22px] text-gray-600">
                        Total Uang Terkumpul
                    </p>

                </div>

            </div>



            {{-- TOTAL PENCAIRAN --}}
            <div
                class="bg-white
                       rounded-[24px]
                       shadow-md
                       border border-gray-100
                       min-h-[225px]
                       overflow-hidden
                       p-5"
            >

                <div
                    class="bg-[#08703F]
                           text-white
                           min-h-[70px]
                           rounded-[16px]
                           px-6
                           flex items-center
                           gap-4"
                >
                    <i class="fa-solid fa-wallet text-[27px]"></i>

                    <span class="text-[23px] font-semibold">
                        Total Pencairan
                    </span>
                </div>

                <div class="px-2 pt-7">

                    <p
                        class="text-[35px]
                               lg:text-[38px]
                               font-bold
                               text-gray-950"
                    >
                        Rp 1.000.000
                    </p>

                    <p class="mt-3 text-[22px] text-gray-600">
                        Jumlah Uang Pencairan
                    </p>

                </div>

            </div>



            {{-- SALDO TERSISA --}}
            <div
                class="bg-white
                       rounded-[24px]
                       shadow-md
                       border border-gray-100
                       min-h-[225px]
                       overflow-hidden
                       p-5"
            >

                <div
                    class="bg-[#08703F]
                           text-white
                           min-h-[70px]
                           rounded-[16px]
                           px-6
                           flex items-center
                           gap-4"
                >
                    <i class="fa-solid fa-wallet text-[27px]"></i>

                    <span class="text-[23px] font-semibold">
                        Saldo Tersisa
                    </span>
                </div>

                <div class="px-2 pt-7">

                    <p
                        class="text-[35px]
                               lg:text-[38px]
                               font-bold
                               text-gray-950"
                    >
                        Rp 500.000
                    </p>

                    <p class="mt-3 text-[22px] text-gray-600">
                        Jumlah Uang Tersisa
                    </p>

                </div>

            </div>



            {{-- TOTAL DONATUR --}}
            <div
                class="bg-white
                       rounded-[24px]
                       shadow-md
                       border border-gray-100
                       min-h-[225px]
                       overflow-hidden
                       p-5"
            >

                <div
                    class="bg-[#08703F]
                           text-white
                           min-h-[70px]
                           rounded-[16px]
                           px-6
                           flex items-center
                           gap-4"
                >
                    <i class="fa-solid fa-users text-[27px]"></i>

                    <span class="text-[23px] font-semibold">
                        Total Donatur
                    </span>
                </div>

                <div class="px-2 pt-7">

                    <p
                        class="text-[35px]
                               lg:text-[38px]
                               font-bold
                               text-gray-950"
                    >
                        18 Orang
                    </p>

                    <p class="mt-3 text-[22px] text-gray-600">
                        Sebagai Donatur
                    </p>

                </div>

            </div>

        </div>

    </section>



    {{-- =====================================================
        GRAFIK DONASI BULANAN
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[26px]
               shadow-md
               border border-gray-100
               px-8 py-9"
    >

        <div class="flex items-center gap-5 mb-9">

            <div
                class="w-[58px]
                       h-[58px]
                       rounded-[14px]
                       bg-[#DDF0E9]
                       text-[#08703F]
                       flex items-center justify-center"
            >
                <i class="fa-solid fa-bars text-[26px]"></i>
            </div>


            <h2
                class="text-[42px]
                       lg:text-[46px]
                       font-bold
                       text-[#16735F]"
            >
                Grafik Donasi Bulanan
            </h2>

        </div>


        <div
            class="bg-[#F8FAF9]
                   rounded-[20px]
                   border border-gray-100
                   px-8
                   py-12"
        >

            <div
                class="grid grid-cols-6
                       gap-8
                       items-end
                       min-h-[320px]"
            >

                {{-- JAN --}}
                <div class="flex flex-col items-center justify-end h-full">

                    <span class="text-[18px] font-semibold text-[#08703F] mb-3">
                        Rp 400.000
                    </span>

                    <div
                        class="w-[52px]
                               h-[190px]
                               bg-[#08703F]
                               rounded-t-[8px]"
                    ></div>

                    <span class="mt-4 text-[20px] text-gray-700">
                        Jan
                    </span>

                </div>


                {{-- FEB --}}
                <div class="flex flex-col items-center justify-end h-full">

                    <span class="text-[18px] font-semibold text-[#08703F] mb-3">
                        Rp 250.000
                    </span>

                    <div
                        class="w-[52px]
                               h-[120px]
                               bg-[#08703F]
                               rounded-t-[8px]"
                    ></div>

                    <span class="mt-4 text-[20px] text-gray-700">
                        Feb
                    </span>

                </div>


                {{-- MAR --}}
                <div class="flex flex-col items-center justify-end h-full">

                    <span class="text-[18px] font-semibold text-[#08703F] mb-3">
                        Rp 300.000
                    </span>

                    <div
                        class="w-[52px]
                               h-[150px]
                               bg-[#08703F]
                               rounded-t-[8px]"
                    ></div>

                    <span class="mt-4 text-[20px] text-gray-700">
                        Mar
                    </span>

                </div>


                {{-- APR --}}
                <div class="flex flex-col items-center justify-end h-full">

                    <span class="text-[18px] font-semibold text-[#08703F] mb-3">
                        Rp 200.000
                    </span>

                    <div
                        class="w-[52px]
                               h-[95px]
                               bg-[#08703F]
                               rounded-t-[8px]"
                    ></div>

                    <span class="mt-4 text-[20px] text-gray-700">
                        Apr
                    </span>

                </div>


                {{-- MAY --}}
                <div class="flex flex-col items-center justify-end h-full">

                    <span class="text-[18px] font-semibold text-[#08703F] mb-3">
                        Rp 150.000
                    </span>

                    <div
                        class="w-[52px]
                               h-[72px]
                               bg-[#08703F]
                               rounded-t-[8px]"
                    ></div>

                    <span class="mt-4 text-[20px] text-gray-700">
                        May
                    </span>

                </div>


                {{-- JUN --}}
                <div class="flex flex-col items-center justify-end h-full">

                    <span class="text-[18px] font-semibold text-[#08703F] mb-3">
                        Rp 200.000
                    </span>

                    <div
                        class="w-[52px]
                               h-[95px]
                               bg-[#08703F]
                               rounded-t-[8px]"
                    ></div>

                    <span class="mt-4 text-[20px] text-gray-700">
                        Jun
                    </span>

                </div>

            </div>

        </div>

    </section>



    {{-- =====================================================
        DETAIL DONASI MASUK
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[26px]
               shadow-md
               border border-gray-100
               px-8 py-9"
    >

        <div class="flex items-center gap-5 mb-9">

            <div
                class="w-[58px]
                       h-[58px]
                       rounded-[14px]
                       bg-[#DDF0E9]
                       text-[#08703F]
                       flex items-center justify-center"
            >
                <i class="fa-solid fa-bars text-[26px]"></i>
            </div>

            <h2
                class="text-[42px]
                       lg:text-[46px]
                       font-bold
                       text-[#16735F]"
            >
                Detail Donasi Masuk
            </h2>

        </div>


        <div
            class="rounded-[20px]
                   overflow-hidden
                   border border-gray-200
                   overflow-x-auto"
        >

            <table class="w-full min-w-[1200px] table-fixed">

                <thead class="bg-[#08703F] text-white">

                    <tr class="text-[24px]">

                        <th class="w-[7%] px-7 py-6 text-center font-semibold">
                            No
                        </th>

                        <th class="w-[30%] px-7 py-6 text-left font-semibold">
                            Donatur
                        </th>

                        <th class="w-[17%] px-7 py-6 text-center font-semibold">
                            Nominal
                        </th>

                        <th class="w-[17%] px-7 py-6 text-center font-semibold">
                            Tanggal
                        </th>

                        <th class="w-[17%] px-7 py-6 text-center font-semibold">
                            Metode
                        </th>

                        <th class="w-[12%] px-7 py-6 text-center font-semibold">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="text-[23px] text-gray-900">

                    <tr class="hover:bg-[#F0F8F4] transition">
                        <td class="px-7 py-6 text-center">1</td>
                        <td class="px-7 py-6 font-medium">Budi</td>
                        <td class="px-7 py-6 text-center">Rp 200.000</td>
                        <td class="px-7 py-6 text-center">15/01/2026</td>
                        <td class="px-7 py-6 text-center">Transfer Bank</td>
                        <td class="px-7 py-6 text-center">
                            <span class="inline-flex min-w-[115px] justify-center bg-[#D7EFE5] text-[#10765B] px-5 py-3 rounded-full text-[19px] font-semibold">
                                Berhasil
                            </span>
                        </td>
                    </tr>


                    <tr class="hover:bg-[#F0F8F4] transition">
                        <td class="px-7 py-6 text-center">2</td>
                        <td class="px-7 py-6 font-medium">Andi</td>
                        <td class="px-7 py-6 text-center">Rp 150.000</td>
                        <td class="px-7 py-6 text-center">22/01/2026</td>
                        <td class="px-7 py-6 text-center">E-Wallet</td>
                        <td class="px-7 py-6 text-center">
                            <span class="inline-flex min-w-[115px] justify-center bg-[#D7EFE5] text-[#10765B] px-5 py-3 rounded-full text-[19px] font-semibold">
                                Berhasil
                            </span>
                        </td>
                    </tr>


                    <tr class="hover:bg-[#F0F8F4] transition">
                        <td class="px-7 py-6 text-center">3</td>
                        <td class="px-7 py-6 font-medium">Sari</td>
                        <td class="px-7 py-6 text-center">Rp 300.000</td>
                        <td class="px-7 py-6 text-center">10/02/2026</td>
                        <td class="px-7 py-6 text-center">Transfer Bank</td>
                        <td class="px-7 py-6 text-center">
                            <span class="inline-flex min-w-[115px] justify-center bg-[#D7EFE5] text-[#10765B] px-5 py-3 rounded-full text-[19px] font-semibold">
                                Berhasil
                            </span>
                        </td>
                    </tr>


                    <tr class="hover:bg-[#F0F8F4] transition">
                        <td class="px-7 py-6 text-center">4</td>
                        <td class="px-7 py-6 font-medium">Dewi</td>
                        <td class="px-7 py-6 text-center">Rp 100.000</td>
                        <td class="px-7 py-6 text-center">05/03/2026</td>
                        <td class="px-7 py-6 text-center">E-Wallet</td>
                        <td class="px-7 py-6 text-center">
                            <span class="inline-flex min-w-[115px] justify-center bg-[#D7EFE5] text-[#10765B] px-5 py-3 rounded-full text-[19px] font-semibold">
                                Berhasil
                            </span>
                        </td>
                    </tr>


                    <tr class="hover:bg-[#F0F8F4] transition">
                        <td class="px-7 py-6 text-center">5</td>
                        <td class="px-7 py-6 font-medium">Rudi</td>
                        <td class="px-7 py-6 text-center">Rp 250.000</td>
                        <td class="px-7 py-6 text-center">18/04/2026</td>
                        <td class="px-7 py-6 text-center">Transfer Bank</td>
                        <td class="px-7 py-6 text-center">
                            <span class="inline-flex min-w-[115px] justify-center bg-[#D7EFE5] text-[#10765B] px-5 py-3 rounded-full text-[19px] font-semibold">
                                Berhasil
                            </span>
                        </td>
                    </tr>


                    <tr class="hover:bg-[#F0F8F4] transition">
                        <td class="px-7 py-6 text-center">6</td>
                        <td class="px-7 py-6 font-medium">Maya</td>
                        <td class="px-7 py-6 text-center">Rp 500.000</td>
                        <td class="px-7 py-6 text-center">02/06/2026</td>
                        <td class="px-7 py-6 text-center">Transfer Bank</td>
                        <td class="px-7 py-6 text-center">
                            <span class="inline-flex min-w-[115px] justify-center bg-[#D7EFE5] text-[#10765B] px-5 py-3 rounded-full text-[19px] font-semibold">
                                Berhasil
                            </span>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </section>



    {{-- =====================================================
        RIWAYAT PENCAIRAN
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[26px]
               shadow-md
               border border-gray-100
               px-8 py-9"
    >

        <div class="flex items-center gap-5 mb-9">

            <div
                class="w-[58px]
                       h-[58px]
                       rounded-[14px]
                       bg-[#DDF0E9]
                       text-[#08703F]
                       flex items-center justify-center"
            >
                <i class="fa-solid fa-bars text-[26px]"></i>
            </div>

            <h2
                class="text-[42px]
                       lg:text-[46px]
                       font-bold
                       text-[#16735F]"
            >
                Riwayat Pencairan
            </h2>

        </div>


        <div
            class="rounded-[20px]
                   overflow-hidden
                   border border-gray-200
                   overflow-x-auto"
        >

            <table class="w-full min-w-[1100px] table-fixed">

                <thead class="bg-[#08703F] text-white">

                    <tr class="text-[24px]">

                        <th class="w-[8%] px-7 py-6 text-center font-semibold">
                            No
                        </th>

                        <th class="w-[32%] px-7 py-6 text-left font-semibold">
                            Nominal
                        </th>

                        <th class="w-[25%] px-7 py-6 text-center font-semibold">
                            Tanggal Pengajuan
                        </th>

                        <th class="w-[20%] px-7 py-6 text-center font-semibold">
                            Tanggal Cair
                        </th>

                        <th class="w-[15%] px-7 py-6 text-center font-semibold">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="text-[23px] text-gray-900">

                    <tr class="hover:bg-[#F0F8F4] transition">
                        <td class="px-7 py-6 text-center">1</td>
                        <td class="px-7 py-6 font-medium">Rp 500.000</td>
                        <td class="px-7 py-6 text-center">01/02/2026</td>
                        <td class="px-7 py-6 text-center">05/02/2026</td>
                        <td class="px-7 py-6 text-center">
                            <span class="inline-flex min-w-[115px] justify-center bg-[#D7EFE5] text-[#10765B] px-5 py-3 rounded-full text-[19px] font-semibold">
                                Berhasil
                            </span>
                        </td>
                    </tr>


                    <tr class="hover:bg-[#F0F8F4] transition">
                        <td class="px-7 py-6 text-center">2</td>
                        <td class="px-7 py-6 font-medium">Rp 300.000</td>
                        <td class="px-7 py-6 text-center">15/03/2026</td>
                        <td class="px-7 py-6 text-center">20/03/2026</td>
                        <td class="px-7 py-6 text-center">
                            <span class="inline-flex min-w-[115px] justify-center bg-[#D7EFE5] text-[#10765B] px-5 py-3 rounded-full text-[19px] font-semibold">
                                Berhasil
                            </span>
                        </td>
                    </tr>


                    <tr class="hover:bg-[#F0F8F4] transition">
                        <td class="px-7 py-6 text-center">3</td>
                        <td class="px-7 py-6 font-medium">Rp 200.000</td>
                        <td class="px-7 py-6 text-center">10/05/2026</td>
                        <td class="px-7 py-6 text-center">-</td>
                        <td class="px-7 py-6 text-center">
                            <span class="inline-flex min-w-[125px] justify-center bg-[#E4E9FA] text-[#5665A6] px-5 py-3 rounded-full text-[19px] font-semibold">
                                Menunggu
                            </span>
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </section>



    {{-- =====================================================
        PAGINATION
    ===================================================== --}}
    <section class="flex justify-center">

        <nav class="flex flex-wrap items-center justify-center gap-5">

            <button
                type="button"
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
            </button>


            <div
                class="flex items-center
                       gap-3
                       bg-white
                       border border-gray-200
                       rounded-[14px]
                       p-2
                       shadow-sm"
            >

                <button
                    class="w-[58px] h-[58px]
                           rounded-xl
                           bg-[#08703F]
                           text-white
                           text-[21px]
                           font-semibold"
                >
                    1
                </button>


                <button
                    class="w-[58px] h-[58px]
                           rounded-xl
                           text-[#08703F]
                           text-[21px]
                           font-semibold
                           hover:bg-[#F0F8F4]"
                >
                    2
                </button>


                <button
                    class="w-[58px] h-[58px]
                           rounded-xl
                           text-[#08703F]
                           text-[21px]
                           font-semibold
                           hover:bg-[#F0F8F4]"
                >
                    3
                </button>

            </div>


            <button
                type="button"
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
            </button>

        </nav>

    </section>

</main>

@endsection