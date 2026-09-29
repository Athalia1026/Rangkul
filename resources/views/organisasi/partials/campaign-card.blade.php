@use('App\Support\OrgFormat')

{{-- Card kampanye organisasi. Butuh $campaign (dengan scope withFinancialSummary) dan $organization. --}}
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
                src="{{ OrgFormat::storageUrl($campaign->foto_cover) }}"
                alt="{{ $campaign->judul }}"
                class="w-full h-[340px] md:h-full object-cover bg-gray-100"
            >

        </div>


        {{-- CONTENT --}}
        <div
            class="flex-1
                   px-9 pt-9 pb-12
                   flex flex-col justify-between"
        >

            <div>

                {{-- TITLE + STATUS --}}
                <div class="flex items-start justify-between gap-8">

                    <div>

                        <h3
                            class="text-[20px]
                                   lg:text-[30px]
                                   font-semibold
                                   text-gray-950
                                   leading-tight"
                        >
                            {{ $campaign->judul }}
                        </h3>

                        <p class="mt-3 text-[22px] text-gray-500">
                            {{ $organization->nama_lembaga }}, {{ $organization->kota }}
                        </p>

                    </div>


                    <span
                        class="shrink-0
                               min-w-[130px]
                               text-center
                               {{ OrgFormat::statusBadge('campaign', $campaign->status) }}
                               text-[21px]
                               font-semibold
                               px-7 py-3
                               rounded-full"
                    >
                        {{ OrgFormat::statusLabel('campaign', $campaign->status) }}
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
                        style="width: {{ $campaign->progressPercent() }}%"
                    ></div>

                </div>


                {{-- INFORMATION --}}
                <div
                    class="mt-7
                           flex flex-wrap
                           items-center
                           gap-x-8
                           gap-y-4
                           text-[22px]"
                >

                    <p>
                        Terkumpul :
                        <span class="text-[#087C3D] font-semibold">
                            {{ OrgFormat::rupiah($campaign->total_terkumpul) }}
                        </span>
                    </p>

                    <span class="hidden md:block h-7 w-px bg-gray-300"></span>

                    <p>
                        Target :
                        <span class="text-[#087C3D] font-semibold">
                            {{ OrgFormat::rupiah($campaign->target_dana) }}
                        </span>
                    </p>

                    <span class="hidden md:block h-7 w-px bg-gray-300"></span>

                    <p>
                        Tenggat :
                        <span class="text-[#087C3D] font-semibold">
                            {{ OrgFormat::date($campaign->tanggal_selesai) }}
                        </span>
                    </p>

                </div>

            </div>


            {{-- DETAIL --}}
            <div class="flex justify-end mt-7">

                <a
                    href="{{ route('organisasi.kampanye.detail', $campaign->id) }}"
                    class="min-w-[160px]
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
