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
                    <p class="text-gray-500 font-medium">
                        Total Per Minggu
                    </p>

                    <p class="text-2xl font-bold">
                        Rp 2.130.000
                    </p>
                </div>

                <div class="text-center">
                    <p class="text-gray-500 font-medium">
                        Donasi Tertinggi
                    </p>

                    <p class="text-2xl font-bold">
                        Rp 600.000
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

                                    <div class="bg-rangkul-green h-full w-[25%] flex items-center px-4">

                                        <span class="text-sm font-bold text-white whitespace-nowrap">
                                            Rp 1.000.000
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <div>

                                <p class="text-sm text-gray-500 mb-2">
                                    Per Tahun
                                </p>

                                <div class="w-full bg-gray-100 rounded-full h-9 overflow-hidden">

                                    <div class="bg-rangkul-green h-full w-[35%] flex items-center px-4">

                                        <span class="text-sm font-bold text-white whitespace-nowrap">
                                            Rp 2.350.000
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

                                    <div class="bg-rangkul-green h-full w-[65%] flex items-center px-4">

                                        <span class="text-sm font-bold text-white whitespace-nowrap">
                                            Rp 50.000.000
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <div>

                                <p class="text-sm text-gray-500 mb-2">
                                    Per Tahun
                                </p>

                                <div class="w-full bg-gray-100 rounded-full h-9 overflow-hidden">

                                    <div class="bg-rangkul-green h-full w-[70%] flex items-center px-4">

                                        <span class="text-sm font-bold text-white whitespace-nowrap">
                                            Rp 65.000.000
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
                        <span class="text-4xl font-bold">
                            75%
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

                        <tr class="text-gray-800 text-sm border-b text-center">

                            <th class="px-6 py-4 font-semibold">
                                No.
                            </th>

                            <th class="px-6 py-4 font-semibold">
                                Nama Organisasi
                            </th>

                            <th class="px-6 py-4 font-semibold">
                                Nominal Dana
                            </th>

                            <th class="px-6 py-4 font-semibold">
                                Verifikator
                            </th>

                            <th class="px-6 py-4 font-semibold">
                                Tanggal Pengajuan
                            </th>

                            <th class="px-6 py-4 font-semibold">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="text-sm">

                        <tr class="border-b hover:bg-gray-50 transition">

                            <td class="px-6 py-4 text-center">
                                1.
                            </td>

                            <td class="px-6 py-4">
                                Panti Asuhan Kasih Bunda
                            </td>

                            <td class="px-6 py-4">
                                Rp 500.000
                            </td>

                            <td class="px-6 py-4 text-center">
                                Staff 2
                            </td>

                            <td class="px-6 py-4 text-center">
                                18/02/2025
                            </td>

                            <td class="px-6 py-4 text-center">

                                <a
                                    href="/manager/detail"
                                    class="inline-block bg-rangkul-green text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-opacity-90"
                                >
                                    Detail
                                </a>

                            </td>

                        </tr>


                        <tr class="border-b hover:bg-gray-50">

                            <td class="px-6 py-4 text-center">
                                2.
                            </td>

                            <td class="px-6 py-4">
                                SD Harapan Bangsa
                            </td>

                            <td class="px-6 py-4">
                                Rp 10.000.000
                            </td>

                            <td class="px-6 py-4 text-center">
                                Staff 2
                            </td>

                            <td class="px-6 py-4 text-center">
                                07/05/2025
                            </td>

                            <td class="px-6 py-4 text-center">

                                <a
                                    href="/manager/detail"
                                    class="inline-block bg-rangkul-green text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-opacity-90"
                                >
                                    Detail
                                </a>

                            </td>

                        </tr>


                        <tr class="border-b hover:bg-gray-50">

                            <td class="px-6 py-4 text-center">
                                3.
                            </td>

                            <td class="px-6 py-4">
                                Panti Asuhan Cahaya Bangsa
                            </td>

                            <td class="px-6 py-4">
                                Rp 850.000
                            </td>

                            <td class="px-6 py-4 text-center">
                                Staff 1
                            </td>

                            <td class="px-6 py-4 text-center">
                                29/08/2025
                            </td>

                            <td class="px-6 py-4 text-center">

                                <a
                                    href="/manager/detail"
                                    class="inline-block bg-rangkul-green text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-opacity-90"
                                >
                                    Detail
                                </a>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </section>

    </main>


    <script>

        // PROFILE DROPDOWN
        const profileButton = document.getElementById('profileButton');
        const profileDropdown = document.getElementById('profileDropdown');

        profileButton.addEventListener('click', function () {
            profileDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', function (event) {

            if (
                !profileButton.contains(event.target) &&
                !profileDropdown.contains(event.target)
            ) {
                profileDropdown.classList.add('hidden');
            }

        });


        // TREN DONASI LINE CHART
        const trenCtx = document.getElementById('trenDonasiChart').getContext('2d');

        new Chart(trenCtx, {

            type: 'line',

            data: {

                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],

                datasets: [{

                    label: 'Tren Donasi',

                    data: [
                        190000,
                        350000,
                        140000,
                        240000,
                        600000,
                        440000,
                        380000
                    ],

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
                    }
                },

                scales: {

                    y: {
                        beginAtZero: true,
                        max: 600000,

                        ticks: {
                            stepSize: 200000
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


        // GAUGE CHART
        const gaugeCtx = document.getElementById('gaugeChart').getContext('2d');

        new Chart(gaugeCtx, {

            type: 'doughnut',

            data: {

                datasets: [{

                    data: [72, 28],

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

    </script>

</body>
</html>