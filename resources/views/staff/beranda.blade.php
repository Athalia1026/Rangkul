<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Staff - Rangkul.com</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F5F7F4; }
    </style>
</head>
<body class="text-gray-800 pb-16">

    @include('staff.layouts.navbar')

    <!-- KONTEN BERANDA STAFF -->
    <main class="max-w-[1200px] mx-auto px-6 py-8 space-y-6">

        <!-- 3 KARTU METRIK DI ATAS (MURNI STATIS / UNABLE DIPENCET) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 select-none">
            <!-- 1. Total Antrian Verifikasi Panti -->
            <div class="bg-white rounded-2xl border border-gray-300 shadow-sm px-6 py-4 flex items-center justify-between cursor-default">
                <div>
                    <span class="text-base font-bold text-gray-900 block tracking-tight">Total Antrian Verifikasi Panti</span>
                    <span class="text-3xl font-bold text-gray-900 leading-none mt-2 block">12</span>
                </div>
                <div class="text-[#086538] pl-3">
                    <svg class="w-10 h-10 fill-[#086538]" viewBox="0 0 24 24">
                        <path d="M19 2H5c-1.1 0-2 .9-2 2v17c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-1.1-.9-2-2-2zm-8 17H5v-2h6v2zm0-4H5v-2h6v2zm0-4H5V9h6v2zm0-4H5V5h6v2zm8 12h-6v-2h6v2zm0-4h-6v-2h6v2zm0-4h-6V9h6v2zm0-4h-6V5h6v2z"></path>
                    </svg>
                </div>
            </div>

            <!-- 2. Total Menunggu Verifikasi Bukti -->
            <div class="bg-white rounded-2xl border border-gray-300 shadow-sm px-6 py-4 flex items-center justify-between cursor-default">
                <div>
                    <span class="text-base font-bold text-gray-900 block tracking-tight">Total Menunggu Verifikasi Bukti</span>
                    <span class="text-3xl font-bold text-gray-900 leading-none mt-2 block">8</span>
                </div>
                <div class="text-[#086538] pl-3">
                    <svg class="w-10 h-10 fill-[#086538]" viewBox="0 0 24 24">
                        <path d="M6 2v6h.01L6 8.01 10 12l-4 4 .01.01H6V22h12v-5.99h-.01L18 16l-4-4 4-3.99-.01-.01H18V2H6zm10 14.5V20H8v-3.5l4-4 4 4zM12 11.5l-4-4V4h8v3.5l-4 4z"></path>
                    </svg>
                </div>
            </div>

            <!-- 3. Total Selesai Verifikasi -->
            <div class="bg-white rounded-2xl border border-gray-300 shadow-sm px-6 py-4 flex items-center justify-between cursor-default">
                <div>
                    <span class="text-base font-bold text-gray-900 block tracking-tight">Total Selesai Verifikasi</span>
                    <span class="text-3xl font-bold text-gray-900 leading-none mt-2 block">5</span>
                </div>
                <div class="text-[#086538] pl-3">
                    <svg class="w-10 h-10 fill-[#086538]" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 2 KARTU PEMILIH TABEL (BISA DIKLIK) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Card Tab 1: Verifikasi Organisasi -->
            <div id="tab-btn-organisasi" onclick="switchTab('organisasi')"
                 class="bg-white rounded-2xl p-4 border-2 border-[#0e4b33] bg-[#0e4b33]/5 shadow-sm transition-all duration-150 cursor-pointer flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📒</span>
                    <div>
                        <h3 class="font-bold text-sm text-gray-900 leading-snug">Data Verifikasi Organisasi</h3>
                        <span class="text-xs text-gray-500">5 antrian pendaftaran lembaga</span>
                    </div>
                </div>
                <span id="badge-organisasi" class="text-xs font-bold px-3 py-1 rounded-full bg-[#0e4b33] text-white">
                    Aktif
                </span>
            </div>

            <!-- Card Tab 2: Verifikasi Bukti -->
            <div id="tab-btn-bukti" onclick="switchTab('bukti')"
                 class="bg-white rounded-2xl p-4 border border-gray-200/90 shadow-xs hover:border-gray-300 transition-all duration-150 cursor-pointer flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📒</span>
                    <div>
                        <h3 class="font-bold text-sm text-gray-900 leading-snug">Daftar Verifikasi Bukti</h3>
                        <span class="text-xs text-gray-500">2 antrian verifikasi bukti donasi</span>
                    </div>
                </div>
                <span id="badge-bukti" class="text-xs font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600">
                    Pilih
                </span>
            </div>
        </div>

        <!-- CARD PENCARIAN (SEARCH CARD) -->
        <div class="bg-white rounded-2xl p-3.5 px-5 border border-gray-200/90 shadow-xs flex items-center gap-3.5 transition-all focus-within:ring-2 focus-within:ring-[#086538] focus-within:border-transparent">
            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" strokeWidth="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
                id="search-input"
                type="text"
                oninput="handleSearch()"
                placeholder="Cari nama organisasi, kategori, atau wilayah..."
                class="w-full bg-transparent text-sm text-gray-800 placeholder-gray-400 focus:outline-none"
            />
            <button
                id="reset-search-btn"
                onclick="resetSearch()"
                class="hidden text-xs text-gray-400 hover:text-gray-600 font-semibold px-2 py-1 rounded-md bg-gray-100 cursor-pointer"
            >
                Reset
            </button>
        </div>

        <!-- TABEL DATA HOMESTAFF -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
            <!-- Header Bar Tabel -->
            <div class="bg-[#0e4b33] px-6 py-3.5 flex items-center justify-between text-white">
                <div class="flex items-center gap-2">
                    <span class="text-lg">📒</span>
                    <h2 id="table-title" class="font-bold text-base tracking-wide">
                        Data Verifikasi Organisasi
                    </h2>
                </div>
                <span id="table-count-badge" class="text-xs text-emerald-100 bg-[#093524] px-3 py-1 rounded-lg font-medium">
                    5 Data Ditampilkan
                </span>
            </div>

            <!-- 1. TABEL ORGANISASI -->
            <div id="section-table-organisasi" class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-sm font-bold text-gray-900 bg-white text-center">
                            <th class="py-3 px-6 w-16">No.</th>
                            <th class="py-3 px-6 text-left">Nama Organisasi</th>
                            <th class="py-3 px-6 w-48 text-left">Kategori</th>
                            <th class="py-3 px-6 w-48 text-left">Wilayah</th>
                            <th class="py-3 px-6 w-40">Tanggal Daftar</th>
                            <th class="py-3 px-6 w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-organisasi" class="divide-y divide-gray-200/80 text-sm">
                        <tr class="org-row hover:bg-gray-50/70 transition-colors">
                            <td class="py-3.5 px-6 text-gray-800 text-center">1.</td>
                            <td class="py-3.5 px-6 font-medium text-gray-900 cell-name">Asrama Pemberdayaan Yatim dan Dhuafa</td>
                            <td class="py-3.5 px-6 text-xs text-gray-600 cell-cat">Panti Asuhan</td>
                            <td class="py-3.5 px-6 text-gray-600 cell-loc">Sidoarjo</td>
                            <td class="py-3.5 px-6 text-gray-800 text-center">16/02/2026</td>
                            <td class="py-3.5 px-6 text-center">
                                <button onclick="openModalOrg('Asrama Pemberdayaan Yatim dan Dhuafa', 'Panti Asuhan', 'Sidoarjo', '16/02/2026')"
                                    class="px-6 py-1.5 bg-[#0e4b33] text-white text-xs font-semibold rounded-xl hover:bg-[#093524] active:scale-95 transition-all shadow-xs cursor-pointer">
                                    Detail
                                </button>
                            </td>
                        </tr>
                        <tr class="org-row hover:bg-gray-50/70 transition-colors">
                            <td class="py-3.5 px-6 text-gray-800 text-center">2.</td>
                            <td class="py-3.5 px-6 font-medium text-gray-900 cell-name">Sekolah Dasar Negeri Gebang 2</td>
                            <td class="py-3.5 px-6 text-xs text-gray-600 cell-cat">Sekolah Dasar</td>
                            <td class="py-3.5 px-6 text-gray-600 cell-loc">Sidoarjo</td>
                            <td class="py-3.5 px-6 text-gray-800 text-center">08/05/2026</td>
                            <td class="py-3.5 px-6 text-center">
                                <button onclick="openModalOrg('Sekolah Dasar Negeri Gebang 2', 'Sekolah Dasar', 'Sidoarjo', '08/05/2026')"
                                    class="px-6 py-1.5 bg-[#0e4b33] text-white text-xs font-semibold rounded-xl hover:bg-[#093524] active:scale-95 transition-all shadow-xs cursor-pointer">
                                    Detail
                                </button>
                            </td>
                        </tr>
                        <tr class="org-row hover:bg-gray-50/70 transition-colors">
                            <td class="py-3.5 px-6 text-gray-800 text-center">3.</td>
                            <td class="py-3.5 px-6 font-medium text-gray-900 cell-name">Panti Asuhan Ruhamaa</td>
                            <td class="py-3.5 px-6 text-xs text-gray-600 cell-cat">Panti Asuhan</td>
                            <td class="py-3.5 px-6 text-gray-600 cell-loc">Surabaya</td>
                            <td class="py-3.5 px-6 text-gray-800 text-center">27/06/2026</td>
                            <td class="py-3.5 px-6 text-center">
                                <button onclick="openModalOrg('Panti Asuhan Ruhamaa', 'Panti Asuhan', 'Surabaya', '27/06/2026')"
                                    class="px-6 py-1.5 bg-[#0e4b33] text-white text-xs font-semibold rounded-xl hover:bg-[#093524] active:scale-95 transition-all shadow-xs cursor-pointer">
                                    Detail
                                </button>
                            </td>
                        </tr>
                        <tr class="org-row hover:bg-gray-50/70 transition-colors">
                            <td class="py-3.5 px-6 text-gray-800 text-center">4.</td>
                            <td class="py-3.5 px-6 font-medium text-gray-900 cell-name">Yayasan Panti Asuhan Nur Iman</td>
                            <td class="py-3.5 px-6 text-xs text-gray-600 cell-cat">Yayasan Panti</td>
                            <td class="py-3.5 px-6 text-gray-600 cell-loc">Surabaya</td>
                            <td class="py-3.5 px-6 text-gray-800 text-center">19/10/2026</td>
                            <td class="py-3.5 px-6 text-center">
                                <button onclick="openModalOrg('Yayasan Panti Asuhan Nur Iman', 'Yayasan Panti', 'Surabaya', '19/10/2026')"
                                    class="px-6 py-1.5 bg-[#0e4b33] text-white text-xs font-semibold rounded-xl hover:bg-[#093524] active:scale-95 transition-all shadow-xs cursor-pointer">
                                    Detail
                                </button>
                            </td>
                        </tr>
                        <tr class="org-row hover:bg-gray-50/70 transition-colors">
                            <td class="py-3.5 px-6 text-gray-800 text-center">5.</td>
                            <td class="py-3.5 px-6 font-medium text-gray-900 cell-name">Panti Asuhan Arrohman</td>
                            <td class="py-3.5 px-6 text-xs text-gray-600 cell-cat">Panti Asuhan</td>
                            <td class="py-3.5 px-6 text-gray-600 cell-loc">Surabaya</td>
                            <td class="py-3.5 px-6 text-gray-800 text-center">15/11/2026</td>
                            <td class="py-3.5 px-6 text-center">
                                <button onclick="openModalOrg('Panti Asuhan Arrohman', 'Panti Asuhan', 'Surabaya', '15/11/2026')"
                                    class="px-6 py-1.5 bg-[#0e4b33] text-white text-xs font-semibold rounded-xl hover:bg-[#093524] active:scale-95 transition-all shadow-xs cursor-pointer">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div id="empty-state-org" class="hidden py-8 text-center text-gray-400 text-xs">
                    Tidak ada data organisasi yang cocok dengan pencarian.
                </div>
            </div>

            <!-- 2. TABEL BUKTI DONASI -->
            <div id="section-table-bukti" class="hidden overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-xs font-bold text-gray-900 bg-white">
                            <th class="py-3 px-6 w-16 text-center">No.</th>
                            <th class="py-3 px-6">Nama Organisasi</th>
                            <th class="py-3 px-6">Keterangan Penyaluran</th>
                            <th class="py-3 px-6 w-44">Nominal</th>
                            <th class="py-3 px-6 w-32 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-bukti" class="divide-y divide-gray-200/80 text-sm">
                        <tr class="bukti-row hover:bg-gray-50/70 transition-colors">
                            <td class="py-3.5 px-6 text-gray-800 text-center">1.</td>
                            <td class="py-3.5 px-6 font-medium text-gray-900 cell-name">Asrama Pemberdayaan Yatim dan Dhuafa</td>
                            <td class="py-3.5 px-6 text-xs text-gray-600 cell-desc">Kebutuhan sandang pangan & sembako santunan</td>
                            <td class="py-3.5 px-6 text-gray-900 font-semibold cell-nominal">Rp 600.000</td>
                            <td class="py-3.5 px-6 text-center">
                                <button onclick="openModalBukti('Asrama Pemberdayaan Yatim dan Dhuafa', 'Kebutuhan sandang pangan & sembako santunan', 'Rp 600.000')"
                                    class="px-6 py-1.5 bg-[#0e4b33] text-white text-xs font-semibold rounded-xl hover:bg-[#093524] active:scale-95 transition-all shadow-xs cursor-pointer">
                                    Detail
                                </button>
                            </td>
                        </tr>
                        <tr class="bukti-row hover:bg-gray-50/70 transition-colors">
                            <td class="py-3.5 px-6 text-gray-800 text-center">2.</td>
                            <td class="py-3.5 px-6 font-medium text-gray-900 cell-name">Sekolah Dasar Negeri Gebang 2</td>
                            <td class="py-3.5 px-6 text-xs text-gray-600 cell-desc">Pengadaan bangku sekolah & papan tulis kelas</td>
                            <td class="py-3.5 px-6 text-gray-900 font-semibold cell-nominal">Rp 50.000.000</td>
                            <td class="py-3.5 px-6 text-center">
                                <button onclick="openModalBukti('Sekolah Dasar Negeri Gebang 2', 'Pengadaan bangku sekolah & papan tulis kelas', 'Rp 50.000.000')"
                                    class="px-6 py-1.5 bg-[#0e4b33] text-white text-xs font-semibold rounded-xl hover:bg-[#093524] active:scale-95 transition-all shadow-xs cursor-pointer">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div id="empty-state-bukti" class="hidden py-8 text-center text-gray-400 text-xs">
                    Tidak ada data verifikasi bukti yang cocok dengan pencarian.
                </div>
            </div>
        </div>

    </main>

    <!-- MODAL DETAIL POPUP INTERAKTIF -->
    <div id="detail-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
        <div class="bg-white rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden border border-gray-200">
            <!-- Header Modal -->
            <div class="bg-[#0e4b33] px-6 py-4 flex items-center justify-between text-white">
                <div class="flex items-center gap-2.5">
                    <span class="text-xl">📋</span>
                    <h3 id="modal-title" class="font-bold text-base">Detail Informasi Organisasi</h3>
                </div>
                <button onclick="closeModal()" class="text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Content Modal -->
            <div class="p-6 space-y-4 text-sm text-gray-700">
                <div class="bg-[#F5F7F4] p-4 rounded-xl space-y-2.5 border border-gray-200/80">
                    <div class="flex justify-between py-1 border-b border-gray-200/60">
                        <span class="text-gray-500 font-medium">Nama Organisasi</span>
                        <span id="modal-org-name" class="font-bold text-gray-900 text-right">-</span>
                    </div>

                    <!-- Spesifik Organisasi -->
                    <div id="modal-field-org" class="space-y-2.5">
                        <div class="flex justify-between py-1 border-b border-gray-200/60">
                            <span class="text-gray-500 font-medium">Kategori</span>
                            <span id="modal-org-cat" class="font-semibold text-gray-900">-</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-200/60">
                            <span class="text-gray-500 font-medium">Wilayah</span>
                            <span id="modal-org-loc" class="font-semibold text-gray-900">-</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-200/60">
                            <span class="text-gray-500 font-medium">Tanggal Registrasi</span>
                            <span id="modal-org-date" class="font-semibold text-gray-900">-</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-500 font-medium">Status</span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                Menunggu Verifikasi
                            </span>
                        </div>
                    </div>

                    <!-- Spesifik Bukti -->
                    <div id="modal-field-bukti" class="hidden space-y-2.5">
                        <div class="flex justify-between py-1 border-b border-gray-200/60">
                            <span class="text-gray-500 font-medium">Keterangan</span>
                            <span id="modal-bukti-desc" class="font-semibold text-gray-900 text-right">-</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-200/60">
                            <span class="text-gray-500 font-medium">Nominal Penyaluran</span>
                            <span id="modal-bukti-nominal" class="font-bold text-[#086538]">-</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="text-gray-500 font-medium">Status Bukti</span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                Menunggu Review Bukti
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button onclick="closeModal()" class="px-5 py-2 border border-gray-300 text-gray-700 text-xs font-semibold rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                    Tutup
                </button>
                <button onclick="closeModal()" class="px-5 py-2 bg-[#086538] text-white text-xs font-semibold rounded-xl hover:bg-[#064e2b] transition-colors cursor-pointer">
                    Proses Verifikasi
                </button>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        let currentTab = 'organisasi';

        function switchTab(tab) {
            currentTab = tab;
            const searchInput = document.getElementById('search-input');
            searchInput.value = '';
            document.getElementById('reset-search-btn').classList.add('hidden');

            const tabOrg = document.getElementById('tab-btn-organisasi');
            const tabBukti = document.getElementById('tab-btn-bukti');
            const badgeOrg = document.getElementById('badge-organisasi');
            const badgeBukti = document.getElementById('badge-bukti');
            const secOrg = document.getElementById('section-table-organisasi');
            const secBukti = document.getElementById('section-table-bukti');
            const title = document.getElementById('table-title');
            const countBadge = document.getElementById('table-count-badge');

            if (tab === 'organisasi') {
                tabOrg.className = "bg-white rounded-2xl p-4 border-2 border-[#0e4b33] bg-[#0e4b33]/5 shadow-sm transition-all duration-150 cursor-pointer flex items-center justify-between";
                badgeOrg.className = "text-xs font-bold px-3 py-1 rounded-full bg-[#0e4b33] text-white";
                badgeOrg.textContent = "Aktif";

                tabBukti.className = "bg-white rounded-2xl p-4 border border-gray-200/90 shadow-xs hover:border-gray-300 transition-all duration-150 cursor-pointer flex items-center justify-between";
                badgeBukti.className = "text-xs font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600";
                badgeBukti.textContent = "Pilih";

                secOrg.classList.remove('hidden');
                secBukti.classList.add('hidden');
                title.textContent = "Data Verifikasi Organisasi";
                searchInput.placeholder = "Cari nama organisasi, kategori, atau wilayah...";

                resetRowsVisibility('org-row');
                countBadge.textContent = "5 Data Ditampilkan";
            } else {
                tabBukti.className = "bg-white rounded-2xl p-4 border-2 border-[#0e4b33] bg-[#0e4b33]/5 shadow-sm transition-all duration-150 cursor-pointer flex items-center justify-between";
                badgeBukti.className = "text-xs font-bold px-3 py-1 rounded-full bg-[#0e4b33] text-white";
                badgeBukti.textContent = "Aktif";

                tabOrg.className = "bg-white rounded-2xl p-4 border border-gray-200/90 shadow-xs hover:border-gray-300 transition-all duration-150 cursor-pointer flex items-center justify-between";
                badgeOrg.className = "text-xs font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600";
                badgeOrg.textContent = "Pilih";

                secBukti.classList.remove('hidden');
                secOrg.classList.add('hidden');
                title.textContent = "Daftar Verifikasi Bukti";
                searchInput.placeholder = "Cari nama organisasi, keterangan pengajuan, atau nominal...";

                resetRowsVisibility('bukti-row');
                countBadge.textContent = "2 Data Ditampilkan";
            }
        }

        function handleSearch() {
            const query = document.getElementById('search-input').value.toLowerCase().trim();
            const resetBtn = document.getElementById('reset-search-btn');
            const countBadge = document.getElementById('table-count-badge');

            if (query.length > 0) {
                resetBtn.classList.remove('hidden');
            } else {
                resetBtn.classList.add('hidden');
            }

            if (currentTab === 'organisasi') {
                const rows = document.querySelectorAll('.org-row');
                let visibleCount = 0;
                rows.forEach(row => {
                    const name = row.querySelector('.cell-name').textContent.toLowerCase();
                    const cat = row.querySelector('.cell-cat').textContent.toLowerCase();
                    const loc = row.querySelector('.cell-loc').textContent.toLowerCase();

                    if (name.includes(query) || cat.includes(query) || loc.includes(query)) {
                        row.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        row.classList.add('hidden');
                    }
                });
                countBadge.textContent = `${visibleCount} Data Ditampilkan`;
                document.getElementById('empty-state-org').classList.toggle('hidden', visibleCount > 0);
            } else {
                const rows = document.querySelectorAll('.bukti-row');
                let visibleCount = 0;
                rows.forEach(row => {
                    const name = row.querySelector('.cell-name').textContent.toLowerCase();
                    const desc = row.querySelector('.cell-desc').textContent.toLowerCase();
                    const nominal = row.querySelector('.cell-nominal').textContent.toLowerCase();

                    if (name.includes(query) || desc.includes(query) || nominal.includes(query)) {
                        row.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        row.classList.add('hidden');
                    }
                });
                countBadge.textContent = `${visibleCount} Data Ditampilkan`;
                document.getElementById('empty-state-bukti').classList.toggle('hidden', visibleCount > 0);
            }
        }

        function resetSearch() {
            document.getElementById('search-input').value = '';
            handleSearch();
        }

        function resetRowsVisibility(rowClass) {
            document.querySelectorAll('.' + rowClass).forEach(row => row.classList.remove('hidden'));
            document.getElementById('empty-state-org').classList.add('hidden');
            document.getElementById('empty-state-bukti').classList.add('hidden');
        }

        function openModalOrg(name, cat, loc, date) {
            document.getElementById('modal-title').textContent = "Detail Informasi Organisasi";
            document.getElementById('modal-org-name').textContent = name;
            document.getElementById('modal-org-cat').textContent = cat;
            document.getElementById('modal-org-loc').textContent = loc;
            document.getElementById('modal-org-date').textContent = date;

            document.getElementById('modal-field-org').classList.remove('hidden');
            document.getElementById('modal-field-bukti').classList.add('hidden');
            document.getElementById('detail-modal').classList.remove('hidden');
        }

        function openModalBukti(name, desc, nominal) {
            document.getElementById('modal-title').textContent = "Detail Bukti Penyaluran";
            document.getElementById('modal-org-name').textContent = name;
            document.getElementById('modal-bukti-desc').textContent = desc;
            document.getElementById('modal-bukti-nominal').textContent = nominal;

            document.getElementById('modal-field-org').classList.add('hidden');
            document.getElementById('modal-field-bukti').classList.remove('hidden');
            document.getElementById('detail-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('detail-modal').classList.add('hidden');
        }
    </script>
</body>
</html>