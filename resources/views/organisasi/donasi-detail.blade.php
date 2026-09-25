@extends('layouts.organization', [
    'title' => 'Detail Donasi',
    'activeNav' => 'donasi'
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
        href="{{ route('organisasi.donasi') }}"
        class="inline-flex items-center gap-3
               text-[22px]
               text-gray-600
               hover:text-[#08703F]
               transition"
    >
        <i class="fa-solid fa-arrow-left text-[16px]"></i>
        Back to Donasi
    </a>



    {{-- =====================================================
        TITLE
    ===================================================== --}}
    <section class="mt-9">

        <h1
            class="text-[54px]
                   lg:text-[60px]
                   xl:text-[64px]
                   font-bold
                   text-[#08703F]
                   leading-tight"
        >
            Detail Donasi
        </h1>


        <p
            class="mt-5
                   text-[25px]
                   lg:text-[28px]
                   text-gray-500
                   font-medium"
        >
            Informasi lengkap mengenai donasi yang telah diterima.
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
                TOP GRID
            ================================================= --}}
            <div
                class="grid grid-cols-1
                       lg:grid-cols-2
                       gap-x-12
                       gap-y-9"
            >

                {{-- NAMA DONATUR --}}
                <div>

                    <label
                        class="block
                               mb-4
                               text-[24px]
                               font-semibold
                               text-gray-900"
                    >
                        Nama Donatur
                    </label>

                    <div
                        class="w-full
                               min-h-[86px]
                               flex items-center
                               bg-[#F3F5F4]
                               border border-gray-200
                               rounded-[16px]
                               px-7
                               text-[24px]
                               text-gray-800
                               font-medium"
                    >
                        Budiman Tandieono
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
                               min-h-[86px]
                               flex items-center
                               bg-[#F3F5F4]
                               border border-gray-200
                               rounded-[16px]
                               px-7
                               text-[24px]
                               text-gray-800
                               font-medium"
                    >
                        Bantu Anak Muda Penerus Bangsa
                    </div>

                </div>



                {{-- NOMINAL --}}
                <div>

                    <label
                        class="block
                               mb-4
                               text-[24px]
                               font-semibold
                               text-gray-900"
                    >
                        Nominal
                    </label>

                    <div
                        class="w-full
                               min-h-[86px]
                               flex items-center
                               bg-[#F3F5F4]
                               border border-gray-200
                               rounded-[16px]
                               px-7
                               text-[24px]
                               text-gray-800
                               font-medium"
                    >
                        Rp 500.000
                    </div>

                </div>



                {{-- TANGGAL --}}
                <div>

                    <label
                        class="block
                               mb-4
                               text-[24px]
                               font-semibold
                               text-gray-900"
                    >
                        Tanggal
                    </label>

                    <div
                        class="w-full
                               min-h-[86px]
                               flex items-center
                               bg-[#F3F5F4]
                               border border-gray-200
                               rounded-[16px]
                               px-7
                               text-[24px]
                               text-gray-800
                               font-medium"
                    >
                        18 / 01 / 2026
                    </div>

                </div>



                {{-- METODE PEMBAYARAN --}}
                <div>

                    <label
                        class="block
                               mb-4
                               text-[24px]
                               font-semibold
                               text-gray-900"
                    >
                        Metode Pembayaran
                    </label>

                    <div
                        class="w-full
                               min-h-[86px]
                               flex items-center
                               bg-[#F3F5F4]
                               border border-gray-200
                               rounded-[16px]
                               px-7
                               text-[24px]
                               text-gray-800
                               font-medium"
                    >
                        Transfer Bank
                    </div>

                </div>



                {{-- STATUS --}}
                <div>

                    <label
                        class="block
                               mb-4
                               text-[24px]
                               font-semibold
                               text-gray-900"
                    >
                        Status
                    </label>

                    <div
                        class="w-full
                               min-h-[86px]
                               flex items-center
                               bg-[#F3F5F4]
                               border border-gray-200
                               rounded-[16px]
                               px-7"
                    >

                        <span
                            class="inline-flex
                                   min-w-[160px]
                                   justify-center
                                   bg-[#D7EFE5]
                                   text-[#10765B]
                                   px-7 py-3
                                   rounded-full
                                   text-[21px]
                                   font-semibold"
                        >
                            Berhasil
                        </span>

                    </div>

                </div>

            </div>



            {{-- =================================================
                PESAN DONATUR
            ================================================= --}}
            <div class="mt-10">

                <label
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-gray-900"
                >
                    Pesan Donatur
                </label>


                <div
                    class="w-full
                           min-h-[190px]
                           bg-[#F3F5F4]
                           border border-gray-200
                           rounded-[18px]
                           px-7 py-7
                           text-[24px]
                           text-gray-800
                           leading-relaxed
                           font-medium"
                >
                    Tetap semangat untuk para generasi muda berbakat,
                    jangan takut untuk memulai sesuatu.
                </div>

            </div>



            {{-- =================================================
                BUTTON
            ================================================= --}}
            <div class="flex justify-end mt-12">

                <a
                    href="{{ route('organisasi.donasi') }}"
                    class="min-w-[210px]
                           text-center
                           bg-[#08703F]
                           hover:bg-[#065D35]
                           text-white
                           px-10 py-5
                           rounded-[16px]
                           text-[24px]
                           font-semibold
                           transition"
                >
                    Kembali
                </a>

            </div>

        </div>

    </section>

</main>

@endsection