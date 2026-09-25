@extends('layouts.organization', [
    'title' => 'Detail Booking',
    'activeNav' => 'kunjungan'
])

@section('content')

<main
    class="w-full
           px-8 sm:px-10 lg:px-16 xl:px-20 2xl:px-24
           pt-16 pb-24"
>

    {{-- =====================================================
        TITLE
    ===================================================== --}}
    <section>

        <h1
            class="text-[54px]
                   lg:text-[60px]
                   xl:text-[64px]
                   font-bold
                   text-[#08703F]
                   leading-tight"
        >
            Detail Booking
        </h1>


        <p
            class="mt-5
                   text-[25px]
                   lg:text-[28px]
                   text-gray-500
                   font-medium"
        >
            Kelola semua campaign donasi yang dibuat oleh organisasi Anda.
        </p>

    </section>



    {{-- =====================================================
        DETAIL FORM
    ===================================================== --}}
    <section class="mt-12">

        <div class="space-y-9">

            {{-- =================================================
                NAMA PENGUNJUNG
            ================================================= --}}
            <div>

                <label
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Nama Pengunjung
                </label>


                <div
                    class="w-full
                           min-h-[86px]
                           flex items-center
                           bg-[#E7E9EE]
                           border border-gray-300
                           rounded-[12px]
                           px-7
                           text-[24px]
                           text-gray-950
                           font-medium"
                >
                    John
                </div>

            </div>



            {{-- =================================================
                NAMA PIC
            ================================================= --}}
            <div>

                <label
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Nama PIC
                </label>


                <div
                    class="w-full
                           min-h-[86px]
                           flex items-center
                           bg-[#E7E9EE]
                           border border-gray-300
                           rounded-[12px]
                           px-7
                           text-[24px]
                           text-gray-950
                           font-medium"
                >
                    John
                </div>

            </div>



            {{-- =================================================
                EMAIL
            ================================================= --}}
            <div class="max-w-[850px]">

                <label
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Email
                </label>


                <div
                    class="w-full
                           min-h-[86px]
                           flex items-center
                           bg-[#E7E9EE]
                           border border-gray-300
                           rounded-[12px]
                           px-7
                           text-[24px]
                           text-gray-950
                           font-medium"
                >
                    JohnChris26@gmail.com
                </div>

            </div>



            {{-- =================================================
                TANGGAL
            ================================================= --}}
            <div class="max-w-[850px]">

                <label
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Tanggal
                </label>


                <div
                    class="w-full
                           min-h-[86px]
                           flex items-center
                           justify-between
                           gap-5
                           bg-[#E7E9EE]
                           border border-gray-300
                           rounded-[12px]
                           px-7
                           text-[24px]
                           text-gray-950
                           font-medium"
                >

                    <span>
                        18/01/2026
                    </span>


                    <i class="fa-regular fa-calendar text-[27px] text-gray-900"></i>

                </div>

            </div>



            {{-- =================================================
                JUMLAH ANAK
            ================================================= --}}
            <div>

                <label
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Jumlah Anak
                </label>


                <div class="flex items-center gap-5">

                    <div
                        class="w-[320px]
                               min-h-[86px]
                               flex items-center
                               bg-[#E7E9EE]
                               border border-gray-300
                               rounded-[12px]
                               px-7
                               text-[24px]
                               text-gray-950
                               font-medium"
                    >
                        12
                    </div>


                    <span
                        class="text-[27px]
                               text-gray-900
                               font-medium"
                    >
                        Orang
                    </span>

                </div>

            </div>



            {{-- =================================================
                TUJUAN KUNJUNGAN
            ================================================= --}}
            <div>

                <label
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Tujuan kunjungan
                </label>


                <div
                    class="w-full
                           min-h-[270px]
                           bg-[#E7E9EE]
                           border border-gray-300
                           rounded-[12px]
                           px-7 py-7
                           text-[24px]
                           text-gray-950
                           font-medium
                           leading-relaxed"
                >
                    Ingin melihat kondisi langsung anak - anak di lokasi
                </div>

            </div>



            {{-- =================================================
                CATATAN
            ================================================= --}}
            <div>

                <label
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Catatan
                </label>


                <div
                    class="w-full
                           min-h-[86px]
                           flex items-center
                           bg-[#E7E9EE]
                           border border-gray-300
                           rounded-[12px]
                           px-7
                           text-[24px]
                           text-gray-950
                           font-medium"
                >
                    Semoga Donasi kami bisa bermanfaat
                </div>

            </div>

        </div>

    </section>



    {{-- =====================================================
        ACTION BUTTONS
    ===================================================== --}}
    <section
        class="flex flex-col
               sm:flex-row
               justify-end
               gap-6
               mt-28"
    >

        {{-- TOLAK --}}
        <button
            type="button"
            class="min-w-[220px]
                   h-[78px]
                   inline-flex
                   items-center
                   justify-center
                   gap-3
                   bg-white
                   border-2 border-red-500
                   text-red-500
                   rounded-[18px]
                   text-[24px]
                   font-semibold
                   hover:bg-red-500
                   hover:text-white
                   transition"
        >

            <i class="fa-solid fa-xmark text-[20px]"></i>

            Tolak

        </button>



        {{-- TERIMA --}}
        <button
            type="button"
            class="min-w-[220px]
                   h-[78px]
                   inline-flex
                   items-center
                   justify-center
                   bg-[#08703F]
                   text-white
                   rounded-[18px]
                   text-[24px]
                   font-semibold
                   hover:bg-[#065D35]
                   transition"
        >
            Terima
        </button>

    </section>

</main>

@endsection