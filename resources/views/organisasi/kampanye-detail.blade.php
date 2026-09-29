@use('App\Support\OrgFormat')

@extends('layouts.organization', [
    'title' => 'Detail Kampanye',
    'activeNav' => 'kampanye'
])

@section('content')

<main
    class="w-full
           org-container px-6 sm:px-8 lg:px-12
           pt-16 pb-24"
>

    {{-- =====================================================
        BACK
    ===================================================== --}}
    <a
        href="{{ route('organisasi.kampanye') }}"
        class="inline-flex
               items-center
               gap-3
               text-[22px]
               text-gray-600
               hover:text-[#08703F]
               transition
               "
    >
        <i class="fa-solid fa-arrow-left text-[16px]"></i>
        Kembali ke Kampanye
    </a>


    {{-- =====================================================
        TITLE
    ===================================================== --}}
    <section class="mt-9">

        <div class="flex flex-wrap items-center gap-6">

            <h1
                class="text-[54px]
                       lg:text-[60px]
                       xl:text-[64px]
                       font-bold
                       text-gray-950
                       leading-tight"
            >
                {{ $campaign->judul }}
            </h1>

            <span
                class="{{ OrgFormat::statusBadge('campaign', $campaign->status) }}
                       min-w-[120px]
                       text-center
                       px-7 py-3
                       rounded-full
                       text-[21px]
                       font-semibold"
            >
                {{ OrgFormat::statusLabel('campaign', $campaign->status) }}
            </span>

        </div>

        <p
            class="mt-4
                   text-[26px]
                   lg:text-[28px]
                   text-[#16735F]
                   font-medium"
        >
            {{ $organization->nama_lembaga }}, {{ $organization->kota }}
        </p>

    </section>


    {{-- =====================================================
        IMAGE + PROGRESS
    ===================================================== --}}
    <section
        class="grid grid-cols-1
               lg:grid-cols-[1.45fr_1fr]
               gap-10
               mt-14"
    >

        {{-- IMAGE --}}
        {{-- Di layar lebar gambar mengikuti tinggi kartu progres (posisi absolut) agar tidak ada ruang kosong --}}
        <div class="relative h-[400px] lg:h-auto rounded-[26px] overflow-hidden shadow-sm bg-gray-100">

            <img
                src="{{ OrgFormat::storageUrl($campaign->foto_cover) }}"
                alt="{{ $campaign->judul }}"
                class="absolute inset-0
                       w-full
                       h-full
                       object-cover"
            >

        </div>


        {{-- PROGRESS CARD --}}
        <div
            class="bg-white
                   rounded-[26px]
                   border border-gray-100
                   shadow-md
                   px-10 py-9"
        >

            <div class="flex items-center justify-between gap-6">

                <h2 class="text-[28px] font-semibold text-gray-950">
                    Progres Kampanye
                </h2>

                <span class="flex items-center gap-2 text-[19px] text-gray-500 whitespace-nowrap">
                    <span class="w-[7px] h-[7px] rounded-full bg-gray-400"></span>
                    Dibuat {{ OrgFormat::shortDate($campaign->created_at) }}
                </span>

            </div>


            <p class="mt-6 text-[40px] font-bold text-gray-950 leading-none">
                {{ OrgFormat::rupiah($campaign->total_terkumpul) }}
            </p>

            <p class="mt-3 text-[22px] text-gray-600">
                dari target {{ OrgFormat::rupiah($campaign->target_dana) }}
            </p>


            <div class="mt-7 h-[18px] bg-[#DFE5F2] rounded-full overflow-hidden">
                <div
                    class="h-full bg-[#087C3D] rounded-full"
                    style="width: {{ $campaign->progressPercent() }}%"
                ></div>
            </div>


            {{-- STATISTIK --}}
            <div
                class="mt-8
                       grid grid-cols-3
                       divide-x divide-gray-200
                       border-t border-gray-100"
            >

                @foreach ([
                    ['icon' => 'fa-regular fa-clock', 'label' => 'Sisa Waktu', 'value' => $campaign->sisa_hari . ' Hari'],
                    ['icon' => 'fa-solid fa-user-group', 'label' => 'Jumlah Donatur', 'value' => $jumlahDonatur . ' Donatur'],
                    ['icon' => 'fa-regular fa-calendar', 'label' => 'Tenggat', 'value' => OrgFormat::shortDate($campaign->tanggal_selesai)],
                ] as $stat)
                    <div class="px-3 pt-6 text-center">

                        <i class="{{ $stat['icon'] }} text-[30px] text-[#08703F]"></i>

                        <p class="mt-3 text-[18px] text-gray-500">
                            {{ $stat['label'] }}
                        </p>

                        <p class="mt-1 text-[23px] font-bold text-gray-950 whitespace-nowrap">
                            {{ $stat['value'] }}
                        </p>

                    </div>
                @endforeach

            </div>

        </div>

    </section>



    {{-- =====================================================
        TABS + KONTEN (kiri) & AJAK BERDONASI (kanan)
    ===================================================== --}}
    <div
        class="grid grid-cols-1
               lg:grid-cols-[1.45fr_1fr]
               gap-10
               mt-14
               items-start"
    >

        <div id="campaignTabsColumn">

            {{-- TABS --}}
            <div class="border-b border-gray-300">

                <nav class="flex flex-wrap gap-x-10">

                    @foreach (['overview' => 'Tentang Kampanye', 'pencairan' => 'Pencairan Dana', 'bukti' => 'Bukti Penyaluran'] as $tabKey => $tabLabel)
                        <button
                            type="button"
                            data-tab="{{ $tabKey }}"
                            class="campaign-tab
                                   -mb-px
                                   px-3 py-5
                                   border-b-[3px] border-transparent
                                   text-[23px]
                                   font-semibold
                                   text-gray-600
                                   hover:text-[#08703F]
                                   transition"
                        >
                            {{ $tabLabel }}
                        </button>
                    @endforeach

                </nav>

            </div>


            {{-- =====================================================
                TAB CONTENT
            ===================================================== --}}
            <section class="mt-10">

                {{-- =================================================
                    TENTANG KAMPANYE
                ================================================= --}}
                <div
                    id="tab-overview"
                    class="campaign-tab-content"
                >

                    <div class="flex items-center justify-between gap-6">

                        <h2 class="text-[32px] font-bold text-gray-950">
                            Deskripsi
                        </h2>

                        <button
                            type="button"
                            id="editDescriptionButton"
                            class="{{ $errors->has('deskripsi') ? 'hidden' : '' }}
                                   inline-flex items-center gap-3
                                   px-6 py-3
                                   bg-white
                                   border border-gray-300
                                   rounded-[14px]
                                   text-[20px]
                                   font-medium
                                   text-gray-700
                                   hover:border-[#08703F]
                                   hover:text-[#08703F]
                                   transition"
                        >
                            <i class="fa-solid fa-pen text-[16px]"></i>
                            Edit
                        </button>

                    </div>


                    {{-- TAMPILAN (read-only) --}}
                    <p
                        id="descriptionText"
                        class="{{ $errors->has('deskripsi') ? 'hidden' : '' }}
                               mt-6
                               text-[23px]
                               text-gray-800
                               leading-relaxed
                               whitespace-pre-line
                               break-words"
                    >{{ $campaign->deskripsi }}</p>


                    {{-- FORM EDIT --}}
                    <form
                        id="descriptionForm"
                        method="POST"
                        action="{{ route('organisasi.kampanye.deskripsi.update', $campaign->id) }}"
                        class="{{ $errors->has('deskripsi') ? '' : 'hidden' }} mt-6"
                    >

                        @csrf
                        @method('PUT')

                        <textarea
                            name="deskripsi"
                            required
                            rows="8"
                            class="w-full
                                   bg-white
                                   border border-gray-300
                                   rounded-[18px]
                                   px-7 py-6
                                   text-[22px]
                                   text-gray-800
                                   leading-relaxed
                                   outline-none
                                   resize-y
                                   focus:border-[#08703F]
                                   focus:ring-2
                                   focus:ring-[#08703F]/10"
                        >{{ old('deskripsi', $campaign->deskripsi) }}</textarea>

                        @error('deskripsi')
                            <p class="mt-3 text-[20px] text-red-500">{{ $message }}</p>
                        @enderror

                        <div class="flex justify-end gap-4 mt-6">

                            <button
                                type="button"
                                id="cancelDescriptionButton"
                                class="min-w-[150px]
                                       px-7 py-4
                                       bg-white
                                       border-2 border-gray-300
                                       text-gray-700
                                       rounded-[14px]
                                       text-[21px]
                                       font-semibold
                                       hover:bg-gray-100
                                       transition"
                            >
                                Batal
                            </button>

                            <button
                                type="submit"
                                class="min-w-[170px]
                                       px-7 py-4
                                       bg-[#08703F]
                                       hover:bg-[#065D35]
                                       text-white
                                       rounded-[14px]
                                       text-[21px]
                                       font-semibold
                                       transition"
                            >
                                Simpan
                            </button>

                        </div>

                    </form>

                </div>



        {{-- =================================================
            PENCAIRAN DANA
        ================================================= --}}
        <div
            id="tab-pencairan"
            class="campaign-tab-content hidden"
        >

            {{-- =================================================
                RINGKASAN SALDO
            ================================================= --}}
            <section class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                @foreach ([
                    ['label' => 'Total Saldo', 'value' => $campaign->total_terkumpul],
                    ['label' => 'Saldo Dicairkan', 'value' => $campaign->total_dicairkan],
                    ['label' => 'Saldo Tersisa', 'value' => $campaign->saldoTersisa()],
                ] as $saldo)
                    <div
                        class="bg-white
                               rounded-[20px]
                               border border-gray-100
                               shadow-md
                               px-8 py-7"
                    >
                        <p class="text-[20px] text-gray-600">
                            {{ $saldo['label'] }}
                        </p>

                        <p class="mt-2 text-[32px] font-bold text-[#08703F] whitespace-nowrap">
                            {{ OrgFormat::rupiah($saldo['value']) }}
                        </p>
                    </div>
                @endforeach

            </section>



            {{-- =================================================
                STATUS PENGAJUAN
            ================================================= --}}
            <section
                class="mt-10
                       bg-white
                       rounded-[24px]
                       border border-gray-100
                       shadow-md
                       px-10 py-10"
            >

                <h2 class="text-[34px] font-bold text-gray-950">
                    Status Pengajuan
                </h2>


                @if ($latestDisbursement)

                    @php
                        $labelClass = 'text-[20px] text-gray-500';
                        $valueClass = 'mt-2 text-[26px] font-semibold text-gray-950 break-words';
                    @endphp

                    <dl class="mt-9 grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-8">

                        <div>
                            <dt class="{{ $labelClass }}">Nominal</dt>
                            <dd class="{{ $valueClass }}">{{ OrgFormat::rupiah($latestDisbursement->nominal_diajukan) }}</dd>
                        </div>

                        <div>
                            <dt class="{{ $labelClass }}">Status</dt>
                            <dd class="mt-2">
                                <span
                                    class="inline-flex
                                           {{ OrgFormat::statusBadge('disbursement', $latestDisbursement->status) }}
                                           px-5 py-2
                                           rounded-[10px]
                                           text-[20px]
                                           font-semibold"
                                >
                                    {{ OrgFormat::statusLabel('disbursement', $latestDisbursement->status) }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="{{ $labelClass }}">Tanggal Pengajuan</dt>
                            <dd class="{{ $valueClass }}">{{ OrgFormat::longDate($latestDisbursement->created_at) }}</dd>
                        </div>

                        <div>
                            <dt class="{{ $labelClass }}">Rekening Tujuan</dt>
                            <dd class="{{ $valueClass }}">{{ $latestDisbursement->bankAccount?->no_rekening ?? '-' }}</dd>
                        </div>

                        <div>
                            <dt class="{{ $labelClass }}">Bank Tujuan</dt>
                            <dd class="{{ $valueClass }}">{{ $latestDisbursement->bankAccount?->bank ?? '-' }}</dd>
                        </div>

                        <div>
                            <dt class="{{ $labelClass }}">Nama Alokasi</dt>
                            <dd class="{{ $valueClass }}">{{ $latestDisbursement->alokasi_dana }}</dd>
                        </div>

                        {{-- ALASAN + TOMBOL AJUKAN (satu baris) --}}
                        <div class="sm:col-span-2 flex flex-col sm:flex-row sm:items-end justify-between gap-6">

                            <div class="min-w-0">
                                <dt class="{{ $labelClass }}">Alasan Pengajuan</dt>
                                <dd class="{{ $valueClass }}">{{ $latestDisbursement->alasan }}</dd>
                            </div>

                            <a
                            href="{{ route('organisasi.kampanye.pencairan.ajukan', $campaign->id) }}"
                            class="shrink-0
                                   inline-flex
                                   items-center
                                   justify-center
                                   px-8 py-4
                                   bg-[#08703F]
                                   hover:bg-[#065D35]
                                   text-white
                                   rounded-[14px]
                                   text-[21px]
                                   font-semibold
                                   transition"
                        >
                            Ajukan Pencairan
                        </a>

                        </div>

                        @if ($latestDisbursement->status === 'ditolak' && $latestDisbursement->alasan_tolak)
                            <div class="sm:col-span-2">
                                <dt class="{{ $labelClass }}">Alasan Penolakan</dt>
                                <dd class="mt-2 text-[24px] font-semibold text-red-500 break-words">{{ $latestDisbursement->alasan_tolak }}</dd>
                            </div>
                        @endif

                    </dl>

                @else

                    <div class="mt-9 flex flex-col sm:flex-row sm:items-center justify-between gap-6">

                        <p class="text-[22px] text-gray-500">
                            Belum ada pengajuan pencairan dana untuk kampanye ini.
                        </p>

                        <a
                            href="{{ route('organisasi.kampanye.pencairan.ajukan', $campaign->id) }}"
                            class="shrink-0
                                   inline-flex
                                   items-center
                                   justify-center
                                   px-8 py-4
                                   bg-[#08703F]
                                   hover:bg-[#065D35]
                                   text-white
                                   rounded-[14px]
                                   text-[21px]
                                   font-semibold
                                   transition"
                        >
                            Ajukan Pencairan
                        </a>

                    </div>

                @endif

            </section>


            {{-- =================================================
                RIWAYAT PENCAIRAN
            ================================================= --}}
            <section class="mt-16">

                <h2
                    class="text-[38px]
                           lg:text-[42px]
                           font-bold
                           text-gray-950"
                >
                    Riwayat Pencairan
                </h2>


                <div
                    class="mt-8
                           bg-white
                           rounded-[22px]
                           shadow-md
                           overflow-hidden
                           overflow-x-auto"
                >

                    <table class="w-full min-w-[1100px] table-fixed">

                        <thead class="bg-[#08703F] text-white">

                            <tr class="text-[24px]">

                                <th
                                    class="w-[34%]
                                           px-8 py-6
                                           text-center
                                           font-semibold"
                                >
                                    Nama Alokasi
                                </th>

                                <th
                                    class="w-[22%]
                                           px-8 py-6
                                           text-center
                                           font-semibold"
                                >
                                    Tanggal
                                </th>

                                <th
                                    class="w-[22%]
                                           px-8 py-6
                                           text-center
                                           font-semibold"
                                >
                                    Nominal
                                </th>

                                <th
                                    class="w-[22%]
                                           px-8 py-6
                                           text-center
                                           font-semibold"
                                >
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody class="text-[23px] text-gray-900">

                            @forelse ($disbursements as $disbursement)
                                <tr class="hover:bg-[#F0F8F4] transition">

                                    <td class="px-8 py-6 text-center font-medium">
                                        {{ $disbursement->alokasi_dana }}
                                    </td>

                                    <td class="px-8 py-6 text-center">
                                        {{ OrgFormat::date($disbursement->created_at, 'Y / m / d') }}
                                    </td>

                                    <td class="px-8 py-6 text-center">
                                        {{ OrgFormat::rupiah($disbursement->nominal_dicairkan ?? $disbursement->nominal_diajukan) }}
                                    </td>

                                    <td class="px-8 py-6 text-center">

                                        <span
                                            class="inline-flex
                                                   min-w-[135px]
                                                   justify-center
                                                   {{ OrgFormat::statusBadge('disbursement', $disbursement->status) }}
                                                   px-6 py-3
                                                   rounded-full
                                                   text-[20px]
                                                   font-semibold"
                                        >
                                            {{ OrgFormat::statusLabel('disbursement', $disbursement->status) }}
                                        </span>

                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-10 text-center text-gray-500">
                                        Belum ada riwayat pencairan.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </section>

        </div>


        {{-- =================================================
            BUKTI PENYALURAN
        ================================================= --}}
        <div
            id="tab-bukti"
            class="campaign-tab-content hidden"
        >

            <section
                class="bg-white
                       rounded-[28px]
                       shadow-md
                       border border-gray-100
                       px-10
                       py-11"
            >

                {{-- HEADER --}}
                <div
                    class="flex
                           flex-col
                           sm:flex-row
                           sm:items-start
                           justify-between
                           gap-6"
                >

                    <div>

                        <h2 class="text-[36px] lg:text-[40px] font-bold text-gray-950">
                            Bukti Penyaluran
                        </h2>

                        <p class="mt-2 text-[21px] text-gray-500">
                            Upload bukti penggunaan dana agar dapat diverifikasi oleh admin.
                        </p>

                    </div>


                    <a
                        href="{{ route('organisasi.kampanye.bukti.upload', $campaign->id) }}"
                        class="shrink-0
                               inline-flex
                               items-center
                               justify-center
                               gap-3
                               px-8 py-4
                               bg-[#08703F]
                               hover:bg-[#065D35]
                               text-white
                               rounded-[14px]
                               text-[21px]
                               font-semibold
                               shadow-sm
                               transition"
                    >
                        <i class="fa-solid fa-plus text-[18px]"></i>
                        Upload Bukti
                    </a>

                </div>


                {{-- CARDS --}}
                @if ($proofs->isNotEmpty())

                    <div
                        class="grid grid-cols-1
                               md:grid-cols-2
                               xl:grid-cols-3
                               gap-8
                               mt-10"
                    >

                        @foreach ($proofs as $proof)
                            <article
                                class="flex flex-col
                                       bg-white
                                       rounded-[20px]
                                       border border-gray-100
                                       shadow-md
                                       p-5
                                       hover:shadow-lg
                                       transition"
                            >

                                @if (\Illuminate\Support\Str::endsWith(strtolower($proof->lokasi_file), '.pdf'))
                                    <div
                                        class="w-full
                                               h-[240px]
                                               rounded-[14px]
                                               bg-[#F0F8F4]
                                               text-[#08703F]
                                               flex flex-col items-center justify-center gap-3"
                                    >
                                        <i class="fa-regular fa-file-pdf text-[64px]"></i>
                                        <span class="text-[19px] font-semibold">Dokumen PDF</span>
                                    </div>
                                @else
                                    <img
                                        src="{{ OrgFormat::storageUrl($proof->lokasi_file) }}"
                                        alt="Bukti {{ $proof->fundDisbursement?->alokasi_dana }}"
                                        class="w-full
                                               h-[240px]
                                               object-cover
                                               rounded-[14px]
                                               bg-gray-100"
                                    >
                                @endif


                                <h3 class="mt-6 text-[25px] font-semibold text-gray-950 leading-snug line-clamp-2">
                                    {{ $proof->fundDisbursement?->alokasi_dana ?? '-' }}
                                </h3>

                                <p class="mt-2 text-[19px] text-gray-500">
                                    {{ OrgFormat::longDate($proof->uploaded_at) }}
                                </p>

                                <div class="mt-4">
                                    <span
                                        class="inline-flex
                                               {{ OrgFormat::statusBadge('proof', $proof->status) }}
                                               px-5 py-2
                                               rounded-full
                                               text-[18px]
                                               font-semibold"
                                    >
                                        {{ OrgFormat::statusLabel('proof', $proof->status) }}
                                    </span>
                                </div>


                                {{-- Tombol selalu di dasar kartu agar sejajar antar kartu --}}
                                <div class="mt-auto pt-7">
                                    <a
                                        href="{{ route('organisasi.kampanye.bukti.detail', $proof->id) }}"
                                        class="w-full
                                               h-[60px]
                                               border-2
                                               border-[#08703F]
                                               text-[#08703F]
                                               rounded-[14px]
                                               flex
                                               items-center
                                               justify-center
                                               gap-3
                                               text-[21px]
                                               font-semibold
                                               hover:bg-[#08703F]
                                               hover:text-white
                                               transition"
                                    >
                                        <i class="fa-regular fa-eye text-[21px]"></i>
                                        Lihat Detail
                                    </a>
                                </div>

                            </article>
                        @endforeach

                    </div>

                @else

                    <div
                        class="mt-10
                               border-2 border-dashed border-gray-200
                               rounded-[20px]
                               px-8 py-14
                               text-center"
                    >
                        <i class="fa-regular fa-images text-[52px] text-gray-400"></i>

                        <p class="mt-4 text-[23px] font-semibold text-gray-700">
                            Belum ada bukti penyaluran
                        </p>

                        <p class="mt-2 text-[19px] text-gray-500">
                            Unggah foto nota atau dokumen penggunaan dana setelah pencairan disetujui.
                        </p>
                    </div>

                @endif

            </section>

        </div>

    </section>

        </div>


        {{-- =====================================================
            AJAK BERDONASI
        ===================================================== --}}
        <aside id="shareAside" class="lg:sticky lg:top-28">

            <div
                class="bg-[#087C3D]
                       text-white
                       rounded-[24px]
                       shadow-md
                       px-10 py-11
                       text-center"
            >

                <div
                    class="w-[76px]
                           h-[76px]
                           mx-auto
                           rounded-full
                           bg-white/15
                           flex
                           items-center
                           justify-center"
                >
                    <i class="fa-solid fa-hand-holding-heart text-[34px]"></i>
                </div>


                <h3 class="mt-6 text-[28px] font-semibold">
                    Ajak Berdonasi
                </h3>


                <p class="mt-4 text-[20px] text-white/85 leading-relaxed">
                    Bantu kami menjangkau lebih banyak orang untuk ikut
                    berkontribusi dalam kampanye ini.
                </p>


                <button
                    type="button"
                    data-share-url="{{ route('campaign.detail', $campaign->id) }}"
                    onclick="navigator.clipboard.writeText(this.dataset.shareUrl).then(function () { orgFlash('Link kampanye disalin ke clipboard.'); })"
                    class="mt-8
                           w-full
                           bg-white
                           text-[#08703F]
                           py-5
                           rounded-[14px]
                           text-[21px]
                           font-semibold
                           hover:bg-gray-100
                           transition"
                >
                    Bagikan Kampanye
                </button>

            </div>

        </aside>

    </div>

</main>


{{-- =========================================================
    TAB & DESKRIPSI SCRIPT
========================================================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const tabs = document.querySelectorAll('.campaign-tab');
        const contents = document.querySelectorAll('.campaign-tab-content');
        const tabsColumn = document.getElementById('campaignTabsColumn');
        const shareAside = document.getElementById('shareAside');

        function activateTab(tabName) {

            if (!document.getElementById('tab-' + tabName)) {
                tabName = 'overview';
            }

            contents.forEach(function (content) {
                content.classList.toggle('hidden', content.id !== 'tab-' + tabName);
            });

            tabs.forEach(function (tab) {
                const isActive = tab.dataset.tab === tabName;
                tab.classList.toggle('border-[#08703F]', isActive);
                tab.classList.toggle('text-[#08703F]', isActive);
                tab.classList.toggle('border-transparent', !isActive);
                tab.classList.toggle('text-gray-600', !isActive);
            });

            // Tab pencairan & bukti berisi tabel lebar: pakai lebar penuh dan sembunyikan kartu samping.
            const isOverview = tabName === 'overview';
            shareAside.classList.toggle('hidden', !isOverview);
            tabsColumn.classList.toggle('lg:col-span-2', !isOverview);
        }

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                activateTab(this.dataset.tab);
            });
        });

        activateTab(new URLSearchParams(window.location.search).get('tab') || 'overview');


        // Deskripsi: tampil read-only, tombol Edit membuka form.
        const descriptionText = document.getElementById('descriptionText');
        const descriptionForm = document.getElementById('descriptionForm');
        const editButton = document.getElementById('editDescriptionButton');

        function toggleDescriptionEdit(editing) {
            descriptionText.classList.toggle('hidden', editing);
            editButton.classList.toggle('hidden', editing);
            descriptionForm.classList.toggle('hidden', !editing);

            if (editing) {
                descriptionForm.querySelector('textarea').focus();
            }
        }

        editButton.addEventListener('click', function () {
            toggleDescriptionEdit(true);
        });

        document.getElementById('cancelDescriptionButton').addEventListener('click', function () {
            descriptionForm.reset();
            toggleDescriptionEdit(false);
        });

    });
</script>

@endsection