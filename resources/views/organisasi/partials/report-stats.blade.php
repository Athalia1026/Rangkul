@use('App\Support\OrgFormat')

{{-- Kartu statistik laporan. Butuh $stats dari OrganizationReportController. --}}
@php
    $cards = [
        ['icon' => 'fa-hand-holding-dollar', 'title' => 'Donasi Uang', 'value' => OrgFormat::rupiah($stats['total_donasi']), 'caption' => 'Total Uang Terkumpul'],
        ['icon' => 'fa-wallet', 'title' => 'Total Pencairan', 'value' => OrgFormat::rupiah($stats['total_pencairan']), 'caption' => 'Jumlah Uang Pencairan'],
        ['icon' => 'fa-wallet', 'title' => 'Saldo Tersisa', 'value' => OrgFormat::rupiah($stats['saldo_tersisa']), 'caption' => 'Jumlah Uang Tersisa'],
        ['icon' => 'fa-users', 'title' => 'Total Donatur', 'value' => $stats['total_donatur'] . ' Orang', 'caption' => 'Sebagai Donatur'],
    ];
@endphp

<section>

    <div
        class="grid grid-cols-1
               sm:grid-cols-2
               xl:grid-cols-4
               gap-8"
    >

        @foreach ($cards as $card)
            <div
                class="bg-white
                       rounded-[24px]
                       shadow-md
                       border border-gray-100
                       min-h-[225px]
                       overflow-hidden
                       p-5"
            >

                <div
                    class="bg-[#08703F]
                           text-white
                           min-h-[70px]
                           rounded-[16px]
                           px-6
                           flex items-center
                           gap-4"
                >

                    <i class="fa-solid {{ $card['icon'] }} text-[27px]"></i>

                    <span class="text-[23px] font-semibold">
                        {{ $card['title'] }}
                    </span>

                </div>


                <div class="px-2 pt-7">

                    <p
                        class="text-[35px]
                               lg:text-[38px]
                               font-bold
                               text-gray-950"
                    >
                        {{ $card['value'] }}
                    </p>


                    <p
                        class="mt-3
                               text-[22px]
                               text-gray-600"
                    >
                        {{ $card['caption'] }}
                    </p>

                </div>

            </div>
        @endforeach

    </div>

</section>
