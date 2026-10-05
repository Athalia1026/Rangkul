<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus Jakarta Sans:wght@300;400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F5F7F4; }
        .text-rangkul-green { color: #086538; }
        .bg-rangkul-green { background-color: #086538; }
        .bg-light-green { background-color: #F5F7F4; }
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

        <a
            href="#"
            class="text-rangkul-green border-b-2 border-rangkul-green pb-1"
        >
            Beranda
        </a>

        <a
            href="#"
            class="hover:text-green-700"
        >
            Daftar Pengguna
        </a>

        <a
            href="#"
            class="hover:text-green-700"
        >
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


    <!-- MAIN CONTENT -->
    <main class="w-full max-w-[1200px] mx-auto px-8 py-1 space-y-6">

        <div id="dashboard-error" class="hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-center text-sm text-red-700"></div>

        <!-- Header Dashboard & Counter Cards -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
      <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Dashboard Super Admin</h1>
        <p class="text-sm text-gray-500 mt-1">Monitoring aktivitas dan kinerja sistem</p>
      </div>

      <div class="flex items-center gap-4">
        <!-- Total Staff -->
        <div class="bg-white rounded-xl border border-gray-200/90 shadow-sm px-5 py-3.5 w-44 flex items-center justify-between">
          <div>
            <span class="text-xs font-semibold text-gray-800 block">Total Staff</span>
            <span id="count-staff" class="text-2xl font-bold text-gray-900 leading-none mt-1.5 block">2</span>
          </div>
          <div class="text-[#0d4a32] pl-2">
            <svg class="w-9 h-9 fill-[#0d4a32]" viewBox="0 0 24 24">
              <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
            </svg>
          </div>
        </div>

        <!-- Total Manager -->
        <div class="bg-white rounded-xl border border-gray-200/90 shadow-sm px-5 py-3.5 w-44 flex items-center justify-between">
          <div>
            <span class="text-xs font-semibold text-gray-800 block">Total Manager</span>
            <span id="count-manager" class="text-2xl font-bold text-gray-900 leading-none mt-1.5 block">1</span>
          </div>
          <div class="text-[#0d4a32] pl-2">
            <svg class="w-9 h-9 fill-[#0d4a32]" viewBox="0 0 24 24">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

        <!-- TREN DONASI SECTION -->
        <section class="w-full bg-white p-6 rounded-xl shadow-sm border border-gray-100">            
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-[25px] font-bold">Tren Donasi</h2>
                <div class="flex items-center gap-2">
                    <input
                        type="date"
                        id="tanggalMulai"
                        class="border rounded-lg px-4 py-2 bg-white text-gray-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                    >
                    
                    <span class="text-gray-500">-</span>

                    <input
                        type="date"
                        id="tanggalSelesai"
                        class="border rounded-lg px-4 py-2 bg-white text-gray-500 focus:outline-none focus:ring-1 focus:ring-green-500"
                    >
                </div>
            </div>
            
            <div class="h-[300px]">
                <canvas id="trenDonasiChart"></canvas>
            </div>

            <div class="flex justify-between mt-8 px-12">
                <div class="text-center">
                    <p class="text-gray-500 font-medium">Total Per Minggu</p>
                    <p id="totalPeriode" class="text-2xl font-bold">Rp 0</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-500 font-medium">Donasi Tertinggi</p>
                    <p id="donasiTertinggi" class="text-2xl font-bold">Rp 0</p>
                </div>
            </div>
        </section>

       <!-- TWO COLUMNS STATS -->
<div class="w-full grid grid-cols-2 gap-6">

    <!-- TOTAL DONASI -->
    <section class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">

        <h2 class="text-center text-[22px] font-bold mb-6">
            Total Donasi
        </h2>

        <div class="space-y-4">

            <!-- PANTI -->
            <div>

                <h3 class="text-base font-bold mb-3">
                    Panti
                </h3>

                <div class="space-y-4">

                    <div>
                        <p class="text-sm text-gray-500 mb-2">
                            Per Bulan
                        </p>

                        <div class="w-full bg-gray-100 rounded-full h-9 overflow-hidden">

                            <div id="pantiBulananBar" class="bg-rangkul-green h-full w-0 flex items-center px-4">

                                <span id="pantiBulanan" class="text-sm font-bold text-white whitespace-nowrap">
                                    Rp 0
                                </span>

                            </div>

                        </div>
                    </div>


                    <div>
                        <p class="text-sm text-gray-500 mb-2">
                            Per Tahun
                        </p>

                        <div class="w-full bg-gray-100 rounded-full h-9 overflow-hidden">

                            <div id="pantiTahunanBar" class="bg-rangkul-green h-full w-0 flex items-center px-4">

                                <span id="pantiTahunan" class="text-sm font-bold text-white whitespace-nowrap">
                                    Rp 0
                                </span>

                            </div>

                        </div>
                    </div>

                </div>

            </div>


            <!-- SEKOLAH -->
            <div>

                <h3 class="text-base font-bold mb-3">
                    Sekolah
                </h3>

                <div class="space-y-4">

                    <div>
                        <p class="text-sm text-gray-500 mb-2">
                            Per Bulan
                        </p>

                        <div class="w-full bg-gray-100 rounded-full h-9 overflow-hidden">

                            <div id="sekolahBulananBar" class="bg-rangkul-green h-full w-0 flex items-center px-4">

                                <span id="sekolahBulanan" class="text-sm font-bold text-white whitespace-nowrap">
                                    Rp 0
                                </span>

                            </div>

                        </div>
                    </div>


                    <div>
                        <p class="text-sm text-gray-500 mb-2">
                            Per Tahun
                        </p>

                        <div class="w-full bg-gray-100 rounded-full h-9 overflow-hidden">

                            <div id="sekolahTahunanBar" class="bg-rangkul-green h-full w-0 flex items-center px-4">

                                <span id="sekolahTahunan" class="text-sm font-bold text-white whitespace-nowrap">
                                    Rp 0
                                </span>

                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </div>

    </section>

            <!-- TOTAL TERSALURKAN (GAUGE) -->
            <section class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center">
                <h2 class="text-center text-[22px] font-bold mb-6">Total Tersalurkan</h2>
                <div class="relative w-full max-w-[300px]">
                    <canvas id="gaugeChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-end pb-8">
                        <span id="persentaseTersalurkan" class="text-4xl font-bold">0%</span>
                    </div>
                </div>
                <div class="flex justify-between w-full text-gray-400 font-semibold px-12 mt-2">
                    <span>0%</span>
                    <span>100%</span>
                </div>
            </section>
        </div>

    <!-- Tabel 1: Daftar Akun Staff & Manager -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200/80">
      <div class="bg-[#0d4a32] px-6 py-3.5 flex items-center justify-between text-white">
        <div class="flex items-center gap-2.5">
          <span class="text-xl">📁</span>
          <h2 class="font-bold text-base tracking-wide">Daftar Akun Staff & Manager</h2>
        </div>
        <button onclick="openModalTambah()" class="px-4 py-1.5 rounded-full bg-[#faecc8] text-[#0d4a32] hover:bg-[#f6e4b2] text-xs font-semibold transition-all shadow-sm">
          Tambahkan Staff/Manager
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b border-gray-200 text-xs font-semibold text-gray-800 bg-white">
              <th class="py-3 px-6 w-16">No.</th>
              <th class="py-3 px-6 w-48">Nama</th>
              <th class="py-3 px-6">Email</th>
              <th class="py-3 px-6 w-36">Role</th>
              <th class="py-3 px-6 w-36">Status</th>
              <th class="py-3 px-6 w-64 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody id="table-staff-body" class="divide-y divide-gray-200/80 text-sm">
            <!-- Data dirender oleh JavaScript -->
          </tbody>
        </table>
      </div>
    </div>

    <!-- Tabel 2: Daftar Nonaktif Akun -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200/80">
      <div class="bg-[#0d4a32] px-6 py-3.5 flex items-center gap-2.5 text-white">
        <span class="text-xl">📁</span>
        <h2 class="font-bold text-base tracking-wide">Daftar Nonaktif Akun</h2>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b border-gray-200 text-xs font-semibold text-gray-800 bg-white">
              <th class="py-3 px-6 w-16">No.</th>
              <th class="py-3 px-6 w-48">Nama</th>
              <th class="py-3 px-6">Email</th>
              <th class="py-3 px-6 w-36">Role</th>
              <th class="py-3 px-6 w-36">Status</th>
              <th class="py-3 px-6 w-64 text-center">Aksi</th>
            </tr>
          </thead>
          <tbody id="table-nonaktif-body" class="divide-y divide-gray-200/80 text-sm">
            <!-- Data dirender oleh JavaScript -->
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <!-- Modal Tambah Akun -->
  <div id="modal-tambah" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-[1px]">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
      <h3 class="text-lg font-bold text-gray-900 pb-3 border-b border-gray-100">Tambahkan Staff / Manager</h3>
      <form onsubmit="handleSimpanTambah(event)" class="mt-4 space-y-4 text-sm">
        <div>
          <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap</label>
          <input type="text" id="input-nama" required class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d4a32]" />
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
          <input type="email" id="input-email" required class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0d4a32]" />
        </div>
        <div>
          <label class="block text-xs font-semibold text-gray-700 mb-1">Role</label>
          <select id="input-role" class="w-full px-3.5 py-2 border border-gray-300 rounded-lg bg-white">
            <option value="Staff">Staff</option>
            <option value="Manager">Manager</option>
          </select>
        </div>
        <div class="pt-2 flex justify-end gap-2">
          <button type="button" onclick="closeModalTambah()" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
          <button type="submit" class="px-5 py-2 bg-[#0d4a32] text-white text-xs font-semibold rounded-lg hover:bg-[#093524]">Simpan Akun</button>
        </div>
      </form>
    </div>
  </div>

 <script>
    // =========================
    // DATA AKUN
    // =========================

    let dataAkun = [
        { id: 1, nama: 'Manager', email: 'manager@rangkul.com', role: 'Manager', status: 'Aktif' },
        { id: 2, nama: 'Staff 1', email: 'staff123@rangkul.com', role: 'Staff', status: 'Aktif' },
        { id: 3, nama: 'Staff 2', email: 'staff456@rangkul.com', role: 'Staff', status: 'Aktif' },
        { id: 4, nama: 'Staff 3', email: 'staff789@rangkul.com', role: 'Staff', status: 'Nonaktif' }
    ];


    // =========================
    // FORMAT RUPIAH
    // =========================

    const formatRupiah = (value) => new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
    }).format(value || 0);


    // =========================
    // RENDER TABEL AKUN
    // =========================

    function renderTabel() {

        const tbodyStaff = document.getElementById('table-staff-body');
        const tbodyNonaktif = document.getElementById('table-nonaktif-body');

        const totalStaff = dataAkun.filter(
            a => a.role === 'Staff' && a.status === 'Aktif'
        ).length;

        const totalManager = dataAkun.filter(
            a => a.role === 'Manager' && a.status === 'Aktif'
        ).length;

        document.getElementById('count-staff').textContent = totalStaff;
        document.getElementById('count-manager').textContent = totalManager;


        tbodyStaff.innerHTML = dataAkun.map((item, index) => {

            const isNonaktif = item.status === 'Nonaktif';

            return `
                <tr class="hover:bg-gray-50/70">

                    <td class="py-3 px-6 text-gray-800">
                        ${index + 1}.
                    </td>

                    <td class="py-3 px-6 font-medium text-gray-900">
                        ${item.nama}
                    </td>

                    <td class="py-3 px-6 text-gray-800">
                        ${item.email}
                    </td>

                    <td class="py-3 px-6 text-gray-800">
                        ${item.role}
                    </td>

                    <td class="py-3 px-6 text-gray-800">
                        ${item.status}
                    </td>

                    <td class="py-3 px-6 text-center">

                        <div class="flex items-center justify-center gap-2">

                            <button
                                ${isNonaktif
                                    ? 'disabled'
                                    : `onclick="aksiEdit(${item.id})"`
                                }
                                class="px-4 py-1 rounded-full text-xs font-medium
                                ${isNonaktif
                                    ? 'bg-gray-300 text-gray-600 cursor-not-allowed'
                                    : 'bg-[#fef3c7] text-[#92400e] hover:bg-[#fde68a]'
                                }">
                                Edit
                            </button>

                            <button
                                ${isNonaktif
                                    ? 'disabled'
                                    : `onclick="aksiReset('${item.email}')"`
                                }
                                class="px-4 py-1 rounded-full text-xs font-medium
                                ${isNonaktif
                                    ? 'bg-gray-300 text-gray-600 cursor-not-allowed'
                                    : 'bg-[#e0f2fe] text-[#0369a1] hover:bg-[#bae6fd]'
                                }">
                                Reset
                            </button>

                            <button
                                ${isNonaktif
                                    ? 'disabled'
                                    : `onclick="aksiNonaktifkan(${item.id})"`
                                }
                                class="px-3 py-1 rounded-full text-xs font-medium
                                ${isNonaktif
                                    ? 'bg-gray-300 text-gray-600 cursor-not-allowed'
                                    : 'bg-[#fee2e2] text-[#b91c1c] hover:bg-[#fecaca]'
                                }">
                                Nonaktifkan
                            </button>

                        </div>

                    </td>

                </tr>
            `;

        }).join('');


        const nonaktifList = dataAkun.filter(
            a => a.status === 'Nonaktif'
        );

        if (nonaktifList.length === 0) {

            tbodyNonaktif.innerHTML = `
                <tr>
                    <td colspan="6"
                        class="py-6 text-center text-gray-500">
                        Tidak ada akun nonaktif.
                    </td>
                </tr>
            `;

        } else {

            tbodyNonaktif.innerHTML = nonaktifList.map((item, index) => `

                <tr class="hover:bg-gray-50/70">

                    <td class="py-3 px-6 text-gray-800">
                        ${index + 1}.
                    </td>

                    <td class="py-3 px-6 font-medium text-gray-900">
                        ${item.nama}
                    </td>

                    <td class="py-3 px-6 text-gray-800">
                        ${item.email}
                    </td>

                    <td class="py-3 px-6 text-gray-800">
                        ${item.role}
                    </td>

                    <td class="py-3 px-6 text-gray-800">
                        ${item.status}
                    </td>

                    <td class="py-3 px-6 text-center">

                        <div class="flex items-center justify-center gap-2">

                            <button
                                onclick="aksiAktifkan(${item.id})"
                                class="px-4 py-1 rounded-full bg-[#e0f2fe]
                                text-[#0284c7] hover:bg-[#bae6fd]
                                text-xs font-medium">
                                Aktifkan
                            </button>

                            <button
                                onclick="aksiHapus(${item.id})"
                                class="px-3.5 py-1 rounded-full bg-[#fee2e2]
                                text-[#b91c1c] hover:bg-[#fecaca]
                                text-xs font-medium">
                                Hapus Permanen
                            </button>

                        </div>

                    </td>

                </tr>

            `).join('');
        }
    }


    // =========================
    // MODAL TAMBAH AKUN
    // =========================

    function openModalTambah() {
        document.getElementById('modal-tambah').classList.remove('hidden');
        document.getElementById('modal-tambah').classList.add('flex');
    }


    function closeModalTambah() {
        document.getElementById('modal-tambah').classList.add('hidden');
        document.getElementById('modal-tambah').classList.remove('flex');
    }


    function handleSimpanTambah(e) {

        e.preventDefault();

        const nama = document.getElementById('input-nama').value;
        const email = document.getElementById('input-email').value;
        const role = document.getElementById('input-role').value;

        dataAkun.push({
            id: Date.now(),
            nama,
            email,
            role,
            status: 'Aktif'
        });

        closeModalTambah();
        renderTabel();

        alert('Akun berhasil ditambahkan!');
    }


    // =========================
    // AKSI AKUN
    // =========================

    function aksiNonaktifkan(id) {

        if (confirm('Yakin ingin menonaktifkan akun ini?')) {

            const item = dataAkun.find(a => a.id === id);

            if (item) {
                item.status = 'Nonaktif';
            }

            renderTabel();
        }
    }


    function aksiAktifkan(id) {

        const item = dataAkun.find(a => a.id === id);

        if (item) {
            item.status = 'Aktif';
        }

        renderTabel();
    }


    function aksiHapus(id) {

        if (confirm('Tindakan ini tidak bisa dibatalkan. Hapus permanen?')) {

            dataAkun = dataAkun.filter(a => a.id !== id);

            renderTabel();
        }
    }


    function aksiReset(email) {
        alert('Instruksi reset password telah dikirim ke ' + email);
    }


    function aksiEdit(id) {

        const item = dataAkun.find(a => a.id === id);

        if (!item) return;

        const namaBaru = prompt('Ubah Nama:', item.nama);

        if (namaBaru) {
            item.nama = namaBaru;
            renderTabel();
        }
    }


    // =====================================================
    // DASHBOARD
    // =====================================================

    let trendChart = null;
    let gaugeChart = null;


    // DATA SEMENTARA
    // Nanti bagian ini akan otomatis diganti data API
    const dashboardDummy = {

        period: {
            tanggal_mulai: '2026-01-01',
            tanggal_selesai: '2026-01-31'
        },

        summary: {
            total_donasi: 9000000,
            donasi_tertinggi: 3200000,
            persentase_tersalurkan: 75
        },

        organization_totals: {
            panti: {
                monthly: 1500000,
                yearly: 5000000
            },

            sekolah: {
                monthly: 2500000,
                yearly: 9000000
            }
        },

        trend: [
            {
                label: 'Minggu 1',
                total: 1500000
            },
            {
                label: 'Minggu 2',
                total: 2500000
            },
            {
                label: 'Minggu 3',
                total: 1800000
            },
            {
                label: 'Minggu 4',
                total: 3200000
            }
        ]
    };


    // =========================
    // RENDER DASHBOARD
    // =========================

    function renderDashboard(payload) {

        const data = payload;
        const summary = data.summary;
        const organizationTotals = data.organization_totals;


        // =========================
        // TANGGAL
        // =========================

        document.getElementById('tanggalMulai').value =
            data.period.tanggal_mulai;

        document.getElementById('tanggalSelesai').value =
            data.period.tanggal_selesai;


        // =========================
        // TOTAL PERIODE
        // =========================

        document.getElementById('totalPeriode').textContent =
            formatRupiah(summary.total_donasi);

        document.getElementById('donasiTertinggi').textContent =
            formatRupiah(summary.donasi_tertinggi);


        // =========================
        // TOTAL TERSALURKAN
        // =========================

        const persentase =
            Number(summary.persentase_tersalurkan || 0);

        document.getElementById('persentaseTersalurkan').textContent =
            `${persentase.toLocaleString('id-ID', {
                maximumFractionDigits: 2
            })}%`;


        // =========================
        // TOTAL DONASI
        // =========================

        const values = [
            organizationTotals.panti.monthly,
            organizationTotals.panti.yearly,
            organizationTotals.sekolah.monthly,
            organizationTotals.sekolah.yearly
        ];

        const maximum = Math.max(...values, 1);


        [
            [
                'pantiBulanan',
                'pantiBulananBar',
                organizationTotals.panti.monthly
            ],
            [
                'pantiTahunan',
                'pantiTahunanBar',
                organizationTotals.panti.yearly
            ],
            [
                'sekolahBulanan',
                'sekolahBulananBar',
                organizationTotals.sekolah.monthly
            ],
            [
                'sekolahTahunan',
                'sekolahTahunanBar',
                organizationTotals.sekolah.yearly
            ]

        ].forEach(([valueId, barId, value]) => {

            document.getElementById(valueId).textContent =
                formatRupiah(value);

            document.getElementById(barId).style.width =
                `${Math.round((value / maximum) * 100)}%`;
        });


        // =========================
        // TREN DONASI
        // =========================

        if (trendChart) {
            trendChart.destroy();
        }

        trendChart = new Chart(
            document.getElementById('trenDonasiChart').getContext('2d'),
            {
                type: 'line',

                data: {
                    labels: data.trend.map(item => item.label),

                    datasets: [{
                        label: 'Tren Donasi',

                        data: data.trend.map(item => item.total),

                        borderColor: '#4ade80',
                        backgroundColor: 'transparent',

                        borderWidth: 3,

                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#4ade80',
                        pointBorderWidth: 2,

                        pointRadius: 6,
                        pointHoverRadius: 8,

                        tension: 0
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: false
                        },

                        tooltip: {
                            callbacks: {
                                label: (context) =>
                                    `${context.dataset.label}: ${formatRupiah(context.raw)}`
                            }
                        }
                    },

                    scales: {
                        y: {
                            beginAtZero: true
                        },

                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            }
        );


        // =========================
        // GAUGE
        // =========================

        if (gaugeChart) {
            gaugeChart.destroy();
        }

        gaugeChart = new Chart(
            document.getElementById('gaugeChart').getContext('2d'),
            {
                type: 'doughnut',

                data: {
                    datasets: [{
                        data: [
                            persentase,
                            100 - persentase
                        ],

                        backgroundColor: [
                            '#4ade80',
                            '#f3f4f6'
                        ],

                        borderWidth: 0,

                        circumference: 180,
                        rotation: 270,

                        cutout: '85%',

                        borderRadius: 10
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        tooltip: {
                            enabled: false
                        },

                        legend: {
                            display: false
                        }
                    }
                }
            }
        );
    }


    // =========================
    // LOAD DASHBOARD
    // =========================

    async function loadDashboard() {

        const authToken =
            localStorage.getItem('rangkul_access_token') ||
            localStorage.getItem('auth_token');


        // ---------------------------------
        // KALAU TOKEN BELUM ADA
        // PAKAI DATA SEMENTARA
        // ---------------------------------

        if (!authToken) {

            renderDashboard(dashboardDummy);

            return;
        }


        try {

            const params = new URLSearchParams({

                tanggal_mulai:
                    document.getElementById('tanggalMulai').value,

                tanggal_selesai:
                    document.getElementById('tanggalSelesai').value

            });


            const response = await fetch(
                `/api/admin/dashboard?${params}`,
                {
                    headers: {
                        Authorization: `Bearer ${authToken}`,
                        Accept: 'application/json'
                    }
                }
            );


            if (response.status === 401) {

                localStorage.removeItem('rangkul_access_token');
                localStorage.removeItem('rangkul_user');
                localStorage.removeItem('auth_token');
                localStorage.removeItem('auth_user');

                renderDashboard(dashboardDummy);

                return;
            }


            const result = await response.json();


            if (!response.ok) {
                throw new Error(
                    result.message ||
                    'Data dashboard gagal dimuat.'
                );
            }


            // API kamu bentuknya { data: {...} }
            renderDashboard(result.data);

        } catch (error) {

            console.error(error);

            // Supaya dashboard tetap tampil
            // meskipun API sedang error
            renderDashboard(dashboardDummy);

            showDashboardError(
                'Data dashboard dari server belum dapat dimuat. Menampilkan data sementara.'
            );
        }
    }


    // =========================
    // ERROR
    // =========================

    function showDashboardError(message) {

        const error =
            document.getElementById('dashboard-error');

        if (!error) return;

        error.textContent = message;
        error.classList.remove('hidden');
    }


    // =========================
    // FILTER TANGGAL
    // =========================

    document
        .getElementById('tanggalMulai')
        .addEventListener('change', loadDashboard);

    document
        .getElementById('tanggalSelesai')
        .addEventListener('change', loadDashboard);


    // =========================
    // INITIAL
    // =========================

    renderTabel();

    loadDashboard();

</script>
</body>
</html>