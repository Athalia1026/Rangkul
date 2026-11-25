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
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F5F7F4; }
    </style>
</head>
<body class="text-gray-800">

    @include('staff.layouts.navbar')

    <!-- KONTEN BERANDA STAFF -->
    <main class="max-w-[1200px] mx-auto px-6 pt-[10px] pb-[28px] space-y-8">

        <div id="dashboard-error" class="hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-center text-sm text-red-700"></div>
        <div id="campaign-queue-error" class="hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-center text-sm text-red-700"></div>
        <div id="campaign-action-message" class="hidden rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-center text-sm text-green-700" role="status"></div>

        <section class="space-y-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Ringkasan Antrian</h1>
                <p class="mt-1 text-sm text-gray-500">Antrian pemeriksaan dan tindak lanjut staff.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                <button id="summary-card-organisasi" type="button" aria-pressed="true" onclick="switchTab('organisasi')"
                        class="w-full min-h-[112px] rounded-lg border border-[#0e4b33] bg-[#0e4b33]/5 px-5 py-4 text-left flex items-center justify-between shadow-sm hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0e4b33] transition-shadow">
                    <span class="flex items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-[#dce9df] bg-[#f5f8f5] text-[#0e4b33]" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                <path d="M4 20h16M6 20V5.5L12 3l6 2.5V20M9 8h.01M15 8h.01M9 12h.01M15 12h.01M10 20v-4h4v4" />
                            </svg>
                        </span>
                        <span>
                            <span class="block text-base font-semibold text-gray-800">Pendaftaran Organisasi</span>
                            <span class="mt-1 block text-sm text-gray-500">Menunggu verifikasi</span>
                        </span>
                    </span>
                    <span id="summary-pending-organizations" class="ml-3 text-3xl font-semibold leading-none tabular-nums text-[#0e4b33]">0</span>
                </button>

                <button id="summary-card-pencairan" type="button" aria-pressed="false" onclick="switchTab('pencairan')"
                        class="w-full min-h-[112px] rounded-lg border border-gray-200 bg-white px-5 py-4 text-left flex items-center justify-between shadow-sm hover:border-[#0e4b33] hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0e4b33] transition-all">
                    <span class="flex items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-[#dce9df] bg-[#f5f8f5] text-[#0e4b33]" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                <rect x="3.5" y="5.5" width="17" height="13" rx="2" />
                                <path d="M3.5 9.5h17M16 14h2" />
                            </svg>
                        </span>
                        <span>
                            <span class="block text-base font-semibold text-gray-800">Ajuan Pencairan</span>
                            <span class="mt-1 block text-sm text-gray-500">Menunggu pemeriksaan staff</span>
                        </span>
                    </span>
                    <span id="summary-pending-disbursements" class="ml-3 text-3xl font-semibold leading-none tabular-nums text-[#0e4b33]">0</span>
                </button>

                <button id="summary-card-bukti" type="button" aria-pressed="false" onclick="switchTab('bukti')"
                        class="w-full min-h-[112px] rounded-lg border border-gray-200 bg-white px-5 py-4 text-left flex items-center justify-between shadow-sm hover:border-[#0e4b33] hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0e4b33] transition-all">
                    <span class="flex items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-[#dce9df] bg-[#f5f8f5] text-[#0e4b33]" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                <path d="M6 3.5h12v17l-3-1.8-3 1.8-3-1.8-3 1.8v-17Z" />
                                <path d="M9 8h6M9 12h6M9 16h3" />
                            </svg>
                        </span>
                        <span>
                            <span class="block text-base font-semibold text-gray-800">Bukti Penyaluran</span>
                            <span class="mt-1 block text-sm text-gray-500">Menunggu pemeriksaan staff</span>
                        </span>
                    </span>
                    <span id="summary-pending-proofs" class="ml-3 text-3xl font-semibold leading-none tabular-nums text-[#0e4b33]">0</span>
                </button>

                <button id="summary-card-kampanye" type="button" aria-pressed="false" onclick="switchTab('kampanye')"
                        class="w-full min-h-[112px] rounded-lg border border-gray-200 bg-white px-5 py-4 text-left flex items-center justify-between shadow-sm hover:border-[#0e4b33] hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0e4b33] transition-all">
                    <span class="flex items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-[#dce9df] bg-[#f5f8f5] text-[#0e4b33]" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                <path d="M4 10v4h3l10 4V6L7 10H4Z" />
                                <path d="m7 14 1.5 5h3L10 14M19 9l2-2m-2 8 2 2" />
                            </svg>
                        </span>
                        <span>
                            <span class="block text-base font-semibold text-gray-800">Verifikasi Kampanye</span>
                            <span class="mt-1 block text-sm text-gray-500">Menunggu verifikasi</span>
                        </span>
                    </span>
                    <span id="summary-pending-campaigns" class="ml-3 text-3xl font-semibold leading-none tabular-nums text-[#0e4b33]">0</span>
                </button>

                <button id="summary-card-transfer" type="button" aria-pressed="false" onclick="switchTab('transfer')"
                        class="w-full min-h-[112px] rounded-lg border border-gray-200 bg-white px-5 py-4 text-left flex items-center justify-between shadow-sm hover:border-[#0e4b33] hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0e4b33] transition-all">
                    <span class="flex items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-[#dce9df] bg-[#f5f8f5] text-[#0e4b33]" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                <rect x="3.5" y="5.5" width="17" height="13" rx="2" />
                                <path d="M3.5 9.5h17M7 14h4" />
                            </svg>
                        </span>
                        <span>
                            <span class="block text-base font-semibold text-gray-800">Transfer Dana</span>
                            <span class="mt-1 block text-sm text-gray-500">Menunggu bukti transfer</span>
                        </span>
                    </span>
                    <span id="summary-pending-transfers" class="ml-3 text-3xl font-semibold leading-none tabular-nums text-[#0e4b33]">0</span>
                </button>

                <div class="w-full min-h-[112px] rounded-lg border border-gray-200 bg-white px-5 py-4 flex items-center justify-between shadow-sm">
                    <span class="flex items-center gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-gray-600" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                                <circle cx="12" cy="12" r="9" />
                                <path d="m8 12 2.5 2.5L16 9" />
                            </svg>
                        </span>
                        <span>
                            <span class="block text-base font-semibold text-gray-800">Verifikasi Selesai</span>
                            <span class="mt-1 block text-sm text-gray-500">Total pemeriksaan selesai</span>
                        </span>
                    </span>
                    <span id="summary-completed" class="ml-3 text-3xl font-semibold leading-none tabular-nums text-gray-700">0</span>
                </div>
            </div>
        </section>

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
                            <th class="py-3 px-6 w-16 text-center">No.</th>
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

            <!-- 2. TABEL AJUAN PENCAIRAN -->
            <div id="section-table-pencairan" class="hidden overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-sm font-bold text-gray-900 bg-white text-center">
                            <th class="py-3 px-6 w-16 text-center">No.</th>
                            <th class="py-3 px-5">Tanggal</th>
                            <th class="py-3 px-5">Organisasi</th>
                            <th class="py-3 px-5">Kampanye</th>
                            <th class="py-3 px-5">Nominal</th>
                            <th class="py-3 px-5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-disbursements" class="divide-y divide-gray-200/80 text-sm">
                        <tr class="disbursement-empty-row">
                            <td colspan="6" class="py-8 text-center text-xs text-gray-400">Memuat ajuan pencairan...</td>
                        </tr>
                    </tbody>
                </table>
                <div id="empty-state-disbursement" class="hidden py-8 text-center text-gray-400 text-xs">
                    Tidak ada ajuan pencairan yang cocok dengan pencarian.
                </div>
            </div>

            <!-- 3. TABEL BUKTI PENYALURAN -->
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
                        <tr class="proof-empty-row">
                            <td colspan="5" class="py-8 text-center text-xs text-gray-400">Memuat bukti penyaluran...</td>
                        </tr>
                    </tbody>
                </table>
                <div id="empty-state-bukti" class="hidden py-8 text-center text-gray-400 text-xs">
                    Tidak ada data verifikasi bukti yang cocok dengan pencarian.
                </div>
            </div>

            <!-- 4. TABEL KAMPANYE -->
            <div id="section-table-kampanye" class="hidden overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-sm font-bold text-gray-900 bg-white text-center">
                            <th class="py-3 px-6 w-16 text-center">No.</th>
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

            <!-- 5. TABEL TRANSFER DANA -->
            <div id="section-table-transfer" class="hidden overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-sm font-bold text-gray-900 bg-white text-center">
                            <th class="py-3 px-6 w-16 text-center">No.</th>
                            <th class="py-3 px-5">Tanggal Persetujuan</th>
                            <th class="py-3 px-5">Organisasi</th>
                            <th class="py-3 px-5">Kampanye</th>
                            <th class="py-3 px-5">Nominal</th>
                            <th class="py-3 px-5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-transfer" class="divide-y divide-gray-200/80 text-sm">
                        <tr class="transfer-empty-row">
                            <td colspan="6" class="py-8 text-center text-xs text-gray-400">Memuat transfer dana...</td>
                        </tr>
                    </tbody>
                </table>
                <div id="empty-state-transfer" class="hidden py-8 text-center text-gray-400 text-xs">
                    Tidak ada transfer yang cocok dengan pencarian.
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
            const tabs = {
                organisasi: {
                    button: 'summary-card-organisasi',
                    section: 'section-table-organisasi',
                    title: 'Data Verifikasi Organisasi',
                    placeholder: 'Cari nama organisasi, kategori, atau wilayah...',
                    rowClass: 'org-row',
                    countText: () => `${totalData.organisasi} Data Ditampilkan`,
                    emptyState: 'empty-state-org',
                    emptyCount: () => totalData.organisasi
                },
                pencairan: {
                    button: 'summary-card-pencairan',
                    section: 'section-table-pencairan',
                    title: 'Ajuan Pencairan',
                    placeholder: 'Cari organisasi, kampanye, atau nominal...',
                    rowClass: 'disbursement-row',
                    countText: () => `${totalData.disbursements} Ajuan`,
                    emptyState: 'empty-state-disbursement',
                    emptyCount: () => totalData.disbursements || 1
                },
                bukti: {
                    button: 'summary-card-bukti',
                    section: 'section-table-bukti',
                    title: 'Bukti Penyaluran',
                    placeholder: 'Cari organisasi, keterangan penyaluran, atau nominal...',
                    rowClass: 'bukti-row',
                    countText: () => `${totalData.bukti} Bukti`,
                    emptyState: 'empty-state-bukti',
                    emptyCount: () => totalData.bukti || 1
                },
                kampanye: {
                    button: 'summary-card-kampanye',
                    section: 'section-table-kampanye',
                    title: 'Antrian Kampanye',
                    placeholder: 'Cari judul kampanye atau nama organisasi...',
                    rowClass: 'campaign-row',
                    countText: () => `${totalData.kampanye} Data Ditampilkan`,
                    emptyState: 'empty-state-kampanye',
                    emptyCount: () => totalData.kampanye
                },
                transfer: {
                    button: 'summary-card-transfer',
                    section: 'section-table-transfer',
                    title: 'Transfer Dana',
                    placeholder: 'Cari organisasi, kampanye, atau nominal transfer...',
                    rowClass: 'transfer-row',
                    countText: () => `${totalData.transfers} Transfer`,
                    emptyState: 'empty-state-transfer',
                    emptyCount: () => totalData.transfers || 1
                }
            };
            const selectedTab = tabs[tab];
            if (!selectedTab) return;

            currentTab = tab;
            const searchInput = document.getElementById('search-input');
            searchInput.value = '';

            const title = document.getElementById('table-title');
            const countBadge = document.getElementById('table-count-badge');
            const activeButtonClass = 'w-full min-h-[112px] rounded-lg border border-[#0e4b33] bg-[#0e4b33]/5 px-5 py-4 text-left flex items-center justify-between shadow-sm hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0e4b33] transition-shadow';
            const inactiveButtonClass = 'w-full min-h-[112px] rounded-lg border border-gray-200 bg-white px-5 py-4 text-left flex items-center justify-between shadow-sm hover:border-[#0e4b33] hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#0e4b33] transition-all';

            Object.entries(tabs).forEach(([name, item]) => {
                const active = name === tab;
                const button = document.getElementById(item.button);
                button.className = active ? activeButtonClass : inactiveButtonClass;
                button.setAttribute('aria-pressed', String(active));
                document.getElementById(item.section).classList.toggle('hidden', !active);
            });

            title.textContent = selectedTab.title;
            searchInput.placeholder = selectedTab.placeholder;
            resetRowsVisibility(selectedTab.rowClass);
            countBadge.textContent = selectedTab.countText();
            toggleEmptyState(selectedTab.emptyState, selectedTab.emptyCount());
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
                const proofEmptyRow = document.querySelector('.proof-empty-row');
                if (proofEmptyRow) {
                    proofEmptyRow.classList.toggle('hidden', Boolean(query));
                }

                countBadge.textContent = `${visibleCount} Bukti`;
                toggleEmptyState('empty-state-bukti', visibleCount || (!query && proofEmptyRow ? 1 : 0), query);
            } else if (currentTab === 'pencairan') {
                const rows = document.querySelectorAll('.disbursement-row');
                let visibleCount = 0;
                rows.forEach(row => {
                    const organization = row.querySelector('.disbursement-org').textContent.toLowerCase();
                    const campaign = row.querySelector('.disbursement-campaign').textContent.toLowerCase();
                    const nominal = row.querySelector('.disbursement-nominal').textContent.toLowerCase();

                    if (organization.includes(query) || campaign.includes(query) || nominal.includes(query)) {
                        row.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        row.classList.add('hidden');
                    }
                });
                const emptyRow = document.querySelector('.disbursement-empty-row');
                if (emptyRow) {
                    emptyRow.classList.toggle('hidden', Boolean(query));
                }

                countBadge.textContent = `${visibleCount} Ajuan`;
                toggleEmptyState('empty-state-disbursement', visibleCount || (!query && emptyRow ? 1 : 0), query);
            } else if (currentTab === 'transfer') {
                const rows = document.querySelectorAll('.transfer-row');
                let visibleCount = 0;
                rows.forEach(row => {
                    const organization = row.querySelector('.transfer-org').textContent.toLowerCase();
                    const campaign = row.querySelector('.transfer-campaign').textContent.toLowerCase();
                    const nominal = row.querySelector('.transfer-nominal').textContent.toLowerCase();

                    if (organization.includes(query) || campaign.includes(query) || nominal.includes(query)) {
                        row.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        row.classList.add('hidden');
                    }
                });
                const emptyRow = document.querySelector('.transfer-empty-row');
                if (emptyRow) {
                    emptyRow.classList.toggle('hidden', Boolean(query));
                }

                countBadge.textContent = `${visibleCount} Transfer`;
                toggleEmptyState('empty-state-transfer', visibleCount || (!query && emptyRow ? 1 : 0), query);
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
            'empty-state-disbursement': ['Tidak ada ajuan pencairan yang cocok dengan pencarian.', 'Belum ada ajuan pencairan yang menunggu verifikasi staff.'],
            'empty-state-bukti': ['Tidak ada data verifikasi bukti yang cocok dengan pencarian.', 'Belum ada bukti penyaluran yang menunggu verifikasi.'],
            'empty-state-kampanye': ['Tidak ada kampanye yang cocok dengan pencarian.', 'Belum ada kampanye yang menunggu verifikasi.'],
            'empty-state-transfer': ['Tidak ada transfer yang cocok dengan pencarian.', 'Belum ada transfer yang menunggu dicatat staff.']
        };

        function toggleEmptyState(id, visibleCount, query = '') {
            const element = document.getElementById(id);

            element.textContent = EMPTY_TEXT[id][query ? 0 : 1];
            element.classList.toggle('hidden', visibleCount > 0);
        }

        function resetRowsVisibility(rowClass) {
            document.querySelectorAll('.' + rowClass).forEach(row => row.classList.remove('hidden'));
            document.getElementById('empty-state-org').classList.add('hidden');
            document.getElementById('empty-state-disbursement').classList.add('hidden');
            document.getElementById('empty-state-bukti').classList.add('hidden');
            document.getElementById('empty-state-kampanye').classList.add('hidden');
            document.getElementById('empty-state-transfer').classList.add('hidden');
            document.querySelectorAll('.proof-empty-row, .disbursement-empty-row, .transfer-empty-row').forEach(row => row.classList.remove('hidden'));
        }

        // ==========================
        // DATA DARI API
        // ==========================

        const { request, formatRupiah, escapeHtml } = window.RangkulAdmin;
        const DETAIL_ORGANISASI_URL = @json(route('staff.pengajuandanauser', ['organization' => '__ID__']));
        const DETAIL_BUKTI_URL = @json(route('staff.ajuanpencairandana', ['proof' => '__ID__']));
        const DISBURSEMENT_REVIEW_URL = @json(route('staff.ajuanpencairan', ['disbursement' => '__ID__']));
        const TRANSFER_URL = @json(route('staff.transfer', ['disbursement' => '__ID__']));
        const totalData = { organisasi: 0, bukti: 0, disbursements: 0, kampanye: 0, transfers: 0 };

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
                    <td class="py-3.5 px-6 w-16 text-gray-800 text-center whitespace-nowrap">${index + 1}.</td>
                    <td class="py-3.5 px-6 text-gray-800 text-center">${escapeHtml(item.registered_at)}</td>
                    <td class="py-3.5 px-6 font-medium text-gray-900 cell-name">${escapeHtml(item.name)}</td>
                    <td class="py-3.5 px-6 text-gray-600 cell-cat text-center">${escapeHtml(item.type)}</td>
                    <td class="py-3.5 px-6 text-gray-600 cell-loc text-center">${escapeHtml(item.city)}</td>
                    <td class="py-3.5 px-6 text-center">${detailButton(DETAIL_ORGANISASI_URL, item.id)}</td>
                </tr>
            `).join('');
        }

        function renderBukti(items) {
            document.getElementById('tbody-bukti').innerHTML = items.length ? items.map((item, index) => `
                <tr class="bukti-row hover:bg-gray-50/70 transition-colors">
                    <td class="py-3.5 px-6 w-16 text-gray-800 text-center whitespace-nowrap">${index + 1}.</td>
                    <td class="py-3.5 px-6 font-medium text-gray-900 cell-name">${escapeHtml(item.organization_name)}</td>
                    <td class="py-3.5 px-6 text-sm text-gray-600 cell-desc">${escapeHtml(item.description)}</td>
                    <td class="py-3.5 px-6 text-gray-900 font-semibold cell-nominal text-center">${escapeHtml(formatRupiah(item.amount))}</td>
                    <td class="py-3.5 px-6 text-center">${detailButton(DETAIL_BUKTI_URL, item.id)}</td>
                </tr>
            `).join('') : `
                <tr class="proof-empty-row">
                    <td colspan="5" class="py-8 text-center text-xs text-gray-400">Belum ada bukti penyaluran yang menunggu verifikasi.</td>
                </tr>
            `;
        }

        function renderDisbursements(items) {
            const rows = document.getElementById('tbody-disbursements');
            document.getElementById('summary-pending-disbursements').textContent = items.length;

            rows.innerHTML = items.length ? items.map((item, index) => `
                <tr class="disbursement-row hover:bg-gray-50/70 transition-colors">
                    <td class="py-3.5 px-6 w-16 text-gray-800 text-center whitespace-nowrap">${index + 1}.</td>
                    <td class="py-3.5 px-5 text-center">${escapeHtml(item.submitted_at)}</td>
                    <td class="py-3.5 px-5 font-medium disbursement-org">${escapeHtml(item.organization_name)}</td>
                    <td class="py-3.5 px-5 disbursement-campaign">${escapeHtml(item.campaign_title)}</td>
                    <td class="py-3.5 px-5 text-center font-semibold disbursement-nominal">${escapeHtml(formatRupiah(item.amount))}</td>
                    <td class="py-3.5 px-5 text-center">
                        ${detailButton(DISBURSEMENT_REVIEW_URL, item.id)}
                    </td>
                </tr>
            `).join('') : `
                <tr class="disbursement-empty-row">
                    <td colspan="6" class="py-8 text-center text-xs text-gray-400">Belum ada ajuan pencairan yang menunggu verifikasi staff.</td>
                </tr>
            `;
            totalData.disbursements = items.length;
            toggleEmptyState('empty-state-disbursement', items.length || 1);
        }

        function renderTransfers(items) {
            const rows = document.getElementById('tbody-transfer');
            document.getElementById('summary-pending-transfers').textContent = items.length;

            rows.innerHTML = items.length ? items.map((item, index) => `
                <tr class="transfer-row hover:bg-gray-50/70 transition-colors">
                    <td class="py-3.5 px-6 w-16 text-gray-800 text-center whitespace-nowrap">${index + 1}.</td>
                    <td class="py-3.5 px-5 text-center">${escapeHtml(item.approved_at || '-')}</td>
                    <td class="py-3.5 px-5 font-medium transfer-org">${escapeHtml(item.organization_name)}</td>
                    <td class="py-3.5 px-5 transfer-campaign">${escapeHtml(item.campaign_title)}</td>
                    <td class="py-3.5 px-5 text-center font-semibold transfer-nominal">${escapeHtml(formatRupiah(item.amount))}</td>
                    <td class="py-3.5 px-5 text-center">
                        ${detailButton(TRANSFER_URL, item.id)}
                    </td>
                </tr>
            `).join('') : `
                <tr class="transfer-empty-row">
                    <td colspan="6" class="py-8 text-center text-xs text-gray-400">Belum ada transfer yang menunggu dicatat staff.</td>
                </tr>
            `;
            totalData.transfers = items.length;
            toggleEmptyState('empty-state-transfer', items.length || 1);
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
                    <td class="py-3.5 px-6 w-16 text-gray-800 text-center whitespace-nowrap">${index + 1}.</td>
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

                totalData.organisasi = data.organizations.length;
                totalData.bukti = data.proofs.length;
                renderOrganisasi(data.organizations);
                renderBukti(data.proofs);
                renderDisbursements(data.disbursements);
                renderTransfers(data.pending_transfers);
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