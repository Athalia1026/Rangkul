@use('App\Support\OrgFormat')

@extends('layouts.organization', [
    'title' => 'Hasil Laporan',
    'activeNav' => 'laporan'
])

@section('content')

{{-- =========================================================
    HERO
========================================================= --}}
<section class="relative h-[500px] overflow-hidden">

    <img
        src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2400&auto=format&fit=crop"
        alt="Laporan"
        class="absolute inset-0 w-full h-full object-cover"
    >

    <div class="absolute inset-0 bg-black/45"></div>

    <div class="relative z-10 h-full flex items-center">

        <div class="w-full org-container px-6 sm:px-8 lg:px-12">

            <h1
                class="text-white
                       text-[62px]
                       lg:text-[70px]
                       xl:text-[76px]
                       font-bold
                       leading-[1.08]"
            >
                Laporan
            </h1>

            <p
                class="mt-7
                       text-white/95
                       text-[28px]
                       lg:text-[31px]
                       font-medium"
            >
                Lihat ringkasan aktivitas dan unduh laporan campaign.
            </p>

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
           space-y-16"
>

    @include('organisasi.partials.report-filter')



    @include('organisasi.partials.report-stats')



    {{-- =====================================================
        GRAFIK DONASI BULANAN
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[26px]
               shadow-md
               border border-gray-100
               px-8 py-9"
    >

        <div class="flex items-center gap-5 mb-9">

            <div
                class="w-[58px]
                       h-[58px]
                       rounded-[14px]
                       bg-[#DDF0E9]
                       text-[#08703F]
                       flex items-center justify-center"
            >
                <i class="fa-solid fa-bars text-[26px]"></i>
            </div>


            <h2
                class="text-[42px]
                       lg:text-[46px]
                       font-bold
                       text-[#16735F]"
            >
                Grafik Donasi Bulanan
            </h2>

        </div>


        <div
            class="bg-[#F8FAF9]
                   rounded-[20px]
                   border border-gray-100
                   px-8
                   py-12"
        >

            <div
                class="grid
                       gap-8
                       items-end
                       min-h-[320px]"
                style="grid-template-columns: repeat({{ count($monthlyChart) }}, minmax(0, 1fr))"
            >

                @foreach ($monthlyChart as $month)
                    <div class="flex flex-col items-center justify-end h-full">

                        <span class="text-[18px] font-semibold text-[#08703F] mb-3 whitespace-nowrap">
                            {{ OrgFormat::rupiah($month['total']) }}
                        </span>

                        <div
                            class="w-[52px]
                                   bg-[#08703F]
                                   rounded-t-[8px]"
                            style="height: {{ max(4, $month['height']) }}px"
                        ></div>

                        <span class="mt-4 text-[20px] text-gray-700">
                            {{ $month['label'] }}
                        </span>

                    </div>
                @endforeach

            </div>

        </div>

    </section>



    {{-- =====================================================
        DETAIL DONASI MASUK
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[26px]
               shadow-md
               border border-gray-100
               px-8 py-9"
    >

        <div class="flex items-center gap-5 mb-9">

            <div
                class="w-[58px]
                       h-[58px]
                       rounded-[14px]
                       bg-[#DDF0E9]
                       text-[#08703F]
                       flex items-center justify-center"
            >
                <i class="fa-solid fa-bars text-[26px]"></i>
            </div>

            <h2
                class="text-[42px]
                       lg:text-[46px]
                       font-bold
                       text-[#16735F]"
            >
                Detail Donasi Masuk
            </h2>

        </div>


        <div
            class="rounded-[20px]
                   overflow-hidden
                   border border-gray-200
                   overflow-x-auto"
        >

            <table class="w-full min-w-[1200px] table-fixed">

                <thead class="bg-[#08703F] text-white">

                    <tr class="text-[24px]">

                        <th class="w-[7%] px-7 py-6 text-center font-semibold">
                            No
                        </th>

                        <th class="w-[30%] px-7 py-6 text-left font-semibold">
                            Donatur
                        </th>

                        <th class="w-[17%] px-7 py-6 text-center font-semibold">
                            Nominal
                        </th>

                        <th class="w-[17%] px-7 py-6 text-center font-semibold">
                            Tanggal
                        </th>

                        <th class="w-[17%] px-7 py-6 text-center font-semibold">
                            Metode
                        </th>

                        <th class="w-[12%] px-7 py-6 text-center font-semibold">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="text-[23px] text-gray-900">

                    @forelse ($donations as $donation)
                        <tr class="hover:bg-[#F0F8F4] transition">
                            <td class="px-7 py-6 text-center">{{ $donations->firstItem() + $loop->index }}</td>
                            <td class="px-7 py-6 font-medium">
                                {{ $donation->anonim ? 'Anonim' : ($donation->donor?->user?->nama ?? '-') }}
                            </td>
                            <td class="px-7 py-6 text-center">{{ OrgFormat::rupiah($donation->nominal) }}</td>
                            <td class="px-7 py-6 text-center">{{ OrgFormat::date($donation->paid_at, 'd/m/Y') }}</td>
                            <td class="px-7 py-6 text-center">{{ $donation->transaction_id ? 'Midtrans' : '-' }}</td>
                            <td class="px-7 py-6 text-center">
                                <span class="inline-flex min-w-[115px] justify-center {{ OrgFormat::statusBadge('donation', $donation->status) }} px-5 py-3 rounded-full text-[19px] font-semibold">
                                    {{ OrgFormat::statusLabel('donation', $donation->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-7 py-10 text-center text-gray-500">Belum ada donasi masuk pada periode ini.</td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </section>



    {{-- =====================================================
        RIWAYAT PENCAIRAN
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[26px]
               shadow-md
               border border-gray-100
               px-8 py-9"
    >

        <div class="flex items-center gap-5 mb-9">

            <div
                class="w-[58px]
                       h-[58px]
                       rounded-[14px]
                       bg-[#DDF0E9]
                       text-[#08703F]
                       flex items-center justify-center"
            >
                <i class="fa-solid fa-bars text-[26px]"></i>
            </div>

            <h2
                class="text-[42px]
                       lg:text-[46px]
                       font-bold
                       text-[#16735F]"
            >
                Riwayat Pencairan
            </h2>

        </div>


        <div
            class="rounded-[20px]
                   overflow-hidden
                   border border-gray-200
                   overflow-x-auto"
        >

            <table class="w-full min-w-[1100px] table-fixed">

                <thead class="bg-[#08703F] text-white">

                    <tr class="text-[24px]">

                        <th class="w-[8%] px-7 py-6 text-center font-semibold">
                            No
                        </th>

                        <th class="w-[32%] px-7 py-6 text-left font-semibold">
                            Nominal
                        </th>

                        <th class="w-[25%] px-7 py-6 text-center font-semibold">
                            Tanggal Pengajuan
                        </th>

                        <th class="w-[20%] px-7 py-6 text-center font-semibold">
                            Tanggal Cair
                        </th>

                        <th class="w-[15%] px-7 py-6 text-center font-semibold">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="text-[23px] text-gray-900">

                    @forelse ($disbursements as $disbursement)
                        <tr class="hover:bg-[#F0F8F4] transition">
                            <td class="px-7 py-6 text-center">{{ $loop->iteration }}</td>
                            <td class="px-7 py-6 font-medium">
                                {{ OrgFormat::rupiah($disbursement->nominal_dicairkan ?? $disbursement->nominal_diajukan) }}
                            </td>
                            <td class="px-7 py-6 text-center">{{ OrgFormat::date($disbursement->created_at, 'd/m/Y') }}</td>
                            <td class="px-7 py-6 text-center">{{ OrgFormat::date($disbursement->paid_at, 'd/m/Y') }}</td>
                            <td class="px-7 py-6 text-center">
                                <span class="inline-flex min-w-[125px] justify-center {{ OrgFormat::statusBadge('disbursement', $disbursement->status) }} px-5 py-3 rounded-full text-[19px] font-semibold">
                                    {{ OrgFormat::statusLabel('disbursement', $disbursement->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-7 py-10 text-center text-gray-500">Belum ada riwayat pencairan pada periode ini.</td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </section>



    @include('organisasi.partials.pagination', ['paginator' => $donations, 'class' => ''])

</main>

@endsection