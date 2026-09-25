@extends('layouts.organization', [
    'title' => 'Dashboard Organisasi',
    'activeNav' => 'dashboard'
])

@section('content')

{{-- =========================================================
    HERO + STATISTICS
========================================================= --}}
<section class="relative bg-[#16735F] overflow-hidden">

    {{-- HERO --}}
    <div class="relative h-[610px]">

        <img
            src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2400&auto=format&fit=crop"
            alt="Anak-anak"
            class="absolute inset-0 w-full h-full object-cover"
        >

        <div class="absolute inset-0 bg-black/40"></div>

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
                    Selamat Datang, Yayasan Makmur Jaya
                </h1>

                <p
                    class="mt-7
                           text-white/95
                           text-[28px]
                           lg:text-[31px]
                           font-medium"
                >
                    Kelola kampanye dan pantau donasi anda disini
                </p>

            </div>

        </div>

    </div>


    {{-- STATISTIC CARDS --}}
    <div class="w-full px-8 sm:px-10 lg:px-16 xl:px-20 2xl:px-24 py-14">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            {{-- CARD 1 --}}
            <div
                class="bg-white
                       rounded-[24px]
                       h-[220px]
                       px-10 py-9
                       shadow-md
                       flex flex-col justify-between"
            >

                <div
                    class="w-[62px] h-[62px]
                           rounded-[16px]
                           bg-[#DDF0E9]
                           text-[#0E6B53]
                           flex items-center justify-center"
                >
                    <i class="fa-solid fa-bullhorn text-[27px]"></i>
                </div>

                <div>

                    <p class="text-[24px] text-gray-700 font-medium">
                        Kampanye Aktif
                    </p>

                    <p class="mt-3 text-[47px] font-bold leading-none">
                        12
                    </p>

                </div>

            </div>


            {{-- CARD 2 --}}
            <div
                class="bg-white
                       rounded-[24px]
                       h-[220px]
                       px-10 py-9
                       shadow-md
                       flex flex-col justify-between"
            >

                <div
                    class="w-[62px] h-[62px]
                           rounded-[16px]
                           bg-[#DDF0E9]
                           text-[#0E6B53]
                           flex items-center justify-center"
                >
                    <i class="fa-solid fa-hand-holding-dollar text-[27px]"></i>
                </div>

                <div>

                    <p class="text-[24px] text-gray-700 font-medium">
                        Total Donasi Masuk
                    </p>

                    <p class="mt-3 text-[47px] font-bold leading-none whitespace-nowrap">
                        Rp 1.200.000
                    </p>

                </div>

            </div>


            {{-- CARD 3 --}}
            <div
                class="bg-white
                       rounded-[24px]
                       h-[220px]
                       px-10 py-9
                       shadow-md
                       flex flex-col justify-between"
            >

                <div
                    class="w-[62px] h-[62px]
                           rounded-[16px]
                           bg-[#DDF0E9]
                           text-[#0E6B53]
                           flex items-center justify-center"
                >
                    <i class="fa-regular fa-calendar-check text-[27px]"></i>
                </div>

                <div>

                    <p class="text-[24px] text-gray-700 font-medium">
                        Kunjungan Menunggu
                    </p>

                    <p class="mt-3 text-[47px] font-bold leading-none">
                        3
                    </p>

                </div>

            </div>


            {{-- CARD 4 --}}
            <div
                class="bg-white
                       rounded-[24px]
                       h-[220px]
                       px-10 py-9
                       shadow-md
                       flex flex-col justify-between"
            >

                <div
                    class="w-[62px] h-[62px]
                           rounded-[16px]
                           bg-[#DDF0E9]
                           text-[#0E6B53]
                           flex items-center justify-center"
                >
                    <i class="fa-solid fa-wallet text-[27px]"></i>
                </div>

                <div>

                    <p class="text-[24px] text-gray-700 font-medium">
                        Saldo Tersedia
                    </p>

                    <p class="mt-3 text-[47px] font-bold leading-none whitespace-nowrap">
                        Rp 200.000
                    </p>

                </div>

            </div>

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
           space-y-20"
>

    {{-- =====================================================
        KAMPANYE
    ===================================================== --}}
    <section>

        <div class="flex items-center justify-between mb-10">

            <h2 class="text-[52px] lg:text-[56px] font-bold text-[#16735F]">
                Kampanye
            </h2>

            <a
                href="{{ route('organisasi.kampanye') }}"
                class="bg-[#08703F]
                       hover:bg-[#065D35]
                       text-white
                       px-10 py-5
                       rounded-[16px]
                       text-[24px]
                       font-semibold
                       transition"
            >
                Lihat Semua
            </a>

        </div>


        <div class="space-y-8">

            {{-- =================================================
                CAMPAIGN 1
            ================================================= --}}
            <article
                class="bg-white
                       rounded-[24px]
                       shadow-md
                       border border-gray-100
                       overflow-hidden"
            >

                <div class="flex flex-col md:flex-row md:h-[325px]">

                    {{-- IMAGE --}}
                    <div class="md:w-[35%] shrink-0">

                        <img
                            src="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1600"
                            alt="Anggaran Pakaian Seragam Sekolah"
                            class="w-full h-[340px] md:h-full object-cover"
                        >

                    </div>


                    {{-- CONTENT --}}
                    <div
                        class="flex-1
                               px-9 py-9
                               flex flex-col justify-between"
                    >

                        <div>

                            {{-- TITLE + STATUS --}}
                            <div class="flex items-start justify-between gap-8">

                                <div>

                                    <h3
                                        class="text-[33px]
                                               lg:text-[35px]
                                               font-semibold
                                               text-gray-950
                                               leading-tight"
                                    >
                                        Anggaran Pakaian Seragam Sekolah
                                    </h3>

                                    <p class="mt-3 text-[24px] text-gray-500">
                                        SMA Bumi Rejo, Sidoarjo, Jawa Timur
                                    </p>

                                </div>


                                <span
                                    class="shrink-0
                                           min-w-[155px]
                                           text-center
                                           bg-[#D7EFE5]
                                           text-[#10765B]
                                           text-[21px]
                                           font-semibold
                                           px-7 py-3
                                           rounded-full"
                                >
                                    Aktif
                                </span>

                            </div>


                            {{-- PROGRESS --}}
                            <div
                                class="mt-7
                                       h-[17px]
                                       bg-[#DFE5F2]
                                       rounded-full
                                       overflow-hidden"
                            >

                                <div
                                    class="h-full
                                           bg-[#087C3D]
                                           rounded-full"
                                    style="width: 50%"
                                ></div>

                            </div>


                            {{-- INFORMATION --}}
                            <div
                                class="mt-7
                                       flex flex-wrap
                                       items-center
                                       gap-x-8
                                       gap-y-4
                                       text-[24px]"
                            >

                                <p>
                                    Terkumpul :
                                    <span class="text-[#087C3D] font-semibold">
                                        Rp 500.000
                                    </span>
                                </p>

                                <span class="hidden md:block h-7 w-px bg-gray-300"></span>

                                <p>
                                    Target :
                                    <span class="text-[#087C3D] font-semibold">
                                        Rp 1.000.000
                                    </span>
                                </p>

                                <span class="hidden md:block h-7 w-px bg-gray-300"></span>

                                <p>
                                    Tenggat :
                                    <span class="text-[#087C3D] font-semibold">
                                        26 / 01 / 2026
                                    </span>
                                </p>

                            </div>

                        </div>


                        {{-- DETAIL --}}
                        <div class="flex justify-end">

                            <a
                                href="{{ route('organisasi.kampanye.detail') }}"
                                class="min-w-[175px]
                                       text-center
                                       border-2 border-[#087C3D]
                                       text-[#087C3D]
                                       hover:bg-[#087C3D]
                                       hover:text-white
                                       px-8 py-3
                                       rounded-[14px]
                                       text-[23px]
                                       font-semibold
                                       transition"
                            >
                                Detail
                            </a>

                        </div>

                    </div>

                </div>

            </article>



            {{-- =================================================
                CAMPAIGN 2
            ================================================= --}}
            <article
                class="bg-white
                       rounded-[24px]
                       shadow-md
                       border border-gray-100
                       overflow-hidden"
            >

                <div class="flex flex-col md:flex-row md:h-[325px]">

                    {{-- IMAGE --}}
                    <div class="md:w-[35%] shrink-0">

                        <img
                            src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1600"
                            alt="Anggaran Kebutuhan Fasilitas Kelas"
                            class="w-full h-[340px] md:h-full object-cover"
                        >

                    </div>


                    {{-- CONTENT --}}
                    <div
                        class="flex-1
                               px-9 py-9
                               flex flex-col justify-between"
                    >

                        <div>

                            {{-- TITLE + STATUS --}}
                            <div class="flex items-start justify-between gap-8">

                                <div>

                                    <h3
                                        class="text-[33px]
                                               lg:text-[35px]
                                               font-semibold
                                               text-gray-950
                                               leading-tight"
                                    >
                                        Anggaran Kebutuhan Fasilitas Kelas
                                    </h3>

                                    <p class="mt-3 text-[24px] text-gray-500">
                                        SMA Bumi Rejo, Sidoarjo, Jawa Timur
                                    </p>

                                </div>


                                <span
                                    class="shrink-0
                                           min-w-[155px]
                                           text-center
                                           bg-[#E4E9FA]
                                           text-[#5665A6]
                                           text-[21px]
                                           font-semibold
                                           px-7 py-3
                                           rounded-full"
                                >
                                    Selesai
                                </span>

                            </div>


                            {{-- PROGRESS --}}
                            <div
                                class="mt-7
                                       h-[17px]
                                       bg-[#DFE5F2]
                                       rounded-full
                                       overflow-hidden"
                            >

                                <div
                                    class="w-full
                                           h-full
                                           bg-[#087C3D]
                                           rounded-full"
                                ></div>

                            </div>


                            {{-- INFORMATION --}}
                            <div
                                class="mt-7
                                       flex flex-wrap
                                       items-center
                                       gap-x-8
                                       gap-y-4
                                       text-[24px]"
                            >

                                <p>
                                    Terkumpul :
                                    <span class="text-[#087C3D] font-semibold">
                                        Rp 800.000
                                    </span>
                                </p>

                                <span class="hidden md:block h-7 w-px bg-gray-300"></span>

                                <p>
                                    Target :
                                    <span class="text-[#087C3D] font-semibold">
                                        Rp 800.000
                                    </span>
                                </p>

                                <span class="hidden md:block h-7 w-px bg-gray-300"></span>

                                <p>
                                    Tenggat :
                                    <span class="text-[#087C3D] font-semibold">
                                        26 / 01 / 2026
                                    </span>
                                </p>

                            </div>

                        </div>


                        {{-- DETAIL --}}
                        <div class="flex justify-end">

                            <a
                                href="{{ route('organisasi.kampanye.detail') }}"
                                class="min-w-[175px]
                                       text-center
                                       border-2 border-[#087C3D]
                                       text-[#087C3D]
                                       hover:bg-[#087C3D]
                                       hover:text-white
                                       px-8 py-3
                                       rounded-[14px]
                                       text-[23px]
                                       font-semibold
                                       transition"
                            >
                                Detail
                            </a>

                        </div>

                    </div>

                </div>

            </article>

        </div>

    </section>



    {{-- =====================================================
        DONASI TERBARU
    ===================================================== --}}
    <section>

        <div class="flex items-center justify-between mb-9">

            <h2 class="text-[50px] lg:text-[54px] font-bold text-[#16735F]">
                Donasi Terbaru
            </h2>

            <button
                type="button"
                class="bg-[#08703F]
                       hover:bg-[#065D35]
                       text-white
                       px-10 py-5
                       rounded-[14px]
                       text-[23px]
                       font-semibold
                       transition"
            >
                Lihat Semua
            </button>

        </div>


        <div
            class="bg-white
                   rounded-[22px]
                   shadow-md
                   overflow-hidden
                   overflow-x-auto"
        >

            <table class="w-full min-w-[1050px] table-fixed">

                <thead class="bg-[#08703F] text-white">

                    <tr class="text-[24px]">

                        <th class="w-[20%] px-8 py-6 text-center font-semibold">
                            Donatur
                        </th>

                        <th class="w-[30%] px-8 py-6 text-center font-semibold">
                            Kampanye
                        </th>

                        <th class="w-[25%] px-8 py-6 text-center font-semibold">
                            Donasi
                        </th>

                        <th class="w-[25%] px-8 py-6 text-center font-semibold">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="text-[23px] text-gray-900">

                    <tr>
                        <td class="px-8 py-6 text-center font-medium">Budi</td>
                        <td class="px-8 py-6 text-center">Perlengkapan Kelas</td>
                        <td class="px-8 py-6 text-center">Rp 500.000,-</td>
                        <td class="px-8 py-6 text-center text-emerald-600 font-semibold">Berhasil</td>
                    </tr>

                    <tr>
                        <td class="px-8 py-6 text-center font-medium">Budi</td>
                        <td class="px-8 py-6 text-center">Makanan Ringan</td>
                        <td class="px-8 py-6 text-center">Rp 500.000,-</td>
                        <td class="px-8 py-6 text-center text-blue-600 font-semibold">Diterima</td>
                    </tr>

                    <tr>
                        <td class="px-8 py-6 text-center font-medium">Budi</td>
                        <td class="px-8 py-6 text-center">Baju Sekolah</td>
                        <td class="px-8 py-6 text-center">Rp 500.000,-</td>
                        <td class="px-8 py-6 text-center text-red-500 font-semibold">Gagal</td>
                    </tr>

                    <tr>
                        <td class="px-8 py-6 text-center font-medium">Budi</td>
                        <td class="px-8 py-6 text-center">Renovasi Kelas</td>
                        <td class="px-8 py-6 text-center">Rp 500.000,-</td>
                        <td class="px-8 py-6 text-center text-red-500 font-semibold">Gagal</td>
                    </tr>

                </tbody>

            </table>

        </div>

    </section>



    {{-- =====================================================
        KUNJUNGAN
    ===================================================== --}}
    <section>

        <div class="flex items-center justify-between mb-9">

            <h2 class="text-[50px] lg:text-[54px] font-bold text-[#16735F]">
                Kunjungan
            </h2>

            <button
                type="button"
                class="bg-[#08703F]
                       hover:bg-[#065D35]
                       text-white
                       px-10 py-5
                       rounded-[14px]
                       text-[23px]
                       font-semibold
                       transition"
            >
                Lihat Semua
            </button>

        </div>


        <div
            class="bg-white
                   rounded-[22px]
                   shadow-md
                   overflow-hidden
                   overflow-x-auto"
        >

            <table class="w-full min-w-[1050px] table-fixed">

                <thead class="bg-[#08703F] text-white">

                    <tr class="text-[24px]">

                        <th class="w-[25%] px-8 py-6 text-center font-semibold">
                            Nama
                        </th>

                        <th class="w-[30%] px-8 py-6 text-center font-semibold">
                            Tanggal
                        </th>

                        <th class="w-[20%] px-8 py-6 text-center font-semibold">
                            Orang
                        </th>

                        <th class="w-[25%] px-8 py-6 text-center font-semibold">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="text-[23px] text-gray-900">

                    <tr>
                        <td class="px-8 py-6 text-center font-medium">John</td>
                        <td class="px-8 py-6 text-center">12 / 03 / 2026</td>
                        <td class="px-8 py-6 text-center">12</td>
                        <td class="px-8 py-6 text-center text-emerald-600 font-semibold">Berhasil</td>
                    </tr>

                    <tr>
                        <td class="px-8 py-6 text-center font-medium">PT Maju</td>
                        <td class="px-8 py-6 text-center">18 / 04 / 2026</td>
                        <td class="px-8 py-6 text-center">8</td>
                        <td class="px-8 py-6 text-center text-blue-600 font-semibold">Diterima</td>
                    </tr>

                    <tr>
                        <td class="px-8 py-6 text-center font-medium">Sane</td>
                        <td class="px-8 py-6 text-center">22 / 04 / 2026</td>
                        <td class="px-8 py-6 text-center">3</td>
                        <td class="px-8 py-6 text-center text-red-500 font-semibold">Batal</td>
                    </tr>

                    <tr>
                        <td class="px-8 py-6 text-center font-medium">Dewi</td>
                        <td class="px-8 py-6 text-center">13 / 05 / 2026</td>
                        <td class="px-8 py-6 text-center">7</td>
                        <td class="px-8 py-6 text-center text-red-500 font-semibold">Batal</td>
                    </tr>

                </tbody>

            </table>

        </div>

    </section>

</main>

@endsection  