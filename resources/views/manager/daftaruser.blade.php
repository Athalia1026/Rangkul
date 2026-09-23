<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar User</title>

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

<nav class="bg-white border-b border-gray-200 px-8 py-3 flex items-center justify-between">

    <div class="flex items-center w-1/3">
        <img
            src="/images/logo.png"
            alt="Rangkul"
            class="h-10 w-auto"
        >
    </div>

    <div class="flex gap-12 font-semibold text-sm">

        <a
            href="/manager/home"
            class="hover:text-green-700"
        >
            Beranda
        </a>

        <a
            href="/manager/daftaruser"
            class="text-rangkul-green border-b-2 border-rangkul-gray-200 pb-1"
        >
            Daftar Pengguna
        </a>

        <a
            href="/manager/laporantransaksi"
            class="hover:text-green-700"
        >
            Laporan Transaksi
        </a>

    </div>

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
                <option value="sekolah">Sekolah</option>
                <option value="panti">Panti</option>
            </select>

        </div>


        <!-- STATS CARD -->
        <div class="bg-rangkul-green rounded-xl p-4 flex gap-6 text-white shadow-lg">

            <div class="text-center">
                <p class="text-xs mb-2 font-medium">
                    Total Organisasi
                </p>

                <div class="bg-white text-black text-3xl font-bold rounded-lg px-8 py-2">
                    2
                </div>
            </div>

            <div class="text-center">
                <p class="text-xs mb-2 font-medium">
                    Total Donatur
                </p>

                <div class="bg-white text-black text-3xl font-bold rounded-lg px-8 py-2">
                    2
                </div>
            </div>

        </div>

    </div>


    <!-- DATA ORGANISASI -->
    <div class="mb-10 bg-white rounded-xl shadow-sm overflow-hidden min-h-[400px]">

        <div class="bg-rangkul-green text-white px-6 py-3 flex items-center gap-2">
            <span>📒</span>

            <span class="font-bold">
                Data Organisasi
            </span>
        </div>

        <table class="w-full text-left">

            <thead class="border-b border-gray-200">

                <tr class="text-sm font-bold text-gray-800">

                    <th class="px-6 py-4">
                        No.
                    </th>

                    <th class="px-6 py-4 text-center">
                        Tanggal Pendaftaran
                    </th>

                    <th class="px-6 py-4 text-center">
                        Nama Organisasi
                    </th>

                    <th class="px-6 py-4 text-center">
                        Alamat
                    </th>

                    <th class="px-6 py-4 text-center">
                        Jenis
                    </th>

                    <th class="px-6 py-4 text-center">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody class="text-sm text-gray-800">

                <!-- DATA ORGANISASI 1 -->
                <tr class="border-b border-gray-200">

                    <td class="px-6 py-4">
                        1.
                    </td>

                    <td class="px-6 py-4 text-center">
                        19/02/2025
                    </td>

                    <td class="px-6 py-4 text-center">
                        SD Harapan Bangsa
                    </td>

                    <td class="px-6 py-4 text-center">
                        Jl. Raya Surabaya No. 10
                    </td>

                    <td class="px-6 py-4 text-center">
                        Sekolah
                    </td>

                    <td class="px-6 py-4 text-center">

                        <a
                            href="/manager/detailuser"
                            class="inline-block bg-rangkul-green text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-opacity-90"
                        >
                            Detail
                        </a>

                    </td>

                </tr>


                <!-- DATA ORGANISASI 2 -->
                <tr class="border-b border-gray-200">

                    <td class="px-6 py-4">
                        2.
                    </td>

                    <td class="px-6 py-4 text-center">
                        08/11/2025
                    </td>

                    <td class="px-6 py-4 text-center">
                        Asrama Pemberdayaan Yatim dan Dhuafa
                    </td>

                    <td class="px-6 py-4 text-center">
                        Jl. Delta Raya III No.4, Sidoarjo
                    </td>

                    <td class="px-6 py-4 text-center">
                        Panti
                    </td>

                    <td class="px-6 py-4 text-center">

                        <a
                            href="/manager/detailuser"
                            class="inline-block bg-rangkul-green text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-opacity-90"
                        >
                            Detail
                        </a>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>


    <!-- DATA DONATUR -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden min-h-[400px]">

        <div class="bg-rangkul-green text-white px-6 py-3 flex items-center gap-2">

            <span>📒</span>

            <span class="font-bold">
                Data Donatur
            </span>

        </div>

        <table class="w-full text-left">

            <thead class="border-b border-gray-200">

                <tr class="text-sm font-bold text-gray-800 text-center">

                    <th class="px-2 py-2">
                        No.
                    </th>

                    <th class="px-6 py-4 text-center">
                        Nama Donatur
                    </th>

                    <th class="px-6 py-4 text-center">
                        Jenis
                    </th>

                    <th class="px-6 py-4 text-center">
                        Detail
                    </th>

                </tr>

            </thead>


            <tbody class="text-sm text-gray-800">

                <!-- DATA DONATUR 1 -->
                <tr class="border-b border-gray-200">

                    <td class="px-2 py-4 text-center">
                        1.
                    </td>

                    <td class="px-6 py-4 text-center">
                        Ahmad Fauzi Ozi
                    </td>

                    <td class="px-6 py-4 text-center">
                        Individu
                    </td>

                    <td class="px-6 py-4 text-center">

                        <a
                            href="/manager/detailuser"
                            class="inline-block bg-rangkul-green text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-opacity-90"
                        >
                            Detail
                        </a>

                    </td>

                </tr>


                <!-- DATA DONATUR 2 -->
                <tr class="border-b border-gray-200">

                    <td class="px-2 py-4 text-center">
                        2.
                    </td>

                    <td class="px-6 py-4 text-center">
                        Himpunan Mahasiswa Sistem Informasi
                    </td>

                    <td class="px-6 py-4 text-center">
                        Organisasi
                    </td>

                    <td class="px-6 py-4 text-center">

                        <a
                            href="/manager/detailuser"
                            class="inline-block bg-rangkul-green text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-opacity-90"
                        >
                            Detail
                        </a>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</main>

</body>
</html>