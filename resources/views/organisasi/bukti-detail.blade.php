@extends('layouts.organization', [
    'title' => 'Detail Bukti',
    'activeNav' => 'kampanye'
])

@section('content')

<main
    class="w-full
           px-8 sm:px-10 lg:px-16 xl:px-20 2xl:px-24
           pt-16 pb-24"
>

    {{-- =====================================================
        HEADER
    ===================================================== --}}
    <section
        class="flex flex-col
               lg:flex-row
               lg:items-start
               justify-between
               gap-8"
    >

        <div>

            <h1
                class="text-[54px]
                       lg:text-[60px]
                       xl:text-[64px]
                       font-bold
                       text-[#08703F]
                       leading-tight"
            >
                Detail Bukti
            </h1>


            <p
                class="mt-5
                       text-[25px]
                       lg:text-[28px]
                       text-gray-500
                       font-medium"
            >
                Kelola semua kampanye donasi yang dibuat oleh organisasi Anda.
            </p>

        </div>


        <a
            href="{{ route('organisasi.kampanye.detail') }}"
            class="min-w-[210px]
                   h-[76px]
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
            Kembali
        </a>

    </section>



    {{-- =====================================================
        DETAIL CARD
    ===================================================== --}}
    <section
        class="mt-14
               bg-white
               rounded-[28px]
               shadow-md
               border border-gray-100
               px-10
               lg:px-12
               py-12"
    >

        {{-- FOTO --}}
        <h2
            class="text-[38px]
                   lg:text-[42px]
                   font-bold
                   text-gray-950"
        >
            Foto
        </h2>


        <div
            class="grid grid-cols-1
                   lg:grid-cols-[1fr_1.05fr]
                   gap-10
                   mt-7"
        >

            {{-- IMAGE --}}
            <div>

                <img
                    src="https://images.unsplash.com/photo-1532375810709-75b1da00537c?q=80&w=1600&auto=format&fit=crop"
                    alt="Bukti Pembelian Beras"
                    class="w-full
                           h-[520px]
                           object-cover
                           rounded-[24px]"
                >

            </div>


            {{-- JUDUL --}}
            <div>

                <label
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Judul
                </label>


                <div
                    class="w-full
                           min-h-[82px]
                           flex items-center
                           bg-white
                           border border-gray-300
                           rounded-[16px]
                           px-7
                           text-[24px]
                           text-gray-950
                           font-medium"
                >
                    Pembelian beras
                </div>

            </div>

        </div>



        {{-- =====================================================
            DESKRIPSI
        ===================================================== --}}
        <div class="mt-12">

            <h2
                class="text-[32px]
                       lg:text-[36px]
                       font-bold
                       text-gray-950"
            >
                Deskripsi
            </h2>


            <div
                class="grid grid-cols-1
                       lg:grid-cols-2
                       gap-8
                       mt-7"
            >

                {{-- NOMINAL --}}
                <div
                    class="min-h-[92px]
                           border-2
                           border-[#08703F]
                           rounded-[18px]
                           px-7 py-5
                           flex items-center
                           gap-5"
                >

                    <div
                        class="w-[58px]
                               h-[58px]
                               shrink-0
                               rounded-full
                               bg-[#08703F]
                               text-white
                               flex items-center
                               justify-center"
                    >
                        <i class="fa-solid fa-dollar-sign text-[26px]"></i>
                    </div>


                    <div>

                        <p
                            class="text-[22px]
                                   font-semibold
                                   text-gray-950"
                        >
                            Nominal
                        </p>

                        <p
                            class="mt-1
                                   text-[28px]
                                   font-semibold
                                   text-[#08703F]"
                        >
                            Rp 2.500.000,-
                        </p>

                    </div>

                </div>



                {{-- STATUS --}}
                <div
                    class="min-h-[92px]
                           border-2
                           border-[#08703F]
                           rounded-[18px]
                           px-7 py-5
                           flex items-center
                           gap-5"
                >

                    <div
                        class="w-[58px]
                               h-[58px]
                               shrink-0
                               rounded-[12px]
                               bg-black
                               text-white
                               flex items-center
                               justify-center"
                    >
                        <i class="fa-solid fa-check text-[27px]"></i>
                    </div>


                    <div>

                        <p
                            class="text-[22px]
                                   font-semibold
                                   text-gray-950"
                        >
                            Status
                        </p>

                        <p
                            class="mt-1
                                   text-[28px]
                                   font-semibold
                                   text-[#08703F]"
                        >
                            Disetujui
                        </p>

                    </div>

                </div>



                {{-- TANGGAL --}}
                <div
                    class="min-h-[92px]
                           border-2
                           border-[#08703F]
                           rounded-[18px]
                           px-7 py-5
                           flex items-center
                           gap-5"
                >

                    <div
                        class="w-[58px]
                               h-[58px]
                               shrink-0
                               text-black
                               flex items-center
                               justify-center"
                    >
                        <i class="fa-solid fa-calendar-days text-[34px]"></i>
                    </div>


                    <div>

                        <p
                            class="text-[22px]
                                   font-semibold
                                   text-gray-950"
                        >
                            Tanggal
                        </p>

                        <p
                            class="mt-1
                                   text-[28px]
                                   font-semibold
                                   text-[#08703F]"
                        >
                            20 Juli 2026
                        </p>

                    </div>

                </div>



                {{-- CATATAN --}}
                <div
                    class="min-h-[260px]
                           row-span-2
                           border-2
                           border-[#08703F]
                           rounded-[18px]
                           px-7 py-6
                           flex items-start
                           gap-5"
                >

                    <div
                        class="w-[58px]
                               h-[58px]
                               shrink-0
                               text-black
                               flex items-center
                               justify-center"
                    >
                        <i class="fa-solid fa-pen-to-square text-[34px]"></i>
                    </div>


                    <div>

                        <p
                            class="text-[22px]
                                   font-semibold
                                   text-gray-950"
                        >
                            Catatan
                        </p>

                        <p
                            class="mt-2
                                   text-[26px]
                                   leading-relaxed
                                   font-medium
                                   text-[#08703F]"
                        >
                            Dana yang telah diterima sepenuhnya telah dialokasikan
                            untuk pembelian beras demi keberlangsungan anak anak.
                            Jumlah beras yang dibeli sebanyak 3 karung.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>

@endsection