@extends('layouts.organization', [
    'title' => 'Kampanye Organisasi',
    'activeNav' => 'kampanye'
])

@section('content')

{{-- =========================================================
    HERO KAMPANYE
========================================================= --}}
<section class="relative h-[500px] overflow-hidden">

    <img
        src="https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=2200&auto=format&fit=crop"
        alt="Kampanye"
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
                Kampanye
            </h1>


            <p
                class="mt-7
                       text-white/95
                       text-[28px]
                       lg:text-[31px]
                       font-medium"
            >
                Kelola semua kampanye donasi yang dibuat oleh organisasi Anda.
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
        SEARCH + FILTER
    ===================================================== --}}
    <section>

        <div class="flex flex-col lg:flex-row lg:items-end gap-8">

            {{-- SEARCH --}}
            <div class="flex-1">

                <label
                    for="searchCampaign"
                    class="block mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Cari Kampanye
                </label>


                <div class="relative">

                    <i
                        class="fa-solid fa-magnifying-glass
                               absolute left-6 top-1/2 -translate-y-1/2
                               text-[24px]
                               text-gray-500"
                    ></i>


                    <input
                        id="searchCampaign"
                        type="text"
                        placeholder="Cari nama kampanye..."
                        class="w-full
                               h-[82px]
                               pl-16 pr-6
                               bg-white
                               border border-gray-300
                               rounded-[16px]
                               text-[23px]
                               text-gray-800
                               placeholder:text-gray-400
                               shadow-sm
                               outline-none
                               focus:border-[#08703F]
                               focus:ring-2
                               focus:ring-[#08703F]/10
                               transition"
                    >

                </div>

            </div>



            {{-- FILTER --}}
            <div class="w-full lg:w-[390px]">

                <label
                    for="statusCampaign"
                    class="block mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Filter Status
                </label>


                <div class="relative">

                    <select
                        id="statusCampaign"
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
                        <option value="aktif">Aktif</option>
                        <option value="selesai">Selesai</option>
                        <option value="tertunda">Tertunda</option>
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

        </div>



        {{-- TAMBAH CAMPAIGN --}}
        <div class="flex justify-end mt-10">

            <button
                type="button"
                class="bg-[#08703F]
                       hover:bg-[#065D35]
                       text-white
                       px-11 py-5
                       rounded-[16px]
                       text-[24px]
                       font-semibold
                       transition"
            >
                Tambah Kampanye
            </button>

        </div>

    </section>



    {{-- =====================================================
        CAMPAIGN LIST
    ===================================================== --}}
    <section class="mt-14">

        @php
            $campaigns = [
                [
                    'title' => 'Anggaran Pembagian Beras',
                    'status' => 'Aktif',
                    'statusClass' => 'bg-[#D7EFE5] text-[#10765B]',
                    'amount' => 'Rp 500.000',
                    'target' => 'Rp 1.000.000',
                    'date' => '26 / 01 / 2026',
                    'progress' => '50%',
                    'image' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1600'
                ],
                [
                    'title' => 'Anggaran Kebutuhan Fasilitas Kelas',
                    'status' => 'Selesai',
                    'statusClass' => 'bg-[#E4E9FA] text-[#5665A6]',
                    'amount' => 'Rp 800.000',
                    'target' => 'Rp 800.000',
                    'date' => '22 / 01 / 2026',
                    'progress' => '100%',
                    'image' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1600'
                ],
                [
                    'title' => 'Anggaran Sarapan Kecil',
                    'status' => 'Tertunda',
                    'statusClass' => 'bg-[#FFF2D5] text-gray-800',
                    'amount' => 'Rp 0',
                    'target' => 'Rp 670.000',
                    'date' => '12 / 02 / 2026',
                    'progress' => '0%',
                    'image' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1600'
                ],
                [
                    'title' => 'Anggaran Keperluan Para Guru',
                    'status' => 'Ditolak',
                    'statusClass' => 'bg-[#B43B3B] text-white',
                    'amount' => 'Rp 0',
                    'target' => 'Rp 9.000.000',
                    'date' => '10 / 03 / 2026',
                    'progress' => '0%',
                    'image' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=1600'
                ],
            ];
        @endphp


        <div class="space-y-10">

            @foreach ($campaigns as $campaign)

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
                                src="{{ $campaign['image'] }}"
                                alt="{{ $campaign['title'] }}"
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

                                        <h2
                                            class="text-[33px]
                                                   lg:text-[35px]
                                                   font-semibold
                                                   text-gray-950
                                                   leading-tight"
                                        >
                                            {{ $campaign['title'] }}
                                        </h2>


                                        <p class="mt-3 text-[24px] text-gray-500">
                                            SMA Bumi Rejo, Sidoarjo, Jawa Timur
                                        </p>

                                    </div>


                                    <span
                                        class="shrink-0
                                               min-w-[165px]
                                               text-center
                                               {{ $campaign['statusClass'] }}
                                               text-[21px]
                                               font-semibold
                                               px-7 py-3
                                               rounded-full"
                                    >
                                        {{ $campaign['status'] }}
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
                                        style="width: {{ $campaign['progress'] }}"
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
                                            {{ $campaign['amount'] }}
                                        </span>
                                    </p>


                                    <span class="hidden md:block h-7 w-px bg-gray-300"></span>


                                    <p>
                                        Target :
                                        <span class="text-[#087C3D] font-semibold">
                                            {{ $campaign['target'] }}
                                        </span>
                                    </p>


                                    <span class="hidden md:block h-7 w-px bg-gray-300"></span>


                                    <p>
                                        Tenggat :
                                        <span class="text-[#087C3D] font-semibold">
                                            {{ $campaign['date'] }}
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

            @endforeach

        </div>

    </section>



    {{-- =====================================================
        PAGINATION
    ===================================================== --}}
    <section class="flex justify-center mt-16">

        <nav class="flex flex-wrap items-center justify-center gap-5">

            {{-- PREVIOUS --}}
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



            {{-- PAGE NUMBERS --}}
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



            {{-- NEXT --}}
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