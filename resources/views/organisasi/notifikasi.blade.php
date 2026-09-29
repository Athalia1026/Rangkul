@extends('layouts.organization', [
    'title' => 'Notifikasi',
    'activeNav' => ''
])

@section('content')

<main
    class="w-full
           org-container px-6 sm:px-8 lg:px-12
           py-20"
>

    {{-- =====================================================
        NOTIFICATION WRAPPER
    ===================================================== --}}
    <section
        class="max-w-[1550px]
               mx-auto
               bg-white
               rounded-[28px]
               shadow-sm
               border border-gray-100
               px-10
               lg:px-14
               py-12"
    >

        {{-- =================================================
            TOP
        ================================================= --}}
        <div class="relative">

            @php
                $backUrl = url()->previous() !== url()->current() ? url()->previous() : route('organisasi.dashboard');
            @endphp

            <a
                href="{{ $backUrl }}"
                class="inline-flex items-center gap-3
                       text-[22px]
                       text-gray-600
                       hover:text-[#08703F]
                       transition"
            >
                <i class="fa-solid fa-arrow-left text-[16px]"></i>
                Kembali
            </a>


            <h1
                class="mt-10
                       lg:mt-0
                       lg:absolute
                       lg:left-1/2
                       lg:top-1/2
                       lg:-translate-x-1/2
                       lg:-translate-y-1/2
                       text-center
                       text-[35px]
                       lg:text-[45px]
                       font-bold
                       text-[#08703F]"
            >
                Notifikasi
            </h1>

        </div>



        @php
            $notificationIcons = [
                'campaign'          => ['fa-solid fa-bullhorn', 'bg-[#FFF4D9] text-[#B67A00]'],
                'donation'          => ['fa-solid fa-hand-holding-dollar', 'bg-[#DDF0E9] text-[#08703F]'],
                'visit'             => ['fa-solid fa-calendar-day', 'bg-[#E8F4FB] text-[#0B4F75]'],
                'fund_disbursement' => ['fa-solid fa-wallet', 'bg-[#FFF4D9] text-[#B67A00]'],
                'purchase_proof'    => ['fa-regular fa-file-lines', 'bg-[#E8F4FB] text-[#0B4F75]'],
            ];
        @endphp

        @forelse ($groups as $label => $notifications)

            <div class="{{ $loop->first ? 'mt-20' : 'mt-14' }}">

                <h2
                    class="text-[27px]
                           font-semibold
                           text-gray-600"
                >
                    {{ $label }}
                </h2>


                <div class="mt-7 space-y-5">

                    @foreach ($notifications as $notification)
                        @php([$icon, $iconClass] = $notificationIcons[$notification->reference_type] ?? ['fa-regular fa-circle-check', 'bg-[#DDF0E9] text-[#08703F]'])

                        <div
                            class="w-full
                                   min-h-[135px]
                                   {{ $notification->is_read ? 'bg-white' : 'bg-[#F0F8F4]' }}
                                   border border-gray-300
                                   rounded-[20px]
                                   shadow-md
                                   px-8
                                   py-6
                                   flex
                                   items-center
                                   gap-6"
                        >

                            <div
                                class="w-[72px]
                                       h-[72px]
                                       shrink-0
                                       rounded-full
                                       {{ $iconClass }}
                                       flex
                                       items-center
                                       justify-center"
                            >
                                <i class="{{ $icon }} text-[30px]"></i>
                            </div>


                            <div class="flex-1 min-w-0">

                                <h3
                                    class="text-[27px]
                                           font-semibold
                                           text-gray-950"
                                >
                                    {{ $notification->judul }}
                                </h3>


                                <p
                                    class="mt-2
                                           text-[21px]
                                           text-gray-700"
                                >
                                    {{ $notification->deskripsi }}
                                </p>

                            </div>


                            <span
                                class="shrink-0
                                       text-[21px]
                                       text-gray-500"
                            >
                                {{ \Carbon\Carbon::parse($notification->created_at)->format('H:i') }}
                            </span>

                        </div>
                    @endforeach

                </div>

            </div>

        @empty

            <p class="mt-20 text-center text-[24px] text-gray-500">
                Belum ada notifikasi.
            </p>

        @endforelse

    </section>

</main>

@endsection