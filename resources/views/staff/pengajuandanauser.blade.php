<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-auth-session-guard />

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
        
        <div id="pendaftaran-error" class="hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-center text-sm text-red-700"></div>

        <!-- INFORMASI ASRAMA -->
        <section class="w-full max-w-[1200px] mx-auto bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="grid grid-cols-1 md:grid-cols-2">

                <!-- FOTO -->
                <div class="h-[320px] bg-gray-100 flex items-center justify-center text-gray-400">
                    <img
                        id="org-photo"
                        alt="Foto Asrama"
                        class="hidden w-full h-full object-cover"
                    >
                    <i id="org-photo-placeholder" class="fa-regular fa-image text-6xl"></i>
                </div>

                <!-- INFORMASI -->
                <div class="px-8 py-4 flex flex-col">

                    <h1 id="org-name" class="text-2xl font-bold text-gray-900 mb-8">
                        -
                    </h1>

                    <div class="grid grid-cols-2 gap-y-7">

                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Jenis
                            </p>
                            <p id="org-type" class="text-lg font-bold">
                                -
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Status
                            </p>
                            <p id="badge-status" class="text-lg font-bold text-orange-400">
                                -
                            </p>
                            <p id="org-rejection-reason" class="hidden text-sm text-rangkul-red mt-1"></p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Jumlah Anak Asuh
                            </p>
                            <p id="org-children" class="text-lg font-bold">
                                -
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Tanggal Daftar
                            </p>
                            <p id="org-registered" class="text-lg font-bold">
                                -
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

                                <p id="org-address" class="text-sm text-gray-600 leading-relaxed">
                                    -
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

                                <p id="org-city" class="text-sm text-gray-600">
                                    -
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
                                    <span id="org-phone">-</span><br>
                                    <span id="org-contact">-</span>
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

                                <p id="org-email" class="text-sm text-gray-600 break-all">
                                    -
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

                                <p id="org-description" class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                                    -
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

                        <tbody id="documents-body" class="text-sm">

                        </tbody>

                    </table>

                </div>

                <!-- SECTION AKSI (3 TOMBOL) -->
                <section id="aksi-pendaftaran" class="hidden pt-2">

                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        Aksi
                    </h2>

                    <div class="flex items-center gap-4">

                        <!-- 1. TOMBOL BATAL (BALIK KE BERANDA STAFF) -->
                        <a
                            href="{{ route('staff.beranda') }}"
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
                    id="btnConfirmSetujui"
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
                    id="btnConfirmTolak"
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
        const { request, escapeHtml } = window.RangkulAdmin;
        const organizationId = @json($organizationId);
        const BERANDA_URL = @json(route('staff.beranda'));

        const STATUS_TAMPIL = {
            menunggu: { label: 'Menunggu', className: 'text-orange-400' },
            disetujui: { label: 'Disetujui', className: 'text-rangkul-green' },
            ditolak: { label: 'Ditolak', className: 'text-rangkul-red' }
        };

        // ==========================
        // DATA PENDAFTARAN
        // ==========================

        function setText(id, value) {
            document.getElementById(id).textContent = value ?? '-';
        }

        function renderDokumen(documents, bankAccount) {
            const rows = documents.map((dokumen) => `
                <tr class="border-t border-gray-200">
                    <td class="px-6 py-5">
                        ${escapeHtml(dokumen.label)}
                    </td>
                    <td class="px-6 py-5">
                        <a
                            href="${escapeHtml(dokumen.url)}"
                            target="_blank"
                            rel="noopener"
                            class="inline-flex items-center gap-2 bg-[#d1e7dd] px-4 py-2 rounded-lg w-[200px] hover:bg-[#c3dfd3] transition cursor-pointer"
                        >
                            <span class="truncate">${escapeHtml(dokumen.file_name)}</span>
                        </a>
                    </td>
                </tr>
            `);

            rows.push(`
                <tr class="border-t border-gray-200">
                    <td class="px-6 py-5">
                        Rekening Lembaga
                    </td>
                    <td class="px-6 py-5">
                        <span class="inline-flex items-center gap-2 bg-[#d1e7dd] px-4 py-2 rounded-lg w-[200px]">
                            ${bankAccount ? `(${escapeHtml(bankAccount.bank)}) ${escapeHtml(bankAccount.no_rekening)}` : '-'}
                        </span>
                    </td>
                </tr>
            `);

            document.getElementById('documents-body').innerHTML = rows.join('');
        }

        function renderPendaftaran(data) {
            const status = STATUS_TAMPIL[data.verification_status] || { label: data.verification_status, className: 'text-gray-700' };
            const badge = document.getElementById('badge-status');
            const alasan = document.getElementById('org-rejection-reason');
            const photo = document.getElementById('org-photo');

            badge.textContent = status.label;
            badge.className = 'text-lg font-bold ' + status.className;
            alasan.textContent = data.alasan_penolakan ? 'Alasan: ' + data.alasan_penolakan : '';
            alasan.classList.toggle('hidden', !data.alasan_penolakan);

            if (data.photo_url) {
                photo.src = data.photo_url;
                photo.classList.remove('hidden');
                document.getElementById('org-photo-placeholder').classList.add('hidden');
            }

            setText('org-name', data.name);
            setText('org-type', data.type);
            setText('org-children', data.jumlah_anak);
            setText('org-registered', data.registered_at);
            setText('org-address', data.address);
            setText('org-city', data.city);
            setText('org-phone', data.phone);
            setText('org-contact', data.contact_name);
            setText('org-email', data.email);
            setText('org-description', data.description);

            renderDokumen(data.documents || [], data.bank_account);

            // Tombol aksi hanya untuk pendaftaran yang belum diverifikasi
            document.getElementById('aksi-pendaftaran').classList.toggle('hidden', data.verification_status !== 'menunggu');
        }

        async function loadPendaftaran() {
            const error = document.getElementById('pendaftaran-error');

            try {
                const { data } = await request('/api/admin/users/organizations/' + encodeURIComponent(organizationId));
                error.classList.add('hidden');
                renderPendaftaran(data);
            } catch (exception) {
                error.textContent = exception.message;
                error.classList.remove('hidden');
            }
        }

        async function verifikasiPendaftaran(body, button) {
            button.disabled = true;

            try {
                const result = await request('/api/admin/verifications/organizations/' + encodeURIComponent(organizationId), {
                    method: 'PUT',
                    body
                });

                showToast(result.message, body.status === 'disetujui' ? 'success' : 'danger');

                setTimeout(() => {
                    window.location.href = BERANDA_URL;
                }, 1200);
            } catch (exception) {
                button.disabled = false;
                showToast(exception.message, 'danger');
            }
        }

        // Modal Setujui
        function openModalSetujui() {
            document.getElementById('modal-setujui').classList.remove('hidden');
        }

        function closeModalSetujui() {
            document.getElementById('modal-setujui').classList.add('hidden');
        }

        function confirmSetujui() {
            closeModalSetujui();
            verifikasiPendaftaran({ status: 'disetujui' }, document.getElementById('btnConfirmSetujui'));
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
            verifikasiPendaftaran({ status: 'ditolak', alasan_penolakan: alasan }, document.getElementById('btnConfirmTolak'));
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

        loadPendaftaran();
    </script>

</body>
</html>