@use('App\Support\OrgFormat')

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

        <div class="w-full org-container px-6 sm:px-8 lg:px-12">

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
           org-container px-6 sm:px-8 lg:px-12
           py-20"
>

    {{-- =====================================================
        SEARCH + FILTER
    ===================================================== --}}
    <section>

        <form
            method="GET"
            action="{{ route('organisasi.kampanye') }}"
            class="flex flex-col lg:flex-row lg:items-end gap-8"
        >

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
                        name="q"
                        type="text"
                        value="{{ request('q') }}"
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
                        @foreach (\App\Http\Controllers\Organizations\CampaignController::STATUS_FILTERS as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>
                                {{ OrgFormat::statusLabel('campaign', $status) }}
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

        </form>



        {{-- TAMBAH CAMPAIGN --}}
        <div class="flex justify-end mt-10">

            <button
                type="button"
                class="bg-[#08703F]
                       hover:bg-[#065D35]
                       text-white
                       px-8 py-5
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

        <div class="space-y-10">

            @forelse ($campaigns as $campaign)
                @include('organisasi.partials.campaign-card', ['campaign' => $campaign, 'organization' => $organization])
            @empty
                <div class="bg-white rounded-[24px] shadow-md border border-gray-100 px-9 py-14 text-center text-[23px] text-gray-500">
                    {{ request()->filled('q') || request()->filled('status') ? 'Tidak ada kampanye yang sesuai dengan pencarian.' : 'Belum ada kampanye yang dibuat.' }}
                </div>
            @endforelse

        </div>

    </section>



    @include('organisasi.partials.pagination', ['paginator' => $campaigns])

</main>

@endsection