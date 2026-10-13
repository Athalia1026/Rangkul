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

        <!-- 3 KARTU METRIK DI ATAS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 select-none">
            <!-- 1. Total Antrian Verifikasi Panti -->
            <div class="bg-white rounded-2xl border border-gray-300 shadow-sm px-6 py-4 flex items-center justify-between cursor-default">
                <div>
                    <span class="text-base font-bold text-gray-900 block tracking-tight">Total Antrian Verifikasi Panti</span>
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

            <!-- 3. Total Selesai Verifikasi -->
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

        <!-- 2 KARTU PEMILIH TABEL (BISA DIKLIK) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                    0 Data Ditampilkan
                </span>
            </div>

            <!-- 1. TABEL ORGANISASI -->
            <div id="section-table-organisasi" class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-sm font-bold text-gray-900 bg-white text-center">
                            <th class="py-3 px-6 w-16">No.</th>
                            <th class="py-3 px-6 ">Nama Organisasi</th>
                            <th class="py-3 px-6 w-48">Kategori</th>
                            <th class="py-3 px-6 w-48">Wilayah</th>
                            <th class="py-3 px-6 w-40">Tanggal Daftar</th>
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
        </div>

    </main>

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
                countBadge.textContent = `${totalData.organisasi} Data Ditampilkan`;
                toggleEmptyState('empty-state-org', totalData.organisasi);
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
                countBadge.textContent = `${totalData.bukti} Data Ditampilkan`;
                toggleEmptyState('empty-state-bukti', totalData.bukti);
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
                toggleEmptyState('empty-state-org', visibleCount, query);
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
                toggleEmptyState('empty-state-bukti', visibleCount, query);
            }
        }

        function resetSearch() {
            document.getElementById('search-input').value = '';
            handleSearch();
        }

        // Pesan kosong: hasil pencarian tidak cocok, atau memang belum ada antrian
        const EMPTY_TEXT = {
            'empty-state-org': ['Tidak ada data organisasi yang cocok dengan pencarian.', 'Belum ada pendaftaran organisasi yang menunggu verifikasi.'],
            'empty-state-bukti': ['Tidak ada data verifikasi bukti yang cocok dengan pencarian.', 'Belum ada bukti penyaluran yang menunggu verifikasi.']
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
        }

        // ==========================
        // DATA DARI API
        // ==========================

        const { request, formatRupiah, escapeHtml } = window.RangkulAdmin;
        const DETAIL_ORGANISASI_URL = @json(route('staff.pengajuandanauser', ['organization' => '__ID__']));
        const DETAIL_BUKTI_URL = @json(route('staff.ajuanpencairandana', ['proof' => '__ID__']));
        const totalData = { organisasi: 0, bukti: 0 };

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
                    <td class="py-3.5 px-6 font-medium text-gray-900 cell-name">${escapeHtml(item.name)}</td>
                    <td class="py-3.5 px-6 text-gray-600 cell-cat text-center">${escapeHtml(item.type)}</td>
                    <td class="py-3.5 px-6 text-gray-600 cell-loc text-center">${escapeHtml(item.city)}</td>
                    <td class="py-3.5 px-6 text-gray-800 text-center">${escapeHtml(item.registered_at)}</td>
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

        loadDashboard();
    </script>
</body>
</html>