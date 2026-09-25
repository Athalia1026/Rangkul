@extends('layouts.organization', [
    'title' => 'Upload Bukti',
    'activeNav' => 'kampanye'
])

@section('content')

<main
    class="w-full
           px-8 sm:px-10 lg:px-16 xl:px-20 2xl:px-24
           pt-16 pb-24"
>

    {{-- HEADER --}}
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
                Upload Bukti
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


    {{-- FORM CARD --}}
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

        {{-- UPLOAD COVER --}}
        <label
            for="uploadCover"
            class="w-full
                   min-h-[420px]
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
                       text-[38px]
                       lg:text-[42px]
                       font-semibold
                       text-[#08703F]"
            >
                Upload Cover Campaign
            </h2>

            <p
                class="mt-2
                       text-[22px]
                       text-gray-700"
            >
                Klik untuk memilih file dengan tipe JPG, PNG
            </p>

            <input
                id="uploadCover"
                type="file"
                accept=".jpg,.jpeg,.png"
                class="hidden"
            >

        </label>


        {{-- FORM --}}
        <div class="mt-10 space-y-8">

            {{-- JUDUL --}}
            <div>

                <label
                    for="judulBukti"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Judul
                </label>

                <input
                    id="judulBukti"
                    type="text"
                    placeholder="Masukan Judul"
                    class="w-full
                           h-[82px]
                           bg-white
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           text-[23px]
                           text-gray-900
                           placeholder:text-[#A9CEC0]
                           outline-none
                           focus:border-[#08703F]
                           focus:ring-2
                           focus:ring-[#08703F]/10"
                >

            </div>


            {{-- NOMINAL --}}
            <div>

                <label
                    for="nominalBukti"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Nominal
                </label>

                <input
                    id="nominalBukti"
                    type="text"
                    placeholder="Contoh : Rp 100.000"
                    class="w-full
                           h-[82px]
                           bg-white
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           text-[23px]
                           text-gray-900
                           placeholder:text-[#A9CEC0]
                           outline-none
                           focus:border-[#08703F]
                           focus:ring-2
                           focus:ring-[#08703F]/10"
                >

            </div>


            {{-- TANGGAL --}}
            <div>

                <label
                    for="tanggalBukti"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Tanggal
                </label>

                <input
                    id="tanggalBukti"
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


            {{-- DESKRIPSI --}}
            <div>

                <label
                    for="deskripsiBukti"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Deskripsi
                </label>

                <textarea
                    id="deskripsiBukti"
                    placeholder="Masukan penjelasan singkat"
                    class="w-full
                           min-h-[150px]
                           bg-white
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           py-5
                           text-[23px]
                           text-gray-900
                           placeholder:text-[#A9CEC0]
                           outline-none
                           resize-none
                           focus:border-[#08703F]
                           focus:ring-2
                           focus:ring-[#08703F]/10"
                ></textarea>

            </div>

        </div>


        {{-- BUTTONS --}}
        <div
            class="flex
                   flex-col
                   sm:flex-row
                   justify-end
                   gap-6
                   mt-24"
        >

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


            <button
                type="button"
                class="min-w-[220px]
                       h-[76px]
                       bg-[#08703F]
                       text-white
                       rounded-[18px]
                       text-[24px]
                       font-semibold
                       hover:bg-[#065D35]
                       transition"
            >
                Simpan
            </button>

        </div>

    </section>

</main>

@endsection