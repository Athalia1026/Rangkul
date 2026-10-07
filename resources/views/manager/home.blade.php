<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-auth-session-guard />
    <title>Beranda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus Jakarta Sans:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F5F7F4;
        }

        .text-rangkul-green {
            color: #086538;
        }

        .bg-rangkul-green {
            background-color: #086538;
        }

        .bg-light-green {
            background-color: #F5F7F4;
        }
    </style>
</head>

<body class="text-black-800">
    
    @include('manager.layouts.navbar')
    
    <!-- MAIN CONTENT -->
    <main class="w-full max-w-[1200px] mx-auto px-8 py-5 space-y-6">

        <div id="dashboard-error" class="hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-center text-sm text-red-700"></div>

        <!-- TREN DONASI SECTION -->
        <section class="h-[520px] bg-white p-6 rounded-xl shadow-sm border border-gray-100">

            <div class="flex justify-between items-start mb-10">

                <h2 class="text-[25px] font-bold">
                    Tren Donasi
                </h2>

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
                    <p id="labelTotalPeriode" class="text-gray-500 font-medium">
                        Total Per Minggu
                    </p>

                    <p id="totalPeriode" class="text-2xl font-bold">
                        Rp 0
                    </p>
                </div>

                <div class="text-center">
                    <p class="text-gray-500 font-medium">
                        Donasi Tertinggi
                    </p>

                    <p id="donasiTertinggi" class="text-2xl font-bold">
                        Rp 0
                    </p>
                </div>

            </div>

        </section>

        <!-- TWO COLUMNS STATS -->
        <div class="grid grid-cols-2 gap-6">

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

                                    <div id="pantiBulananBar" class="bg-rangkul-green h-full w-0 min-w-max flex items-center px-4">

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

                                    <div id="pantiTahunanBar" class="bg-rangkul-green h-full w-0 min-w-max flex items-center px-4">

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

                                    <div id="sekolahBulananBar" class="bg-rangkul-green h-full w-0 min-w-max flex items-center px-4">

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

                                    <div id="sekolahTahunanBar" class="bg-rangkul-green h-full w-0 min-w-max flex items-center px-4">

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

            <!-- TOTAL TERSALURKAN -->
            <section class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center">

                <h2 class="text-center text-[22px] font-bold mb-6">
                    Total Tersalurkan
                </h2>

                <div class="relative w-full max-w-[300px]">

                    <canvas id="gaugeChart"></canvas>

                    <div class="absolute inset-0 flex flex-col items-center justify-end pb-8">
                        <span id="persentaseTersalurkan" class="text-4xl font-bold">
                            0%
                        </span>
                    </div>

                </div>

                <div class="flex justify-between w-full text-gray-400 font-semibold px-12 mt-2">
                    <span>0%</span>
                    <span>100%</span>
                </div>

            </section>

        </div>

        <!-- DAFTAR PENCAIRAN DANA TABLE -->
        <section class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="bg-rangkul-green text-white px-6 py-4 flex items-center gap-2">
                <span>📒</span>
                <h2 class="font-bold">
                    Daftar Pencairan Dana
                </h2>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-left border-collapse">

                    <thead>

                        <tr class="text-black text-sm border-b text-center">

                            <th class="px-6 py-4 font-bold">
                                No.
                            </th>

                            <th class="px-2 py-4 font-bold">
                                Nama Organisasi
                            </th>

                            <th class="px-2 py-4 font-bold">
                                Nominal Dana
                            </th>

                            <th class="px-2 py-4 font-bold">
                                Verifikator
                            </th>

                            <th class="px-2 py-4 font-bold">
                                Tanggal Pengajuan
                            </th>

                            <th class="px-2 py-4 font-bold">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody id="disbursementRows" class="text-sm">

                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Memuat data...
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </section>

    </main>

    <script>

        const { request, formatRupiah, escapeHtml } = window.RangkulAdmin;
        const tanggalMulai = document.getElementById('tanggalMulai');
        const tanggalSelesai = document.getElementById('tanggalSelesai');

        let trenChart;
        let gaugeChart;

        // TREN DONASI LINE CHART
        function renderTrenChart(trend) {

            if (trenChart) {
                trenChart.destroy();
            }

            trenChart = new Chart(document.getElementById('trenDonasiChart').getContext('2d'), {

                type: 'line',

                data: {

                    labels: trend.map((item) => item.label),

                    datasets: [{

                        label: 'Tren Donasi',

                        data: trend.map((item) => item.total),

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
                                label: (context) => formatRupiah(context.raw)
                            }
                        }
                    },

                    scales: {

                        y: {
                            beginAtZero: true,

                            ticks: {
                                maxTicksLimit: 4,
                                callback: (value) => Number(value).toLocaleString('id-ID')
                            }
                        },

                        x: {
                            grid: {
                                display: false
                            }
                        }

                    }

                }

            });

        }

        // GAUGE CHART
        function renderGaugeChart(percentage) {

            if (gaugeChart) {
                gaugeChart.destroy();
            }

            gaugeChart = new Chart(document.getElementById('gaugeChart').getContext('2d'), {

                type: 'doughnut',

                data: {

                    datasets: [{

                        data: [percentage, 100 - percentage],

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
                        }
                    }

                }

            });

        }

        // TOTAL DONASI PER JENIS ORGANISASI
        function renderOrganizationTotals(totals) {

            const bars = [
                ['pantiBulanan', totals.panti.monthly],
                ['pantiTahunan', totals.panti.yearly],
                ['sekolahBulanan', totals.sekolah.monthly],
                ['sekolahTahunan', totals.sekolah.yearly]
            ];
            const maximum = Math.max(...bars.map(([, value]) => value), 1);

            bars.forEach(([id, value]) => {
                document.getElementById(id).textContent = formatRupiah(value);
                document.getElementById(id + 'Bar').style.width = Math.round((value / maximum) * 100) + '%';
            });

        }

        // DAFTAR PENCAIRAN DANA
        function renderDisbursements(items) {

            const rows = document.getElementById('disbursementRows');

            if (!items.length) {
                rows.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Belum ada data pencairan dana.
                        </td>
                    </tr>
                `;
                return;
            }

            rows.innerHTML = items.map((item, index) => `
                <tr class="border-b hover:bg-gray-50 transition">

                    <td class="px-6 py-4 text-center">
                        ${index + 1}.
                    </td>

                    <td class="px-6 py-4">
                        ${escapeHtml(item.organization_name)}
                    </td>

                    <td class="px-6 py-4">
                        ${formatRupiah(item.amount)}
                    </td>

                    <td class="px-6 py-4 text-center">
                        ${escapeHtml(item.verifier_name)}
                    </td>

                    <td class="px-6 py-4 text-center">
                        ${escapeHtml(item.submitted_at)}
                    </td>

                    <td class="px-6 py-4 text-center">

                        <a
                            href="/manager/detailpengajuan/${encodeURIComponent(item.id)}"
                            class="inline-block bg-rangkul-green text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-opacity-90"
                        >
                            Detail
                        </a>

                    </td>

                </tr>
            `).join('');

        }

        function showDashboardError(message) {
            const error = document.getElementById('dashboard-error');
            error.textContent = message;
            error.classList.toggle('hidden', !message);
        }

        async function loadDashboard() {

            const params = new URLSearchParams();

            // Tanpa rentang tanggal, server memakai minggu berjalan.
            if (tanggalMulai.value && tanggalSelesai.value) {
                params.set('tanggal_mulai', tanggalMulai.value);
                params.set('tanggal_selesai', tanggalSelesai.value);
            }

            try {
                const { data } = await request('/api/admin/dashboard?' + params);
                const summary = data.summary;
                const percentage = Number(summary.persentase_tersalurkan) || 0;
                const periodDays = Math.round(
                    (new Date(data.period.tanggal_selesai) - new Date(data.period.tanggal_mulai)) / 86400000
                ) + 1;

                showDashboardError('');

                tanggalMulai.value = data.period.tanggal_mulai;
                tanggalSelesai.value = data.period.tanggal_selesai;

                document.getElementById('labelTotalPeriode').textContent = periodDays === 7 ? 'Total Per Minggu' : 'Total Periode';
                document.getElementById('totalPeriode').textContent = formatRupiah(summary.total_donasi);
                document.getElementById('donasiTertinggi').textContent = formatRupiah(summary.donasi_tertinggi);
                document.getElementById('persentaseTersalurkan').textContent =
                    percentage.toLocaleString('id-ID', { maximumFractionDigits: 2 }) + '%';

                renderTrenChart(data.trend);
                renderGaugeChart(percentage);
                renderOrganizationTotals(data.organization_totals);
                renderDisbursements(data.disbursements);
            } catch (error) {
                showDashboardError(error.message);
            }

        }

        tanggalMulai.addEventListener('change', loadDashboard);
        tanggalSelesai.addEventListener('change', loadDashboard);

        loadDashboard();

    </script>

</body>
</html>