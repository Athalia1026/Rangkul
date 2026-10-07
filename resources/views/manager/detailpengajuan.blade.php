<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-auth-session-guard />
    <title>Detail Pengajuan</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

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
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F5F7F4;
        }
    </style>
</head>

<body class="text-black-800">

@include('manager.layouts.navbar')

    <!-- CONTENT SECTION -->
    <div class="w-full max-w-[1200px] mx-auto px-8 py-5 space-y-6">

        <!-- TOMBOL KEMBALI -->
        <div>
            <a
                href="/manager/home"
                class="inline-flex items-center gap-2 bg-[#d1e7dd] hover:bg-green-200 text-gray-800 px-6 py-2 rounded-lg shadow-sm font-bold text-sm transition active:scale-95"
            >
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>

        <div id="pengajuan-error" class="hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-center text-sm text-red-700"></div>

        <!-- Grid Layout 2 Kolom -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- Card Kiri: Informasi Pengajuan -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">

                <div class="bg-rangkul-green text-white text-center py-3 font-semibold text-lg">
                    Informasi Pengajuan
                </div>

                <div class="px-6 pt-3 pb-6 space-y-4">

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1 ma-2">
                            Status
                        </label>

                        <span id="statusPengajuan" class="text-orange-400 font-bold text-xl">
                            Memuat...
                        </span>

                        <p id="alasanTolakInfo" class="hidden text-sm text-gray-600 mt-1"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Nama Organisasi
                        </label>

                        <input
                            id="namaOrganisasi"
                            type="text"
                            readonly
                            value=""
                            class="w-full border border-black rounded-lg px-3 py-2 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Jumlah Diajukan
                        </label>

                        <input
                            id="jumlahDiajukan"
                            type="text"
                            readonly
                            value=""
                            class="w-full border border-black rounded-lg px-3 py-2 focus:outline-none"
                        >
                    </div>

                    <div class="py-3">
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Deskripsi
                        </label>

                        <textarea
                            id="deskripsiPengajuan"
                            readonly
                            rows="4"
                            class="w-full border border-black rounded-lg px-3 py-2 focus:outline-none resize-none"
                        ></textarea>
                    </div>

                </div>
            </div>

            <!-- Card Kanan: Bukti Pembelian -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">

                <div class="bg-rangkul-green text-white text-center py-3 font-semibold text-lg">
                    Bukti Pembelian
                </div>

                <div class="p-8 h-full">
                    <div id="buktiPreview" class="border-2 border-dashed border-gray-300 rounded-lg h-[350px] flex flex-col items-center justify-center text-gray-400 overflow-hidden">
                        <i class="fa-regular fa-image text-6xl mb-3"></i>

                        <p class="font-medium">
                            Pratinjau Gambar
                        </p>
                    </div>
                </div>

            </div>

        </div>

        <!-- Tombol Aksi di Bawah -->
        <div id="aksiPengajuan" class="hidden w-[590px] flex gap-6 mt-10">

            <!-- SETUJUI -->
            <button
                id="btnSetujui"
                class="flex-1 bg-rangkul-green hover:bg-green-900 text-white font-bold py-4 rounded-xl shadow-md transition transform active:scale-95"
            >
                Setujui
            </button>

            <!-- TOLAK -->
            <button
                id="btnTolak"
                class="flex-1 bg-rangkul-red hover:bg-red-900 text-white font-bold py-4 rounded-xl shadow-md transition transform active:scale-95"
            >
                Tolak
            </button>

        </div>

    </div>

    <!-- ====================================== -->
    <!-- ALERT BERHASIL DISETUJUI -->
    <!-- ====================================== -->

    <div
        id="alertSetujui"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60] hidden"
    >
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 text-center">

            <div class="flex justify-center mb-4">
                <div class="text-rangkul-green text-5xl">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-3">
                Pengajuan Disetujui
            </h2>

            <p class="text-gray-600 text-base">
                Permohonan pencairan dana berhasil disetujui.
            </p>

            <button
                id="btnTutupAlertSetujui"
                class="mt-6 px-8 py-3 bg-rangkul-green hover:bg-green-900 text-white font-bold rounded-xl transition shadow-md"
            >
                Mengerti
            </button>

        </div>
    </div>

    <!-- ====================================== -->
    <!-- POPUP KONFIRMASI TOLAK -->
    <!-- ====================================== -->

    <div
        id="modalTolak"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden"
    >
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full mx-4">

            <!-- ICON WARNING -->
            <div class="flex justify-center mb-4">
                <div class="text-rangkul-red">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-20 w-20"
                        fill="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 16h2v2h-2zm0-6h2v4h-2z"/>
                    </svg>
                </div>
            </div>

            <!-- JUDUL -->
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-6">
                Konfirmasi Penolakan
            </h2>

            <!-- PERTANYAAN -->
            <p class="text-gray-700 text-center text-lg mb-5">
                Apakah Anda yakin ingin menolak permohonan pencairan dana ini?
            </p>

            <!-- FORM ALASAN PENOLAKAN -->
            <div class="mb-6 text-left">

                <label class="block text-lg font-bold text-gray-900 mb-2">
                    Alasan Penolakan
                </label>

                <textarea
                    id="alasanTolak"
                    class="w-full h-48 p-4 border border-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-rangkul-red placeholder-gray-400 resize-none text-lg"
                    placeholder="Tuliskan alasan penolakan disini......"
                ></textarea>

                <p class="mt-2 text-sm text-gray-500 font-medium">
                    Kolom ini wajib diisi untuk melanjutkan proses.
                </p>

            </div>

            <!-- BUTTONS -->
            <div class="flex justify-end gap-4 mt-8">

                <button
                    id="btnBatalTolak"
                    class="px-10 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold rounded-xl transition shadow-md transform active:scale-95"
                >
                    Batal
                </button>

                <button
                    id="btnConfirmTolak"
                    class="px-10 py-3 bg-rangkul-red hover:bg-red-900 text-white font-bold rounded-xl transition shadow-md transform active:scale-95"
                >
                    Tolak
                </button>

            </div>

        </div>
    </div>

    <!-- ====================================== -->
    <!-- ALERT ALASAN BELUM DIISI -->
    <!-- ====================================== -->

    <div
        id="alertAlasan"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60] hidden"
    >
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 text-center">

            <div class="flex justify-center mb-4">
                <div class="text-rangkul-red text-5xl">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
            </div>

            <h2 id="alertAlasanJudul" class="text-2xl font-bold text-gray-900 mb-3">
                Alasan Belum Diisi
            </h2>

            <p id="alertAlasanPesan" class="text-gray-600 text-base">
                Silakan isi alasan penolakan terlebih dahulu
                sebelum melanjutkan.
            </p>

            <button
                id="btnTutupAlert"
                class="mt-6 px-8 py-3 bg-gray-600 hover:bg-green-900 text-white font-bold rounded-xl transition shadow-md"
            >
                Mengerti
            </button>

        </div>
    </div>

    <!-- ====================================== -->
    <!-- ALERT BERHASIL DITOLAK -->
    <!-- ====================================== -->

    <div
        id="alertTolakBerhasil"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[60] hidden"
    >
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 text-center">

            <div class="flex justify-center mb-4">
                <div class="text-rangkul-green text-5xl">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-3">
                Pengajuan Ditolak
            </h2>

            <p class="text-gray-600 text-base">
                Permohonan pencairan dana berhasil ditolak.
            </p>

            <button
                id="btnTutupAlertTolak"
                class="mt-6 px-8 py-3 bg-rangkul-green hover:bg-green-900 text-white font-bold rounded-xl transition shadow-md"
            >
                Mengerti
            </button>

        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script>

        const { request, formatRupiah, escapeHtml } = window.RangkulAdmin;
        const disbursementId = @json($disbursementId);

        const STATUS_TAMPIL = {
            menunggu: { label: 'Menunggu', className: 'text-orange-400' },
            diterima: { label: 'Disetujui', className: 'text-rangkul-green' },
            ditolak: { label: 'Ditolak', className: 'text-rangkul-red' }
        };

        // ==========================
        // DATA PENGAJUAN
        // ==========================

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

            const status = STATUS_TAMPIL[data.status] || { label: data.status, className: 'text-gray-700' };
            const statusElement = document.getElementById('statusPengajuan');
            const alasanTolakInfo = document.getElementById('alasanTolakInfo');

            statusElement.textContent = status.label;
            statusElement.className = status.className + ' font-bold text-xl';

            alasanTolakInfo.textContent = data.alasan_tolak ? 'Alasan penolakan: ' + data.alasan_tolak : '';
            alasanTolakInfo.classList.toggle('hidden', !data.alasan_tolak);

            document.getElementById('namaOrganisasi').value = data.organization_name;
            document.getElementById('jumlahDiajukan').value = formatRupiah(data.amount);
            document.getElementById('deskripsiPengajuan').value = [data.alokasi_dana, data.alasan]
                .filter(Boolean)
                .join('\n\n');

            renderBukti(data.attachment);

            // Tombol aksi hanya untuk pengajuan yang belum diverifikasi
            document.getElementById('aksiPengajuan').classList.toggle('hidden', data.status !== 'menunggu');

        }

        async function loadPengajuan() {

            const error = document.getElementById('pengajuan-error');

            try {
                const { data } = await request('/api/admin/disbursements/' + encodeURIComponent(disbursementId));
                error.classList.add('hidden');
                renderPengajuan(data);
            } catch (exception) {
                error.textContent = exception.message;
                error.classList.remove('hidden');
                document.getElementById('statusPengajuan').textContent = '-';
            }

        }

        async function verifikasiPengajuan(body) {
            return request('/api/admin/disbursements/' + encodeURIComponent(disbursementId) + '/verify', {
                method: 'PUT',
                body
            });
        }

        // ==========================
        // POPUP PERINGATAN / GAGAL
        // ==========================

        const alertAlasan = document.getElementById('alertAlasan');
        const btnTutupAlert = document.getElementById('btnTutupAlert');
        const alertAlasanJudul = document.getElementById('alertAlasanJudul');
        const alertAlasanPesan = document.getElementById('alertAlasanPesan');
        const judulAlasanAwal = alertAlasanJudul.textContent;
        const pesanAlasanAwal = alertAlasanPesan.textContent;

        function tampilkanPeringatan(judul, pesan) {
            alertAlasanJudul.textContent = judul || judulAlasanAwal;
            alertAlasanPesan.textContent = pesan || pesanAlasanAwal;
            alertAlasan.classList.remove('hidden');
        }

        // ==========================
        // POPUP SETUJUI
        // ==========================

        const btnSetujui = document.getElementById('btnSetujui');
        const alertSetujui = document.getElementById('alertSetujui');
        const btnTutupAlertSetujui = document.getElementById('btnTutupAlertSetujui');

        // Klik Setujui → simpan persetujuan lalu tampilkan popup berhasil
        btnSetujui.addEventListener('click', async () => {

            btnSetujui.disabled = true;

            try {
                await verifikasiPengajuan({ status: 'diterima' });
                alertSetujui.classList.remove('hidden');
            } catch (exception) {
                tampilkanPeringatan('Gagal Menyetujui', exception.message);
            } finally {
                btnSetujui.disabled = false;
            }

        });

        // Klik Mengerti → tutup popup
        btnTutupAlertSetujui.addEventListener('click', () => {
            alertSetujui.classList.add('hidden');
            loadPengajuan();
        });

        // ==========================
        // POPUP TOLAK
        // ==========================

        const btnTolak = document.getElementById('btnTolak');
        const modalTolak = document.getElementById('modalTolak');
        const btnBatalTolak = document.getElementById('btnBatalTolak');
        const btnConfirmTolak = document.getElementById('btnConfirmTolak');
        const alasanTolak = document.getElementById('alasanTolak');

        const alertTolakBerhasil = document.getElementById('alertTolakBerhasil');
        const btnTutupAlertTolak = document.getElementById('btnTutupAlertTolak');

        // Klik Tolak → buka popup
        btnTolak.addEventListener('click', () => {
            modalTolak.classList.remove('hidden');
        });

        // Klik Batal → tutup popup
        btnBatalTolak.addEventListener('click', () => {
            modalTolak.classList.add('hidden');
        });

        // Klik Tolak → cek alasan lalu simpan penolakan
        btnConfirmTolak.addEventListener('click', async () => {

            const alasan = alasanTolak.value.trim();

            // Kalau alasan kosong → tampilkan alert di halaman
            if (alasan === "") {
                tampilkanPeringatan();
                return;
            }

            btnConfirmTolak.disabled = true;

            try {
                await verifikasiPengajuan({ status: 'ditolak', alasan_tolak: alasan });

                modalTolak.classList.add('hidden');
                alertTolakBerhasil.classList.remove('hidden');

                // Kosongkan alasan setelah berhasil
                alasanTolak.value = '';
            } catch (exception) {
                tampilkanPeringatan('Gagal Menolak', exception.message);
            } finally {
                btnConfirmTolak.disabled = false;
            }

        });

        // Tutup alert alasan
        btnTutupAlert.addEventListener('click', () => {
            alertAlasan.classList.add('hidden');
        });

        btnTutupAlertTolak.addEventListener('click', () => {
            alertTolakBerhasil.classList.add('hidden');
            loadPengajuan();
        });

        // ==========================
        // KLIK DI LUAR POPUP
        // ==========================

        window.addEventListener('click', (e) => {

        if (e.target === modalTolak) {
            modalTolak.classList.add('hidden');
        }

        if (e.target === alertAlasan) {
            alertAlasan.classList.add('hidden');
        }

        if (e.target === alertTolakBerhasil) {
            alertTolakBerhasil.classList.add('hidden');
            loadPengajuan();
        }

        if (e.target === alertSetujui) {
            alertSetujui.classList.add('hidden');
            loadPengajuan();
        }

    });

        loadPengajuan();

    </script>

</body>
</html>