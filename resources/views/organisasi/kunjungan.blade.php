@use('App\Support\OrgFormat')

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

        <div class="w-full org-container px-6 sm:px-8 lg:px-12">

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
           org-container px-6 sm:px-8 lg:px-12
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

        <form
            method="GET"
            action="{{ route('organisasi.kunjungan') }}"
            class="grid grid-cols-1 lg:grid-cols-3 gap-8"
        >

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
                        name="q"
                        type="text"
                        value="{{ request('q') }}"
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
                        name="status"
                        onchange="this.form.submit()"
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
                        <option value="">Semua status</option>
                        @foreach (\App\Http\Controllers\Organizations\OrganizationVisitController::STATUS_FILTERS as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>
                                {{ OrgFormat::statusLabel('visit', $status) }}
                            </option>
                        @endforeach
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
                    name="tanggal"
                    type="date"
                    value="{{ request('tanggal') }}"
                    onchange="this.form.submit()"
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

        </form>

    </section>



    {{-- =====================================================
        LIST KUNJUNGAN
    ===================================================== --}}
    <section class="mt-16">

        <h2
            class="text-[35px]
                   lg:text-[45px]
                   font-bold
                   text-[#16735F]"
        >
            Daftar Kunjungan
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

                    @forelse ($visits as $visit)
                        <tr class="hover:bg-[#F0F8F4] transition">

                            <td class="px-7 py-7 text-center font-medium">
                                {{ $visit->donor?->user?->nama ?? '-' }}
                            </td>

                            <td class="px-7 py-7 text-center">
                                {{ OrgFormat::date($visit->tanggal_kunjungan) }}
                            </td>

                            <td class="px-7 py-7 text-center">
                                {{ $visit->pengunjung }}
                            </td>

                            <td class="px-7 py-7">

                                <div class="flex items-center justify-center gap-4">

                                    @if ($visit->status === 'terkirim')
                                        <button
                                            type="button"
                                            data-respond-url="{{ route('organisasi.kunjungan.respond', $visit->id) }}"
                                            data-visit-status="ditolak"
                                            data-visitor="{{ $visit->donor?->user?->nama ?? 'donatur' }}"
                                            data-visit-date="{{ OrgFormat::longDate($visit->tanggal_kunjungan) }}"
                                            aria-label="Tolak kunjungan"
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
                                            data-respond-url="{{ route('organisasi.kunjungan.respond', $visit->id) }}"
                                            data-visit-status="dikonfirmasi"
                                            data-visitor="{{ $visit->donor?->user?->nama ?? 'donatur' }}"
                                            data-visit-date="{{ OrgFormat::longDate($visit->tanggal_kunjungan) }}"
                                            aria-label="Terima kunjungan"
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
                                    @else
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
                                    @endif


                                    <a
                                        href="{{ route('organisasi.kunjungan.detail', $visit->id) }}"
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
                    @empty
                        <tr>
                            <td colspan="4" class="px-7 py-10 text-center text-gray-500">
                                Belum ada permintaan kunjungan.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </section>



    @include('organisasi.partials.pagination', ['paginator' => $visits])


    @include('organisasi.partials.visit-confirm-modal')

</main>

@endsection
