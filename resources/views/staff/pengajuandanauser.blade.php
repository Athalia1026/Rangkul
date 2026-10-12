<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Information</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    >

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

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap"
        rel="stylesheet"
    >

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
    <div id="toast" class="hidden fixed top-5 right-5 z-50 flex items-center gap-3 px-5 py-3 rounded-xl shadow-lg border text-sm font-semibold transition-all">
        <span id="toast-icon">✓</span>
        <span id="toast-text">Pesan</span>
    </div>

    <!-- MAIN CONTENT -->
    <main class="w-full max-w-[1200px] mx-auto px-8 py-5 space-y-6">
        
        <!-- INFORMASI ASRAMA -->
        <section class="w-full max-w-[1200px] mx-auto bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="grid grid-cols-1 md:grid-cols-2">

                <!-- FOTO -->
                <div class="h-[320px]">
                    <img
                        src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=900&q=80"
                        alt="Foto Asrama"
                        class="w-full h-full object-cover"
                    >
                </div>

                <!-- INFORMASI -->
                <div class="px-8 py-4 flex flex-col">

                    <h1 class="text-2xl font-bold text-gray-900 mb-8">
                        Asrama Pemberdayaan Yatim dan Dhuafa
                    </h1>

                    <div class="grid grid-cols-2 gap-y-7">

                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Jenis
                            </p>
                            <p class="text-lg font-bold">
                                Panti
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Status
                            </p>
                            <p class="text-lg font-bold text-orange-400">
                                Menunggu
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Jumlah Anak Asuh
                            </p>
                            <p class="text-lg font-bold">
                                50
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Tanggal Daftar
                            </p>
                            <p class="text-lg font-bold">
                                3 Februari 2026
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </section>

        <!-- INFORMASI PENDAFTARAN & DOKUMEN + AKSI -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

            <!-- INFORMASI PENDAFTARAN -->
            <section>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    Informasi Pendaftaran
                </h2>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                    <h3 class="text-lg font-bold mb-5">
                        Details
                    </h3>

                    <!-- ALAMAT & KOTA -->
                    <div class="grid grid-cols-2 gap-6 border-t border-gray-200 py-5">

                        <div class="flex gap-3">
                            <span class="text-rangkul-green text-lg">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>

                            <div>
                                <p class="font-bold text-sm mb-1">
                                    Alamat
                                </p>

                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Jl. Delta Raya III No.4, Ngingas, Kec. Waru,
                                    Kabupaten Sidoarjo, Jawa Timur 61256
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <span class="text-rangkul-green text-lg">
                                <i class="fa-solid fa-building"></i>
                            </span>

                            <div>
                                <p class="font-bold text-sm mb-1">
                                    Kota
                                </p>

                                <p class="text-sm text-gray-600">
                                    Sidoarjo
                                </p>
                            </div>
                        </div>

                    </div>


                    <!-- KONTAK & EMAIL -->
                    <div class="grid grid-cols-2 gap-6 border-t border-gray-200 py-5">

                        <div class="flex gap-3">
                            <span class="text-rangkul-green text-lg">
                                <i class="fa-solid fa-phone"></i>
                            </span>

                            <div>
                                <p class="font-bold text-sm mb-1">
                                    Kontak
                                </p>

                                <p class="text-sm text-gray-600 leading-relaxed">
                                    (031) 8552980<br>
                                    Asrama Yatim dan Dhuafa
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <span class="text-rangkul-green text-lg">
                                <i class="fa-solid fa-envelope"></i>
                            </span>

                            <div>
                                <p class="font-bold text-sm mb-1">
                                    Email
                                </p>

                                <p class="text-sm text-gray-600">
                                    PYIindonesia@gmail.com
                                </p>
                            </div>
                        </div>

                    </div>


                    <!-- DESKRIPSI -->
                    <div class="border-t border-gray-200 pt-5">

                        <div class="flex gap-3">

                            <span class="text-rangkul-green text-lg">
                                <i class="fa-solid fa-align-left"></i>
                            </span>

                            <div>
                                <p class="font-bold text-sm mb-1">
                                    Deskripsi
                                </p>

                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Panti Asuhan kami merupakan lembaga sosial yang
                                    berkomitmen memberikan pengasuhan, pendidikan,
                                    serta pembinaan kepada anak-anak yang membutuhkan.
                                    Kami berupaya menciptakan lingkungan yang aman,
                                    nyaman, dan penuh kasih sayang guna mendukung
                                    tumbuh kembang anak secara optimal.
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

            </section>


            <!-- DOKUMEN -->
            <section>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    Dokumen
                </h2>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

                    <table class="w-full text-left">

                        <thead class="bg-[#d1e7dd]">
                            <tr class="text-sm font-bold text-gray-800">

                                <th class="px-6 py-4 text-center">
                                    Keterangan
                                </th>

                                <th class="px-6 py-4 text-center">
                                    Lampiran
                                </th>

                            </tr>
                        </thead>

                        <tbody class="text-sm">

                            <tr class="border-t border-gray-200">

                                <td class="px-6 py-5">
                                    SK Pendirian / SK Operasional
                                </td>

                                <td class="px-6 py-5">
                                    <a
                                        href="/documents/SK%20Pendirian.pdf"
                                        download
                                        class="inline-flex items-center gap-2 bg-[#d1e7dd] px-4 py-2 rounded-lg w-[200px] hover:bg-[#c3dfd3] transition cursor-pointer"
                                    >
                                        SK Pendirian.pdf
                                    </a>
                                </td>

                            </tr>


                            <tr class="border-t border-gray-200">

                                <td class="px-6 py-5">
                                    KTP Penanggung Jawab
                                </td>

                                <td class="px-6 py-5">
                                    <a
                                        href="/documents/KTP.pdf"
                                        download
                                        class="inline-flex items-center gap-2 bg-[#d1e7dd] px-4 py-2 rounded-lg w-[200px] hover:bg-[#c3dfd3] transition cursor-pointer"
                                    >
                                        KTP.pdf
                                    </a>
                                </td>

                            </tr>


                            <tr class="border-t border-gray-200">

                                <td class="px-6 py-5">
                                    Foto Kegiatan dan Penerima Manfaat
                                </td>

                                <td class="px-6 py-5">
                                    <a
                                        href="/documents/Foto.jpg"
                                        download
                                        class="inline-flex items-center gap-2 bg-[#d1e7dd] px-4 py-2 rounded-lg w-[200px] hover:bg-[#c3dfd3] transition cursor-pointer"
                                    >
                                        Foto.jpg
                                    </a>
                                </td>

                            </tr>


                            <tr class="border-t border-gray-200">

                                <td class="px-6 py-5">
                                    Rekening Lembaga
                                </td>

                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-2 bg-[#d1e7dd] px-4 py-2 rounded-lg w-[200px]">
                                        (Mandiri) 1420026154855
                                    </span>
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

                <!-- SECTION AKSI (3 TOMBOL) -->
                <section class="pt-2">

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        Aksi
                    </h2>

                    <div class="flex items-center gap-4">

                        <!-- 1. TOMBOL BATAL (BALIK KE BERANDA STAFF) -->
                        <a
                            href="/staff/beranda"
                            class="flex-1 py-3 px-6 bg-[#D9D9D9] hover:bg-gray-300 text-gray-900 font-bold rounded-xl text-center text-sm shadow-xs transition active:scale-98"
                        >
                            Batal
                        </a>

                        <!-- 2. TOMBOL SETUJUI (BUKA MODAL FRAME 376.PNG) -->
                        <button
                            type="button"
                            onclick="openModalSetujui()"
                            class="flex-1 py-3 px-6 bg-rangkul-green hover:bg-[#064e2b] text-white font-bold rounded-xl text-center text-sm shadow-xs transition active:scale-98 cursor-pointer"
                        >
                            Setujui
                        </button>

                        <!-- 3. TOMBOL TOLAK (BUKA MODAL FRAME 376-1.PNG) -->
                        <button
                            type="button"
                            onclick="openModalTolak()"
                            class="flex-1 py-3 px-6 bg-rangkul-red hover:bg-[#721513] text-white font-bold rounded-xl text-center text-sm shadow-xs transition active:scale-98 cursor-pointer"
                        >
                            Tolak
                        </button>

                    </div>

                </section>

            </div>

        </div>

    </main>


    <!-- ======================================================== -->
    <!-- MODAL 1: SETUJUI PENDAFTARAN (FRAME 376.PNG)            -->
    <!-- ======================================================== -->
    <div id="modal-setujui" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl space-y-6 animate-in fade-in zoom-in-95">

            <!-- Lingkaran Hijau Centang -->
            <div class="w-16 h-16 rounded-full bg-rangkul-green text-white flex items-center justify-center mx-auto text-2xl shadow-sm">
                <i class="fa-solid fa-check"></i>
            </div>

            <!-- Judul Modal -->
            <h3 class="text-2xl font-bold text-gray-900 tracking-tight">
                Setujui Pendaftaran
            </h3>

            <!-- 2 Tombol: Batal & Setujui -->
            <div class="flex items-center justify-center gap-4 pt-2">
                <button
                    type="button"
                    onclick="closeModalSetujui()"
                    class="px-7 py-2.5 bg-[#D9D9D9] hover:bg-gray-300 text-gray-900 font-semibold rounded-xl text-sm transition active:scale-95 cursor-pointer shadow-xs"
                >
                    Batal
                </button>
                <button
                    type="button"
                    onclick="confirmSetujui()"
                    class="px-7 py-2.5 bg-rangkul-green hover:bg-[#064e2b] text-white font-semibold rounded-xl text-sm transition active:scale-95 cursor-pointer shadow-xs"
                >
                    Setujui
                </button>
            </div>

        </div>
    </div>


    <!-- ======================================================== -->
    <!-- MODAL 2: TOLAK PENDAFTARAN (FRAME 376-1.PNG)            -->
    <!-- ======================================================== -->
    <div id="modal-tolak" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl space-y-5 text-center animate-in fade-in zoom-in-95">

            <!-- Ikon Segitiga Merah Peringatan -->
            <div class="text-rangkul-red text-5xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <!-- Judul Modal -->
            <h3 class="text-2xl font-bold text-gray-900 tracking-tight">
                Tolak Pendaftaran
            </h3>

            <!-- Form Alasan Penolakan -->
            <div class="text-left space-y-1.5 pt-1">
                <label class="block text-sm font-bold text-gray-900">
                    Alasan Penolakan
                </label>
                <textarea
                    id="input-alasan"
                    rows="4"
                    placeholder="Tuliskan alasan penolakan disini......"
                    class="w-full border border-gray-400 rounded-xl p-3.5 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-rangkul-red focus:border-transparent text-gray-800 resize-none transition"
                ></textarea>
                <span id="warning-alasan" class="hidden text-[11px] text-red-600 italic">
                    * Harap isi alasan penolakan terlebih dahulu!
                </span>
            </div>

            <!-- 2 Tombol: Batal & Tolak -->
            <div class="flex items-center justify-end gap-3.5 pt-2">
                <button
                    type="button"
                    onclick="closeModalTolak()"
                    class="px-7 py-2.5 bg-[#D9D9D9] hover:bg-gray-300 text-gray-900 font-semibold rounded-xl text-sm transition active:scale-95 cursor-pointer shadow-xs"
                >
                    Batal
                </button>
                <button
                    type="button"
                    onclick="confirmTolak()"
                    class="px-7 py-2.5 bg-rangkul-red hover:bg-[#721513] text-white font-semibold rounded-xl text-sm transition active:scale-95 cursor-pointer shadow-xs"
                >
                    Tolak
                </button>
            </div>

        </div>
    </div>


    <!-- ======================================================== -->
    <!-- JAVASCRIPT MODAL & AKSI                                  -->
    <!-- ======================================================== -->
    <script>
        // Modal Setujui
        function openModalSetujui() {
            document.getElementById('modal-setujui').classList.remove('hidden');
        }

        function closeModalSetujui() {
            document.getElementById('modal-setujui').classList.add('hidden');
        }

        function confirmSetujui() {
            closeModalSetujui();

            const badge = document.getElementById('badge-status');
            if (badge) {
                badge.textContent = 'Disetujui';
                badge.className = 'text-lg font-bold text-rangkul-green';
            }

            showToast('Pendaftaran asrama berhasil disetujui!', 'success');

            setTimeout(() => {
                window.history.back();
            }, 1200);
        }

        // Modal Tolak
        function openModalTolak() {
            document.getElementById('input-alasan').value = '';
            document.getElementById('warning-alasan').classList.add('hidden');
            document.getElementById('modal-tolak').classList.remove('hidden');
        }

        function closeModalTolak() {
            document.getElementById('modal-tolak').classList.add('hidden');
        }

        function confirmTolak() {
            const alasan = document.getElementById('input-alasan').value.trim();

            if (!alasan) {
                document.getElementById('warning-alasan').classList.remove('hidden');
                return;
            }

            closeModalTolak();

            const badge = document.getElementById('badge-status');
            if (badge) {
                badge.textContent = 'Ditolak';
                badge.className = 'text-lg font-bold text-rangkul-red';
            }

            showToast(`Pendaftaran ditolak: "${alasan}"`, 'danger');

            setTimeout(() => {
                window.history.back();
            }, 1200);
        }

        // Toast Helper
        function showToast(msg, type) {
            const toast = document.getElementById('toast');
            const icon = document.getElementById('toast-icon');
            const text = document.getElementById('toast-text');

            text.textContent = msg;

            if (type === 'success') {
                icon.textContent = '✓';
                toast.className = 'fixed top-5 right-5 z-50 flex items-center gap-3 px-5 py-3 rounded-xl shadow-lg border text-sm font-semibold bg-emerald-50 text-emerald-800 border-emerald-300 transition-all';
            } else {
                icon.textContent = '⚠️';
                toast.className = 'fixed top-5 right-5 z-50 flex items-center gap-3 px-5 py-3 rounded-xl shadow-lg border text-sm font-semibold bg-red-50 text-red-800 border-red-300 transition-all';
            }

            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }
    </script>

</body>
</html>