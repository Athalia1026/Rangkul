<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-auth-session-guard />
    <title>Dashboard Staff</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F5F7F4; }
    </style>
</head>
<body class="text-gray-800 pb-16">

    @include('staff.layouts.navbar')

    <!-- KONTEN BERANDA STAFF -->
    <main class="max-w-[1200px] mx-auto px-6 py-8 space-y-6">

        <div id="dashboard-error" class="hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-center text-sm text-red-700"></div>
        <div id="campaign-queue-error" class="hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-center text-sm text-red-700"></div>
        <div id="campaign-action-message" class="hidden rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-center text-sm text-green-700" role="status"></div>

        <!-- KARTU METRIK -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 select-none">
            <!-- 1. Total Antrian Verifikasi Organisasi -->
            <div class="bg-white rounded-2xl border border-gray-300 shadow-sm px-6 py-4 flex items-center justify-between cursor-default">
                <div>
                    <span class="text-base font-bold text-gray-900 block tracking-tight">Total Antrian Verifikasi Organisasi</span>
                    <span id="summary-pending-organizations" class="text-3xl font-bold text-gray-900 leading-none mt-2 block">0</span>
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
                    <span id="summary-pending-proofs" class="text-3xl font-bold text-gray-900 leading-none mt-2 block">0</span>
                </div>
                <div class="text-[#086538] pl-3">
                    <svg class="w-10 h-10 fill-[#086538]" viewBox="0 0 24 24">
                        <path d="M6 2v6h.01L6 8.01 10 12l-4 4 .01.01H6V22h12v-5.99h-.01L18 16l-4-4 4-3.99-.01-.01H18V2H6zm10 14.5V20H8v-3.5l4-4 4 4zM12 11.5l-4-4V4h8v3.5l-4 4z"></path>
                    </svg>
                </div>
            </div>

            <!-- 3. Total Antrian Verifikasi Kampanye -->
            <div class="bg-white rounded-2xl border border-gray-300 shadow-sm px-6 py-4 flex items-center justify-between cursor-default">
                <div>
                    <span class="text-base font-bold text-gray-900 block tracking-tight">Total Antrian Kampanye</span>
                    <span id="summary-pending-campaigns" class="text-3xl font-bold text-gray-900 leading-none mt-2 block">0</span>
                </div>
                <div class="text-[#086538] pl-3">
                    <svg class="w-10 h-10 fill-[#086538]" viewBox="0 0 24 24">
                        <path d="M12 2 3 6v5c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V6l-9-4zm0 18.9c-3.8-1.2-7-5.3-7-9.9V7.3l7-3.1 7 3.1V11c0 4.6-3.2 8.7-7 9.9zm-1-5.3-3-3 1.4-1.4 1.6 1.6 4.6-4.6 1.4 1.4-6 6z"></path>
                    </svg>
                </div>
            </div>

            <!-- 4. Total Selesai Verifikasi -->
            <div class="bg-white rounded-2xl border border-gray-300 shadow-sm px-6 py-4 flex items-center justify-between cursor-default">
                <div>
                    <span class="text-base font-bold text-gray-900 block tracking-tight">Total Selesai Verifikasi</span>
                    <span id="summary-completed" class="text-3xl font-bold text-gray-900 leading-none mt-2 block">0</span>
                </div>
                <div class="text-[#086538] pl-3">
                    <svg class="w-10 h-10 fill-[#086538]" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- KARTU PEMILIH TABEL (BISA DIKLIK) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Card Tab 1: Verifikasi Organisasi -->
            <div id="tab-btn-organisasi" onclick="switchTab('organisasi')"
                 class="bg-white rounded-2xl p-4 border-2 border-[#0e4b33] bg-[#0e4b33]/5 shadow-sm transition-all duration-150 cursor-pointer flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📒</span>
                    <div>
                        <h3 class="font-bold text-sm text-gray-900 leading-snug">Data Verifikasi Organisasi</h3>
                        <span id="tab-caption-organisasi" class="text-xs text-gray-500">0 antrian pendaftaran lembaga</span>
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
                        <span id="tab-caption-bukti" class="text-xs text-gray-500">0 antrian verifikasi bukti donasi</span>
                    </div>
                </div>
                <span id="badge-bukti" class="text-xs font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600">
                    Pilih
                </span>
            </div>

            <!-- Card Tab 3: Verifikasi Kampanye -->
            <div id="tab-btn-kampanye" onclick="switchTab('kampanye')"
                 class="bg-white rounded-2xl p-4 border border-gray-200/90 shadow-xs hover:border-gray-300 transition-all duration-150 cursor-pointer flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📒</span>
                    <div>
                        <h3 class="font-bold text-sm text-gray-900 leading-snug">Antrian Kampanye</h3>
                        <span id="tab-caption-kampanye" class="text-xs text-gray-500">0 kampanye menunggu verifikasi</span>
                    </div>
                </div>
                <span id="badge-kampanye" class="text-xs font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600">
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
                placeholder="Cari nama organisasi, kampanye, kategori, atau wilayah..."
                class="w-full bg-transparent text-sm text-gray-800 placeholder-gray-400 focus:outline-none"
            />
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
                    0 Data Ditampilkan
                </span>
            </div>

            <!-- 1. TABEL ORGANISASI -->
            <div id="section-table-organisasi" class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-sm font-bold text-gray-900 bg-white text-center">
                            <th class="py-3 px-6 w-16">No.</th>
                            <th class="py-3 px-6 w-40">Tanggal Daftar</th>
                            <th class="py-3 px-6 ">Nama Organisasi</th>
                            <th class="py-3 px-6 w-48">Kategori</th>
                            <th class="py-3 px-6 w-48">Wilayah</th>
                            <th class="py-3 px-6 w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-organisasi" class="divide-y divide-gray-200/80 text-sm">
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
                        <tr class="border-b border-gray-200 text-sm font-bold text-gray-900 bg-white text-center">
                            <th class="py-3 px-6 w-16 text-center">No.</th>
                            <th class="py-3 px-6">Nama Organisasi</th>
                            <th class="py-3 px-6">Keterangan Penyaluran</th>
                            <th class="py-3 px-6 w-44">Nominal</th>
                            <th class="py-3 px-6 w-32 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-bukti" class="divide-y divide-gray-200/80 text-sm">
                    </tbody>
                </table>
                <div id="empty-state-bukti" class="hidden py-8 text-center text-gray-400 text-xs">
                    Tidak ada data verifikasi bukti yang cocok dengan pencarian.
                </div>
            </div>

            <!-- 3. TABEL KAMPANYE -->
            <div id="section-table-kampanye" class="hidden overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-sm font-bold text-gray-900 bg-white text-center">
                            <th class="py-3 px-6 w-16">No.</th>
                            <th class="py-3 px-6">Kampanye</th>
                            <th class="py-3 px-6">Organisasi</th>
                            <th class="py-3 px-6 w-44">Target Dana</th>
                            <th class="py-3 px-6 w-48">Periode</th>
                            <th class="py-3 px-6 w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-kampanye" class="divide-y divide-gray-200/80 text-sm"></tbody>
                </table>
                <div id="empty-state-kampanye" class="hidden py-8 text-center text-gray-400 text-xs">
                    Belum ada kampanye yang menunggu verifikasi.
                </div>
            </div>
        </div>

    </main>

    <div id="campaign-detail-modal" class="hidden fixed inset-0 z-50 bg-black/50 px-4 py-8 overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl mx-auto p-6 space-y-5" role="dialog" aria-modal="true" aria-labelledby="campaign-detail-title">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 id="campaign-detail-title" class="text-xl font-bold text-gray-900"></h2>
                    <p id="campaign-detail-organization" class="text-sm text-gray-500 mt-1"></p>
                </div>
                <button id="campaign-detail-close" type="button" class="text-gray-500 hover:text-gray-900 text-2xl leading-none" aria-label="Tutup">&times;</button>
            </div>
            <img id="campaign-detail-cover" class="hidden w-full max-h-72 object-cover rounded-xl" alt="Foto sampul kampanye">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="block text-gray-500">Target dana</span>
                    <strong id="campaign-detail-target" class="text-gray-900"></strong>
                </div>
                <div>
                    <span class="block text-gray-500">Periode kampanye</span>
                    <strong id="campaign-detail-period" class="text-gray-900"></strong>
                </div>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-1">Deskripsi</h3>
                <p id="campaign-detail-description" class="text-sm text-gray-700 whitespace-pre-line"></p>
            </div>
            <p id="campaign-detail-feedback" class="hidden rounded-lg px-3 py-2 text-sm" role="status"></p>
            <div id="campaign-action-options" class="flex justify-end gap-3">
                <button id="campaign-detail-reject" type="button" class="px-4 py-2 rounded-lg bg-red-700 text-white font-semibold hover:bg-red-700">Tolak</button>
                <button id="campaign-detail-approve" type="button" class="px-4 py-2 rounded-lg bg-[#0e4b33] text-white font-semibold hover:bg-[#093524]">Setujui kampanye</button>
            </div>
            <div id="campaign-approve-confirmation" class="hidden rounded-xl border border-green-200 bg-green-50 p-4">
                <p id="campaign-approve-confirmation-text" class="text-sm text-gray-800"></p>
                <div class="flex justify-end gap-3 mt-4">
                    <button id="campaign-approve-cancel" type="button" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-white">Batal</button>
                    <button id="campaign-approve-confirm" type="button" class="px-4 py-2 rounded-lg bg-[#0e4b33] text-white font-semibold hover:bg-[#093524]">Ya, setujui</button>
                </div>
            </div>
            <div id="campaign-reject-form" class="hidden rounded-xl border border-red-200 bg-red-50 p-4">
                <label for="campaign-reject-reason" class="block text-sm font-semibold text-gray-800 mb-2">Alasan penolakan</label>
                <textarea id="campaign-reject-reason" rows="3" class="w-full rounded-lg border border-gray-300 p-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Tuliskan alasan penolakan kampanye"></textarea>
                <div class="flex justify-end gap-3 mt-4">
                    <button id="campaign-reject-cancel" type="button" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-white">Batal</button>
                    <button id="campaign-reject-confirm" type="button" class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700">Kirim penolakan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        let currentTab = 'organisasi';
        let campaigns = [];
        let selectedCampaignId = null;

        function switchTab(tab) {
            currentTab = tab;
            const searchInput = document.getElementById('search-input');
            searchInput.value = '';

            const tabOrg = document.getElementById('tab-btn-organisasi');
            const tabBukti = document.getElementById('tab-btn-bukti');
            const tabKampanye = document.getElementById('tab-btn-kampanye');
            const badgeOrg = document.getElementById('badge-organisasi');
            const badgeBukti = document.getElementById('badge-bukti');
            const badgeKampanye = document.getElementById('badge-kampanye');
            const secOrg = document.getElementById('section-table-organisasi');
            const secBukti = document.getElementById('section-table-bukti');
            const secKampanye = document.getElementById('section-table-kampanye');
            const title = document.getElementById('table-title');
            const countBadge = document.getElementById('table-count-badge');

            if (tab === 'organisasi') {
                tabOrg.className = "bg-white rounded-2xl p-4 border-2 border-[#0e4b33] bg-[#0e4b33]/5 shadow-sm transition-all duration-150 cursor-pointer flex items-center justify-between";
                badgeOrg.className = "text-xs font-bold px-3 py-1 rounded-full bg-[#0e4b33] text-white";
                badgeOrg.textContent = "Aktif";

                tabBukti.className = "bg-white rounded-2xl p-4 border border-gray-200/90 shadow-xs hover:border-gray-300 transition-all duration-150 cursor-pointer flex items-center justify-between";
                badgeBukti.className = "text-xs font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600";
                badgeBukti.textContent = "Pilih";
                tabKampanye.className = "bg-white rounded-2xl p-4 border border-gray-200/90 shadow-xs hover:border-gray-300 transition-all duration-150 cursor-pointer flex items-center justify-between";
                badgeKampanye.className = "text-xs font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600";
                badgeKampanye.textContent = "Pilih";

                secOrg.classList.remove('hidden');
                secBukti.classList.add('hidden');
                secKampanye.classList.add('hidden');
                title.textContent = "Data Verifikasi Organisasi";
                searchInput.placeholder = "Cari nama organisasi, kategori, atau wilayah...";

                resetRowsVisibility('org-row');
                countBadge.textContent = `${totalData.organisasi} Data Ditampilkan`;
                toggleEmptyState('empty-state-org', totalData.organisasi);
            } else if (tab === 'bukti') {
                tabBukti.className = "bg-white rounded-2xl p-4 border-2 border-[#0e4b33] bg-[#0e4b33]/5 shadow-sm transition-all duration-150 cursor-pointer flex items-center justify-between";
                badgeBukti.className = "text-xs font-bold px-3 py-1 rounded-full bg-[#0e4b33] text-white";
                badgeBukti.textContent = "Aktif";

                tabOrg.className = "bg-white rounded-2xl p-4 border border-gray-200/90 shadow-xs hover:border-gray-300 transition-all duration-150 cursor-pointer flex items-center justify-between";
                badgeOrg.className = "text-xs font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600";
                badgeOrg.textContent = "Pilih";
                tabKampanye.className = "bg-white rounded-2xl p-4 border border-gray-200/90 shadow-xs hover:border-gray-300 transition-all duration-150 cursor-pointer flex items-center justify-between";
                badgeKampanye.className = "text-xs font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600";
                badgeKampanye.textContent = "Pilih";

                secBukti.classList.remove('hidden');
                secOrg.classList.add('hidden');
                secKampanye.classList.add('hidden');
                title.textContent = "Daftar Verifikasi Bukti";
                searchInput.placeholder = "Cari nama organisasi, keterangan pengajuan, atau nominal...";

                resetRowsVisibility('bukti-row');
                countBadge.textContent = `${totalData.bukti} Data Ditampilkan`;
                toggleEmptyState('empty-state-bukti', totalData.bukti);
            } else {
                tabKampanye.className = "bg-white rounded-2xl p-4 border-2 border-[#0e4b33] bg-[#0e4b33]/5 shadow-sm transition-all duration-150 cursor-pointer flex items-center justify-between";
                badgeKampanye.className = "text-xs font-bold px-3 py-1 rounded-full bg-[#0e4b33] text-white";
                badgeKampanye.textContent = "Aktif";

                tabOrg.className = "bg-white rounded-2xl p-4 border border-gray-200/90 shadow-xs hover:border-gray-300 transition-all duration-150 cursor-pointer flex items-center justify-between";
                badgeOrg.className = "text-xs font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600";
                badgeOrg.textContent = "Pilih";
                tabBukti.className = "bg-white rounded-2xl p-4 border border-gray-200/90 shadow-xs hover:border-gray-300 transition-all duration-150 cursor-pointer flex items-center justify-between";
                badgeBukti.className = "text-xs font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600";
                badgeBukti.textContent = "Pilih";

                secKampanye.classList.remove('hidden');
                secOrg.classList.add('hidden');
                secBukti.classList.add('hidden');
                title.textContent = "Antrian Kampanye";
                searchInput.placeholder = "Cari judul kampanye atau nama organisasi...";

                resetRowsVisibility('campaign-row');
                countBadge.textContent = `${totalData.kampanye} Data Ditampilkan`;
                toggleEmptyState('empty-state-kampanye', totalData.kampanye);
            }
        }

        function handleSearch() {
            const query = document.getElementById('search-input').value.toLowerCase().trim();
            const countBadge = document.getElementById('table-count-badge');

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
                toggleEmptyState('empty-state-org', visibleCount, query);
            } else if (currentTab === 'bukti') {
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
                toggleEmptyState('empty-state-bukti', visibleCount, query);
            } else {
                const rows = document.querySelectorAll('.campaign-row');
                let visibleCount = 0;
                rows.forEach(row => {
                    const title = row.querySelector('.cell-campaign-name').textContent.toLowerCase();
                    const organization = row.querySelector('.cell-campaign-org').textContent.toLowerCase();

                    if (title.includes(query) || organization.includes(query)) {
                        row.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        row.classList.add('hidden');
                    }
                });
                countBadge.textContent = `${visibleCount} Data Ditampilkan`;
                toggleEmptyState('empty-state-kampanye', visibleCount, query);
            }
        }

        // Pesan kosong: hasil pencarian tidak cocok, atau memang belum ada antrian
        const EMPTY_TEXT = {
            'empty-state-org': ['Tidak ada data organisasi yang cocok dengan pencarian.', 'Belum ada pendaftaran organisasi yang menunggu verifikasi.'],
            'empty-state-bukti': ['Tidak ada data verifikasi bukti yang cocok dengan pencarian.', 'Belum ada bukti penyaluran yang menunggu verifikasi.'],
            'empty-state-kampanye': ['Tidak ada kampanye yang cocok dengan pencarian.', 'Belum ada kampanye yang menunggu verifikasi.']
        };

        function toggleEmptyState(id, visibleCount, query = '') {
            const element = document.getElementById(id);

            element.textContent = EMPTY_TEXT[id][query ? 0 : 1];
            element.classList.toggle('hidden', visibleCount > 0);
        }

        function resetRowsVisibility(rowClass) {
            document.querySelectorAll('.' + rowClass).forEach(row => row.classList.remove('hidden'));
            document.getElementById('empty-state-org').classList.add('hidden');
            document.getElementById('empty-state-bukti').classList.add('hidden');
            document.getElementById('empty-state-kampanye').classList.add('hidden');
        }

        // ==========================
        // DATA DARI API
        // ==========================

        const { request, formatRupiah, escapeHtml } = window.RangkulAdmin;
        const DETAIL_ORGANISASI_URL = @json(route('staff.pengajuandanauser', ['organization' => '__ID__']));
        const DETAIL_BUKTI_URL = @json(route('staff.ajuanpencairandana', ['proof' => '__ID__']));
        const totalData = { organisasi: 0, bukti: 0, kampanye: 0 };

        function detailButton(url, id) {
            return `
                <a href="${escapeHtml(url.replace('__ID__', encodeURIComponent(id)))}"
                    class="inline-block px-6 py-1.5 bg-[#0e4b33] text-white text-xs font-semibold rounded-xl hover:bg-[#093524] active:scale-95 transition-all shadow-xs cursor-pointer">
                    Detail
                </a>
            `;
        }

        function renderOrganisasi(items) {
            document.getElementById('tbody-organisasi').innerHTML = items.map((item, index) => `
                <tr class="org-row hover:bg-gray-50/70 transition-colors">
                    <td class="py-3.5 px-6 text-gray-800 text-center">${index + 1}.</td>
                    <td class="py-3.5 px-6 text-gray-800 text-center">${escapeHtml(item.registered_at)}</td>
                    <td class="py-3.5 px-6 font-medium text-gray-900 cell-name">${escapeHtml(item.name)}</td>
                    <td class="py-3.5 px-6 text-gray-600 cell-cat text-center">${escapeHtml(item.type)}</td>
                    <td class="py-3.5 px-6 text-gray-600 cell-loc text-center">${escapeHtml(item.city)}</td>
                    <td class="py-3.5 px-6 text-center">${detailButton(DETAIL_ORGANISASI_URL, item.id)}</td>
                </tr>
            `).join('');
        }

        function renderBukti(items) {
            document.getElementById('tbody-bukti').innerHTML = items.map((item, index) => `
                <tr class="bukti-row hover:bg-gray-50/70 transition-colors">
                    <td class="py-3.5 px-6 text-gray-800 text-center">${index + 1}.</td>
                    <td class="py-3.5 px-6 font-medium text-gray-900 cell-name">${escapeHtml(item.organization_name)}</td>
                    <td class="py-3.5 px-6 text-sm text-gray-600 cell-desc">${escapeHtml(item.description)}</td>
                    <td class="py-3.5 px-6 text-gray-900 font-semibold cell-nominal text-center">${escapeHtml(formatRupiah(item.amount))}</td>
                    <td class="py-3.5 px-6 text-center">${detailButton(DETAIL_BUKTI_URL, item.id)}</td>
                </tr>
            `).join('');
        }

        function formatCampaignDate(value) {
            if (!value) return '-';
            const date = new Date(value);

            return Number.isNaN(date.getTime())
                ? '-'
                : date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        }

        function renderCampaigns() {
            document.getElementById('tbody-kampanye').innerHTML = campaigns.map((campaign, index) => `
                <tr class="campaign-row hover:bg-gray-50/70 transition-colors">
                    <td class="py-3.5 px-6 text-gray-800 text-center">${index + 1}.</td>
                    <td class="py-3.5 px-6 font-medium text-gray-900 cell-campaign-name">${escapeHtml(campaign.judul)}</td>
                    <td class="py-3.5 px-6 text-gray-700 cell-campaign-org">${escapeHtml(campaign.organization?.nama_lembaga || '-')}</td>
                    <td class="py-3.5 px-6 text-gray-900 font-semibold text-center">${escapeHtml(formatRupiah(campaign.target_dana))}</td>
                    <td class="py-3.5 px-6 text-gray-600 text-center">${escapeHtml(formatCampaignDate(campaign.tanggal_mulai))} - ${escapeHtml(formatCampaignDate(campaign.tanggal_selesai))}</td>
                    <td class="py-3.5 px-6 text-center">
                        <button type="button" data-campaign-action="detail" data-campaign-id="${escapeHtml(campaign.id)}"
                            class="inline-block px-5 py-1.5 bg-[#0e4b33] text-white text-xs font-semibold rounded-xl hover:bg-[#093524] transition-all">
                            Tinjau
                        </button>
                    </td>
                </tr>
            `).join('');

            totalData.kampanye = campaigns.length;
            document.getElementById('summary-pending-campaigns').textContent = campaigns.length;
            document.getElementById('tab-caption-kampanye').textContent = `${campaigns.length} kampanye menunggu verifikasi`;
            if (currentTab === 'kampanye') {
                handleSearch();
            }
        }

        function showCampaignDetail(campaign) {
            selectedCampaignId = campaign.id;
            document.getElementById('campaign-detail-title').textContent = campaign.judul || '-';
            document.getElementById('campaign-detail-organization').textContent = campaign.organization?.nama_lembaga || 'Organisasi tidak tersedia';
            document.getElementById('campaign-detail-target').textContent = formatRupiah(campaign.target_dana);
            document.getElementById('campaign-detail-period').textContent =
                `${formatCampaignDate(campaign.tanggal_mulai)} - ${formatCampaignDate(campaign.tanggal_selesai)}`;
            document.getElementById('campaign-detail-description').textContent = campaign.deskripsi || '-';

            const cover = document.getElementById('campaign-detail-cover');
            if (campaign.foto_cover) {
                cover.src = '/storage/' + campaign.foto_cover.split('/').map(encodeURIComponent).join('/');
                cover.classList.remove('hidden');
            } else {
                cover.removeAttribute('src');
                cover.classList.add('hidden');
            }

            const feedback = document.getElementById('campaign-detail-feedback');
            feedback.textContent = '';
            feedback.className = 'hidden rounded-lg px-3 py-2 text-sm';
            document.getElementById('campaign-action-options').classList.remove('hidden');
            document.getElementById('campaign-approve-confirmation').classList.add('hidden');
            document.getElementById('campaign-reject-form').classList.add('hidden');
            document.getElementById('campaign-reject-reason').value = '';
            document.getElementById('campaign-detail-modal').classList.remove('hidden');
        }

        async function verifyCampaign(status) {
            const campaign = campaigns.find(item => item.id === selectedCampaignId);
            if (!campaign) return;

            let body = { status };
            if (status === 'ditolak') {
                const reason = document.getElementById('campaign-reject-reason').value;
                if (!reason.trim()) {
                    showCampaignFeedback('Alasan penolakan wajib diisi.');
                    return;
                }
                body.alasan_tolak = reason.trim();
            }

            const approveButton = document.getElementById('campaign-approve-confirm');
            const rejectButton = document.getElementById('campaign-reject-confirm');
            approveButton.disabled = true;
            rejectButton.disabled = true;

            try {
                const result = await request('/api/admin/campaigns/' + encodeURIComponent(campaign.id) + '/verify', {
                    method: 'PUT',
                    body
                });
                campaigns = campaigns.filter(item => item.id !== campaign.id);
                renderCampaigns();
                document.getElementById('campaign-detail-modal').classList.add('hidden');
                const message = document.getElementById('campaign-action-message');
                message.textContent = result.message || 'Status kampanye berhasil diperbarui.';
                message.classList.remove('hidden');
            } catch (exception) {
                showCampaignFeedback(exception.message);
            } finally {
                approveButton.disabled = false;
                rejectButton.disabled = false;
            }
        }

        function showCampaignFeedback(message) {
            const feedback = document.getElementById('campaign-detail-feedback');
            feedback.textContent = message;
            feedback.className = 'rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700';
        }

        document.getElementById('tbody-kampanye').addEventListener('click', event => {
            const button = event.target.closest('[data-campaign-action="detail"]');
            if (!button) return;
            const campaign = campaigns.find(item => item.id === button.dataset.campaignId);
            if (campaign) showCampaignDetail(campaign);
        });

        document.getElementById('campaign-detail-close').addEventListener('click', () => {
            document.getElementById('campaign-detail-modal').classList.add('hidden');
        });
        document.getElementById('campaign-detail-modal').addEventListener('click', event => {
            if (event.target.id === 'campaign-detail-modal') {
                event.currentTarget.classList.add('hidden');
            }
        });
        document.getElementById('campaign-detail-approve').addEventListener('click', () => {
            const campaign = campaigns.find(item => item.id === selectedCampaignId);
            if (!campaign) return;
            document.getElementById('campaign-action-options').classList.add('hidden');
            document.getElementById('campaign-approve-confirmation-text').textContent =
                `Yakin ingin menyetujui kampanye "${campaign.judul}"?`;
            document.getElementById('campaign-approve-confirmation').classList.remove('hidden');
        });
        document.getElementById('campaign-detail-reject').addEventListener('click', () => {
            document.getElementById('campaign-action-options').classList.add('hidden');
            document.getElementById('campaign-reject-form').classList.remove('hidden');
            document.getElementById('campaign-reject-reason').focus();
        });
        document.getElementById('campaign-approve-cancel').addEventListener('click', () => {
            document.getElementById('campaign-approve-confirmation').classList.add('hidden');
            document.getElementById('campaign-action-options').classList.remove('hidden');
        });
        document.getElementById('campaign-approve-confirm').addEventListener('click', () => verifyCampaign('aktif'));
        document.getElementById('campaign-reject-cancel').addEventListener('click', () => {
            document.getElementById('campaign-reject-form').classList.add('hidden');
            document.getElementById('campaign-action-options').classList.remove('hidden');
        });
        document.getElementById('campaign-reject-confirm').addEventListener('click', () => verifyCampaign('ditolak'));

        async function loadDashboard() {
            const error = document.getElementById('dashboard-error');

            try {
                const { data } = await request('/api/admin/staff/dashboard');
                error.classList.add('hidden');

                document.getElementById('summary-pending-organizations').textContent = data.summary.pending_organizations;
                document.getElementById('summary-pending-proofs').textContent = data.summary.pending_proofs;
                document.getElementById('summary-completed').textContent = data.summary.completed_verifications;
                document.getElementById('tab-caption-organisasi').textContent = data.organizations.length + ' antrian pendaftaran lembaga';
                document.getElementById('tab-caption-bukti').textContent = data.proofs.length + ' antrian verifikasi bukti donasi';

                totalData.organisasi = data.organizations.length;
                totalData.bukti = data.proofs.length;
                renderOrganisasi(data.organizations);
                renderBukti(data.proofs);
                handleSearch();
            } catch (exception) {
                error.textContent = exception.message;
                error.classList.remove('hidden');
            }
        }

        async function loadCampaigns() {
            const error = document.getElementById('campaign-queue-error');

            try {
                const { data } = await request('/api/admin/campaigns/pending');
                campaigns = data;
                error.classList.add('hidden');
                renderCampaigns();
            } catch (exception) {
                error.textContent = exception.message;
                error.classList.remove('hidden');
            }
        }

        loadDashboard();
        loadCampaigns();
    </script>
</body>
</html>