@use('App\Support\OrgFormat')

@extends('layouts.organization', [
    'title' => 'Upload Bukti',
    'activeNav' => 'kampanye'
])

@section('content')

@php
    $backUrl = route('organisasi.kampanye.detail', ['campaign' => $campaign->id, 'tab' => 'bukti']);
    $labelClass = 'block mb-3 text-[22px] font-medium text-gray-900';
    $inputClass = 'w-full bg-white border border-gray-300 rounded-[14px] px-6 text-[22px] text-gray-900 placeholder:text-gray-400 outline-none focus:border-[#08703F] focus:ring-2 focus:ring-[#08703F]/10 transition';
@endphp

<main
    class="w-full
           org-container px-6 sm:px-8 lg:px-12
           pt-16 pb-24"
>

    {{-- =====================================================
        BACK
    ===================================================== --}}
    {{-- Lebar & posisi disamakan dengan kartu form agar sejajar --}}
    <div class="max-w-[1100px] mx-auto">

        <a
            href="{{ $backUrl }}"
            class="inline-flex items-center gap-3
                   text-[22px]
                   text-gray-600
                   hover:text-[#08703F]
                   transition"
        >
            <i class="fa-solid fa-arrow-left text-[16px]"></i>
            Kembali ke Kampanye
        </a>

    </div>



    {{-- =====================================================
        FORM CARD
    ===================================================== --}}
    <form
        id="proofForm"
        enctype="multipart/form-data"
        novalidate
        class="mt-9
               max-w-[1100px]
               mx-auto
               bg-white
               rounded-[28px]
               shadow-md
               border border-gray-100
               px-10
               lg:px-14
               py-12"
    >

        {{-- HEADER --}}
        <h1 class="text-[40px] lg:text-[44px] font-bold text-[#08703F] leading-tight">
            Upload Bukti Penyaluran
        </h1>

        <p class="mt-2 text-[21px] text-gray-500">
            Lengkapi detail dan unggah dokumen bukti transaksi untuk kampanye
            <span class="font-semibold text-gray-700">{{ $campaign->judul }}</span>.
        </p>


        <div class="mt-10 space-y-8">

            {{-- PENCAIRAN DANA --}}
            <div>

                <label for="judulBukti" class="{{ $labelClass }}">
                    Pencairan Dana <span class="text-red-500">*</span>
                </label>

                <div class="relative">

                    <select
                        id="judulBukti"
                        class="{{ $inputClass }} h-[70px] appearance-none pr-16"
                    >
                        <option value="">Pilih pencairan yang disetujui</option>
                        @foreach ($disbursements as $disbursement)
                            <option value="{{ $disbursement->id }}">
                                {{ $disbursement->alokasi_dana }} - {{ OrgFormat::rupiah($disbursement->nominal_dicairkan ?? $disbursement->nominal_diajukan) }}
                            </option>
                        @endforeach
                    </select>

                    <i
                        class="fa-solid fa-chevron-down
                               absolute right-6 top-1/2 -translate-y-1/2
                               text-[18px]
                               text-gray-500
                               pointer-events-none"
                    ></i>

                </div>

                @if ($disbursements->isEmpty())
                    <p class="mt-3 text-[19px] text-red-500">
                        Belum ada pencairan yang disetujui admin untuk kampanye ini.
                    </p>
                @endif

            </div>


            {{-- NOMINAL --}}
            <div>

                <label for="nominalBukti" class="{{ $labelClass }}">
                    Nominal <span class="text-red-500">*</span>
                </label>

                <input
                    id="nominalBukti"
                    name="nominal"
                    type="text"
                    inputmode="numeric"
                    autocomplete="off"
                    placeholder="Rp 100.000"
                    class="{{ $inputClass }} h-[70px]"
                >

            </div>


            {{-- DESKRIPSI --}}
            <div>

                <label for="deskripsiBukti" class="{{ $labelClass }}">
                    Deskripsi <span class="text-red-500">*</span>
                </label>

                <textarea
                    id="deskripsiBukti"
                    name="deskripsi"
                    rows="3"
                    placeholder="Masukkan penjelasan singkat transaksi..."
                    class="{{ $inputClass }} py-5 leading-relaxed resize-none"
                ></textarea>

            </div>


            {{-- UPLOAD BUKTI (DROPZONE) --}}
            <div>

                <p class="{{ $labelClass }}">
                    Upload Bukti Pembayaran <span class="text-red-500">*</span>
                </p>

                <div
                    id="dropzone"
                    class="w-full
                           min-h-[240px]
                           px-8 py-10
                           bg-[#F7F8F8]
                           border-2 border-dashed border-gray-300
                           rounded-[18px]
                           flex flex-col items-center justify-center
                           text-center
                           transition"
                >

                    {{-- KOSONG --}}
                    <div id="dropzoneEmpty" class="flex flex-col items-center">

                        <i class="fa-regular fa-file-lines text-[46px] text-gray-500"></i>

                        <p class="mt-4 text-[22px] text-gray-800">
                            Drag &amp; drop berkas di sini, atau
                        </p>

                        <label
                            for="uploadCover"
                            class="mt-4
                                   inline-flex
                                   px-6 py-3
                                   bg-white
                                   border border-gray-300
                                   rounded-[12px]
                                   text-[19px]
                                   font-semibold
                                   text-gray-800
                                   cursor-pointer
                                   hover:border-[#08703F]
                                   hover:text-[#08703F]
                                   transition"
                        >
                            Pilih Berkas
                        </label>

                        <p class="mt-4 text-[18px] text-gray-500">
                            Format: PDF, JPG, PNG (Maks. 2 MB)
                        </p>

                    </div>


                    {{-- BERKAS TERPILIH --}}
                    <div id="dropzoneFile" class="hidden w-full max-w-[640px]">

                        <div
                            class="flex items-center gap-5
                                   bg-white
                                   border border-gray-200
                                   rounded-[14px]
                                   px-6 py-5
                                   text-left"
                        >

                            <i id="fileIcon" class="fa-regular fa-file-image text-[34px] text-[#08703F]"></i>

                            <div class="min-w-0 flex-1">
                                <p id="fileName" class="text-[20px] font-semibold text-gray-900 truncate"></p>
                                <p id="fileSize" class="text-[17px] text-gray-500"></p>
                            </div>

                            <button
                                type="button"
                                id="removeFile"
                                class="w-[44px] h-[44px]
                                       rounded-full
                                       text-gray-500
                                       hover:bg-red-50
                                       hover:text-red-500
                                       transition"
                                aria-label="Hapus berkas"
                            >
                                <i class="fa-solid fa-xmark text-[20px]"></i>
                            </button>

                        </div>

                        <label
                            for="uploadCover"
                            class="mt-4 inline-block text-[18px] font-semibold text-[#08703F] cursor-pointer hover:underline"
                        >
                            Ganti berkas
                        </label>

                    </div>

                    <input
                        id="uploadCover"
                        name="bukti_file"
                        type="file"
                        accept=".pdf,.jpg,.jpeg,.png"
                        class="hidden"
                    >

                </div>

            </div>

        </div>


        {{-- BUTTONS --}}
        <div class="flex items-center justify-end gap-8 mt-12">

            <a
                href="{{ $backUrl }}"
                class="text-[21px]
                       font-semibold
                       text-[#08703F]
                       hover:underline"
            >
                Batal
            </a>

            <button
                type="submit"
                id="submitProof"
                class="min-w-[200px]
                       px-8 py-4
                       bg-[#08703F]
                       hover:bg-[#065D35]
                       text-white
                       rounded-[14px]
                       text-[21px]
                       font-semibold
                       disabled:opacity-60
                       transition"
            >
                Upload Bukti
            </button>

        </div>

    </form>

</main>

@endsection


@push('scripts')
<script>
    (function () {
        const form = document.getElementById('proofForm');
        const fileInput = document.getElementById('uploadCover');
        const nominalInput = document.getElementById('nominalBukti');
        const dropzone = document.getElementById('dropzone');
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        const maxSize = 2 * 1024 * 1024;

        // ---------- Format nominal "Rp 100.000" saat diketik ----------
        nominalInput.addEventListener('input', function () {
            const digits = this.value.replace(/\D/g, '');
            this.value = digits ? 'Rp ' + Number(digits).toLocaleString('id-ID') : '';
        });

        // ---------- Dropzone ----------
        function formatSize(bytes) {
            return bytes >= 1024 * 1024
                ? (bytes / 1024 / 1024).toFixed(1) + ' MB'
                : Math.max(1, Math.round(bytes / 1024)) + ' KB';
        }

        function showFile(file) {
            document.getElementById('dropzoneEmpty').classList.toggle('hidden', !!file);
            document.getElementById('dropzoneFile').classList.toggle('hidden', !file);

            if (file) {
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileSize').textContent = formatSize(file.size);
                document.getElementById('fileIcon').className = (file.type === 'application/pdf'
                    ? 'fa-regular fa-file-pdf'
                    : 'fa-regular fa-file-image') + ' text-[34px] text-[#08703F]';
            }
        }

        function acceptFile(file) {
            if (!allowedTypes.includes(file.type)) {
                orgFlash('Format berkas harus PDF, JPG, atau PNG.', 'error');
                return false;
            }

            if (file.size > maxSize) {
                orgFlash('Ukuran berkas maksimal 2 MB.', 'error');
                return false;
            }

            return true;
        }

        fileInput.addEventListener('change', function () {
            const file = this.files[0];

            if (file && !acceptFile(file)) {
                this.value = '';
            }

            showFile(this.files[0]);
        });

        document.getElementById('removeFile').addEventListener('click', function () {
            fileInput.value = '';
            showFile(null);
        });

        ['dragenter', 'dragover'].forEach(function (eventName) {
            dropzone.addEventListener(eventName, function (event) {
                event.preventDefault();
                dropzone.classList.add('border-[#08703F]', 'bg-[#F0F8F4]');
            });
        });

        ['dragleave', 'drop'].forEach(function (eventName) {
            dropzone.addEventListener(eventName, function (event) {
                event.preventDefault();
                dropzone.classList.remove('border-[#08703F]', 'bg-[#F0F8F4]');
            });
        });

        dropzone.addEventListener('drop', function (event) {
            const file = event.dataTransfer.files[0];

            if (!file || !acceptFile(file)) {
                return;
            }

            const transfer = new DataTransfer();
            transfer.items.add(file);
            fileInput.files = transfer.files;
            showFile(file);
        });

        // ---------- Submit ----------
        form.addEventListener('submit', async function (event) {
            event.preventDefault();

            const disbursementId = document.getElementById('judulBukti').value;
            const nominal = nominalInput.value.replace(/\D/g, '');
            const deskripsi = document.getElementById('deskripsiBukti').value.trim();

            const missing = !disbursementId ? 'Pilih pencairan dana terlebih dahulu.'
                : !nominal ? 'Nominal wajib diisi.'
                : !deskripsi ? 'Deskripsi wajib diisi.'
                : !fileInput.files[0] ? 'Unggah berkas bukti pembayaran terlebih dahulu.'
                : null;

            if (missing) {
                orgFlash(missing, 'error');
                return;
            }

            const button = document.getElementById('submitProof');
            const formData = new FormData(form);
            formData.set('nominal', nominal);

            const url = '{{ route('organisasi.kampanye.bukti.store', '__ID__') }}'.replace('__ID__', disbursementId);

            button.disabled = true;
            button.textContent = 'Mengunggah...';

            try {
                await orgRequest(url, { body: formData });
                window.location.href = '{{ $backUrl }}';
            } catch (error) {
                orgFlash(error.message, 'error');
                button.disabled = false;
                button.textContent = 'Upload Bukti';
            }
        });
    })();
</script>
@endpush
