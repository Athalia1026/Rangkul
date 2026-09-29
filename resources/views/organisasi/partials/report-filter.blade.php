{{-- Filter periode laporan. Butuh $filters dan $campaignOptions dari OrganizationReportController. --}}
<section
    class="bg-white
           rounded-[26px]
           shadow-md
           border border-gray-100
           px-10
           py-10"
>

    {{-- TITLE --}}
    <div class="flex items-center gap-5">

        <div
            class="w-[62px]
                   h-[62px]
                   rounded-[16px]
                   bg-[#DDF0E9]
                   text-[#08703F]
                   flex items-center justify-center"
        >
            <i class="fa-solid fa-calendar-days text-[28px]"></i>
        </div>


        <h2
            class="text-[38px]
                   lg:text-[42px]
                   font-bold
                   text-gray-950"
        >
            Periode Laporan
        </h2>

    </div>


    <form method="GET" action="{{ route('organisasi.laporan.hasil') }}">

        {{-- FILTER --}}
        <div
            class="grid grid-cols-1
                   lg:grid-cols-3
                   gap-8
                   mt-9"
        >

            {{-- TANGGAL AWAL --}}
            <div>

                <label
                    for="tanggalAwal"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-gray-900"
                >
                    Tanggal Awal
                </label>


                <div class="relative">

                    <i
                        class="fa-regular fa-calendar-days
                               absolute
                               left-6
                               top-1/2
                               -translate-y-1/2
                               text-[26px]
                               text-gray-700
                               pointer-events-none"
                    ></i>


                    <input
                        id="tanggalAwal"
                        name="tanggal_awal"
                        type="{{ $filters['start'] ? 'date' : 'text' }}"
                        value="{{ $filters['start']?->format('Y-m-d') }}"
                        placeholder="Pilih tanggal awal"
                        onfocus="this.type='date'"
                        onblur="if(!this.value)this.type='text'"
                        class="w-full
                               h-[82px]
                               pl-[65px]
                               pr-6
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



            {{-- TANGGAL AKHIR --}}
            <div>

                <label
                    for="tanggalAkhir"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-gray-900"
                >
                    Tanggal Akhir
                </label>


                <div class="relative">

                    <i
                        class="fa-regular fa-calendar-days
                               absolute
                               left-6
                               top-1/2
                               -translate-y-1/2
                               text-[26px]
                               text-gray-700
                               pointer-events-none"
                    ></i>


                    <input
                        id="tanggalAkhir"
                        name="tanggal_akhir"
                        type="{{ $filters['end'] ? 'date' : 'text' }}"
                        value="{{ $filters['end']?->format('Y-m-d') }}"
                        placeholder="Pilih tanggal akhir"
                        onfocus="this.type='date'"
                        onblur="if(!this.value)this.type='text'"
                        class="w-full
                               h-[82px]
                               pl-[65px]
                               pr-6
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



            {{-- CAMPAIGN --}}
            <div>

                <label
                    for="campaign"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-gray-900"
                >
                    Campaign
                </label>


                <div class="relative">

                    <select
                        id="campaign"
                        name="campaign"
                        class="w-full
                               h-[82px]
                               appearance-none
                               bg-white
                               border border-gray-300
                               rounded-[16px]
                               px-6
                               pr-16
                               text-[23px]
                               text-gray-800
                               outline-none
                               focus:border-[#08703F]
                               focus:ring-2
                               focus:ring-[#08703F]/10"
                    >

                        <option value="">
                            Semua campaign
                        </option>

                        @foreach ($campaignOptions as $option)
                            <option value="{{ $option->id }}" @selected($filters['campaign'] === $option->id)>
                                {{ $option->judul }}
                            </option>
                        @endforeach

                    </select>


                    <i
                        class="fa-solid fa-chevron-down
                               absolute
                               right-6
                               top-1/2
                               -translate-y-1/2
                               text-[20px]
                               text-gray-700
                               pointer-events-none"
                    ></i>

                </div>

            </div>

        </div>


        @if ($errors->any())
            <p class="mt-6 text-[20px] text-red-500">{{ $errors->first() }}</p>
        @endif


        {{-- BUTTONS --}}
        <div
            class="flex
                   flex-wrap
                   items-center
                   gap-5
                   mt-10"
        >

            <button
                type="submit"
                class="min-w-[190px]
                       h-[76px]
                       inline-flex
                       items-center
                       justify-center
                       border-2 border-[#08703F]
                       bg-white
                       text-[#08703F]
                       rounded-[16px]
                       text-[23px]
                       font-semibold
                       hover:bg-[#F0F8F4]
                       transition"
            >
                Tampilkan
            </button>


            <button
                type="submit"
                formaction="{{ route('organisasi.laporan.download') }}"
                class="min-w-[190px]
                       h-[76px]
                       bg-[#08703F]
                       text-white
                       rounded-[16px]
                       text-[23px]
                       font-semibold
                       hover:bg-[#065D35]
                       transition"
            >
                Download
            </button>

        </div>

    </form>

</section>
