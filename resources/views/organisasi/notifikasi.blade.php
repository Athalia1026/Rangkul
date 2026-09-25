@extends('layouts.organization', [
    'title' => 'Notifikasi',
    'activeNav' => ''
])

@section('content')

<main
    class="w-full
           px-8 sm:px-10 lg:px-16 xl:px-20 2xl:px-24
           py-20"
>

    {{-- =====================================================
        NOTIFICATION WRAPPER
    ===================================================== --}}
    <section
        class="max-w-[1550px]
               mx-auto
               bg-white
               rounded-[28px]
               shadow-sm
               border border-gray-100
               px-10
               lg:px-14
               py-12"
    >

        {{-- =================================================
            TOP
        ================================================= --}}
        <div class="relative">

            <a
                href="{{ url()->previous() }}"
                class="inline-flex
                       items-center
                       justify-center
                       min-w-[220px]
                       h-[70px]
                       bg-[#08703F]
                       text-white
                       rounded-[18px]
                       text-[23px]
                       font-semibold
                       hover:bg-[#065D35]
                       transition"
            >
                Kembali
            </a>


            <h1
                class="mt-10
                       lg:mt-0
                       lg:absolute
                       lg:left-1/2
                       lg:top-1/2
                       lg:-translate-x-1/2
                       lg:-translate-y-1/2
                       text-center
                       text-[46px]
                       lg:text-[52px]
                       font-bold
                       text-[#08703F]"
            >
                Notifikasi
            </h1>

        </div>



        {{-- =================================================
            HARI INI
        ================================================= --}}
        <div class="mt-20">

            <h2
                class="text-[27px]
                       font-semibold
                       text-gray-600"
            >
                Hari Ini
            </h2>


            <div class="mt-7 space-y-5">

                {{-- NOTIF 1 --}}
                <div
                    class="w-full
                           min-h-[135px]
                           bg-white
                           border border-gray-300
                           rounded-[20px]
                           shadow-md
                           px-8
                           py-6
                           flex
                           items-center
                           gap-6"
                >

                    <div
                        class="w-[72px]
                               h-[72px]
                               shrink-0
                               rounded-full
                               bg-[#DDF0E9]
                               text-[#08703F]
                               flex
                               items-center
                               justify-center"
                    >
                        <i class="fa-regular fa-circle-check text-[35px]"></i>
                    </div>


                    <div class="flex-1 min-w-0">

                        <h3
                            class="text-[27px]
                                   font-semibold
                                   text-gray-950"
                        >
                            Kampanye Berhasil Berhasil Diterbitkan
                        </h3>


                        <p
                            class="mt-2
                                   text-[21px]
                                   text-gray-700"
                        >
                            Kampanye anda berjudul Anggaran Pembelian Beras berhasil diterbitkan
                        </p>

                    </div>


                    <span
                        class="shrink-0
                               text-[21px]
                               text-gray-500"
                    >
                        10:00
                    </span>

                </div>



                {{-- NOTIF 2 --}}
                <div
                    class="w-full
                           min-h-[135px]
                           bg-white
                           border border-gray-300
                           rounded-[20px]
                           shadow-md
                           px-8
                           py-6
                           flex
                           items-center
                           gap-6"
                >

                    <div
                        class="w-[72px]
                               h-[72px]
                               shrink-0
                               rounded-full
                               bg-[#E8F4FB]
                               text-[#0B4F75]
                               flex
                               items-center
                               justify-center"
                    >
                        <i class="fa-solid fa-calendar-day text-[30px]"></i>
                    </div>


                    <div class="flex-1 min-w-0">

                        <h3
                            class="text-[27px]
                                   font-semibold
                                   text-gray-950"
                        >
                            Jadwal Kunjungan Baru Masuk
                        </h3>


                        <p
                            class="mt-2
                                   text-[21px]
                                   text-gray-700"
                        >
                            Jadwal kunjungan terbaru telah masuk di daftar kunjungan
                        </p>

                    </div>


                    <span
                        class="shrink-0
                               text-[21px]
                               text-gray-500"
                    >
                        08:00
                    </span>

                </div>

            </div>

        </div>



        {{-- =================================================
            KEMARIN
        ================================================= --}}
        <div class="mt-14">

            <h2
                class="text-[27px]
                       font-semibold
                       text-gray-600"
            >
                Kemarin
            </h2>


            <div class="mt-7">

                <div
                    class="w-full
                           min-h-[135px]
                           bg-white
                           border border-gray-300
                           rounded-[20px]
                           shadow-md
                           px-8
                           py-6
                           flex
                           items-center
                           gap-6"
                >

                    <div
                        class="w-[72px]
                               h-[72px]
                               shrink-0
                               rounded-full
                               bg-[#FFF4D9]
                               text-[#B67A00]
                               flex
                               items-center
                               justify-center"
                    >
                        <i class="fa-solid fa-bullhorn text-[30px]"></i>
                    </div>


                    <div class="flex-1 min-w-0">

                        <h3
                            class="text-[27px]
                                   font-semibold
                                   text-gray-950"
                        >
                            Pengajuan Mencairkan Dana Berhasil
                        </h3>


                        <p
                            class="mt-2
                                   text-[21px]
                                   text-gray-700"
                        >
                            Anda berhasil mencairkan dana uang sebesar Rp 200.000
                        </p>

                    </div>


                    <span
                        class="shrink-0
                               text-[21px]
                               text-gray-500"
                    >
                        18:00
                    </span>

                </div>

            </div>

        </div>

    </section>

</main>

@endsection