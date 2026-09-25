@extends('layouts.organization', [
    'title' => 'Profil Panti',
    'activeNav' => 'profil'
])

@section('content')

{{-- =========================================================
    HERO PROFIL
========================================================= --}}
<section class="relative h-[500px] overflow-hidden">

    <img
        src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2400&auto=format&fit=crop"
        alt="Profil Panti"
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
                Profil Panti
            </h1>

            <p
                class="mt-7
                       text-white/95
                       text-[28px]
                       lg:text-[31px]
                       font-medium"
            >
                Kelola Profil dan Informasi Panti
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
           space-y-12"
>

    {{-- =====================================================
        FOTO DAN IDENTITAS
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[28px]
               shadow-md
               border border-gray-100
               px-10
               lg:px-12
               py-12"
    >

        {{-- TITLE --}}
        <div class="flex items-center gap-5">

            <div
                class="w-[60px]
                       h-[60px]
                       rounded-[15px]
                       bg-[#DDF0E9]
                       text-[#08703F]
                       flex items-center justify-center"
            >
                <i class="fa-solid fa-camera text-[28px]"></i>
            </div>


            <h2
                class="text-[38px]
                       lg:text-[42px]
                       font-bold
                       text-gray-950"
            >
                Foto dan Identitas Panti
            </h2>

        </div>



        {{-- =================================================
            FOTO + FORM
        ================================================= --}}
        <div
            class="grid grid-cols-1
                   lg:grid-cols-[420px_1fr]
                   gap-12
                   mt-11"
        >

            {{-- FOTO UPLOAD --}}
            <div>

                <label
                    for="fotoPanti"
                    class="relative
                           w-full
                           h-[470px]
                           bg-[#ECFAF7]
                           border-2
                           border-[#7B9F94]
                           rounded-[22px]
                           flex
                           flex-col
                           items-center
                           justify-center
                           cursor-pointer
                           hover:bg-[#E2F6F1]
                           transition"
                >

                    <div
                        class="w-[80px]
                               h-[80px]
                               rounded-full
                               bg-[#D6F0E7]
                               text-[#08703F]
                               flex
                               items-center
                               justify-center"
                    >
                        <i class="fa-solid fa-cloud-arrow-up text-[38px]"></i>
                    </div>


                    <p
                        class="mt-6
                               text-[28px]
                               font-bold
                               text-gray-950"
                    >
                        Unduh Foto
                    </p>


                    <p
                        class="mt-3
                               px-8
                               text-center
                               text-[20px]
                               text-gray-500"
                    >
                        Klik untuk memilih file dengan tipe JPG, PNG
                    </p>


                    <input
                        id="fotoPanti"
                        type="file"
                        accept=".jpg,.jpeg,.png"
                        class="hidden"
                    >

                </label>

            </div>



            {{-- IDENTITAS --}}
            <div class="space-y-8">

                {{-- NAMA --}}
                <div>

                    <label
                        for="namaPanti"
                        class="block
                               mb-4
                               text-[24px]
                               font-semibold
                               text-[#08703F]"
                    >
                        Nama Panti
                    </label>

                    <input
                        id="namaPanti"
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



                {{-- EMAIL --}}
                <div>

                    <label
                        for="emailPanti"
                        class="block
                               mb-4
                               text-[24px]
                               font-semibold
                               text-[#08703F]"
                    >
                        Email
                    </label>

                    <input
                        id="emailPanti"
                        type="email"
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



                {{-- TELEPON --}}
                <div>

                    <label
                        for="teleponPanti"
                        class="block
                               mb-4
                               text-[24px]
                               font-semibold
                               text-[#08703F]"
                    >
                        No.telepon
                    </label>

                    <input
                        id="teleponPanti"
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



                {{-- ALAMAT --}}
                <div>

                    <label
                        for="alamatPanti"
                        class="block
                               mb-4
                               text-[24px]
                               font-semibold
                               text-[#08703F]"
                    >
                        Alamat panti
                    </label>

                    <input
                        id="alamatPanti"
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

            </div>

        </div>



        {{-- =================================================
            DESKRIPSI
        ================================================= --}}
        <div class="mt-12">

            <div class="flex items-center gap-4 mb-5">

                <div
                    class="w-[52px]
                           h-[52px]
                           rounded-[13px]
                           bg-[#EEF2F1]
                           text-gray-700
                           flex items-center justify-center"
                >
                    <i class="fa-regular fa-rectangle-list text-[25px]"></i>
                </div>


                <label
                    for="deskripsiPanti"
                    class="text-[30px]
                           lg:text-[34px]
                           font-bold
                           text-gray-950"
                >
                    Deskripsi Panti
                </label>

            </div>


            <textarea
                id="deskripsiPanti"
                class="w-full
                       h-[330px]
                       bg-white
                       border border-gray-300
                       rounded-[18px]
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

    </section>



    {{-- =====================================================
        INFORMASI TAMBAHAN
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[28px]
               shadow-md
               border border-gray-100
               px-10
               lg:px-12
               py-12"
    >

        {{-- TITLE --}}
        <div class="flex items-center gap-5">

            <div
                class="w-[60px]
                       h-[60px]
                       rounded-full
                       bg-[#08703F]
                       text-white
                       flex items-center justify-center"
            >
                <i class="fa-solid fa-plus text-[28px]"></i>
            </div>


            <h2
                class="text-[38px]
                       lg:text-[42px]
                       font-bold
                       text-gray-950"
            >
                Informasi Tambahan
            </h2>

        </div>



        {{-- FORM GRID --}}
        <div
            class="grid grid-cols-1
                   lg:grid-cols-2
                   gap-x-16
                   gap-y-9
                   mt-11"
        >

            {{-- =================================================
                JUMLAH ANAK
            ================================================= --}}
            <div>

                <label
                    for="jumlahAnak"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Jumlah Anak
                </label>


                <div class="flex items-center gap-5">

                    <div class="relative w-[300px]">

                        <input
                            id="jumlahAnak"
                            type="number"
                            class="w-full
                                   h-[82px]
                                   bg-white
                                   border border-gray-300
                                   rounded-[16px]
                                   px-6
                                   pr-16
                                   text-[23px]
                                   text-gray-900
                                   outline-none
                                   focus:border-[#08703F]
                                   focus:ring-2
                                   focus:ring-[#08703F]/10"
                        >

                        <i
                            class="fa-solid fa-children
                                   absolute
                                   right-6
                                   top-1/2
                                   -translate-y-1/2
                                   text-[24px]
                                   text-gray-900"
                        ></i>

                    </div>


                    <span
                        class="text-[26px]
                               font-medium
                               text-gray-900"
                    >
                        Orang
                    </span>

                </div>

            </div>



            {{-- =================================================
                TAHUN BERDIRI
            ================================================= --}}
            <div>

                <label
                    for="tahunBerdiri"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Tahun Berdiri
                </label>


                <input
                    id="tahunBerdiri"
                    type="number"
                    placeholder="Masukan tahun"
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



            {{-- =================================================
                NAMA BANK
            ================================================= --}}
            <div>

                <label
                    for="namaBank"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Nama Bank
                </label>


                <div class="relative">

                    <select
                        id="namaBank"
                        class="w-full
                               h-[82px]
                               appearance-none
                               bg-white
                               border border-gray-300
                               rounded-[16px]
                               px-6
                               pr-16
                               text-[23px]
                               text-[#A9CEC0]
                               outline-none
                               focus:border-[#08703F]
                               focus:ring-2
                               focus:ring-[#08703F]/10"
                    >

                        <option value="">
                            Silahkan pilih
                        </option>

                        <option value="bca">
                            BCA
                        </option>

                        <option value="bni">
                            BNI
                        </option>

                        <option value="bri">
                            BRI
                        </option>

                        <option value="mandiri">
                            Mandiri
                        </option>

                    </select>


                    <i
                        class="fa-solid fa-chevron-down
                               absolute
                               right-6
                               top-1/2
                               -translate-y-1/2
                               text-[20px]
                               text-gray-600
                               pointer-events-none"
                    ></i>

                </div>

            </div>



            {{-- =================================================
                NOMOR REKENING
            ================================================= --}}
            <div>

                <label
                    for="nomorRekening"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Nomor Rekening
                </label>


                <input
                    id="nomorRekening"
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



            {{-- =================================================
                NAMA PEMILIK REKENING
            ================================================= --}}
            <div>

                <label
                    for="pemilikRekening"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Nama Pemilik Rekening
                </label>


                <input
                    id="pemilikRekening"
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



            {{-- =================================================
                STATUS
            ================================================= --}}
            <div>

                <label
                    for="statusPanti"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Status
                </label>


                <div class="relative">

                    <select
                        id="statusPanti"
                        class="w-full
                               h-[82px]
                               appearance-none
                               bg-white
                               border border-gray-300
                               rounded-[16px]
                               px-6
                               pr-16
                               text-[23px]
                               text-[#A9CEC0]
                               outline-none
                               focus:border-[#08703F]
                               focus:ring-2
                               focus:ring-[#08703F]/10"
                    >

                        <option value="">
                            Silahkan pilih
                        </option>

                        <option value="aktif">
                            Aktif
                        </option>

                        <option value="nonaktif">
                            Nonaktif
                        </option>

                    </select>


                    <i
                        class="fa-solid fa-chevron-down
                               absolute
                               right-6
                               top-1/2
                               -translate-y-1/2
                               text-[20px]
                               text-gray-600
                               pointer-events-none"
                    ></i>

                </div>

            </div>

        </div>



        {{-- =================================================
            BUTTON SIMPAN
        ================================================= --}}
        <div class="flex justify-end mt-14">

            <button
                type="button"
                class="min-w-[220px]
                       h-[76px]
                       bg-[#08703F]
                       hover:bg-[#065D35]
                       text-white
                       rounded-[18px]
                       text-[24px]
                       font-semibold
                       transition"
            >
                Simpan
            </button>

        </div>

    </section>

</main>

@endsection