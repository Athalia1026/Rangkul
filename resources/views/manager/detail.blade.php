<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <!-- NAVIGATION BAR -->
    <nav class="bg-white border-b border-gray-200 px-8 py-3 flex items-center justify-between">

        <!-- LOGO RANGKUL -->
        <div class="flex items-center w-1/3">
            <img
                src="/images/logo.png"
                alt="Rangkul"
                class="h-10 w-auto"
            >
        </div>

        <!-- MENU NAVBAR -->
        <div class="flex gap-12 font-semibold text-sm">

            <a href="#" class="hover:text-green-700">
                Beranda
            </a>

            <a href="#" class="hover:text-green-700">
                Daftar Pengguna
            </a>

            <a href="#" class="hover:text-green-700">
                Laporan Transaksi
            </a>

        </div>

        <!-- PROFILE -->
        <div class="flex items-center justify-end w-1/3">
            <div class="text-rangkul-green text-3xl cursor-pointer">
                <i class="fa-solid fa-circle-user"></i>
            </div>
        </div>

    </nav>

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

                        <span class="text-orange-400 font-bold text-xl">
                            Menunggu
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Nama Organisasi
                        </label>

                        <input
                            type="text"
                            readonly
                            value="Asrama Pemberdayaan Yatim dan Dhuafa"
                            class="w-full border border-black rounded-lg px-3 py-2 focus:outline-none"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Jumlah Diajukan
                        </label>

                        <input
                            type="text"
                            readonly
                            value="Rp 150.000"
                            class="w-full border border-black rounded-lg px-3 py-2 focus:outline-none"
                        >
                    </div>

                    <div class="py-3">
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Deskripsi
                        </label>

                        <textarea
                            readonly
                            rows="4"
                            class="w-full border border-black rounded-lg px-3 py-2 focus:outline-none resize-none"
                        >Butuh sembako</textarea>
                    </div>

                </div>
            </div>

            <!-- Card Kanan: Bukti Pembelian -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">

                <div class="bg-rangkul-green text-white text-center py-3 font-semibold text-lg">
                    Bukti Pembelian
                </div>

                <div class="p-8 h-full">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg h-[350px] flex flex-col items-center justify-center text-gray-400">
                        <i class="fa-regular fa-image text-6xl mb-3"></i>

                        <p class="font-medium">
                            Pratinjau Gambar
                        </p>
                    </div>
                </div>

            </div>

        </div>

        <!-- Tombol Aksi di Bawah -->
        <div class="w-[590px] flex gap-6 mt-10">

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

            <h2 class="text-2xl font-bold text-gray-900 mb-3">
                Alasan Belum Diisi
            </h2>

            <p class="text-gray-600 text-base">
                Silakan isi alasan penolakan terlebih dahulu
                sebelum melanjutkan.
            </p>

            <button
                id="btnTutupAlert"
                class="mt-6 px-8 py-3 bg-rangkul-green hover:bg-green-900 text-white font-bold rounded-xl transition shadow-md"
            >
                Mengerti
            </button>

        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script>

        // ==========================
        // POPUP SETUJUI
        // ==========================

        const btnSetujui = document.getElementById('btnSetujui');
        const alertSetujui = document.getElementById('alertSetujui');
        const btnTutupAlertSetujui = document.getElementById('btnTutupAlertSetujui');

        // Klik Setujui → tampilkan popup berhasil
        btnSetujui.addEventListener('click', () => {
            alertSetujui.classList.remove('hidden');
        });

        // Klik Mengerti → tutup popup
        btnTutupAlertSetujui.addEventListener('click', () => {
            alertSetujui.classList.add('hidden');
        });


        // ==========================
        // POPUP TOLAK
        // ==========================

        const btnTolak = document.getElementById('btnTolak');
        const modalTolak = document.getElementById('modalTolak');
        const btnBatalTolak = document.getElementById('btnBatalTolak');
        const btnConfirmTolak = document.getElementById('btnConfirmTolak');
        const alasanTolak = document.getElementById('alasanTolak');

        // ALERT ALASAN BELUM DIISI
        const alertAlasan = document.getElementById('alertAlasan');
        const btnTutupAlert = document.getElementById('btnTutupAlert');


        // Klik Tolak → buka popup
        btnTolak.addEventListener('click', () => {
            modalTolak.classList.remove('hidden');
        });


        // Klik Batal → tutup popup
        btnBatalTolak.addEventListener('click', () => {
            modalTolak.classList.add('hidden');
        });


        // Klik Tolak → cek alasan
        btnConfirmTolak.addEventListener('click', () => {

            const alasan = alasanTolak.value.trim();

            // Kalau alasan kosong → tampilkan alert di halaman
            if (alasan === "") {
                alertAlasan.classList.remove('hidden');
                return;
            }

            // Kalau alasan sudah diisi
            alert('Permohonan berhasil ditolak!');

            modalTolak.classList.add('hidden');

            // Kosongkan alasan setelah berhasil
            alasanTolak.value = '';
        });


        // Tutup alert alasan
        btnTutupAlert.addEventListener('click', () => {
            alertAlasan.classList.add('hidden');
        });


        // ==========================
        // KLIK DI LUAR POPUP
        // ==========================

        window.addEventListener('click', (e) => {

            if (e.target === modalKonfirmasi) {
                modalKonfirmasi.classList.add('hidden');
            }

            if (e.target === modalTolak) {
                modalTolak.classList.add('hidden');
            }

            if (e.target === alertAlasan) {
                alertAlasan.classList.add('hidden');
            }

        });

    </script>

</body>
</html>