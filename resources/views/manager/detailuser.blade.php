<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Information</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    >

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'rangkul-green': '#086538',
                        'rangkul-red': '#8C1A11',
                        'rangkul-bg': '#F5F7F4',
                    }
                }
            }
        }
    </script>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap"
        rel="stylesheet"
    >

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F5F7F4;
        }
    </style>
</head>

<body class="text-gray-800">

    <!-- NAVIGATION BAR -->
    <nav class="bg-white border-b border-gray-200 px-8 py-3 flex items-center justify-between">

        <!-- LOGO -->
        <div class="flex items-center w-1/3">
            <img
                src="/images/logo.png"
                alt="Rangkul"
                class="h-10 w-auto"
            >
        </div>

        <!-- MENU NAVBAR -->
        <div class="flex gap-12 font-semibold text-sm">

            <a href="#" class="hover:text-green-700">
                Beranda
            </a>

            <a href="#" class="hover:text-green-700">
                Daftar Pengguna
            </a>

            <a href="#" class="hover:text-green-700">
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

        <!-- TOMBOL KEMBALI -->
        <div>
            <a
                href="/manager/home"
                class="inline-flex items-center gap-2 bg-[#d1e7dd] hover:bg-green-200 text-gray-800 px-6 py-2 rounded-lg shadow-sm font-bold text-sm transition active:scale-95"
            >
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>


        <!-- INFORMASI ASRAMA -->
        <section class="w-full max-w-[1200px] mx-auto bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="grid grid-cols-1 md:grid-cols-2">

                <!-- FOTO -->
                <div class="h-[320px]">
                    <img
                        src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=900&q=80"
                        alt="Foto Asrama"
                        class="w-full h-full object-cover"
                    >
                </div>

                <!-- INFORMASI -->
                <div class="px-8 py-4 flex flex-col">

                    <h1 class="text-2xl font-bold text-gray-900 mb-8">
                        Asrama Pemberdayaan Yatim dan Dhuafa
                    </h1>

                    <div class="grid grid-cols-2 gap-y-7">

                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Jenis
                            </p>
                            <p class="text-lg font-bold">
                                Panti
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Status
                            </p>
                            <p class="text-lg font-bold text-orange-400">
                                Menunggu
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Jumlah Anak Asuh
                            </p>
                            <p class="text-lg font-bold">
                                50
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Tanggal Daftar
                            </p>
                            <p class="text-lg font-bold">
                                3 Februari 2026
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </section>


        <!-- INFORMASI PENDAFTARAN & DOKUMEN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- INFORMASI PENDAFTARAN -->
            <section>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    Informasi Pendaftaran
                </h2>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                    <h3 class="text-lg font-bold mb-5">
                        Details
                    </h3>

                    <!-- ALAMAT & KOTA -->
                    <div class="grid grid-cols-2 gap-6 border-t border-gray-200 py-5">

                        <div class="flex gap-3">
                            <span class="text-rangkul-green">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>

                            <div>
                                <p class="font-bold text-sm mb-1">
                                    Alamat
                                </p>

                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Jl. Delta Raya III No.4, Ngingas, Kec. Waru,
                                    Kabupaten Sidoarjo, Jawa Timur 61256
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <span class="text-rangkul-green">
                                <i class="fa-solid fa-building"></i>
                            </span>

                            <div>
                                <p class="font-bold text-sm mb-1">
                                    Kota
                                </p>

                                <p class="text-sm text-gray-600">
                                    Sidoarjo
                                </p>
                            </div>
                        </div>

                    </div>


                    <!-- KONTAK & EMAIL -->
                    <div class="grid grid-cols-2 gap-6 border-t border-gray-200 py-5">

                        <div class="flex gap-3">
                            <span class="text-rangkul-green">
                                <i class="fa-solid fa-phone"></i>
                            </span>

                            <div>
                                <p class="font-bold text-sm mb-1">
                                    Kontak
                                </p>

                                <p class="text-sm text-gray-600 leading-relaxed">
                                    (031) 8552980<br>
                                    Asrama Yatim dan Dhuafa
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <span class="text-rangkul-green">
                                <i class="fa-solid fa-envelope"></i>
                            </span>

                            <div>
                                <p class="font-bold text-sm mb-1">
                                    Email
                                </p>

                                <p class="text-sm text-gray-600">
                                    PYIindonesia@gmail.com
                                </p>
                            </div>
                        </div>

                    </div>


                    <!-- DESKRIPSI -->
                    <div class="border-t border-gray-200 pt-5">

                        <div class="flex gap-3">

                            <span class="text-rangkul-green">
                                <i class="fa-solid fa-align-left"></i>
                            </span>

                            <div>
                                <p class="font-bold text-sm mb-1">
                                    Deskripsi
                                </p>

                                <p class="text-sm text-gray-600 leading-relaxed">
                                    Panti Asuhan kami merupakan lembaga sosial yang
                                    berkomitmen memberikan pengasuhan, pendidikan,
                                    serta pembinaan kepada anak-anak yang membutuhkan.
                                    Kami berupaya menciptakan lingkungan yang aman,
                                    nyaman, dan penuh kasih sayang guna mendukung
                                    tumbuh kembang anak secara optimal.
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

            </section>


            <!-- DOKUMEN -->
            <section>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    Dokumen
                </h2>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

                    <table class="w-full text-left">

                        <thead class="bg-[#d1e7dd]">

                            <tr class="text-sm font-bold text-gray-800">

                                <th class="px-6 py-4 text-center">
                                    Keterangan
                                </th>

                                <th class="px-6 py-4 text-center">
                                    Lampiran
                                </th>

                            </tr>

                        </thead>

                        <tbody class="text-sm">

                            <tr class="border-t border-gray-200">

                                <td class="px-6 py-5">
                                    SK Pendirian / SK Operasional
                                </td>

                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-2 bg-[#d1e7dd] px-4 py-2 rounded-lg w-[200px]">
                                        SK Pendirian.pdf
                                    </span>
                                </td>

                            </tr>


                            <tr class="border-t border-gray-200">

                                <td class="px-6 py-5">
                                    KTP Penanggung Jawab
                                </td>

                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-2 bg-[#d1e7dd] px-4 py-2 rounded-lg w-[200px]">
                                        KTP.pdf
                                    </span>
                                </td>

                            </tr>


                            <tr class="border-t border-gray-200">

                                <td class="px-6 py-5">
                                    Foto Kegiatan dan Penerima Manfaat
                                </td>

                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-2 bg-[#d1e7dd] px-4 py-2 rounded-lg w-[200px]">
                                        Foto.jpg
                                    </span>
                                </td>

                            </tr>


                            <tr class="border-t border-gray-200">

                                <td class="px-6 py-5">
                                    Rekening Lembaga
                                </td>

                                <td class="px-6 py-5">
                                    <span class="inline-flex items-center gap-2 bg-[#d1e7dd] px-4 py-2 rounded-lg w-[200px]">
                                        (Mandiri) 1420026154855
                                    </span>
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </section>

        </div>

    </main>

</body>
</html>