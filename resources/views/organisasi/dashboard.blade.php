@use('App\Support\OrgFormat')

@extends('layouts.organization', [
    'title' => 'Dashboard Organisasi',
    'activeNav' => 'dashboard'
])

@section('content')

{{-- =========================================================
    HERO + STATISTICS
========================================================= --}}
<section class="relative bg-[#16735F] overflow-hidden">

    {{-- HERO --}}
    <div class="relative h-[610px]">

        <img
            src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2400&auto=format&fit=crop"
            alt="Anak-anak"
            class="absolute inset-0 w-full h-full object-cover"
        >

        <div class="absolute inset-0 bg-black/40"></div>

        <div class="relative z-10 h-full flex items-center">

            <div class="w-full org-container px-6 sm:px-8 lg:px-12">

                <h1
                    class="text-white
                           text-[62px]
                           lg:text-[50px]
                           xl:text-[60px]
                           font-bold
                           leading-[1.08]"
                >
                    Selamat Datang, {{ $organization->nama_lembaga }}
                </h1>

                <p
                    class="mt-7
                           text-white/95
                           text-[28px]
                           lg:text-[31px]
                           font-medium"
                >
                    Kelola kampanye dan pantau donasi anda disini
                </p>

            </div>

        </div>

    </div>


    {{-- STATISTIC CARDS --}}
    <div class="w-full org-container px-6 sm:px-8 lg:px-12 py-14">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            {{-- CARD 1 --}}
            <div
                class="bg-white
                       rounded-[24px]
                       h-[220px]
                       px-10 py-9
                       shadow-md
                       flex flex-col justify-between"
            >

                <div
                    class="w-[62px] h-[62px]
                           rounded-[16px]
                           bg-[#DDF0E9]
                           text-[#0E6B53]
                           flex items-center justify-center"
                >
                    <i class="fa-solid fa-bullhorn text-[27px]"></i>
                </div>

                <div>

                    <p class="mt-3 text-[24px] text-gray-700 font-medium">
                        Kampanye Aktif
                    </p>

                    <p class="mt-3 text-[35px] font-bold leading-none">
                        {{ $stats['kampanye_aktif'] }}
                    </p>

                </div>

            </div>


            {{-- CARD 2 --}}
            <div
                class="bg-white
                       rounded-[24px]
                       h-[220px]
                       px-10 py-9
                       shadow-md
                       flex flex-col justify-between"
            >

                <div
                    class="w-[62px] h-[62px]
                           rounded-[16px]
                           bg-[#DDF0E9]
                           text-[#0E6B53]
                           flex items-center justify-center"
                >
                    <i class="fa-solid fa-hand-holding-dollar text-[27px]"></i>
                </div>

                <div>

                    <p class="mt-3 text-[24px] text-gray-700 font-medium">
                        Total Donasi Masuk
                    </p>

                    <p class="mt-3 text-[35px] font-bold leading-none whitespace-nowrap">
                        {{ OrgFormat::rupiah($stats['total_donasi']) }}
                    </p>

                </div>

            </div>


            {{-- CARD 3 --}}
            <div
                class="bg-white
                       rounded-[24px]
                       h-[220px]
                       px-10 py-9
                       shadow-md
                       flex flex-col justify-between"
            >

                <div
                    class="w-[62px] h-[62px]
                           rounded-[16px]
                           bg-[#DDF0E9]
                           text-[#0E6B53]
                           flex items-center justify-center"
                >
                    <i class="fa-regular fa-calendar-check text-[27px]"></i>
                </div>

                <div>

                    <p class="mt-3 text-[24px] text-gray-700 font-medium">
                        Kunjungan Menunggu
                    </p>

                    <p class="mt-3 text-[35px] font-bold leading-none">
                        {{ $stats['kunjungan_menunggu'] }}
                    </p>

                </div>

            </div>


            {{-- CARD 4 --}}
            <div
                class="bg-white
                       rounded-[24px]
                       h-[220px]
                       px-10 py-9
                       shadow-md
                       flex flex-col justify-between"
            >

                <div
                    class="w-[62px] h-[62px]
                           rounded-[16px]
                           bg-[#DDF0E9]
                           text-[#0E6B53]
                           flex items-center justify-center"
                >
                    <i class="fa-solid fa-wallet text-[27px]"></i>
                </div>

                <div>

                    <p class="mt-3 text-[24px] text-gray-700 font-medium">
                        Saldo Tersedia
                    </p>

                    <p class="mt-3 text-[35px] font-bold leading-none whitespace-nowrap">
                        {{ OrgFormat::rupiah($stats['saldo_tersedia']) }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- =========================================================
    MAIN CONTENT
========================================================= --}}
<main
    class="w-full
           org-container px-6 sm:px-8 lg:px-12
           py-20
           space-y-20"
>

    {{-- =====================================================
        KAMPANYE
    ===================================================== --}}
    <section>

        <div class="flex items-center justify-between mb-10">

            <h2 class="text-[35px] lg:text-[45px] font-bold text-[#16735F]">
                Kampanye
            </h2>

            <a
                href="{{ route('organisasi.kampanye') }}"
                class="bg-[#08703F]
                       hover:bg-[#065D35]
                       text-white
                       px-8 py-4
                       rounded-[16px]
                       text-[24px]
                       font-semibold
                       transition"
            >
                Lihat Semua
            </a>

        </div>


        <div class="space-y-8">

            @forelse ($campaigns as $campaign)
                @include('organisasi.partials.campaign-card', ['campaign' => $campaign, 'organization' => $organization])
            @empty
                <div class="bg-white rounded-[24px] shadow-md border border-gray-100 px-9 py-14 text-center text-[23px] text-gray-500">
                    Belum ada kampanye yang dibuat.
                </div>
            @endforelse

        </div>

    </section>



    {{-- =====================================================
        DONASI TERBARU
    ===================================================== --}}
    <section>

        <div class="flex items-center justify-between mb-9">

            <h2 class="text-[35px] lg:text-[45px] font-bold text-[#16735F]">
                Donasi Terbaru
            </h2>

            <a
                href="{{ route('organisasi.donasi') }}"
                class="bg-[#08703F]
                       hover:bg-[#065D35]
                       text-white
                       px-8 py-4
                       rounded-[16px]
                       text-[24px]
                       font-semibold
                       transition"
            >
                Lihat Semua
            </a>

        </div>


        <div
            class="bg-white
                   rounded-[22px]
                   shadow-md
                   overflow-hidden
                   overflow-x-auto"
        >

            <table class="w-full min-w-[1050px] table-fixed">

                <thead class="bg-[#08703F] text-white">

                    <tr class="text-[24px]">

                        <th class="w-[20%] px-8 py-6 text-center font-semibold">
                            Donatur
                        </th>

                        <th class="w-[30%] px-8 py-6 text-center font-semibold">
                            Kampanye
                        </th>

                        <th class="w-[25%] px-8 py-6 text-center font-semibold">
                            Donasi
                        </th>

                        <th class="w-[25%] px-8 py-6 text-center font-semibold">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="text-[23px] text-gray-900">

                    @forelse ($latestDonations as $donation)
                        <tr
                            onclick="window.location='{{ route('organisasi.donasi.detail', $donation->id) }}'"
                            class="cursor-pointer hover:bg-[#EAF7F1] transition"
                        >
                            <td class="px-8 py-6 text-center font-medium">
                                {{ $donation->anonim ? 'Anonim' : ($donation->donor?->user?->nama ?? '-') }}
                            </td>
                            <td class="px-8 py-6 text-center">{{ $donation->campaign?->judul ?? '-' }}</td>
                            <td class="px-8 py-6 text-center">{{ OrgFormat::rupiah($donation->nominal) }},-</td>
                            <td class="px-8 py-6 text-center {{ OrgFormat::statusText('donation', $donation->status) }} font-semibold">
                                {{ OrgFormat::statusLabel('donation', $donation->status) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-10 text-center text-gray-500">Belum ada donasi masuk.</td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </section>



    {{-- =====================================================
        KUNJUNGAN
    ===================================================== --}}
    <section>

        <div class="flex items-center justify-between mb-9">

            <h2 class="text-[35px] lg:text-[45px] font-bold text-[#16735F]">
                Kunjungan
            </h2>

            <a
                href="{{ route('organisasi.kunjungan') }}"
                class="bg-[#08703F]
                       hover:bg-[#065D35]
                       text-white
                       px-8 py-4
                       rounded-[16px]
                       text-[24px]
                       font-semibold
                       transition"
            >
                Lihat Semua
            </a>

        </div>


        <div
            class="bg-white
                   rounded-[22px]
                   shadow-md
                   overflow-hidden
                   overflow-x-auto"
        >

            <table class="w-full min-w-[1050px] table-fixed">

                <thead class="bg-[#08703F] text-white">

                    <tr class="text-[24px]">

                        <th class="w-[25%] px-8 py-6 text-center font-semibold">
                            Nama
                        </th>

                        <th class="w-[30%] px-8 py-6 text-center font-semibold">
                            Tanggal
                        </th>

                        <th class="w-[20%] px-8 py-6 text-center font-semibold">
                            Orang
                        </th>

                        <th class="w-[25%] px-8 py-6 text-center font-semibold">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="text-[23px] text-gray-900">

                    @forelse ($latestVisits as $visit)
                        <tr
                            onclick="window.location='{{ route('organisasi.kunjungan.detail', $visit->id) }}'"
                            class="cursor-pointer hover:bg-[#EAF7F1] transition"
                        >
                            <td class="px-8 py-6 text-center font-medium">{{ $visit->donor?->user?->nama ?? '-' }}</td>
                            <td class="px-8 py-6 text-center">{{ OrgFormat::date($visit->tanggal_kunjungan) }}</td>
                            <td class="px-8 py-6 text-center">{{ $visit->pengunjung }}</td>
                            <td class="px-8 py-6 text-center {{ OrgFormat::statusText('visit', $visit->status) }} font-semibold">
                                {{ OrgFormat::statusLabel('visit', $visit->status) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-10 text-center text-gray-500">Belum ada permintaan kunjungan.</td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</main>

@endsection  