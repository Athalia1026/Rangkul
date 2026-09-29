@use('App\Support\OrgFormat')

@extends('layouts.organization', [
    'title' => 'Detail Donasi',
    'activeNav' => 'donasi'
])

@section('content')

    <main class="w-full
               org-container px-6 sm:px-8 lg:px-12
               pt-16 pb-24">

        {{-- =====================================================
        BACK
        ===================================================== --}}
        <a href="{{ route('organisasi.donasi') }}" class="inline-flex items-center gap-3
                   text-[22px]
                   text-gray-600
                   hover:text-[#08703F]
                   transition">
            <i class="fa-solid fa-arrow-left text-[16px]"></i>
            Back to Donasi
        </a>



        {{-- =====================================================
        TITLE
        ===================================================== --}}
        <section class="mt-9">

            <h1 class="text-[35px]
                       lg:text-[45px]
                       xl:text-[50px]
                       font-bold
                       text-[#08703F]
                       leading-tight">
                Detail Donasi
            </h1>


            <p class="mt-5
                       text-[15px]
                       lg:text-[25px]
                       text-gray-500
                       font-medium">
                Informasi lengkap mengenai donasi yang telah diterima.
            </p>

        </section>



        {{-- =====================================================
        DETAIL CARD
        ===================================================== --}}
        <section class="mt-14">

            <div class="bg-white
                       rounded-[28px]
                       border border-gray-100
                       shadow-md
                       px-10
                       lg:px-14
                       py-12">

                {{-- =================================================
                DETAIL DONASI
                ================================================= --}}
                @php
                    $labelClass = 'text-[20px] text-gray-500';
                    $valueClass = 'mt-2 text-[26px] font-semibold text-gray-950 leading-snug break-words';

                    $rows = [
                        [
                            ['label' => 'Nama Donatur', 'value' => $donation->anonim ? 'Anonim' : ($donation->donor?->user?->nama ?? '-')],
                            ['label' => 'Campaign', 'value' => $donation->campaign?->judul ?? '-'],
                        ],
                        [
                            ['label' => 'Nominal', 'value' => OrgFormat::rupiah($donation->nominal)],
                            ['label' => 'Tanggal', 'value' => OrgFormat::date($donation->paid_at ?? $donation->created_at)],
                        ],
                    ];
                @endphp

                <dl class="divide-y divide-gray-200">

                    @foreach ($rows as $row)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-7 py-8 first:pt-0">

                            @foreach ($row as $item)
                                <div>
                                    <dt class="{{ $labelClass }}">{{ $item['label'] }}</dt>
                                    <dd class="{{ $valueClass }}">{{ $item['value'] }}</dd>
                                </div>
                            @endforeach

                        </div>
                    @endforeach


                    {{-- METODE PEMBAYARAN + STATUS --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-7 py-8">

                        <div>
                            <dt class="{{ $labelClass }}">Metode Pembayaran</dt>
                            <dd class="{{ $valueClass }}">{{ $donation->transaction_id ? 'Midtrans' : '-' }}</dd>
                        </div>

                        <div>
                            <dt class="{{ $labelClass }}">Status</dt>
                            <dd class="mt-3">
                                <span
                                    class="inline-flex
                                           justify-center
                                           {{ OrgFormat::statusBadge('donation', $donation->status) }}
                                           px-6 py-2
                                           rounded-full
                                           text-[21px]
                                           font-semibold"
                                >
                                    {{ OrgFormat::statusLabel('donation', $donation->status) }}
                                </span>
                            </dd>
                        </div>

                    </div>


                    {{-- PESAN DONATUR --}}
                    <div class="py-8 last:pb-0">
                        <dt class="{{ $labelClass }}">Pesan Donatur</dt>
                        <dd class="mt-2 text-[24px] text-gray-900 leading-relaxed break-words">
                            {{ $donation->note ?: 'Tidak ada pesan.' }}
                        </dd>
                    </div>

                </dl>

                </div>

            </div>

        </section>

    </main>

@endsection