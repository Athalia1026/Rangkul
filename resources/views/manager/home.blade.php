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
    <main class="w-full max-w-[1200px] mx-auto px-8 py-5 space-y-6">

        <div id="dashboard-error" class="hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-center text-sm text-red-700"></div>

        <!-- TREN DONASI SECTION -->
        <section class="w-[1200px] h-[520px] mx-auto bg-white p-6 rounded-xl shadow-sm border border-gray-100">
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
<div class="w-[1200px] mx-auto grid grid-cols-2 gap-6">

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

        <!-- DAFTAR PENCAIRAN DANA TABLE -->
        <section class="w-[1200px] mx-auto bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-rangkul-green text-white px-6 py-4 flex items-center gap-2">
                 <span>📒</span>
                <h2 class="font-bold">Daftar Pencairan Dana</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse">
                    <thead>
                        <tr class="text-gray-500 text-sm border-b text-center">
                            <th class="px-6 py-4 font-semibold">No.</th>
                            <th class="px-6 py-4 font-semibold">Nama Organisasi</th>
                            <th class="px-6 py-4 font-semibold">Nominal Dana</th>
                            <th class="px-6 py-4 font-semibold">Verifikator</th>
                            <th class="px-6 py-4 font-semibold">Tanggal Pengajuan</th>
                            <th class="px-6 py-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="disbursementRows" class="text-sm"></tbody>
                </table>
            </div>
        </section>

    </main>

    <script>
        const authToken = localStorage.getItem('rangkul_access_token') || localStorage.getItem('auth_token');
        const formatRupiah = (value) => new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(value || 0);

        let trendChart;
        let gaugeChart;

        function escapeHtml(value) {
            return String(value ?? '-').replace(/[&<>'"]/g, (character) => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;'
            }[character]));
        }

        function renderDisbursements(items) {
            const rows = document.getElementById('disbursementRows');

            if (!items.length) {
                rows.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada data pencairan dana.</td></tr>';
                return;
            }

            rows.innerHTML = items.map((item, index) => `
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-center">${index + 1}.</td>
                    <td class="px-6 py-4">${escapeHtml(item.organization_name)}</td>
                    <td class="px-6 py-4">${formatRupiah(item.amount)}</td>
                    <td class="px-6 py-4 text-center">${escapeHtml(item.verifier_name)}</td>
                    <td class="px-6 py-4 text-center">${escapeHtml(item.submitted_at)}</td>
                    <td class="px-6 py-4"><button class="bg-rangkul-green text-white px-4 py-1.5 rounded-lg text-xs font-semibold">Detail</button></td>
                </tr>
            `).join('');
        }

        function renderDashboard(payload) {
            const data = payload.data;
            const summary = data.summary;
            const organizationTotals = data.organization_totals;
            const values = [
                organizationTotals.panti.monthly,
                organizationTotals.panti.yearly,
                organizationTotals.sekolah.monthly,
                organizationTotals.sekolah.yearly
            ];
            const maximum = Math.max(...values, 1);

            document.getElementById('tanggalMulai').value = data.period.tanggal_mulai;
            document.getElementById('tanggalSelesai').value = data.period.tanggal_selesai;
            document.getElementById('totalPeriode').textContent = formatRupiah(summary.total_donasi);
            document.getElementById('donasiTertinggi').textContent = formatRupiah(summary.donasi_tertinggi);
            document.getElementById('persentaseTersalurkan').textContent = `${Number(summary.persentase_tersalurkan).toLocaleString('id-ID', { maximumFractionDigits: 2 })}%`;

            [
                ['pantiBulanan', 'pantiBulananBar', organizationTotals.panti.monthly],
                ['pantiTahunan', 'pantiTahunanBar', organizationTotals.panti.yearly],
                ['sekolahBulanan', 'sekolahBulananBar', organizationTotals.sekolah.monthly],
                ['sekolahTahunan', 'sekolahTahunanBar', organizationTotals.sekolah.yearly]
            ].forEach(([valueId, barId, value]) => {
                document.getElementById(valueId).textContent = formatRupiah(value);
                document.getElementById(barId).style.width = `${Math.round((value / maximum) * 100)}%`;
            });

            if (trendChart) trendChart.destroy();
            trendChart = new Chart(document.getElementById('trenDonasiChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: data.trend.map((item) => item.label),
                    datasets: [{
                        label: 'Tren Donasi',
                        data: data.trend.map((item) => item.total),
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
                    plugins: { legend: { display: false }, tooltip: { callbacks: {
                        label: (context) => `${context.dataset.label}: ${formatRupiah(context.raw)}`
                    }}},
                    scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
                }
            });

            if (gaugeChart) gaugeChart.destroy();
            gaugeChart = new Chart(document.getElementById('gaugeChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [summary.persentase_tersalurkan, 100 - summary.persentase_tersalurkan],
                        backgroundColor: ['#4ade80', '#f3f4f6'],
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
                    plugins: { tooltip: { enabled: false } }
                }
            });

            renderDisbursements(data.disbursements);
        }

        async function loadDashboard() {
            if (!authToken) {
                showDashboardError('Sesi login tidak ditemukan. Silakan login kembali.');
                return;
            }

            const params = new URLSearchParams({
                tanggal_mulai: document.getElementById('tanggalMulai').value,
                tanggal_selesai: document.getElementById('tanggalSelesai').value
            });
            const response = await fetch(`/api/admin/dashboard?${params}`, {
                headers: { Authorization: `Bearer ${authToken}`, Accept: 'application/json' }
            });

            if (response.status === 401) {
                localStorage.removeItem('rangkul_access_token');
                localStorage.removeItem('rangkul_user');
                localStorage.removeItem('auth_token');
                localStorage.removeItem('auth_user');
            }

            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Data dashboard gagal dimuat.');
            renderDashboard(result);
        }

        function showDashboardError(message) {
            const error = document.getElementById('dashboard-error');
            error.textContent = message;
            error.classList.remove('hidden');
        }

        document.getElementById('tanggalMulai').addEventListener('change', loadDashboard);
        document.getElementById('tanggalSelesai').addEventListener('change', loadDashboard);
        loadDashboard().catch((error) => {
            console.error(error);
            showDashboardError(error.message);
        });
    </script>
</body>
</html>