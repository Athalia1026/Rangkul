@extends('layouts.organization', [
    'title' => 'Laporan Organisasi',
    'activeNav' => 'laporan'
])

@section('content')

{{-- =========================================================
    HERO LAPORAN
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
        PERIODE LAPORAN
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[26px]
               shadow-md
               border border-gray-100
               px-10
               py-10"
    >

        {{-- TITLE --}}
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



        {{-- FILTER --}}
        <div
            class="grid grid-cols-1
                   lg:grid-cols-3
                   gap-8
                   mt-9"
        >

            {{-- TANGGAL AWAL --}}
            <div>

                <label
                    for="tanggalAwal"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-gray-900"
                >
                    Tanggal Awal
                </label>


                <div class="relative">

                    <i
                        class="fa-regular fa-calendar-days
                               absolute
                               left-6
                               top-1/2
                               -translate-y-1/2
                               text-[26px]
                               text-gray-700
                               pointer-events-none"
                    ></i>


                    <input
                        id="tanggalAwal"
                        type="text"
                        placeholder="Pilih tanggal awal"
                        onfocus="this.type='date'"
                        onblur="if(!this.value)this.type='text'"
                        class="w-full
                               h-[82px]
                               pl-[65px]
                               pr-6
                               bg-white
                               border border-gray-300
                               rounded-[16px]
                               text-[23px]
                               text-gray-800
                               placeholder:text-gray-400
                               outline-none
                               focus:border-[#08703F]
                               focus:ring-2
                               focus:ring-[#08703F]/10
                               transition"
                    >

                </div>

            </div>



            {{-- TANGGAL AKHIR --}}
            <div>

                <label
                    for="tanggalAkhir"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-gray-900"
                >
                    Tanggal Akhir
                </label>


                <div class="relative">

                    <i
                        class="fa-regular fa-calendar-days
                               absolute
                               left-6
                               top-1/2
                               -translate-y-1/2
                               text-[26px]
                               text-gray-700
                               pointer-events-none"
                    ></i>


                    <input
                        id="tanggalAkhir"
                        type="text"
                        placeholder="Pilih tanggal akhir"
                        onfocus="this.type='date'"
                        onblur="if(!this.value)this.type='text'"
                        class="w-full
                               h-[82px]
                               pl-[65px]
                               pr-6
                               bg-white
                               border border-gray-300
                               rounded-[16px]
                               text-[23px]
                               text-gray-800
                               placeholder:text-gray-400
                               outline-none
                               focus:border-[#08703F]
                               focus:ring-2
                               focus:ring-[#08703F]/10
                               transition"
                    >

                </div>

            </div>



            {{-- CAMPAIGN --}}
            <div>

                <label
                    for="campaign"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-gray-900"
                >
                    Campaign
                </label>


                <div class="relative">

                    <select
                        id="campaign"
                        class="w-full
                               h-[82px]
                               appearance-none
                               bg-white
                               border border-gray-300
                               rounded-[16px]
                               px-6
                               pr-16
                               text-[23px]
                               text-gray-500
                               outline-none
                               focus:border-[#08703F]
                               focus:ring-2
                               focus:ring-[#08703F]/10"
                    >

                        <option value="">
                            Pilih campaign
                        </option>

                        <option>
                            Perlengkapan Kelas
                        </option>

                        <option>
                            Makanan Ringan
                        </option>

                        <option>
                            Baju Sekolah
                        </option>

                        <option>
                            Renovasi Kelas
                        </option>

                    </select>


                    <i
                        class="fa-solid fa-chevron-down
                               absolute
                               right-6
                               top-1/2
                               -translate-y-1/2
                               text-[20px]
                               text-gray-700
                               pointer-events-none"
                    ></i>

                </div>

            </div>

        </div>



        {{-- BUTTONS --}}
        <div
            class="flex
                   flex-wrap
                   items-center
                   gap-5
                   mt-10"
        >

            <a
    href="{{ route('organisasi.laporan.hasil') }}"
    class="min-w-[190px]
           h-[76px]
           inline-flex
           items-center
           justify-center
           border-2 border-[#08703F]
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
                        Rp 3.500.000
                    </p>


                    <p
                        class="mt-3
                               text-[22px]
                               text-gray-600"
                    >
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
                        Rp 2.000.000
                    </p>


                    <p
                        class="mt-3
                               text-[22px]
                               text-gray-600"
                    >
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
                        Rp 1.500.000
                    </p>


                    <p
                        class="mt-3
                               text-[22px]
                               text-gray-600"
                    >
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
                        47 Orang
                    </p>


                    <p
                        class="mt-3
                               text-[22px]
                               text-gray-600"
                    >
                        Sebagai Donatur
                    </p>

                </div>

            </div>

        </div>

    </section>



    {{-- =====================================================
        RINGKASAN CAMPAIGN
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[26px]
               shadow-md
               border border-gray-100
               px-8
               py-9"
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
                Ringkasan Campaign
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

                        <th class="w-[37%] px-7 py-6 text-left font-semibold">
                            Campaign
                        </th>

                        <th class="w-[18%] px-7 py-6 text-center font-semibold">
                            Donasi Masuk
                        </th>

                        <th class="w-[17%] px-7 py-6 text-center font-semibold">
                            Pencairan
                        </th>

                        <th class="w-[15%] px-7 py-6 text-center font-semibold">
                            Saldo
                        </th>

                        <th class="w-[13%] px-7 py-6 text-center font-semibold">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="text-[23px] text-gray-900">

                    <tr class="hover:bg-[#F0F8F4] transition">

                        <td class="px-7 py-6 font-medium">
                            Perlengkapan Kelas
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 1.500.000
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 1.000.000
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 500.000
                        </td>

                        <td class="px-7 py-6 text-center">

                            <span
                                class="inline-flex
                                       min-w-[110px]
                                       justify-center
                                       bg-[#D7EFE5]
                                       text-[#10765B]
                                       px-5 py-3
                                       rounded-full
                                       text-[19px]
                                       font-semibold"
                            >
                                Aktif
                            </span>

                        </td>

                    </tr>


                    <tr class="hover:bg-[#F0F8F4] transition">

                        <td class="px-7 py-6 font-medium">
                            Makanan Ringan
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 800.000
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 500.000
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 300.000
                        </td>

                        <td class="px-7 py-6 text-center">

                            <span
                                class="inline-flex
                                       min-w-[110px]
                                       justify-center
                                       bg-[#E4E9FA]
                                       text-[#5665A6]
                                       px-5 py-3
                                       rounded-full
                                       text-[19px]
                                       font-semibold"
                            >
                                Selesai
                            </span>

                        </td>

                    </tr>


                    <tr class="hover:bg-[#F0F8F4] transition">

                        <td class="px-7 py-6 font-medium">
                            Baju Sekolah
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 700.000
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 300.000
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 400.000
                        </td>

                        <td class="px-7 py-6 text-center">

                            <span
                                class="inline-flex
                                       min-w-[110px]
                                       justify-center
                                       bg-[#D7EFE5]
                                       text-[#10765B]
                                       px-5 py-3
                                       rounded-full
                                       text-[19px]
                                       font-semibold"
                            >
                                Aktif
                            </span>

                        </td>

                    </tr>


                    <tr class="hover:bg-[#F0F8F4] transition">

                        <td class="px-7 py-6 font-medium">
                            Renovasi Kelas
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 500.000
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 200.000
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 300.000
                        </td>

                        <td class="px-7 py-6 text-center">

                            <span
                                class="inline-flex
                                       min-w-[110px]
                                       justify-center
                                       bg-[#D7EFE5]
                                       text-[#10765B]
                                       px-5 py-3
                                       rounded-full
                                       text-[19px]
                                       font-semibold"
                            >
                                Aktif
                            </span>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </section>



    {{-- =====================================================
        RINGKASAN BOOKING
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[26px]
               shadow-md
               border border-gray-100
               px-8
               py-9"
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
                Ringkasan Booking
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

                        <th class="w-[52%] px-7 py-6 text-left font-semibold">
                            Nama
                        </th>

                        <th class="w-[20%] px-7 py-6 text-center font-semibold">
                            Tanggal
                        </th>

                        <th class="w-[15%] px-7 py-6 text-center font-semibold">
                            Jumlah Orang
                        </th>

                        <th class="w-[13%] px-7 py-6 text-center font-semibold">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="text-[23px] text-gray-900">

                    <tr class="hover:bg-[#F0F8F4] transition">

                        <td class="px-7 py-6 font-medium">
                            John
                        </td>

                        <td class="px-7 py-6 text-center">
                            18 / 01 / 2026
                        </td>

                        <td class="px-7 py-6 text-center">
                            12
                        </td>

                        <td class="px-7 py-6 text-center">

                            <span
                                class="inline-flex
                                       min-w-[125px]
                                       justify-center
                                       bg-[#D7EFE5]
                                       text-[#10765B]
                                       px-5 py-3
                                       rounded-full
                                       text-[19px]
                                       font-semibold"
                            >
                                Diterima
                            </span>

                        </td>

                    </tr>


                    <tr class="hover:bg-[#F0F8F4] transition">

                        <td class="px-7 py-6 font-medium">
                            PT Maju
                        </td>

                        <td class="px-7 py-6 text-center">
                            18 / 04 / 2026
                        </td>

                        <td class="px-7 py-6 text-center">
                            8
                        </td>

                        <td class="px-7 py-6 text-center">

                            <span
                                class="inline-flex
                                       min-w-[125px]
                                       justify-center
                                       bg-[#E4E9FA]
                                       text-[#5665A6]
                                       px-5 py-3
                                       rounded-full
                                       text-[19px]
                                       font-semibold"
                            >
                                Menunggu
                            </span>

                        </td>

                    </tr>


                    <tr class="hover:bg-[#F0F8F4] transition">

                        <td class="px-7 py-6 font-medium">
                            Sane
                        </td>

                        <td class="px-7 py-6 text-center">
                            22 / 04 / 2026
                        </td>

                        <td class="px-7 py-6 text-center">
                            3
                        </td>

                        <td class="px-7 py-6 text-center">

                            <span
                                class="inline-flex
                                       min-w-[125px]
                                       justify-center
                                       bg-[#F8DEDE]
                                       text-[#B43B3B]
                                       px-5 py-3
                                       rounded-full
                                       text-[19px]
                                       font-semibold"
                            >
                                Ditolak
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
               px-8
               py-9"
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

                        <th class="w-[50%] px-7 py-6 text-left font-semibold">
                            Kampanye
                        </th>

                        <th class="w-[20%] px-7 py-6 text-center font-semibold">
                            Nominal
                        </th>

                        <th class="w-[17%] px-7 py-6 text-center font-semibold">
                            Tanggal
                        </th>

                        <th class="w-[13%] px-7 py-6 text-center font-semibold">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="text-[23px] text-gray-900">

                    <tr class="hover:bg-[#F0F8F4] transition">

                        <td class="px-7 py-6 font-medium">
                            Perlengkapan Kelas
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 500.000
                        </td>

                        <td class="px-7 py-6 text-center">
                            12 / 02 / 2026
                        </td>

                        <td class="px-7 py-6 text-center">

                            <span
                                class="inline-flex
                                       min-w-[125px]
                                       justify-center
                                       bg-[#D7EFE5]
                                       text-[#10765B]
                                       px-5 py-3
                                       rounded-full
                                       text-[19px]
                                       font-semibold"
                            >
                                Berhasil
                            </span>

                        </td>

                    </tr>


                    <tr class="hover:bg-[#F0F8F4] transition">

                        <td class="px-7 py-6 font-medium">
                            Makanan Ringan
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 300.000
                        </td>

                        <td class="px-7 py-6 text-center">
                            15 / 03 / 2026
                        </td>

                        <td class="px-7 py-6 text-center">

                            <span
                                class="inline-flex
                                       min-w-[125px]
                                       justify-center
                                       bg-[#D7EFE5]
                                       text-[#10765B]
                                       px-5 py-3
                                       rounded-full
                                       text-[19px]
                                       font-semibold"
                            >
                                Berhasil
                            </span>

                        </td>

                    </tr>


                    <tr class="hover:bg-[#F0F8F4] transition">

                        <td class="px-7 py-6 font-medium">
                            Baju Sekolah
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 200.000
                        </td>

                        <td class="px-7 py-6 text-center">
                            20 / 04 / 2026
                        </td>

                        <td class="px-7 py-6 text-center">

                            <span
                                class="inline-flex
                                       min-w-[125px]
                                       justify-center
                                       bg-[#E4E9FA]
                                       text-[#5665A6]
                                       px-5 py-3
                                       rounded-full
                                       text-[19px]
                                       font-semibold"
                            >
                                Menunggu
                            </span>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </section>

</main>

@endsection