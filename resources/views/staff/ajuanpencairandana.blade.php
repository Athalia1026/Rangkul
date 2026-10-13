
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-auth-session-guard />
    <title>Detail Pengajuan</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'rangkul-green': '#086538',
                        'rangkul-red': '#8C1A11',
                        'rangkul-bg': '#F5F7F4',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F5F7F4;
        }
    </style>
</head>

<body class="text-gray-800 pb-16">

    @include('staff.layouts.navbar')
    
    <!-- TOAST NOTIFIKASI -->
    <div
        id="toast"
        role="status"
        aria-live="polite"
        class="hidden fixed top-5 right-5 z-[70] flex items-center gap-3 px-5 py-3 rounded-xl shadow-lg border text-sm font-semibold transition-all"
    >
        <span id="toast-icon">✓</span>
        <span id="toast-text">Pesan</span>
    </div>

    <!-- CONTENT SECTION -->
    <main class="w-full max-w-[1200px] mx-auto px-8 py-5 space-y-6">

        <div id="pengajuan-error" class="hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-center text-sm text-red-700"></div>

        <!-- TOMBOL KEMBALI -->
        <div>
            <a
                href="{{ route('staff.beranda') }}"
                class="inline-flex items-center gap-2 bg-[#d1e7dd] hover:bg-green-200 text-gray-800 px-6 py-2 rounded-lg shadow-sm font-bold text-sm transition active:scale-95"
            >
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>


        <!-- ====================================== -->
        <!-- GRID UTAMA 2 KOLOM -->
        <!-- ====================================== -->

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">

            <!-- ====================================== -->
            <!-- KOLOM KIRI -->
            <!-- ====================================== -->

            <div class="flex flex-col gap-6 w-full min-w-0">

                <!-- CARD INFORMASI PENGAJUAN -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 w-full">

                    <div class="bg-rangkul-green text-white text-center py-3 font-semibold text-lg">
                        Informasi Pengajuan
                    </div>

                    <div class="px-6 pt-3 pb-6 space-y-4">

                        <!-- STATUS -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">
                                Status
                            </label>

                            <span id="statusPengajuan" class="text-orange-400 font-bold text-xl">
                                -
                            </span>
                            <p id="alasanTolakInfo" class="hidden text-sm text-rangkul-red mt-1"></p>
                        </div>

                        <!-- NAMA ORGANISASI -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">
                                Nama Organisasi
                            </label>

                            <input
                                id="namaOrganisasi"
                                type="text"
                                readonly
                                value="-"
                                class="w-full border border-black rounded-lg px-3 py-2 focus:outline-none"
                            >
                        </div>

                        <!-- JUMLAH DIAJUKAN -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">
                                Jumlah Diajukan
                            </label>

                            <input
                                id="jumlahDiajukan"
                                type="text"
                                readonly
                                value="-"
                                class="w-full border border-black rounded-lg px-3 py-2 focus:outline-none"
                            >
                        </div>

                        <!-- DESKRIPSI -->
                        <div class="py-3">
                            <label class="block text-sm font-bold text-gray-700 mb-1">
                                Deskripsi
                            </label>

                            <textarea
                                id="deskripsiPengajuan"
                                readonly
                                rows="4"
                                class="w-full border border-black rounded-lg px-3 py-2 focus:outline-none resize-none"
                            >-</textarea>
                        </div>

                    </div>

                </div>
                <!-- END CARD INFORMASI PENGAJUAN -->


                <!-- ====================================== -->
                <!-- SECTION AKSI DI BAWAH CARD KIRI -->
                <!-- ====================================== -->

                <section id="aksiPengajuan" class="hidden w-full">

                    <div class="grid grid-cols-3 gap-4 w-full">

                        <!-- TOMBOL BATAL -->
                        <a
                            href="{{ route('staff.beranda') }}"
                            class="w-full min-w-0 flex items-center justify-center py-3 px-3 bg-[#D9D9D9] hover:bg-gray-300 text-gray-900 font-bold rounded-xl text-center text-sm shadow-sm transition active:scale-95"
                        >
                            Batal
                        </a>

                        <!-- TOMBOL SETUJUI -->
                        <button
                            type="button"
                            id="btnSetujui"
                            class="w-full min-w-0 py-3 px-3 bg-rangkul-green hover:bg-[#064e2b] text-white font-bold rounded-xl text-center text-sm shadow-sm transition active:scale-95 cursor-pointer"
                        >
                            Setujui
                        </button>

                        <!-- TOMBOL TOLAK -->
                        <button
                            type="button"
                            id="btnTolak"
                            class="w-full min-w-0 py-3 px-3 bg-rangkul-red hover:bg-[#721513] text-white font-bold rounded-xl text-center text-sm shadow-sm transition active:scale-95 cursor-pointer"
                        >
                            Tolak
                        </button>

                    </div>

                </section>
                <!-- END SECTION AKSI -->

            </div>
            <!-- END KOLOM KIRI -->


            <!-- ====================================== -->
            <!-- KOLOM KANAN: BUKTI PEMBELIAN -->
            <!-- ====================================== -->

            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 w-full min-w-0">

                <div class="bg-rangkul-green text-white text-center py-3 font-semibold text-lg">
                    Bukti Pembelian
                </div>

                <div class="p-8">

                    <div id="buktiPreview" class="border-2 border-dashed border-gray-300 rounded-lg h-[350px] flex flex-col items-center justify-center text-gray-400 overflow-hidden">

                        <i class="fa-regular fa-image text-6xl mb-3"></i>

                        <p class="font-medium">
                            Pratinjau Gambar
                        </p>

                    </div>

                </div>

            </div>
            <!-- END KOLOM KANAN -->

        </div>
        <!-- END GRID UTAMA -->

    </main>

    
        <!-- MODAL KONFIRMASI PENOLAKAN -->
        <div
            id="modalTolak"
            class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4"
        >
            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full text-center">

                <!-- ICON -->
                <div class="text-rangkul-red text-5xl mb-4">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>

                <!-- JUDUL -->
                <h2 class="text-2xl font-bold text-gray-900 mb-3">
                    Konfirmasi Penolakan
                </h2>

                <p class="text-sm text-gray-600 mb-6">
                    Apakah Anda yakin ingin menolak permohonan pencairan dana ini?
                </p>

                <!-- ALASAN PENOLAKAN -->
                <div class="text-left">

                    <label class="block text-sm font-bold text-gray-900 mb-2">
                        Alasan Penolakan
                    </label>

                    <textarea
                        id="alasanTolak"
                        rows="4"
                        placeholder="Tuliskan alasan penolakan di sini..."
                        class="w-full border border-gray-400 rounded-xl p-3.5 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-rangkul-red resize-none"
                    ></textarea>

                    <!-- PERINGATAN TANPA POPUP -->
                    <p
                        id="warningAlasan"
                        class="hidden text-xs text-red-600 mt-2 font-medium"
                    >
                        * Harap isi alasan penolakan terlebih dahulu!
                    </p>

                </div>

                <!-- TOMBOL -->
                <div class="flex justify-end gap-3 mt-6">

                    <button
                        type="button"
                        id="btnBatalTolak"
                        class="px-7 py-2.5 bg-[#D9D9D9] hover:bg-gray-300 text-gray-900 font-semibold rounded-xl text-sm transition cursor-pointer"
                    >
                        Batal
                    </button>

                    <button
                        type="button"
                        id="btnConfirmTolak"
                        class="px-7 py-2.5 bg-rangkul-red hover:bg-[#721513] text-white font-semibold rounded-xl text-sm transition cursor-pointer"
                    >
                        Tolak
                    </button>

                </div>

            </div>
        </div>

    
