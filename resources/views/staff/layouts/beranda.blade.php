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
<body class="text-gray-800">

    @include('staff.layouts.navbar')
    
    <!-- MAIN CONTENT -->
    <main class="max-w-[1320px] mx-auto px-6 py-8 space-y-8">

        <!-- 3 KARTU METRIK DI ATAS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- 1. Total Antrian Verifikasi Panti -->
            <div class="bg-white rounded-2xl border border-gray-300 shadow-sm px-6 py-4 flex items-center justify-between">
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
            <div class="bg-white rounded-2xl border border-gray-300 shadow-sm px-6 py-4 flex items-center justify-between">
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
            <div class="bg-white rounded-2xl border border-gray-300 shadow-sm px-6 py-4 flex items-center justify-between">
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

        <!-- DUA TABEL BERDAMPINGAN -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            
            <!-- TABEL KIRI: DATA VERIFIKASI ORGANISASI -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
                <div class="bg-[#0e4b33] px-6 py-3.5 flex items-center gap-2 text-white">
                    <span class="text-lg">📒</span>
                    <h2 class="font-bold text-base tracking-wide">Data Verifikasi Organisasi</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 text-xs font-bold text-gray-900 bg-white">
                                <th class="py-3 px-6 w-14">No.</th>
                                <th class="py-3 px-6">Nama Organisasi</th>
                                <th class="py-3 px-6 w-36">Tanggal Daftar</th>
                                <th class="py-3 px-6 w-28 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/80 text-sm">
                            <tr class="hover:bg-gray-50/70">
                                <td class="py-3.5 px-6 text-gray-800">1.</td>
                                <td class="py-3.5 px-6 font-medium text-gray-900">Asrama Pemberdayaan Yatim dan Dhuafa</td>
                                <td class="py-3.5 px-6 text-gray-800">16/02/2026</td>
                                <td class="py-3.5 px-6 text-center">
                                    <button onclick="bukaDetailOrg('Asrama Pemberdayaan Yatim dan Dhuafa', '16/02/2026')" class="px-6 py-1.5 bg-[#0e4b33] text-white text-xs font-semibold rounded-lg hover:bg-[#093524] transition-all shadow-xs cursor-pointer">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/70">
                                <td class="py-3.5 px-6 text-gray-800">2.</td>
                                <td class="py-3.5 px-6 font-medium text-gray-900">Sekolah Dasar Negeri Gebang 2</td>
                                <td class="py-3.5 px-6 text-gray-800">08/05/2026</td>
                                <td class="py-3.5 px-6 text-center">
                                    <button onclick="bukaDetailOrg('Sekolah Dasar Negeri Gebang 2', '08/05/2026')" class="px-6 py-1.5 bg-[#0e4b33] text-white text-xs font-semibold rounded-lg hover:bg-[#093524] transition-all shadow-xs cursor-pointer">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/70">
                                <td class="py-3.5 px-6 text-gray-800">3.</td>
                                <td class="py-3.5 px-6 font-medium text-gray-900">Panti Asuhan Ruhamaa</td>
                                <td class="py-3.5 px-6 text-gray-800">27/06/2026</td>
                                <td class="py-3.5 px-6 text-center">
                                    <button onclick="bukaDetailOrg('Panti Asuhan Ruhamaa', '27/06/2026')" class="px-6 py-1.5 bg-[#0e4b33] text-white text-xs font-semibold rounded-lg hover:bg-[#093524] transition-all shadow-xs cursor-pointer">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/70">
                                <td class="py-3.5 px-6 text-gray-800">4.</td>
                                <td class="py-3.5 px-6 font-medium text-gray-900">Yayasan Panti Asuhan Nur Iman</td>
                                <td class="py-3.5 px-6 text-gray-800">19/10/2026</td>
                                <td class="py-3.5 px-6 text-center">
                                    <button onclick="bukaDetailOrg('Yayasan Panti Asuhan Nur Iman', '19/10/2026')" class="px-6 py-1.5 bg-[#0e4b33] text-white text-xs font-semibold rounded-lg hover:bg-[#093524] transition-all shadow-xs cursor-pointer">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/70">
                                <td class="py-3.5 px-6 text-gray-800">5.</td>
                                <td class="py-3.5 px-6 font-medium text-gray-900">Panti Asuhan Arrohman</td>
                                <td class="py-3.5 px-6 text-gray-800">15/11/2026</td>
                                <td class="py-3.5 px-6 text-center">
                                    <button onclick="bukaDetailOrg('Panti Asuhan Arrohman', '15/11/2026')" class="px-6 py-1.5 bg-[#0e4b33] text-white text-xs font-semibold rounded-lg hover:bg-[#093524] transition-all shadow-xs cursor-pointer">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TABEL KANAN: DAFTAR VERIFIKASI BUKTI -->
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
                <div class="bg-[#0e4b33] px-6 py-3.5 flex items-center gap-2 text-white">
                    <span class="text-lg">📒</span>
                    <h2 class="font-bold text-base tracking-wide">Daftar Verifikasi Bukti</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 text-xs font-bold text-gray-900 bg-white">
                                <th class="py-3 px-6 w-14">No.</th>
                                <th class="py-3 px-6">Nama Organisasi</th>
                                <th class="py-3 px-6 w-36">Nominal</th>
                                <th class="py-3 px-6 w-28 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200/80 text-sm">
                            <tr class="hover:bg-gray-50/70">
                                <td class="py-3.5 px-6 text-gray-800">1.</td>
                                <td class="py-3.5 px-6 font-medium text-gray-900">Asrama Pemberdayaan Yatim dan Dhuafa</td>
                                <td class="py-3.5 px-6 text-gray-800">Rp 600.000</td>
                                <td class="py-3.5 px-6 text-center">
                                    <button onclick="bukaDetailBukti('Asrama Pemberdayaan Yatim dan Dhuafa', 'Rp 600.000')" class="px-6 py-1.5 bg-[#0e4b33] text-white text-xs font-semibold rounded-lg hover:bg-[#093524] transition-all shadow-xs cursor-pointer">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50/70">
                                <td class="py-3.5 px-6 text-gray-800">2.</td>
                                <td class="py-3.5 px-6 font-medium text-gray-900">Sekolah Dasar Negeri Gebang 2</td>
                                <td class="py-3.5 px-6 text-gray-800">Rp 50.000.000</td>
                                <td class="py-3.5 px-6 text-center">
                                    <button onclick="bukaDetailBukti('Sekolah Dasar Negeri Gebang 2', 'Rp 50.000.000')" class="px-6 py-1.5 bg-[#0e4b33] text-white text-xs font-semibold rounded-lg hover:bg-[#093524] transition-all shadow-xs cursor-pointer">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <!-- MODAL DETAIL ORGANISASI -->
    <div id="modal-org" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-[2px]">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Detail Verifikasi Organisasi</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-xs text-gray-500 font-semibold block">Nama Organisasi</span>
                    <span id="detail-org-nama" class="font-medium text-gray-900"></span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-semibold block">Tanggal Terdaftar</span>
                    <span id="detail-org-tanggal" class="text-gray-800"></span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-semibold block">Status</span>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">Menunggu Verifikasi</span>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="tutupModal('modal-org')" class="px-5 py-2 bg-[#0e4b33] text-white text-xs font-semibold rounded-xl hover:bg-[#093524]">Tutup</button>
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL BUKTI -->
    <div id="modal-bukti" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-[2px]">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Detail Verifikasi Bukti</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-xs text-gray-500 font-semibold block">Nama Organisasi</span>
                    <span id="detail-bukti-nama" class="font-medium text-gray-900"></span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-semibold block">Nominal Donasi</span>
                    <span id="detail-bukti-nominal" class="text-lg font-bold text-[#086538]"></span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 font-semibold block">Status</span>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Menunggu Verifikasi</span>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="tutupModal('modal-bukti')" class="px-5 py-2 bg-[#0e4b33] text-white text-xs font-semibold rounded-xl hover:bg-[#093524]">Tutup</button>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script>
        function bukaDetailOrg(nama, tanggal) {
            document.getElementById('detail-org-nama').textContent = nama;
            document.getElementById('detail-org-tanggal').textContent = tanggal;
            const m = document.getElementById('modal-org');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function bukaDetailBukti(nama, nominal) {
            document.getElementById('detail-bukti-nama').textContent = nama;
            document.getElementById('detail-bukti-nominal').textContent = nominal;
            const m = document.getElementById('modal-bukti');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function tutupModal(id) {
            const m = document.getElementById(id);
            m.classList.add('hidden');
            m.classList.remove('flex');
        }
    </script>
</body>
</html>