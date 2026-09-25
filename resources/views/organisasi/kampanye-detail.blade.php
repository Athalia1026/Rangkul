@extends('layouts.organization', [
    'title' => 'Detail Kampanye',
    'activeNav' => 'kampanye'
])

@section('content')

<main
    class="w-full
           px-8 sm:px-10 lg:px-16 xl:px-20 2xl:px-24
           pt-16 pb-24"
>

    {{-- =====================================================
        BACK
    ===================================================== --}}
    <a
        href="{{ route('organisasi.kampanye') }}"
        class="inline-flex
               items-center
               gap-3
               text-[22px]
               text-gray-600
               hover:text-[#08703F]
               transition"
    >
        <i class="fa-solid fa-arrow-left text-[16px]"></i>
        Kembali ke Kampanye
    </a>


    {{-- =====================================================
        TITLE
    ===================================================== --}}
    <section class="mt-9">

        <div class="flex flex-wrap items-center gap-6">

            <h1
                class="text-[54px]
                       lg:text-[60px]
                       xl:text-[64px]
                       font-bold
                       text-gray-950
                       leading-tight"
            >
                Anggaran Pembagian Beras
            </h1>

            <span
                class="bg-[#D7EFE5]
                       text-[#10765B]
                       min-w-[120px]
                       text-center
                       px-7 py-3
                       rounded-full
                       text-[21px]
                       font-semibold"
            >
                Aktif
            </span>

        </div>

        <p
            class="mt-4
                   text-[26px]
                   lg:text-[28px]
                   text-[#16735F]
                   font-medium"
        >
            SMA Bumi Rejo, Sidoarjo, Jawa Timur
        </p>

    </section>


    {{-- =====================================================
        IMAGE + PROGRESS
    ===================================================== --}}
    <section
        class="grid grid-cols-1
               lg:grid-cols-[1.45fr_1fr]
               gap-10
               mt-14"
    >

        {{-- IMAGE --}}
        <div class="rounded-[26px] overflow-hidden shadow-sm">

            <img
                src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1800&auto=format&fit=crop"
                alt="Kampanye"
                class="w-full
                       h-[520px]
                       lg:h-[560px]
                       object-cover"
            >

        </div>


        {{-- PROGRESS CARD --}}
        <div
            class="bg-white
                   rounded-[26px]
                   border border-gray-100
                   shadow-md
                   px-10 py-10"
        >

            <h2
                class="text-[34px]
                       lg:text-[36px]
                       font-semibold
                       text-[#086641]"
            >
                Campaign Progress
            </h2>


            <div class="mt-11">

                <div class="flex items-end justify-between gap-6">

                    <span class="text-[23px] font-medium text-gray-900">
                        Dana Diperoleh
                    </span>

                    <span class="text-[38px] font-semibold text-[#08703F]">
                        Rp 500.000
                    </span>

                </div>


                <div
                    class="mt-5
                           h-[24px]
                           bg-[#DFE5F5]
                           rounded-full
                           overflow-hidden"
                >

                    <div
                        class="h-full
                               w-1/2
                               bg-[#087C3D]
                               rounded-full"
                    ></div>

                </div>


                <p class="text-right mt-7 text-[24px] text-gray-900">

                    Target :

                    <span class="text-[#08703F] font-semibold">
                        Rp 1.000.000
                    </span>

                </p>

            </div>


            <div class="h-px bg-gray-300 mt-7"></div>


            <div
                class="grid grid-cols-1
                       sm:grid-cols-2
                       gap-10
                       mt-9"
            >

                {{-- SISA WAKTU --}}
                <div class="flex items-start gap-5">

                    <div
                        class="w-[64px]
                               h-[64px]
                               shrink-0
                               rounded-full
                               border-[3px]
                               border-[#08703F]
                               text-[#08703F]
                               flex
                               items-center
                               justify-center"
                    >
                        <i class="fa-regular fa-clock text-[28px]"></i>
                    </div>


                    <div>

                        <p class="text-[22px] font-medium text-gray-900">
                            Sisa Waktu
                        </p>

                        <p class="mt-3 text-[30px] font-semibold text-[#08703F]">
                            21 Hari
                        </p>

                    </div>

                </div>


                {{-- TENGGAT --}}
                <div class="flex items-start gap-5">

                    <div
                        class="w-[64px]
                               h-[64px]
                               shrink-0
                               text-[#08703F]
                               flex
                               items-center
                               justify-center"
                    >
                        <i class="fa-regular fa-calendar text-[46px]"></i>
                    </div>


                    <div>

                        <p class="text-[22px] font-medium text-gray-900">
                            Tenggat
                        </p>

                        <p
                            class="mt-3
                                   text-[30px]
                                   font-semibold
                                   text-[#08703F]
                                   whitespace-nowrap"
                        >
                            2026 - 02 - 10
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
    TABS
===================================================== --}}
<section class="mt-16">

    <div class="border-b border-gray-400">

        <div class="grid grid-cols-1 sm:grid-cols-3">

            {{-- OVERVIEW --}}
            <button
                type="button"
                data-tab="overview"
                class="campaign-tab active-tab
                       py-6 px-8
                       text-center
                       text-[23px]
                       font-semibold
                       rounded-t-[20px]
                       transition-all
                       duration-200"
            >
                Overview
            </button>


            {{-- PENCAIRAN DANA --}}
            <button
                type="button"
                data-tab="pencairan"
                class="campaign-tab
                       py-6 px-8
                       text-center
                       text-[23px]
                       font-semibold
                       rounded-t-[20px]
                       transition-all
                       duration-200"
            >
                Pencairan Dana
            </button>


            {{-- BUKTI PENYALURAN --}}
            <button
                type="button"
                data-tab="bukti"
                class="campaign-tab
                       py-6 px-8
                       text-center
                       text-[23px]
                       font-semibold
                       rounded-t-[20px]
                       transition-all
                       duration-200"
            >
                Bukti Penyaluran
            </button>

        </div>

    </div>

</section>


    {{-- =====================================================
        TAB CONTENT
    ===================================================== --}}
    <section class="mt-10">

        {{-- =================================================
            OVERVIEW
        ================================================= --}}
        <div
            id="tab-overview"
            class="campaign-tab-content"
        >

            <section
                class="grid grid-cols-1
                       lg:grid-cols-[1.9fr_0.85fr]
                       gap-10"
            >

                {{-- LEFT --}}
                <div>

                    <h2 class="text-[30px] font-semibold text-gray-950">
                        Deskripsi
                    </h2>


                    <textarea
                        class="mt-5
                               w-full
                               h-[410px]
                               bg-white
                               border border-gray-100
                               rounded-[22px]
                               p-8
                               text-[22px]
                               text-gray-800
                               outline-none
                               resize-none
                               shadow-sm
                               focus:border-[#08703F]
                               focus:ring-2
                               focus:ring-[#08703F]/10"
                    ></textarea>


                    <button
                        type="button"
                        class="mt-10
                               min-w-[240px]
                               bg-[#08703F]
                               hover:bg-[#065D35]
                               text-white
                               px-12 py-5
                               rounded-[18px]
                               text-[25px]
                               font-semibold
                               transition"
                    >
                        Simpan
                    </button>

                </div>


                {{-- RIGHT --}}
                <aside class="space-y-8">

                    {{-- DETAIL CARD --}}
                    <div
                        class="bg-white
                               rounded-[22px]
                               overflow-hidden
                               shadow-md
                               border border-gray-100"
                    >

                        <div class="bg-[#08703F] text-white px-8 py-7">

                            <h3 class="text-[30px] font-semibold">
                                Detail Kampanye
                            </h3>

                        </div>


                        <div class="px-7 py-6 text-[21px]">

                            <div class="flex justify-between gap-6 py-4">

                                <span class="text-gray-500">
                                    Tanggal Dibuat
                                </span>

                                <span class="text-[#08703F] font-semibold">
                                    2026 / 01 / 10
                                </span>

                            </div>


                            <div class="flex justify-between gap-6 py-4">

                                <span class="text-gray-500">
                                    Jumlah Donatur
                                </span>

                                <span class="text-[#08703F] font-semibold">
                                    4
                                </span>

                            </div>


                            <div class="flex justify-between gap-6 py-4">

                                <span class="text-gray-500">
                                    Dana Diperoleh
                                </span>

                                <span class="text-[#08703F] font-semibold">
                                    Rp 500.000
                                </span>

                            </div>


                            <div class="flex justify-between gap-6 py-4">

                                <span class="text-gray-500">
                                    Target Donasi
                                </span>

                                <span class="text-[#08703F] font-semibold">
                                    Rp 1.000.000
                                </span>

                            </div>


                            <div class="h-px bg-gray-300 my-2"></div>


                            <div class="flex justify-between items-center gap-6 py-4">

                                <span class="text-gray-500">
                                    Status
                                </span>

                                <span
                                    class="min-w-[150px]
                                           text-center
                                           bg-[#D7EFE5]
                                           text-[#10765B]
                                           px-7 py-3
                                           rounded-xl
                                           text-[20px]
                                           font-semibold"
                                >
                                    Aktif
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- SHARE --}}
                    <div
                        class="bg-[#087C3D]
                               text-white
                               rounded-[22px]
                               shadow-md
                               px-9 py-10
                               text-center"
                    >

                        <div
                            class="w-[76px]
                                   h-[76px]
                                   mx-auto
                                   rounded-full
                                   bg-white/15
                                   flex
                                   items-center
                                   justify-center"
                        >
                            <i class="fa-solid fa-hand-holding-heart text-[37px]"></i>
                        </div>


                        <h3 class="mt-6 text-[28px] font-semibold">
                            Ajak Berdonasi
                        </h3>


                        <p
                            class="mt-5
                                   text-[21px]
                                   text-white/85
                                   leading-relaxed"
                        >
                            Bantu kami menjangkau lebih banyak orang untuk ikut
                            berkontribusi dalam kampanye ini.
                        </p>


                        <button
                            type="button"
                            class="mt-8
                                   w-full
                                   bg-white
                                   text-[#08703F]
                                   py-5
                                   rounded-[14px]
                                   text-[21px]
                                   font-semibold
                                   hover:bg-gray-100
                                   transition"
                        >
                            Bagikan Kampanye
                        </button>

                    </div>

                </aside>

            </section>

        </div>


        {{-- =================================================
            PENCAIRAN DANA
        ================================================= --}}
        <div
            id="tab-pencairan"
            class="campaign-tab-content hidden"
        >

            {{-- =================================================
                SUMMARY SALDO
            ================================================= --}}
            <section
                class="bg-white
                       rounded-[26px]
                       shadow-md
                       border border-gray-100
                       overflow-hidden"
            >

                <div class="grid grid-cols-1 md:grid-cols-3">

                    {{-- TOTAL SALDO --}}
                    <div class="border-b md:border-b-0 md:border-r border-gray-300">

                        <div
                            class="bg-[#08703F]
                                   text-white
                                   text-center
                                   px-6 py-5
                                   text-[24px]
                                   font-semibold"
                        >
                            Total Saldo
                        </div>


                        <div
                            class="min-h-[150px]
                                   px-8 py-7
                                   flex
                                   items-center
                                   justify-center
                                   gap-6"
                        >

                            <div
                                class="w-[66px]
                                       h-[66px]
                                       text-[#08703F]
                                       flex
                                       items-center
                                       justify-center"
                            >
                                <i class="fa-regular fa-money-bill-1 text-[45px]"></i>
                            </div>


                            <div>

                                <p class="text-[30px] font-bold text-[#08703F]">
                                    Rp.1.300.000
                                </p>

                                <p class="mt-1 text-[19px] text-gray-600">
                                    Total Uang Terkumpul
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- SALDO DICAIRKAN --}}
                    <div class="border-b md:border-b-0 md:border-r border-gray-300">

                        <div
                            class="bg-[#08703F]
                                   text-white
                                   text-center
                                   px-6 py-5
                                   text-[24px]
                                   font-semibold"
                        >
                            Saldo Dicairkan
                        </div>


                        <div
                            class="min-h-[150px]
                                   px-8 py-7
                                   flex
                                   items-center
                                   justify-center
                                   gap-6"
                        >

                            <div
                                class="w-[66px]
                                       h-[66px]
                                       text-[#08703F]
                                       flex
                                       items-center
                                       justify-center"
                            >
                                <i class="fa-solid fa-wallet text-[42px]"></i>
                            </div>


                            <div>

                                <p class="text-[30px] font-bold text-[#08703F]">
                                    Rp.200.000
                                </p>

                                <p class="mt-1 text-[19px] text-gray-600">
                                    Jumlah Uang Pencairan
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- SALDO TERSISA --}}
                    <div>

                        <div
                            class="bg-[#08703F]
                                   text-white
                                   text-center
                                   px-6 py-5
                                   text-[24px]
                                   font-semibold"
                        >
                            Saldo Tersisa
                        </div>


                        <div
                            class="min-h-[150px]
                                   px-8 py-7
                                   flex
                                   items-center
                                   justify-center
                                   gap-6"
                        >

                            <div
                                class="w-[66px]
                                       h-[66px]
                                       text-[#08703F]
                                       flex
                                       items-center
                                       justify-center"
                            >
                                <i class="fa-solid fa-wallet text-[42px]"></i>
                            </div>


                            <div>

                                <p class="text-[30px] font-bold text-[#08703F]">
                                    Rp.300.000
                                </p>

                                <p class="mt-1 text-[19px] text-gray-600">
                                    Jumlah Uang Tersisa
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </section>


            {{-- =================================================
    STATUS PENGAJUAN
================================================= --}}
<section class="mt-14">

    <div
        class="flex flex-col
               md:flex-row
               md:items-center
               justify-between
               gap-6"
    >

        <h2
            class="text-[38px]
                   lg:text-[42px]
                   font-bold
                   text-gray-950"
        >
            Status Pengajuan
        </h2>


        <a
            href="{{ route('organisasi.kampanye.pencairan.ajukan') }}"
            class="min-w-[280px]
                   h-[68px]
                   inline-flex
                   items-center
                   justify-center
                   bg-[#08703F]
                   text-white
                   rounded-[16px]
                   text-[22px]
                   font-semibold
                   hover:bg-[#065D35]
                   transition"
        >
            Ajukan Pencairan
        </a>

    </div>


    <div
        class="grid grid-cols-1
               lg:grid-cols-2
               gap-x-12
               gap-y-5
               mt-9"
    >

        {{-- =================================================
            LEFT
        ================================================= --}}
        <div class="space-y-5">

            {{-- NOMINAL --}}
            <div
                class="min-h-[88px]
                       border-2
                       border-[#08703F]
                       rounded-[16px]
                       px-7 py-4
                       flex
                       items-center
                       gap-5"
            >

                <div
                    class="w-[54px]
                           h-[54px]
                           shrink-0
                           rounded-[11px]
                           bg-black
                           text-white
                           flex
                           items-center
                           justify-center"
                >
                    <i class="fa-solid fa-dollar-sign text-[25px]"></i>
                </div>


                <div>

                    <p class="text-[20px] font-semibold text-gray-950">
                        Nominal
                    </p>

                    <p class="text-[27px] font-semibold text-[#08703F]">
                        Rp 2.500.000,-
                    </p>

                </div>

            </div>


            {{-- TANGGAL --}}
            <div
                class="min-h-[88px]
                       border-2
                       border-[#08703F]
                       rounded-[16px]
                       px-7 py-4
                       flex
                       items-center
                       gap-5"
            >

                <div
                    class="w-[54px]
                           h-[54px]
                           shrink-0
                           rounded-[11px]
                           bg-black
                           text-white
                           flex
                           items-center
                           justify-center"
                >
                    <i class="fa-solid fa-calendar-days text-[25px]"></i>
                </div>


                <div>

                    <p class="text-[20px] font-semibold text-gray-950">
                        Tanggal Pengajuan
                    </p>

                    <p class="text-[27px] font-semibold text-[#08703F]">
                        20 Juli 2026
                    </p>

                </div>

            </div>


            {{-- BANK --}}
            <div
                class="min-h-[88px]
                       border-2
                       border-[#08703F]
                       rounded-[16px]
                       px-7 py-4
                       flex
                       items-center
                       gap-5"
            >

                <div
                    class="w-[54px]
                           h-[54px]
                           shrink-0
                           rounded-[11px]
                           bg-black
                           text-white
                           flex
                           items-center
                           justify-center"
                >
                    <i class="fa-solid fa-building-columns text-[25px]"></i>
                </div>


                <div>

                    <p class="text-[20px] font-semibold text-gray-950">
                        Bank Tujuan
                    </p>

                    <p class="text-[27px] font-semibold text-[#08703F]">
                        BCA
                    </p>

                </div>

            </div>

        </div>



        {{-- =================================================
            RIGHT
        ================================================= --}}
        <div class="space-y-5">

            {{-- STATUS --}}
            <div
                class="min-h-[88px]
                       border-2
                       border-[#08703F]
                       rounded-[16px]
                       px-7 py-4
                       flex
                       items-center
                       gap-5"
            >

                <div
                    class="w-[54px]
                           h-[54px]
                           shrink-0
                           rounded-[11px]
                           bg-black
                           text-white
                           flex
                           items-center
                           justify-center"
                >
                    <i class="fa-solid fa-check text-[25px]"></i>
                </div>


                <div>

                    <p class="text-[20px] font-semibold text-gray-950">
                        Status
                    </p>

                    <p class="text-[25px] font-semibold text-[#08703F]">
                        Menunggu Verif Admin
                    </p>

                </div>

            </div>


            {{-- REKENING --}}
            <div
                class="min-h-[88px]
                       border-2
                       border-[#08703F]
                       rounded-[16px]
                       px-7 py-4
                       flex
                       items-center
                       gap-5"
            >

                <div
                    class="w-[54px]
                           h-[54px]
                           shrink-0
                           rounded-[11px]
                           bg-black
                           text-white
                           flex
                           items-center
                           justify-center"
                >
                    <i class="fa-regular fa-credit-card text-[25px]"></i>
                </div>


                <div>

                    <p class="text-[20px] font-semibold text-gray-950">
                        Rekening Tujuan
                    </p>

                    <p class="text-[27px] font-semibold text-[#08703F]">
                        123456789
                    </p>

                </div>

            </div>


            {{-- ALASAN --}}
            <div
                class="min-h-[250px]
                       border-2
                       border-[#08703F]
                       rounded-[16px]
                       px-7 py-5
                       flex
                       items-start
                       gap-5"
            >

                <div
                    class="w-[54px]
                           h-[54px]
                           shrink-0
                           rounded-[11px]
                           bg-black
                           text-white
                           flex
                           items-center
                           justify-center"
                >
                    <i class="fa-solid fa-pen-to-square text-[25px]"></i>
                </div>


                <div>

                    <p class="text-[20px] font-semibold text-gray-950">
                        Alasan Pengajuan
                    </p>

                    <p
                        class="mt-1
                               text-[27px]
                               font-semibold
                               text-[#08703F]"
                    >
                        ...
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


            {{-- =================================================
                RIWAYAT PENCAIRAN
            ================================================= --}}
            <section class="mt-16">

                <h2
                    class="text-[38px]
                           lg:text-[42px]
                           font-bold
                           text-gray-950"
                >
                    Riwayat Pencairan
                </h2>


                <div
                    class="mt-8
                           bg-white
                           rounded-[22px]
                           shadow-md
                           overflow-hidden
                           overflow-x-auto"
                >

                    <table class="w-full min-w-[1100px] table-fixed">

                        <thead class="bg-[#08703F] text-white">

                            <tr class="text-[24px]">

                                <th
                                    class="w-[34%]
                                           px-8 py-6
                                           text-center
                                           font-semibold"
                                >
                                    Nama Alokasi
                                </th>

                                <th
                                    class="w-[22%]
                                           px-8 py-6
                                           text-center
                                           font-semibold"
                                >
                                    Tanggal
                                </th>

                                <th
                                    class="w-[22%]
                                           px-8 py-6
                                           text-center
                                           font-semibold"
                                >
                                    Nominal
                                </th>

                                <th
                                    class="w-[22%]
                                           px-8 py-6
                                           text-center
                                           font-semibold"
                                >
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody class="text-[23px] text-gray-900">

                            {{-- ROW 1 --}}
                            <tr class="hover:bg-[#F0F8F4] transition">

                                <td class="px-8 py-6 text-center font-medium">
                                    Pembelian Beras
                                </td>

                                <td class="px-8 py-6 text-center">
                                    2026 / 01 / 13
                                </td>

                                <td class="px-8 py-6 text-center">
                                    Rp 200.000
                                </td>

                                <td class="px-8 py-6 text-center">

                                    <span
                                        class="inline-flex
                                               min-w-[135px]
                                               justify-center
                                               bg-[#D7EFE5]
                                               text-[#10765B]
                                               px-6 py-3
                                               rounded-full
                                               text-[20px]
                                               font-semibold"
                                    >
                                        Berhasil
                                    </span>

                                </td>

                            </tr>


                            {{-- ROW 2 --}}
                            <tr class="hover:bg-[#F0F8F4] transition">

                                <td class="px-8 py-6 text-center font-medium">
                                    Kebutuhan Konsumsi
                                </td>

                                <td class="px-8 py-6 text-center">
                                    2026 / 02 / 20
                                </td>

                                <td class="px-8 py-6 text-center">
                                    Rp 150.000
                                </td>

                                <td class="px-8 py-6 text-center">

                                    <span
                                        class="inline-flex
                                               min-w-[135px]
                                               justify-center
                                               bg-[#F8DEDE]
                                               text-[#B43B3B]
                                               px-6 py-3
                                               rounded-full
                                               text-[20px]
                                               font-semibold"
                                    >
                                        Gagal
                                    </span>

                                </td>

                            </tr>


                            {{-- ROW 3 --}}
                            <tr class="hover:bg-[#F0F8F4] transition">

                                <td class="px-8 py-6 text-center font-medium">
                                    Perlengkapan Kelas
                                </td>

                                <td class="px-8 py-6 text-center">
                                    2026 / 03 / 12
                                </td>

                                <td class="px-8 py-6 text-center">
                                    Rp 300.000
                                </td>

                                <td class="px-8 py-6 text-center">

                                    <span
                                        class="inline-flex
                                               min-w-[135px]
                                               justify-center
                                               bg-[#D7EFE5]
                                               text-[#10765B]
                                               px-6 py-3
                                               rounded-full
                                               text-[20px]
                                               font-semibold"
                                    >
                                        Berhasil
                                    </span>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </section>

        </div>


        {{-- =================================================
            BUKTI PENYALURAN
        ================================================= --}}
        <div
            id="tab-bukti"
            class="campaign-tab-content hidden"
        >

            <section
                class="bg-white
                       rounded-[28px]
                       shadow-md
                       border border-gray-100
                       px-10
                       py-11"
            >

                {{-- HEADER --}}
                <div
                    class="flex
                           flex-col
                           lg:flex-row
                           lg:items-center
                           justify-between
                           gap-7"
                >

                    <div>

                        <h2
                            class="text-[44px]
                                   lg:text-[48px]
                                   font-bold
                                   text-[#08703F]"
                        >
                            Bukti Penyaluran
                        </h2>


                        <p
                            class="mt-3
                                   text-[23px]
                                   text-gray-700"
                        >
                            Upload bukti penggunaan dana agar dapat diverifikasi oleh admin.
                        </p>

                    </div>


                    <a
                        href="{{ route('organisasi.kampanye.bukti.upload') }}"
                        class="min-w-[245px]
                               h-[72px]
                               border-2
                               border-[#08703F]
                               rounded-[18px]
                               text-[#08703F]
                               inline-flex
                               items-center
                               justify-center
                               gap-4
                               text-[23px]
                               font-semibold
                               hover:bg-[#F0F8F4]
                               transition"
                    >
                        <i class="fa-solid fa-cloud-arrow-up text-[28px]"></i>
                        Upload Bukti
                    </a>

                </div>


                {{-- CARDS --}}
                <div
                    class="grid grid-cols-1
                           md:grid-cols-2
                           xl:grid-cols-3
                           gap-9
                           mt-12"
                >

                    {{-- CARD 1 --}}
                    <article
                        class="border-2
                               border-[#08703F]
                               rounded-[24px]
                               bg-white
                               p-5"
                    >

                        <img
                            src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1000&auto=format&fit=crop"
                            alt="Bukti Penyaluran"
                            class="w-full
                                   h-[270px]
                                   object-cover
                                   rounded-[18px]"
                        >

                        <div class="mt-6 text-[22px] text-gray-950 space-y-3">

                            <div class="grid grid-cols-[120px_20px_1fr]">
                                <span class="font-semibold">Judul</span>
                                <span>:</span>
                                <span class="font-medium">
                                    Pembagian Beras
                                </span>
                            </div>

                            <div class="grid grid-cols-[120px_20px_1fr]">
                                <span class="font-semibold">Tanggal</span>
                                <span>:</span>
                                <span class="font-medium">
                                    20 Juli 2026
                                </span>
                            </div>

                            <div class="grid grid-cols-[120px_20px_1fr] pt-5">
                                <span class="font-semibold">Status</span>
                                <span>:</span>
                                <span class="font-semibold text-[#16A34A]">
                                    Disetujui
                                </span>
                            </div>

                        </div>


                        <a
                            href="{{ route('organisasi.kampanye.bukti.detail') }}"
                            class="mt-7
                                   w-full
                                   h-[66px]
                                   border-2
                                   border-[#08703F]
                                   text-[#08703F]
                                   rounded-[16px]
                                   inline-flex
                                   items-center
                                   justify-center
                                   gap-4
                                   text-[22px]
                                   font-semibold
                                   hover:bg-[#08703F]
                                   hover:text-white
                                   transition"
                        >
                            <i class="fa-regular fa-eye text-[25px]"></i>
                            Lihat Detail
                        </a>

                    </article>


                    {{-- CARD 2 --}}
                    <article
                        class="border-2
                               border-[#08703F]
                               rounded-[24px]
                               bg-white
                               p-5"
                    >

                        <img
                            src="https://images.unsplash.com/photo-1594708767771-a7502209ff51?q=80&w=1000&auto=format&fit=crop"
                            alt="Bukti Penyaluran"
                            class="w-full
                                   h-[270px]
                                   object-cover
                                   rounded-[18px]"
                        >

                        <div class="mt-6 text-[22px] text-gray-950 space-y-3">

                            <div class="grid grid-cols-[120px_20px_1fr]">
                                <span class="font-semibold">Judul</span>
                                <span>:</span>
                                <span class="font-medium">
                                    Pembagian Beras
                                </span>
                            </div>

                            <div class="grid grid-cols-[120px_20px_1fr]">
                                <span class="font-semibold">Tanggal</span>
                                <span>:</span>
                                <span class="font-medium">
                                    20 Juli 2026
                                </span>
                            </div>

                            <div class="grid grid-cols-[120px_20px_1fr] pt-5">
                                <span class="font-semibold">Status</span>
                                <span>:</span>
                                <span class="font-semibold text-[#16A34A]">
                                    Disetujui
                                </span>
                            </div>

                        </div>


                        <a
                            href="{{ route('organisasi.kampanye.bukti.detail') }}"
                            class="mt-7
                                   w-full
                                   h-[66px]
                                   border-2
                                   border-[#08703F]
                                   text-[#08703F]
                                   rounded-[16px]
                                   inline-flex
                                   items-center
                                   justify-center
                                   gap-4
                                   text-[22px]
                                   font-semibold
                                   hover:bg-[#08703F]
                                   hover:text-white
                                   transition"
                        >
                            <i class="fa-regular fa-eye text-[25px]"></i>
                            Lihat Detail
                        </a>

                    </article>


                    {{-- CARD 3 --}}
                    <article
                        class="border-2
                               border-[#08703F]
                               rounded-[24px]
                               bg-white
                               p-5"
                    >

                        <img
                            src="https://images.unsplash.com/photo-1509099836639-18ba1795216d?q=80&w=1000&auto=format&fit=crop"
                            alt="Bukti Penyaluran"
                            class="w-full
                                   h-[270px]
                                   object-cover
                                   rounded-[18px]"
                        >

                        <div class="mt-6 text-[22px] text-gray-950 space-y-3">

                            <div class="grid grid-cols-[120px_20px_1fr]">
                                <span class="font-semibold">Judul</span>
                                <span>:</span>
                                <span class="font-medium">
                                    Pembagian Beras
                                </span>
                            </div>

                            <div class="grid grid-cols-[120px_20px_1fr]">
                                <span class="font-semibold">Tanggal</span>
                                <span>:</span>
                                <span class="font-medium">
                                    20 Juli 2026
                                </span>
                            </div>

                            <div class="grid grid-cols-[120px_20px_1fr] pt-5">
                                <span class="font-semibold">Status</span>
                                <span>:</span>
                                <span class="font-semibold text-[#16A34A]">
                                    Disetujui
                                </span>
                            </div>

                        </div>


                        <a
                            href="{{ route('organisasi.kampanye.bukti.detail') }}"
                            class="mt-7
                                   w-full
                                   h-[66px]
                                   border-2
                                   border-[#08703F]
                                   text-[#08703F]
                                   rounded-[16px]
                                   inline-flex
                                   items-center
                                   justify-center
                                   gap-4
                                   text-[22px]
                                   font-semibold
                                   hover:bg-[#08703F]
                                   hover:text-white
                                   transition"
                        >
                            <i class="fa-regular fa-eye text-[25px]"></i>
                            Lihat Detail
                        </a>

                    </article>

                </div>

            </section>

        </div>

    </section>

</main>


{{-- =========================================================
    TAB SCRIPT
========================================================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const tabs = document.querySelectorAll('.campaign-tab');
        const contents = document.querySelectorAll('.campaign-tab-content');

        function activateTab(tabName) {

            contents.forEach(function (content) {
                content.classList.add('hidden');
            });

            tabs.forEach(function (tab) {

                tab.classList.remove(
                    'bg-[#08703F]',
                    'text-white'
                );

                tab.classList.add('text-gray-900');

            });


            const selectedContent = document.getElementById(
                'tab-' + tabName
            );

            if (selectedContent) {
                selectedContent.classList.remove('hidden');
            }


            const selectedTab = document.querySelector(
                '.campaign-tab[data-tab="' + tabName + '"]'
            );

            if (selectedTab) {

                selectedTab.classList.remove('text-gray-900');

                selectedTab.classList.add(
                    'bg-[#08703F]',
                    'text-white'
                );

            }

        }


        tabs.forEach(function (tab) {

            tab.addEventListener('click', function () {

                const tabName = this.getAttribute('data-tab');

                activateTab(tabName);

            });

        });

    });
</script>

@endsection