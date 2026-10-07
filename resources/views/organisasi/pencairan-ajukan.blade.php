@use('App\Support\OrgFormat')

@extends('layouts.organization', [
    'title' => 'Ajukan Pencairan Dana',
    'activeNav' => 'kampanye'
])

@section('content')

<main
    class="w-full
           org-container px-6 sm:px-8 lg:px-12
           pt-16 pb-24"
>

    {{-- =====================================================
        HEADER
    ===================================================== --}}
    <section
        class="flex flex-col
               lg:flex-row
               lg:items-start
               justify-between
               gap-8"
    >

        <div>

            <h1
                class="text-[52px]
                       lg:text-[58px]
                       xl:text-[62px]
                       font-bold
                       text-[#08703F]
                       leading-tight"
            >
                Ajukan Pencairan Dana
            </h1>


            {{-- SALDO TERSEDIA --}}
            <div
                class="mt-5
                       inline-flex
                       items-center
                       gap-4
                       bg-white
                       rounded-[16px]
                       shadow-md
                       border border-gray-100
                       px-8
                       h-[68px]"
            >

                <span
                    class="text-[22px]
                           font-semibold
                           text-[#08703F]"
                >
                    SALDO TERSEDIA
                </span>

                <span
                    class="text-[24px]
                           font-bold
                           text-[#08703F]"
                >
                    {{ OrgFormat::rupiah($campaign->saldoTersisa()) }}
                </span>

            </div>

        </div>


        {{-- KEMBALI --}}
        <a
            href="{{ route('organisasi.kampanye.detail', ['campaign' => $campaign->id, 'tab' => 'pencairan']) }}"
            class="min-w-[210px]
                   h-[72px]
                   inline-flex
                   items-center
                   justify-center
                   bg-[#08703F]
                   text-white
                   rounded-[18px]
                   text-[24px]
                   font-semibold
                   hover:bg-[#065D35]
                   transition"
        >
            Kembali
        </a>

    </section>


    <p class="mt-4 text-[24px] text-gray-500 font-medium">
        Kampanye: {{ $campaign->judul }}
    </p>


    {{-- PENGAJUAN DIBLOKIR: bukti penyaluran pencairan sebelumnya belum diverifikasi admin --}}
    @if ($blockingDisbursement)
        @php
            $blockReason = $blockingDisbursement->newRequestBlockReason();
            [$blockActionUrl, $blockActionLabel] = match ($blockReason) {
                'belum_upload_bukti' => [route('organisasi.kampanye.bukti.upload', $blockingDisbursement->id_campaign), 'Upload Bukti Penyaluran'],
                'bukti_menunggu_verifikasi' => [route('organisasi.kampanye.detail', ['campaign' => $blockingDisbursement->id_campaign, 'tab' => 'bukti']), 'Lihat Bukti Penyaluran'],
                default => [route('organisasi.kampanye.detail', ['campaign' => $blockingDisbursement->id_campaign, 'tab' => 'pencairan']), 'Lihat Pengajuan'],
            };
        @endphp
        <section
            class="mt-10
                   flex flex-col
                   lg:flex-row
                   lg:items-center
                   justify-between
                   gap-6
                   bg-red-50
                   border border-red-200
                   rounded-[20px]
                   px-8
                   py-6"
        >

            <div class="flex items-start gap-4">
                <i class="fa-solid fa-triangle-exclamation text-[26px] text-red-500 mt-1"></i>

                <p class="text-[20px] text-red-600 font-medium">
                    {{ $blockingDisbursement->newRequestBlockMessage() }}
                </p>
            </div>

            <a
                href="{{ $blockActionUrl }}"
                class="shrink-0
                       inline-flex
                       items-center
                       justify-center
                       px-8 py-4
                       bg-[#08703F]
                       hover:bg-[#065D35]
                       text-white
                       rounded-[14px]
                       text-[20px]
                       font-semibold
                       transition"
            >
                {{ $blockActionLabel }}
            </a>

        </section>
    @endif


    <form id="disbursementForm" enctype="multipart/form-data">

    <input type="hidden" name="id_campaign" value="{{ $campaign->id }}">

    {{-- =====================================================
        FORM CARD
    ===================================================== --}}
    <section
        class="mt-14
               bg-white
               rounded-[28px]
               shadow-md
               border border-gray-100
               px-10
               lg:px-12
               py-12"
    >

        <div class="space-y-9">

            {{-- NOMINAL --}}
            <div>

                <label
                    for="nominal"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Nominal yang diajukan
                </label>

                <input
                    id="nominal"
                    name="nominal_diajukan"
                    type="text"
                    inputmode="numeric"
                    placeholder="Contoh : 100000"
                    required
                    class="w-full
                           h-[82px]
                           bg-white
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           text-[23px]
                           text-gray-900
                           outline-none
                           focus:border-[#08703F]
                           focus:ring-2
                           focus:ring-[#08703F]/10"
                >

            </div>


            {{-- ALOKASI DANA --}}
            <div>

                <label
                    for="alokasi"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Nama Alokasi
                </label>

                <input
                    id="alokasi"
                    name="alokasi_dana"
                    type="text"
                    placeholder="Contoh : Pembelian Beras"
                    required
                    class="w-full
                           h-[82px]
                           bg-white
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           text-[23px]
                           text-gray-900
                           outline-none
                           focus:border-[#08703F]
                           focus:ring-2
                           focus:ring-[#08703F]/10"
                >

            </div>


            {{-- REKENING --}}
            <div>

                <label
                    for="rekening"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Rekening Tujuan
                </label>

                <input
                    id="rekening"
                    type="text"
                    readonly
                    value="{{ $bankAccount ? $bankAccount->bank . ' - ' . $bankAccount->no_rekening . ' a.n. ' . $bankAccount->pemilik_rekening : 'Belum ada rekening terdaftar' }}"
                    class="w-full
                           h-[82px]
                           bg-[#F3F5F4]
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           text-[23px]
                           text-gray-900
                           outline-none
                           focus:border-[#08703F]
                           focus:ring-2
                           focus:ring-[#08703F]/10"
                >

                @if ($bankAccount && $bankAccount->status_verifikasi !== 'diterima')
                    <p class="mt-3 text-[20px] text-red-500">
                        Rekening belum diverifikasi admin, pencairan belum dapat diajukan.
                    </p>
                @endif

            </div>


            {{-- ALASAN --}}
            <div>

                <label
                    for="alasan"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Alasan Pengajuan
                </label>

                <textarea
                    id="alasan"
                    name="alasan"
                    required
                    class="w-full
                           min-h-[340px]
                           bg-white
                           border border-gray-300
                           rounded-[16px]
                           px-7
                           py-6
                           text-[23px]
                           text-gray-900
                           leading-relaxed
                           outline-none
                           resize-none
                           focus:border-[#08703F]
                           focus:ring-2
                           focus:ring-[#08703F]/10"
                ></textarea>

            </div>


            {{-- =================================================
                LAMPIRAN
            ================================================= --}}
            <div>

                <label
                    for="lampiran"
                    class="block
                           mb-5
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Lampiran Pendukung ( Opsional )
                </label>


                <label
                    for="lampiran"
                    class="w-full
                           min-h-[380px]
                           bg-[#E2E2E2]
                           border-2
                           border-gray-600
                           rounded-[24px]
                           flex
                           flex-col
                           items-center
                           justify-center
                           cursor-pointer
                           hover:bg-[#D9D9D9]
                           transition"
                >

                    <i
                        class="fa-solid fa-cloud-arrow-up
                               text-[66px]
                               text-black"
                    ></i>


                    <h2
                        class="mt-6
                               text-[37px]
                               lg:text-[40px]
                               font-semibold
                               text-[#08703F]"
                    >
                        Unggah Lampiran
                    </h2>


                    <p
                        class="mt-2
                               text-[22px]
                               text-gray-700"
                    >
                        Klik untuk memilih file dengan tipe PDF, JPG, PNG (maks. 2 MB)
                    </p>


                    <p
                        id="lampiranName"
                        class="mt-4
                               text-[21px]
                               font-semibold
                               text-gray-900"
                    ></p>


                    <input
                        id="lampiran"
                        name="lampiran"
                        type="file"
                        accept=".pdf,.jpg,.jpeg,.png"
                        class="hidden"
                        onchange="document.getElementById('lampiranName').textContent = this.files[0]?.name ?? ''"
                    >

                </label>

            </div>

        </div>

    </section>


    {{-- =====================================================
        ACTION BUTTONS
    ===================================================== --}}
    <section
        class="flex
               flex-col
               sm:flex-row
               justify-end
               gap-6
               mt-10"
    >

        {{-- BATAL --}}
        <a
            href="{{ route('organisasi.kampanye.detail', ['campaign' => $campaign->id, 'tab' => 'pencairan']) }}"
            class="min-w-[220px]
                   h-[76px]
                   inline-flex
                   items-center
                   justify-center
                   gap-3
                   bg-white
                   border-2
                   border-red-500
                   text-red-500
                   rounded-[18px]
                   text-[24px]
                   font-semibold
                   hover:bg-red-500
                   hover:text-white
                   transition"
        >
            <i class="fa-solid fa-xmark text-[20px]"></i>
            Batal
        </a>


        {{-- AJUKAN --}}
        <button
            type="submit"
            id="submitDisbursement"
            @disabled($blockingDisbursement)
            class="min-w-[300px]
                   h-[76px]
                   bg-[#08703F]
                   text-white
                   rounded-[18px]
                   text-[24px]
                   font-semibold
                   hover:bg-[#065D35]
                   disabled:opacity-60
                   disabled:cursor-not-allowed
                   disabled:hover:bg-[#08703F]
                   transition"
        >
            Ajukan Verifikasi
        </button>

    </section>

    </form>

</main>

@endsection


@push('scripts')
<script>
    document.getElementById('disbursementForm').addEventListener('submit', async function (event) {
        event.preventDefault();

        const button = document.getElementById('submitDisbursement');
        const formData = new FormData(this);
        formData.set('nominal_diajukan', (formData.get('nominal_diajukan') || '').replace(/\D/g, ''));

        if (!formData.get('lampiran')?.size) {
            formData.delete('lampiran');
        }

        button.disabled = true;

        try {
            await orgRequest('{{ route('organisasi.kampanye.pencairan.store') }}', { body: formData });
            window.location.href = '{{ route('organisasi.kampanye.detail', ['campaign' => $campaign->id, 'tab' => 'pencairan']) }}';
        } catch (error) {
            orgFlash(error.message, 'error');
            button.disabled = false;
        }
    });
</script>
@endpush