<script>

    const { request, formatRupiah, escapeHtml } = window.RangkulAdmin;
    const proofId = @json($proofId);
    const BERANDA_URL = @json(route('staff.beranda'));

    // Bukti diperiksa staf lebih dulu, lalu diteruskan ke manajer untuk keputusan akhir
    const STATUS_TAMPIL = {
        menunggu_staf: { label: 'Menunggu', className: 'text-orange-400' },
        menunggu_manajer: { label: 'Diteruskan ke Manajer', className: 'text-orange-400' },
        diterima: { label: 'Disetujui', className: 'text-rangkul-green' },
        ditolak: { label: 'Ditolak', className: 'text-rangkul-red' }
    };


    // ==========================================
    // DATA PENGAJUAN
    // ==========================================

    function renderBukti(attachment) {

        const preview = document.getElementById('buktiPreview');

        if (!attachment) {
            preview.innerHTML = `
                <i class="fa-regular fa-image text-6xl mb-3"></i>

                <p class="font-medium">
                    Tidak ada lampiran
                </p>
            `;
            return;
        }

        if (attachment.is_image) {
            preview.innerHTML = `
                <a href="${escapeHtml(attachment.url)}" target="_blank" rel="noopener" class="w-full h-full">
                    <img src="${escapeHtml(attachment.url)}" alt="Bukti Pembelian" class="w-full h-full object-contain">
                </a>
            `;
            return;
        }

        preview.innerHTML = `
            <i class="fa-regular fa-file-pdf text-6xl mb-3"></i>

            <a href="${escapeHtml(attachment.url)}" target="_blank" rel="noopener" class="font-medium text-rangkul-green underline">
                Lihat Lampiran
            </a>
        `;

    }

    function renderPengajuan(data) {

        const status = STATUS_TAMPIL[data.stage] || { label: data.status, className: 'text-gray-700' };
        const statusElement = document.getElementById('statusPengajuan');
        const alasanTolakInfo = document.getElementById('alasanTolakInfo');

        statusElement.textContent = status.label;
        statusElement.className = status.className + ' font-bold text-xl';

        alasanTolakInfo.textContent = data.alasan_tolak ? 'Alasan penolakan: ' + data.alasan_tolak : '';
        alasanTolakInfo.classList.toggle('hidden', !data.alasan_tolak);

        document.getElementById('namaOrganisasi').value = data.organization_name;
        document.getElementById('jumlahDiajukan').value = formatRupiah(data.amount);
        document.getElementById('deskripsiPengajuan').value = [data.alokasi_dana, data.deskripsi]
            .filter(Boolean)
            .join('\n\n') || '-';

        renderBukti(data.attachment);

        // Tombol aksi hanya untuk bukti yang belum diperiksa staf
        document.getElementById('aksiPengajuan').classList.toggle('hidden', data.stage !== 'menunggu_staf');

    }

    async function loadPengajuan() {

        const error = document.getElementById('pengajuan-error');

        try {
            const { data } = await request('/api/admin/proof-verifications/' + encodeURIComponent(proofId));
            error.classList.add('hidden');
            renderPengajuan(data);
        } catch (exception) {
            error.textContent = exception.message;
            error.classList.remove('hidden');
            document.getElementById('statusPengajuan').textContent = '-';
        }

    }

    async function verifikasiBukti(body) {

        // Mencegah tombol diklik berulang kali
        btnSetujui.disabled = true;
        btnTolak.disabled = true;
        btnConfirmTolak.disabled = true;

        try {
            const result = await request('/api/admin/proof-verifications/' + encodeURIComponent(proofId) + '/verify', {
                method: 'PUT',
                body
            });

            showToast(result.message, body.status === 'diterima' ? 'success' : 'danger');

            // Kembali ke Beranda Staff
            setTimeout(() => {
                window.location.href = BERANDA_URL;
            }, 1800);
        } catch (exception) {
            btnSetujui.disabled = false;
            btnTolak.disabled = false;
            btnConfirmTolak.disabled = false;
            showToast(exception.message, 'danger');
        }

    }


    // ==========================================
    // ELEMENT
    // ==========================================

    const btnSetujui = document.getElementById('btnSetujui');

    const btnTolak = document.getElementById('btnTolak');
    const modalTolak = document.getElementById('modalTolak');

    const btnBatalTolak = document.getElementById('btnBatalTolak');
    const btnConfirmTolak = document.getElementById('btnConfirmTolak');

    const alasanTolak = document.getElementById('alasanTolak');
    const warningAlasan = document.getElementById('warningAlasan');


    // ==========================================
    // FUNGSI TOAST NOTIFIKASI
    // ==========================================

    function showToast(message, type) {

        const toast = document.getElementById('toast');
        const icon = document.getElementById('toast-icon');
        const text = document.getElementById('toast-text');

        text.textContent = message;

        if (type === 'success') {

            icon.textContent = '✓';

            toast.className =
                'fixed top-5 right-5 z-[70] flex items-center gap-3 ' +
                'px-5 py-3 rounded-xl shadow-lg border text-sm font-semibold ' +
                'bg-emerald-50 text-emerald-800 border-emerald-300 transition-all';

        } else {

            icon.textContent = '⚠';

            toast.className =
                'fixed top-5 right-5 z-[70] flex items-center gap-3 ' +
                'px-5 py-3 rounded-xl shadow-lg border text-sm font-semibold ' +
                'bg-red-50 text-red-800 border-red-300 transition-all';

        }

        toast.classList.remove('hidden');

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);

    }


    // ==========================================
    // TOMBOL SETUJUI
    // ==========================================

    btnSetujui.addEventListener('click', function () {

        // Setujui → bukti diteruskan ke manajer untuk verifikasi akhir
        verifikasiBukti({ status: 'diterima' });

    });


    // ==========================================
    // TOMBOL TOLAK
    // ==========================================

    btnTolak.addEventListener('click', function () {

        // Reset form
        alasanTolak.value = '';
        warningAlasan.classList.add('hidden');

        // Tampilkan modal alasan penolakan
        modalTolak.classList.remove('hidden');

    });


    // ==========================================
    // BATAL PENOLAKAN
    // ==========================================

    btnBatalTolak.addEventListener('click', function () {

        modalTolak.classList.add('hidden');

        alasanTolak.value = '';
        warningAlasan.classList.add('hidden');

    });


    // ==========================================
    // KONFIRMASI PENOLAKAN
    // ==========================================

    btnConfirmTolak.addEventListener('click', function () {

        const alasan = alasanTolak.value.trim();

        // Kalau alasan masih kosong
        if (alasan === '') {

            warningAlasan.classList.remove('hidden');

            alasanTolak.focus();

            return;
        }

        // Kalau alasan sudah terisi
        warningAlasan.classList.add('hidden');

        modalTolak.classList.add('hidden');

        verifikasiBukti({ status: 'ditolak', catatan: alasan });

    });


    // ==========================================
    // HILANGKAN WARNING SAAT MULAI MENGETIK
    // ==========================================

    alasanTolak.addEventListener('input', function () {

        if (alasanTolak.value.trim() !== '') {
            warningAlasan.classList.add('hidden');
        }

    });


    // ==========================================
    // KLIK DI LUAR MODAL
    // ==========================================

    window.addEventListener('click', function (event) {

        if (event.target === modalTolak) {

            modalTolak.classList.add('hidden');

            warningAlasan.classList.add('hidden');

        }

    });

    loadPengajuan();

</script>
</body>
</html>