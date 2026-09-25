@extends('layouts.organization', [
    'title' => 'Donasi Organisasi',
    'activeNav' => 'donasi'
])

@section('content')

{{-- =========================================================
    HERO DONASI
========================================================= --}}
<section class="relative h-[500px] overflow-hidden">

    <img
        src="https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?q=80&w=2200&auto=format&fit=crop"
        alt="Donasi"
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
                Donasi
            </h1>

            <p
                class="mt-7
                       text-white/95
                       text-[28px]
                       lg:text-[31px]
                       font-medium"
            >
                Pantau seluruh donasi yang diterima oleh campaign panti.
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
           py-20"
>

    {{-- =====================================================
        FILTER CARD
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[26px]
               shadow-md
               border border-gray-100
               px-10 py-10"
    >

        <div class="grid grid-cols-1 lg:grid-cols-[1.5fr_0.8fr_auto] gap-8 items-end">

            {{-- SEARCH --}}
            <div>

                <label
                    for="searchDonatur"
                    class="block mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Cari Donatur
                </label>

                <div class="relative">

                    <i
                        class="fa-solid fa-magnifying-glass
                               absolute left-6 top-1/2 -translate-y-1/2
                               text-[24px]
                               text-gray-500"
                    ></i>

                    <input
                        id="searchDonatur"
                        type="text"
                        placeholder="Cari nama donatur..."
                        class="w-full
                               h-[82px]
                               pl-16 pr-6
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


            {{-- STATUS --}}
            <div>

                <label
                    for="statusDonasi"
                    class="block mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Status
                </label>

                <div class="relative">

                    <select
                        id="statusDonasi"
                        class="w-full
                               h-[82px]
                               appearance-none
                               bg-white
                               border border-gray-300
                               rounded-[16px]
                               px-6 pr-16
                               text-[23px]
                               text-[#8BB3A3]
                               outline-none
                               focus:border-[#08703F]
                               focus:ring-2
                               focus:ring-[#08703F]/10"
                    >
                        <option value="">Silakan pilih</option>
                        <option value="berhasil">Berhasil</option>
                        <option value="diterima">Diterima</option>
                        <option value="gagal">Gagal</option>
                    </select>

                    <i
                        class="fa-solid fa-chevron-down
                               absolute right-6 top-1/2 -translate-y-1/2
                               text-[20px]
                               text-gray-500
                               pointer-events-none"
                    ></i>

                </div>

            </div>


            {{-- SEARCH BUTTON --}}
            <button
                type="button"
                class="h-[82px]
                       px-12
                       bg-[#08703F]
                       hover:bg-[#065D35]
                       text-white
                       rounded-[16px]
                       text-[24px]
                       font-semibold
                       transition"
            >
                Cari
            </button>

        </div>



        {{-- =================================================
            STATISTIC CARDS
        ================================================= --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-8 mt-12">

            {{-- CARD 1 --}}
            <div
                class="bg-[#F8FAF9]
                       rounded-[22px]
                       border border-gray-100
                       px-8 py-8
                       min-h-[190px]
                       flex flex-col justify-between"
            >

                <div class="flex items-center justify-between gap-5">

                    <p class="text-[24px] font-semibold text-gray-900">
                        Donasi Uang
                    </p>

                    <div
                        class="w-[58px] h-[58px]
                               rounded-[15px]
                               bg-[#DDF0E9]
                               text-[#08703F]
                               flex items-center justify-center"
                    >
                        <i class="fa-solid fa-money-bill-wave text-[25px]"></i>
                    </div>

                </div>

                <div class="mt-8">

                    <p class="text-[36px] lg:text-[39px] font-bold text-[#08703F]">
                        Rp.1.200.000
                    </p>

                    <p class="mt-2 text-[21px] text-gray-500">
                        Total Uang Terkumpul
                    </p>

                </div>

            </div>


            {{-- CARD 2 --}}
            <div
                class="bg-[#F8FAF9]
                       rounded-[22px]
                       border border-gray-100
                       px-8 py-8
                       min-h-[190px]
                       flex flex-col justify-between"
            >

                <div class="flex items-center justify-between gap-5">

                    <p class="text-[24px] font-semibold text-gray-900">
                        Donasi Hari Ini
                    </p>

                    <div
                        class="w-[58px] h-[58px]
                               rounded-[15px]
                               bg-[#DDF0E9]
                               text-[#08703F]
                               flex items-center justify-center"
                    >
                        <i class="fa-regular fa-calendar-check text-[25px]"></i>
                    </div>

                </div>

                <div class="mt-8">

                    <p class="text-[36px] lg:text-[39px] font-bold text-[#08703F]">
                        Rp.500.000
                    </p>

                    <p class="mt-2 text-[21px] text-gray-500">
                        Diterima Hari ini
                    </p>

                </div>

            </div>


            {{-- CARD 3 --}}
            <div
                class="bg-[#F8FAF9]
                       rounded-[22px]
                       border border-gray-100
                       px-8 py-8
                       min-h-[190px]
                       flex flex-col justify-between"
            >

                <div class="flex items-center justify-between gap-5">

                    <p class="text-[24px] font-semibold text-gray-900">
                        Donasi Bulan Ini
                    </p>

                    <div
                        class="w-[58px] h-[58px]
                               rounded-[15px]
                               bg-[#DDF0E9]
                               text-[#08703F]
                               flex items-center justify-center"
                    >
                        <i class="fa-solid fa-chart-line text-[25px]"></i>
                    </div>

                </div>

                <div class="mt-8">

                    <p class="text-[36px] lg:text-[39px] font-bold text-[#08703F]">
                        Rp.700.000
                    </p>

                    <p class="mt-2 text-[21px] text-gray-500">
                        Diterima Bulan ini
                    </p>

                </div>

            </div>


            {{-- CARD 4 --}}
            <div
                class="bg-[#F8FAF9]
                       rounded-[22px]
                       border border-gray-100
                       px-8 py-8
                       min-h-[190px]
                       flex flex-col justify-between"
            >

                <div class="flex items-center justify-between gap-5">

                    <p class="text-[24px] font-semibold text-gray-900">
                        Kampanye
                    </p>

                    <div
                        class="w-[58px] h-[58px]
                               rounded-[15px]
                               bg-[#DDF0E9]
                               text-[#08703F]
                               flex items-center justify-center"
                    >
                        <i class="fa-solid fa-bullhorn text-[25px]"></i>
                    </div>

                </div>

                <div class="mt-8">

                    <p class="text-[39px] font-bold text-[#08703F]">
                        23
                    </p>

                    <p class="mt-2 text-[21px] text-gray-500">
                        Campaign Sedang Berjalan
                    </p>

                </div>

            </div>

        </div>

    </section>



    {{-- =====================================================
        RIWAYAT DONASI
    ===================================================== --}}
    <section class="mt-16">

        <h2
            class="text-[50px]
                   lg:text-[54px]
                   font-bold
                   text-[#16735F]"
        >
            Riwayat Donasi
        </h2>


        <div
            class="mt-9
                   bg-white
                   rounded-[22px]
                   shadow-md
                   border border-gray-100
                   overflow-hidden
                   overflow-x-auto"
        >

            <table class="w-full min-w-[1250px] table-fixed">

                {{-- HEADER --}}
                <thead class="bg-[#08703F] text-white">

                    <tr class="text-[24px]">

                        <th class="w-[18%] px-7 py-6 text-center font-semibold">
                            Donatur
                        </th>

                        <th class="w-[29%] px-7 py-6 text-center font-semibold">
                            Kampanye
                        </th>

                        <th class="w-[18%] px-7 py-6 text-center font-semibold">
                            Nominal
                        </th>

                        <th class="w-[20%] px-7 py-6 text-center font-semibold">
                            Tanggal
                        </th>

                        <th class="w-[15%] px-7 py-6 text-center font-semibold">
                            Status
                        </th>

                    </tr>

                </thead>


                {{-- BODY --}}
                <tbody class="text-[23px] text-gray-900">

                    {{-- ROW 1 --}}
                    <tr
                        onclick="window.location='{{ route('organisasi.donasi.detail') }}'"
                        class="cursor-pointer
                               transition-all duration-200
                               hover:bg-[#EAF7F1]
                               hover:shadow-[inset_5px_0_0_#08703F]"
                    >

                        <td class="px-7 py-6 text-center font-medium">
                            Budiman
                        </td>

                        <td class="px-7 py-6 text-center">
                            Bantu Anak Muda Penerus Bangsa
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 500.000
                        </td>

                        <td class="px-7 py-6 text-center">
                            18 / 01 / 2026
                        </td>

                        <td class="px-7 py-6 text-center">
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
                    <tr
                        onclick="window.location='{{ route('organisasi.donasi.detail') }}'"
                        class="cursor-pointer
                               transition-all duration-200
                               hover:bg-[#EAF7F1]
                               hover:shadow-[inset_5px_0_0_#08703F]"
                    >

                        <td class="px-7 py-6 text-center font-medium">
                            Budi
                        </td>

                        <td class="px-7 py-6 text-center">
                            Anggaran Pembagian Beras
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 350.000
                        </td>

                        <td class="px-7 py-6 text-center">
                            18 / 01 / 2026
                        </td>

                        <td class="px-7 py-6 text-center">
                            <span
                                class="inline-flex
                                       min-w-[135px]
                                       justify-center
                                       bg-[#E4E9FA]
                                       text-[#5665A6]
                                       px-6 py-3
                                       rounded-full
                                       text-[20px]
                                       font-semibold"
                            >
                                Diterima
                            </span>
                        </td>

                    </tr>


                    {{-- ROW 3 --}}
                    <tr
                        onclick="window.location='{{ route('organisasi.donasi.detail') }}'"
                        class="cursor-pointer
                               transition-all duration-200
                               hover:bg-[#EAF7F1]
                               hover:shadow-[inset_5px_0_0_#08703F]"
                    >

                        <td class="px-7 py-6 text-center font-medium">
                            Sane
                        </td>

                        <td class="px-7 py-6 text-center">
                            Anggaran Sarapan Kecil
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 200.000
                        </td>

                        <td class="px-7 py-6 text-center">
                            17 / 01 / 2026
                        </td>

                        <td class="px-7 py-6 text-center">
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


                    {{-- ROW 4 --}}
                    <tr
                        onclick="window.location='{{ route('organisasi.donasi.detail') }}'"
                        class="cursor-pointer
                               transition-all duration-200
                               hover:bg-[#EAF7F1]
                               hover:shadow-[inset_5px_0_0_#08703F]"
                    >

                        <td class="px-7 py-6 text-center font-medium">
                            Dewi
                        </td>

                        <td class="px-7 py-6 text-center">
                            Anggaran Keperluan Para Guru
                        </td>

                        <td class="px-7 py-6 text-center">
                            Rp 150.000
                        </td>

                        <td class="px-7 py-6 text-center">
                            16 / 01 / 2026
                        </td>

                        <td class="px-7 py-6 text-center">
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



    {{-- =====================================================
        PAGINATION
    ===================================================== --}}
    <section class="flex justify-center mt-16">

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
                    type="button"
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
                    type="button"
                    class="w-[58px] h-[58px]
                           rounded-xl
                           text-[#08703F]
                           text-[21px]
                           font-semibold
                           hover:bg-[#F0F8F4]
                           transition"
                >
                    2
                </button>

                <button
                    type="button"
                    class="w-[58px] h-[58px]
                           rounded-xl
                           text-[#08703F]
                           text-[21px]
                           font-semibold
                           hover:bg-[#F0F8F4]
                           transition"
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