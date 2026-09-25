@extends('layouts.organization', [
    'title' => 'Ajukan Pencairan Dana',
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
                class="text-[52px]
                       lg:text-[58px]
                       xl:text-[62px]
                       font-bold
                       text-[#08703F]
                       leading-tight"
            >
                Ajukan Pencairan Dana
            </h1>


            {{-- SALDO TERSEDIA --}}
            <div
                class="mt-5
                       inline-flex
                       items-center
                       gap-4
                       bg-white
                       rounded-[16px]
                       shadow-md
                       border border-gray-100
                       px-8
                       h-[68px]"
            >

                <span
                    class="text-[22px]
                           font-semibold
                           text-[#08703F]"
                >
                    SALDO TERSEDIA
                </span>

                <span
                    class="text-[24px]
                           font-bold
                           text-[#08703F]"
                >
                    Rp.200.000
                </span>

            </div>

        </div>


        {{-- KEMBALI --}}
        <a
            href="{{ route('organisasi.kampanye.detail') }}"
            class="min-w-[210px]
                   h-[72px]
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
        FORM CARD
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

        <div class="space-y-9">

            {{-- NOMINAL --}}
            <div>

                <label
                    for="nominal"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Nominal yang diajukan
                </label>

                <input
                    id="nominal"
                    type="text"
                    class="w-full
                           h-[82px]
                           bg-white
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           text-[23px]
                           text-gray-900
                           outline-none
                           focus:border-[#08703F]
                           focus:ring-2
                           focus:ring-[#08703F]/10"
                >

            </div>


            {{-- TANGGAL --}}
            <div class="max-w-[520px]">

                <label
                    for="tanggal"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Tanggal Pengajuan
                </label>

                <input
                    id="tanggal"
                    type="date"
                    class="w-full
                           h-[82px]
                           bg-white
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           text-[23px]
                           text-gray-900
                           outline-none
                           focus:border-[#08703F]
                           focus:ring-2
                           focus:ring-[#08703F]/10"
                >

            </div>


            {{-- REKENING --}}
            <div>

                <label
                    for="rekening"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Rekening Tujuan
                </label>

                <input
                    id="rekening"
                    type="text"
                    class="w-full
                           h-[82px]
                           bg-white
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           text-[23px]
                           text-gray-900
                           outline-none
                           focus:border-[#08703F]
                           focus:ring-2
                           focus:ring-[#08703F]/10"
                >

            </div>


            {{-- ALASAN --}}
            <div>

                <label
                    for="alasan"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Alasan Pengajuan
                </label>

                <textarea
                    id="alasan"
                    class="w-full
                           min-h-[340px]
                           bg-white
                           border border-gray-300
                           rounded-[16px]
                           px-7
                           py-6
                           text-[23px]
                           text-gray-900
                           leading-relaxed
                           outline-none
                           resize-none
                           focus:border-[#08703F]
                           focus:ring-2
                           focus:ring-[#08703F]/10"
                ></textarea>

            </div>


            {{-- =================================================
                LAMPIRAN
            ================================================= --}}
            <div>

                <label
                    for="lampiran"
                    class="block
                           mb-5
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Lampiran Pendukung ( Opsional )
                </label>


                <label
                    for="lampiran"
                    class="w-full
                           min-h-[380px]
                           bg-[#E2E2E2]
                           border-2
                           border-gray-600
                           rounded-[24px]
                           flex
                           flex-col
                           items-center
                           justify-center
                           cursor-pointer
                           hover:bg-[#D9D9D9]
                           transition"
                >

                    <i
                        class="fa-solid fa-cloud-arrow-up
                               text-[66px]
                               text-black"
                    ></i>


                    <h2
                        class="mt-6
                               text-[37px]
                               lg:text-[40px]
                               font-semibold
                               text-[#08703F]"
                    >
                        Unduh Tampilan Campaign
                    </h2>


                    <p
                        class="mt-2
                               text-[22px]
                               text-gray-700"
                    >
                        Klik untuk memilih file dengan tipe JPG, PNG
                    </p>


                    <input
                        id="lampiran"
                        type="file"
                        accept=".jpg,.jpeg,.png"
                        class="hidden"
                    >

                </label>

            </div>

        </div>

    </section>


    {{-- =====================================================
        ACTION BUTTONS
    ===================================================== --}}
    <section
        class="flex
               flex-col
               sm:flex-row
               justify-end
               gap-6
               mt-10"
    >

        {{-- BATAL --}}
        <a
            href="{{ route('organisasi.kampanye.detail') }}"
            class="min-w-[220px]
                   h-[76px]
                   inline-flex
                   items-center
                   justify-center
                   gap-3
                   bg-white
                   border-2
                   border-red-500
                   text-red-500
                   rounded-[18px]
                   text-[24px]
                   font-semibold
                   hover:bg-red-500
                   hover:text-white
                   transition"
        >
            <i class="fa-solid fa-xmark text-[20px]"></i>
            Batal
        </a>


        {{-- AJUKAN --}}
        <button
            type="button"
            class="min-w-[300px]
                   h-[76px]
                   bg-[#08703F]
                   text-white
                   rounded-[18px]
                   text-[24px]
                   font-semibold
                   hover:bg-[#065D35]
                   transition"
        >
            Ajukan Verifikasi
        </button>

    </section>

</main>

@endsection