@use('App\Support\OrgFormat')

@extends('layouts.organization', [
    'title' => 'Laporan Organisasi',
    'activeNav' => 'laporan'
])

@section('content')

{{-- =========================================================
    HERO LAPORAN
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
        RINGKASAN CAMPAIGN
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[26px]
               shadow-md
               border border-gray-100
               px-8
               py-9"
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
                Ringkasan Campaign
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

                        <th class="w-[37%] px-7 py-6 text-left font-semibold">
                            Campaign
                        </th>

                        <th class="w-[18%] px-7 py-6 text-center font-semibold">
                            Donasi Masuk
                        </th>

                        <th class="w-[17%] px-7 py-6 text-center font-semibold">
                            Pencairan
                        </th>

                        <th class="w-[15%] px-7 py-6 text-center font-semibold">
                            Saldo
                        </th>

                        <th class="w-[13%] px-7 py-6 text-center font-semibold">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="text-[23px] text-gray-900">

                    @forelse ($campaignSummaries as $campaign)
                        <tr class="hover:bg-[#F0F8F4] transition">

                            <td class="px-7 py-6 font-medium">
                                {{ $campaign->judul }}
                            </td>

                            <td class="px-7 py-6 text-center">
                                {{ OrgFormat::rupiah($campaign->total_terkumpul) }}
                            </td>

                            <td class="px-7 py-6 text-center">
                                {{ OrgFormat::rupiah($campaign->total_dicairkan) }}
                            </td>

                            <td class="px-7 py-6 text-center">
                                {{ OrgFormat::rupiah($campaign->saldoTersisa()) }}
                            </td>

                            <td class="px-7 py-6 text-center">

                                <span
                                class="inline-flex
                                       min-w-[110px]
                                       justify-center
                                       {{ OrgFormat::statusBadge('campaign', $campaign->status) }}
                                       px-5 py-3
                                       rounded-full
                                       text-[19px]
                                       font-semibold"
                            >
                                {{ OrgFormat::statusLabel('campaign', $campaign->status) }}
                            </span>

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-7 py-10 text-center text-gray-500">Belum ada kampanye.</td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </section>



    {{-- =====================================================
        RINGKASAN BOOKING
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[26px]
               shadow-md
               border border-gray-100
               px-8
               py-9"
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
                Ringkasan Booking
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

                        <th class="w-[52%] px-7 py-6 text-left font-semibold">
                            Nama
                        </th>

                        <th class="w-[20%] px-7 py-6 text-center font-semibold">
                            Tanggal
                        </th>

                        <th class="w-[15%] px-7 py-6 text-center font-semibold">
                            Jumlah Orang
                        </th>

                        <th class="w-[13%] px-7 py-6 text-center font-semibold">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="text-[23px] text-gray-900">

                    @forelse ($visits as $visit)
                        <tr class="hover:bg-[#F0F8F4] transition">

                            <td class="px-7 py-6 font-medium">
                                {{ $visit->donor?->user?->nama ?? '-' }}
                            </td>

                            <td class="px-7 py-6 text-center">
                                {{ OrgFormat::date($visit->tanggal_kunjungan) }}
                            </td>

                            <td class="px-7 py-6 text-center">
                                {{ $visit->pengunjung }}
                            </td>

                            <td class="px-7 py-6 text-center">

                                <span
                                class="inline-flex
                                       min-w-[125px]
                                       justify-center
                                       {{ OrgFormat::statusBadge('visit', $visit->status) }}
                                       px-5 py-3
                                       rounded-full
                                       text-[19px]
                                       font-semibold"
                            >
                                {{ OrgFormat::statusLabel('visit', $visit->status) }}
                            </span>

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-7 py-10 text-center text-gray-500">Belum ada kunjungan pada periode ini.</td>
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
               px-8
               py-9"
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

                        <th class="w-[50%] px-7 py-6 text-left font-semibold">
                            Kampanye
                        </th>

                        <th class="w-[20%] px-7 py-6 text-center font-semibold">
                            Nominal
                        </th>

                        <th class="w-[17%] px-7 py-6 text-center font-semibold">
                            Tanggal
                        </th>

                        <th class="w-[13%] px-7 py-6 text-center font-semibold">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="text-[23px] text-gray-900">

                    @forelse ($disbursements as $disbursement)
                        <tr class="hover:bg-[#F0F8F4] transition">

                            <td class="px-7 py-6 font-medium">
                                {{ $disbursement->campaign?->judul ?? '-' }}
                            </td>

                            <td class="px-7 py-6 text-center">
                                {{ OrgFormat::rupiah($disbursement->nominal_dicairkan ?? $disbursement->nominal_diajukan) }}
                            </td>

                            <td class="px-7 py-6 text-center">
                                {{ OrgFormat::date($disbursement->created_at) }}
                            </td>

                            <td class="px-7 py-6 text-center">

                                <span
                                class="inline-flex
                                       min-w-[125px]
                                       justify-center
                                       {{ OrgFormat::statusBadge('disbursement', $disbursement->status) }}
                                       px-5 py-3
                                       rounded-full
                                       text-[19px]
                                       font-semibold"
                            >
                                {{ OrgFormat::statusLabel('disbursement', $disbursement->status) }}
                            </span>

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-7 py-10 text-center text-gray-500">Belum ada riwayat pencairan.</td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</main>

@endsection