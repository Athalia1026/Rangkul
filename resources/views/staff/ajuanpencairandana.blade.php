
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        <!-- TOMBOL KEMBALI -->
        <div>
            <a
                href="/staff/beranda"
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

                            <span class="text-orange-400 font-bold text-xl">
                                Menunggu
                            </span>
                        </div>

                        <!-- NAMA ORGANISASI -->
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

                        <!-- JUMLAH DIAJUKAN -->
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

                        <!-- DESKRIPSI -->
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
                <!-- END CARD INFORMASI PENGAJUAN -->


                <!-- ====================================== -->
                <!-- SECTION AKSI DI BAWAH CARD KIRI -->
                <!-- ====================================== -->

                <section class="w-full">

                    <div class="grid grid-cols-3 gap-4 w-full">

                        <!-- TOMBOL BATAL -->
                        <a
                            href="/staff/beranda"
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

                    <div class="border-2 border-dashed border-gray-300 rounded-lg h-[350px] flex flex-col items-center justify-center text-gray-400">

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

        // Mencegah tombol diklik berulang kali
        btnSetujui.disabled = true;
        btnTolak.disabled = true;

        // Menampilkan notifikasi berhasil
        showToast(
            'Permohonan pencairan dana berhasil disetujui!',
            'success'
        );

        // Kembali ke Beranda Staff
        setTimeout(() => {
            window.location.href = '/staff/beranda';
        }, 1800);

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

        // Mencegah klik berulang
        btnConfirmTolak.disabled = true;
        btnSetujui.disabled = true;
        btnTolak.disabled = true;

        // Tampilkan toast penolakan
        showToast(
            'Permohonan pencairan dana berhasil ditolak!',
            'danger'
        );

        // Kembali ke Beranda Staff
        setTimeout(() => {
            window.location.href = '/staff/beranda';
        }, 1800);

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

</script>
</body>
</html>