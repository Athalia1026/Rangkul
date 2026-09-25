@extends('layouts.organization', [
    'title' => 'Kunjungan Organisasi',
    'activeNav' => 'kunjungan'
])

@section('content')

{{-- =========================================================
    HERO KUNJUNGAN
========================================================= --}}
<section class="relative h-[500px] overflow-hidden">

    <img
        src="https://images.unsplash.com/photo-1489493512598-d08130f49bea?q=80&w=2200&auto=format&fit=crop"
        alt="Kunjungan"
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
                Kunjungan
            </h1>

            <p
                class="mt-7
                       text-white/95
                       text-[28px]
                       lg:text-[31px]
                       font-medium"
            >
                Kelola seluruh permintaan kunjungan ke panti.
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
        FILTER
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[26px]
               shadow-md
               border border-gray-100
               px-10 py-10"
    >

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- SEARCH --}}
            <div>

                <label
                    for="searchInstansi"
                    class="block mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Cari Instansi
                </label>

                <div class="relative">

                    <i
                        class="fa-solid fa-magnifying-glass
                               absolute left-6 top-1/2 -translate-y-1/2
                               text-[24px]
                               text-gray-500"
                    ></i>

                    <input
                        id="searchInstansi"
                        type="text"
                        placeholder="Cari nama instansi..."
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
                    for="statusKunjungan"
                    class="block mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Status
                </label>

                <div class="relative">

                    <select
                        id="statusKunjungan"
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
                        <option value="menunggu">Menunggu</option>
                        <option value="diterima">Diterima</option>
                        <option value="ditolak">Ditolak</option>
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



            {{-- TANGGAL --}}
            <div>

                <label
                    for="tanggalKunjungan"
                    class="block mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Tanggal
                </label>

                <input
                    id="tanggalKunjungan"
                    type="date"
                    class="w-full
                           h-[82px]
                           bg-white
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           text-[23px]
                           text-gray-700
                           outline-none
                           focus:border-[#08703F]
                           focus:ring-2
                           focus:ring-[#08703F]/10"
                >

            </div>

        </div>

    </section>



    {{-- =====================================================
        LIST KUNJUNGAN
    ===================================================== --}}
    <section class="mt-16">

        <h2
            class="text-[50px]
                   lg:text-[54px]
                   font-bold
                   text-[#16735F]"
        >
            List Kunjungan
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

                        <th class="w-[25%] px-7 py-6 text-center font-semibold">
                            Nama
                        </th>

                        <th class="w-[25%] px-7 py-6 text-center font-semibold">
                            Tanggal
                        </th>

                        <th class="w-[15%] px-7 py-6 text-center font-semibold">
                            Orang
                        </th>

                        <th class="w-[35%] px-7 py-6 text-center font-semibold">
                            Aksi
                        </th>

                    </tr>

                </thead>


                {{-- BODY --}}
                <tbody class="text-[23px] text-gray-900">

                    {{-- ROW 1 --}}
                    <tr class="hover:bg-[#F0F8F4] transition">

                        <td class="px-7 py-7 text-center font-medium">
                            John
                        </td>

                        <td class="px-7 py-7 text-center">
                            18 / 01 / 2026
                        </td>

                        <td class="px-7 py-7 text-center">
                            12
                        </td>

                        <td class="px-7 py-7">

                            <div class="flex items-center justify-center gap-4">

                                <button
                                    type="button"
                                    class="w-[56px] h-[56px]
                                           rounded-[14px]
                                           bg-[#FBE7E7]
                                           text-[#D94A4A]
                                           hover:bg-[#D94A4A]
                                           hover:text-white
                                           flex items-center justify-center
                                           text-[22px]
                                           transition"
                                >
                                    <i class="fa-solid fa-xmark"></i>
                                </button>


                                <button
                                    type="button"
                                    class="w-[56px] h-[56px]
                                           rounded-[14px]
                                           bg-[#DDF0E9]
                                           text-[#08703F]
                                           hover:bg-[#08703F]
                                           hover:text-white
                                           flex items-center justify-center
                                           text-[22px]
                                           transition"
                                >
                                    <i class="fa-solid fa-check"></i>
                                </button>


                                <a
                                    href="{{ route('organisasi.kunjungan.detail') }}"
                                    class="min-w-[145px]
                                           h-[56px]
                                           inline-flex
                                           items-center
                                           justify-center
                                           border-2 border-[#08703F]
                                           text-[#08703F]
                                           hover:bg-[#08703F]
                                           hover:text-white
                                           rounded-[14px]
                                           px-7
                                           text-[21px]
                                           font-semibold
                                           transition"
                                >
                                    Detail
                                </a>

                            </div>

                        </td>

                    </tr>


                    {{-- ROW 2 --}}
                    <tr class="hover:bg-[#F0F8F4] transition">

                        <td class="px-7 py-7 text-center font-medium">
                            PT Maju
                        </td>

                        <td class="px-7 py-7 text-center">
                            18 / 04 / 2026
                        </td>

                        <td class="px-7 py-7 text-center">
                            8
                        </td>

                        <td class="px-7 py-7">

                            <div class="flex items-center justify-center gap-4">

                                <button
                                    type="button"
                                    class="w-[56px] h-[56px]
                                           rounded-[14px]
                                           bg-[#FBE7E7]
                                           text-[#D94A4A]
                                           hover:bg-[#D94A4A]
                                           hover:text-white
                                           flex items-center justify-center
                                           text-[22px]
                                           transition"
                                >
                                    <i class="fa-solid fa-xmark"></i>
                                </button>


                                <button
                                    type="button"
                                    class="w-[56px] h-[56px]
                                           rounded-[14px]
                                           bg-[#DDF0E9]
                                           text-[#08703F]
                                           hover:bg-[#08703F]
                                           hover:text-white
                                           flex items-center justify-center
                                           text-[22px]
                                           transition"
                                >
                                    <i class="fa-solid fa-check"></i>
                                </button>


                                <a
                                    href="{{ route('organisasi.kunjungan.detail') }}"
                                    class="min-w-[145px]
                                           h-[56px]
                                           inline-flex
                                           items-center
                                           justify-center
                                           border-2 border-[#08703F]
                                           text-[#08703F]
                                           hover:bg-[#08703F]
                                           hover:text-white
                                           rounded-[14px]
                                           px-7
                                           text-[21px]
                                           font-semibold
                                           transition"
                                >
                                    Detail
                                </a>

                            </div>

                        </td>

                    </tr>


                    {{-- ROW 3 --}}
                    <tr class="hover:bg-[#F0F8F4] transition">

                        <td class="px-7 py-7 text-center font-medium">
                            Sane
                        </td>

                        <td class="px-7 py-7 text-center">
                            22 / 04 / 2026
                        </td>

                        <td class="px-7 py-7 text-center">
                            3
                        </td>

                        <td class="px-7 py-7">

                            <div class="flex items-center justify-center gap-4">

                                <button
                                    type="button"
                                    class="w-[56px] h-[56px]
                                           rounded-[14px]
                                           bg-[#FBE7E7]
                                           text-[#D94A4A]
                                           hover:bg-[#D94A4A]
                                           hover:text-white
                                           flex items-center justify-center
                                           text-[22px]
                                           transition"
                                >
                                    <i class="fa-solid fa-xmark"></i>
                                </button>


                                <button
                                    type="button"
                                    class="w-[56px] h-[56px]
                                           rounded-[14px]
                                           bg-[#DDF0E9]
                                           text-[#08703F]
                                           hover:bg-[#08703F]
                                           hover:text-white
                                           flex items-center justify-center
                                           text-[22px]
                                           transition"
                                >
                                    <i class="fa-solid fa-check"></i>
                                </button>


                                <a
                                    href="{{ route('organisasi.kunjungan.detail') }}"
                                    class="min-w-[145px]
                                           h-[56px]
                                           inline-flex
                                           items-center
                                           justify-center
                                           border-2 border-[#08703F]
                                           text-[#08703F]
                                           hover:bg-[#08703F]
                                           hover:text-white
                                           rounded-[14px]
                                           px-7
                                           text-[21px]
                                           font-semibold
                                           transition"
                                >
                                    Detail
                                </a>

                            </div>

                        </td>

                    </tr>


                    {{-- ROW 4 --}}
                    <tr class="hover:bg-[#F0F8F4] transition">

                        <td class="px-7 py-7 text-center font-medium">
                            Dewi
                        </td>

                        <td class="px-7 py-7 text-center">
                            13 / 05 / 2026
                        </td>

                        <td class="px-7 py-7 text-center">
                            7
                        </td>

                        <td class="px-7 py-7">

                            <div class="flex items-center justify-center gap-4">

                                <button
                                    type="button"
                                    class="w-[56px] h-[56px]
                                           rounded-[14px]
                                           bg-[#FBE7E7]
                                           text-[#D94A4A]
                                           hover:bg-[#D94A4A]
                                           hover:text-white
                                           flex items-center justify-center
                                           text-[22px]
                                           transition"
                                >
                                    <i class="fa-solid fa-xmark"></i>
                                </button>


                                <button
                                    type="button"
                                    class="w-[56px] h-[56px]
                                           rounded-[14px]
                                           bg-[#DDF0E9]
                                           text-[#08703F]
                                           hover:bg-[#08703F]
                                           hover:text-white
                                           flex items-center justify-center
                                           text-[22px]
                                           transition"
                                >
                                    <i class="fa-solid fa-check"></i>
                                </button>


                                <a
                                    href="{{ route('organisasi.kunjungan.detail') }}"
                                    class="min-w-[145px]
                                           h-[56px]
                                           inline-flex
                                           items-center
                                           justify-center
                                           border-2 border-[#08703F]
                                           text-[#08703F]
                                           hover:bg-[#08703F]
                                           hover:text-white
                                           rounded-[14px]
                                           px-7
                                           text-[21px]
                                           font-semibold
                                           transition"
                                >
                                    Detail
                                </a>

                            </div>

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