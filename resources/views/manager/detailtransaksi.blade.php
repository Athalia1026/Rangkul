<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'rangkul-green': '#086538',
                    }
                }
            }
        }
    </script>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    >

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F5F7F4;
        }
    </style>
</head>

<body>

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
            class="hover:text-green-700"
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
            class="text-rangkul-green border-b-2 border-rangkul-green pb-1"
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

    <!-- TOP SECTION -->
    <div class="flex justify-between items-start mb-10">

        <!-- SEARCH & FILTER -->
        <div class="flex gap-4">

            <!-- SEARCH -->
            <div class="relative">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </span>

                <input
                    type="text"
                    placeholder="Search"
                    class="pl-10 pr-4 py-2 w-64 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-rangkul-green"
                >
            </div>


            <!-- FILTER -->
            <select
                class="border border-gray-300 rounded-md px-4 py-2 w-56 text-gray-400 bg-white focus:outline-none focus:ring-1 focus:ring-rangkul-green appearance-none cursor-pointer"
                style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%23ccc%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 10px center; background-size: 18px;"
            >
                <option value="disetujui">Disetujui</option>
                <option value="menunggu">Menunggu</option>
            </select>

        </div>


        <!-- STATS CARD -->
        <div class="bg-rangkul-green rounded-xl p-4 text-white shadow-lg">

            <div class="text-center">

                <p class="text-xs mb-2 font-medium">
                    Total Transaksi Selesai
                </p>

                <div class="bg-white text-black text-3xl font-bold rounded-lg px-10 py-2">
                    0
                </div>

            </div>

        </div>

    </div>


    <!-- LAPORAN TRANSAKSI -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden min-h-[400px]">

        <!-- HEADER -->
        <div class="bg-rangkul-green text-white px-6 py-3 flex items-center gap-2">

            <span>📒</span>

            <span class="font-bold">
                Laporan Transaksi
            </span>

        </div>


        <!-- TABLE -->
        <table class="w-full border">

        <colgroup>
            <col class="w-[60px]">
            <col class="w-[200px]">
            <col class="w-[350px]">
            <col class="w-[350px]">
            <col class="w-[200px]">
            <col class="w-[120px]">
            <col class="w-[120px]">
            <col class="w-[120px]">
        </colgroup>

            <thead class="border-b border-gray-200">

                <tr class="text-sm font-bold text-gray-800 text-center">
                    <th class="px-4 py-4 font-semibold">No.</th>
                    <th class="px-4 py-4 font-semibold">Tanggal Transaksi</th>
                    <th class="px-4 py-4 font-semibold">Nama Donatur</th>
                    <th class="px-4 py-4 font-semibold">Organisasi</th>
                    <th class="px-4 py-4 font-semibold">Nominal</th>
                    <th class="px-4 py-4 font-semibold">Status</th>
                    <th class="px-4 py-4 font-semibold">Metode</th>
                    <th class="px-4 py-4 font-semibold">Aksi</th>

                </tr>

            </thead>

            <tbody class="text-sm">
                    <tr  class="py-32 text-gray-800 text-sm px-20">
                            <td class="px-4 py-4 text-center">1.</td>
                            <td class="px-4 py-2 text-center">19/02/2025</td>
                            <td class="px-4 py-4">Komunitas Anak Surabaya</td>
                            <td class="px-4 py-4">SD Harapan Bangsa</td>
                            <td class="px-4 py-2">Rp 500.000</td>
                            <td class="px-4 py-2 text-center">Qris</td>
                            <td class="px-4 py-2 text-center">Disetujui</td>
                            <td class="px-4 py-4 text-center">
                                <button class="bg-rangkul-green text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-opacity-90">Detail</button>
                            </td>
                        </tr>
                        
                        <tr class="py-32 text-gray-800 text-sm px-20">
                            <td class="px-4 py-4 text-center">2.</td>
                            <td class="px-4 py-2 text-center">08/11/2025</td>
                            <td class="px-4 py-4">Himpunan Mahasiswa Sistem Informasi</td>
                            <td class="px-4 py-4">Asrama Pemberdayaan Yatim dan Dhuafa</td>
                            <td class="px-4 py-2">Rp 100.000.000</td>
                            <td class="px-4 py-2 text-center">Transfer</td>
                            <td class="px-4 py-2 text-center">Menunggu</td>
                            <td class="px-4 py-4 text-center">
                                <button class="bg-rangkul-green text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-opacity-90">Detail</button>
                            </td>
                </tr>

            </tbody>

        </table>

    </div>

</main>

</body>
</html>