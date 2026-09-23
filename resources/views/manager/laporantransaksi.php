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

@include('manager.layouts.navbar')


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
                    2
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

                    <th class="px-4 py-4 font-semibold">
                        No.
                    </th>

                    <th class="px-4 py-4 font-semibold">
                        Tanggal Transaksi
                    </th>

                    <th class="px-4 py-4 font-semibold">
                        Nama Donatur
                    </th>

                    <th class="px-4 py-4 font-semibold">
                        Organisasi
                    </th>

                    <th class="px-4 py-4 font-semibold">
                        Nominal
                    </th>

                    <th class="px-4 py-4 font-semibold">
                        Metode
                    </th>

                    <th class="px-4 py-4 font-semibold">
                        Status
                    </th>

                    <th class="px-4 py-4 font-semibold">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody class="text-sm">

                <!-- DATA 1 -->
                <tr class="text-gray-800 border-b border-gray-200">

                    <td class="px-4 py-4 text-center">
                        1.
                    </td>

                    <td class="px-4 py-2 text-center">
                        04/06/2026
                    </td>

                    <td class="px-4 py-4">
                        Ahmad Fauzil Ozi
                    </td>

                    <td class="px-4 py-4">
                        Asrama Pemberdayaan Yatim dan Dhuafa
                    </td>

                    <td class="px-4 py-2">
                        Rp 1.500.000
                    </td>

                    <td class="px-4 py-2 text-center">
                        QRIS
                    </td>

                    <td class="px-4 py-2 text-center">
                        Selesai
                    </td>

                    <td class="px-4 py-4 text-center">

                        <button
                            onclick="openDetail(1)"
                            class="bg-rangkul-green text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-opacity-90"
                        >
                            Detail
                        </button>

                    </td>

                </tr>


                <!-- DATA 2 -->
                <tr class="text-gray-800 border-b border-gray-200">

                    <td class="px-4 py-4 text-center">
                        2.
                    </td>

                    <td class="px-4 py-2 text-center">
                        08/11/2025
                    </td>

                    <td class="px-4 py-4">
                        Komunitas Anak Surabaya
                    </td>

                    <td class="px-4 py-4">
                        SD Harapan Bangsa
                    </td>

                    <td class="px-4 py-2">
                        Rp 5.000.000
                    </td>

                    <td class="px-4 py-2 text-center">
                        Transfer
                    </td>

                    <td class="px-4 py-2 text-center">
                        Menunggu
                    </td>

                    <td class="px-4 py-4 text-center">

                        <button
                            onclick="openDetail(2)"
                            class="bg-rangkul-green text-white px-4 py-1.5 rounded-lg text-xs font-semibold hover:bg-opacity-90"
                        >
                            Detail
                        </button>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</main>


<!-- POPUP DETAIL TRANSAKSI -->
<div
    id="detailModal"
    class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 hidden"
>

    <div class="bg-white w-[610px] rounded-xl shadow-xl p-7 relative">

        <!-- KEMBALI -->
        <button
            onclick="closeDetail()"
            class="bg-rangkul-green/20 text-gray-800 px-4 py-1.5 rounded-lg font-semibold text-sm mb-4"
        >
            Kembali
        </button>


        <!-- JUDUL -->
        <h2 class="text-2xl font-semibold text-gray-900 mb-2">
            Detail Transaksi
        </h2>


        <!-- DETAIL DATA -->
        <div class="text-sm text-gray-800 leading-5">

            <div class="grid grid-cols-[185px_15px_1fr]">

                <span>ID Transaksi</span>
                <span>:</span>
                <span id="detailId"></span>


                <span>Tanggal</span>
                <span>:</span>
                <span id="detailTanggal"></span>


                <span>Organisasi</span>
                <span>:</span>
                <span id="detailOrganisasi"></span>


                <span>Alamat</span>
                <span>:</span>
                <span id="detailAlamat"></span>


                <span class="mt-3">Donatur</span>
                <span class="mt-3">:</span>
                <span id="detailDonatur" class="mt-3"></span>


                <span>Tipe Donatur</span>
                <span>:</span>
                <span id="detailTipe"></span>


                <span>Nominal Donasi</span>
                <span>:</span>
                <span id="detailNominal"></span>


                <span>Metode Pembayaran</span>
                <span>:</span>
                <span id="detailMetode"></span>


                <span>Status</span>
                <span>:</span>
                <span id="detailStatus"></span>

            </div>

        </div>


        <!-- BUKTI PENYALURAN -->
        <div class="mt-14">

            <h3 class="text-2xl font-semibold text-gray-900 mb-4">
                Bukti Penyaluran
            </h3>

        <!-- CONTAINER GAMBAR -->
            <div id="detailBuktiContainer" class="flex gap-3">
            </div>

        <!-- KETERANGAN -->
            <div class="mt-2 text-sm text-gray-800 leading-5">

                <p id="detailKeterangan"></p>

                <p id="detailUpload"></p>

            </div>

        </div>

    </div>

</div>


        <script>

            // DATA TRANSAKSI
            const transaksi = {

                1: {
                    id: "TRX-002345",
                    tanggal: "4 Juni 2026",
                    organisasi: "Asrama Pemberdayaan Yatim dan Dhuafa",
                    alamat: "Jl. Delta Raya III No.4, Ngtingas, Kec. Waru, Kabupaten Sidoarjo, Jawa Timur 61256",
                    donatur: "Ahmad Fauzi Ozi",
                    tipe: "Individu",
                    nominal: "Rp 150.000",
                    metode: "QRIS",
                    status: "Selesai",

                    // DAFTAR GAMBAR
                    gambar: [
                        "/images/bukti-penyaluran.png",
                        "/images/bukti-penyaluran2.png",
                        "/images/bukti-penyaluran3.png",
                        "/images/bukti-penyaluran4.png",
                    ],

                    keterangan: "Keterangan: Donasi untuk kebutuhan sandang pangan",
                    upload: "Diupload: 13 Juli 2026"
                },

                2: {
                    id: "TRX-002346",
                    tanggal: "8 November 2025",
                    organisasi: "SD Harapan Bangsa",
                    alamat: "Jl. Kertajaya No. 10, Surabaya, Jawa Timur",
                    donatur: "Komunitas Anak Surabaya",
                    tipe: "Organisasi",
                    nominal: "Rp 500.000",
                    metode: "Transfer",
                    status: "Menunggu",

                    // DAFTAR GAMBAR
                    gambar: [
                        "/images/bukti-penyaluran.png",
                        "/images/bukti-penyaluran2.png",
                        "/images/bukti-penyaluran3.png",
                        "/images/bukti-penyaluran4.png",
                        "/images/bukti-penyaluran5.png"
                    ],

                    keterangan: "Keterangan: Donasi untuk kebutuhan pendidikan",
                    upload: "Diupload: 15 November 2025"
                }

            };


            // ==============================
            // BUKA DETAIL TRANSAKSI
            // ==============================

            function openDetail(id) {

                const data = transaksi[id];

                // Isi data detail
                document.getElementById("detailId").textContent = data.id;
                document.getElementById("detailTanggal").textContent = data.tanggal;
                document.getElementById("detailOrganisasi").textContent = data.organisasi;
                document.getElementById("detailAlamat").textContent = data.alamat;
                document.getElementById("detailDonatur").textContent = data.donatur;
                document.getElementById("detailTipe").textContent = data.tipe;
                document.getElementById("detailNominal").textContent = data.nominal;
                document.getElementById("detailMetode").textContent = data.metode;
                document.getElementById("detailStatus").textContent = data.status;

                document.getElementById("detailKeterangan").textContent =
                    data.keterangan;

                document.getElementById("detailUpload").textContent =
                    data.upload;


                // ==============================
                // TAMPILKAN GAMBAR
                // ==============================

                const container =
                    document.getElementById("detailBuktiContainer");

                // Bersihkan gambar sebelumnya
                container.innerHTML = "";


                // ==============================
                // 3 GAMBAR ATAU KURANG
                // ==============================

                if (data.gambar.length <= 3) {

                    data.gambar.forEach(function(src) {

                        const img = document.createElement("img");

                        img.src = src;
                        img.alt = "Bukti Penyaluran";

                        img.className =
                            "w-[70px] h-[70px] object-cover rounded-lg cursor-pointer";

                        // Klik gambar untuk preview
                        img.onclick = function() {
                            openImagePreview(src);
                        };

                        container.appendChild(img);

                    });

                }


                // ==============================
                // LEBIH DARI 3 GAMBAR
                // ==============================

                else {

                    // Tampilkan gambar pertama dan kedua
                    for (let i = 0; i < 2; i++) {

                        const img = document.createElement("img");

                        img.src = data.gambar[i];
                        img.alt = "Bukti Penyaluran";

                        img.className =
                            "w-[70px] h-[70px] object-cover rounded-lg cursor-pointer";

                        img.onclick = function() {
                            openImagePreview(data.gambar[i]);
                        };

                        container.appendChild(img);

                    }


                    // ==============================
                    // GAMBAR KETIGA + OVERLAY
                    // ==============================

                    const wrapper = document.createElement("div");

                    wrapper.className =
                        "relative w-[70px] h-[70px] rounded-lg overflow-hidden cursor-pointer";


                    // Gambar ketiga
                    const imgKetiga = document.createElement("img");

                    imgKetiga.src = data.gambar[2];
                    imgKetiga.alt = "Bukti Penyaluran";

                    imgKetiga.className =
                        "w-full h-full object-cover blur-[2px]";

                    wrapper.appendChild(imgKetiga);


                    // Jumlah gambar setelah gambar ketiga
                    const jumlahTambahan =
                        data.gambar.length - 3;


                    // Overlay
                    const overlay = document.createElement("div");

                    overlay.className =
                        "absolute inset-0 bg-black/40 flex items-center justify-center";

                    overlay.innerHTML = `
                        <span class="text-white text-lg font-semibold">
                            +${jumlahTambahan}
                        </span>
                    `;


                    wrapper.appendChild(overlay);


                    // Klik gambar ketiga
                    wrapper.onclick = function() {
                        openImagePreview(data.gambar[2]);
                    };


                    container.appendChild(wrapper);

                }
                // Tampilkan popup
                document
                    .getElementById("detailModal")
                    .classList.remove("hidden");

            }


            // ==============================
            // TUTUP DETAIL
            // ==============================

            function closeDetail() {

                document
                    .getElementById("detailModal")
                    .classList.add("hidden");

            }

        </script>

</body>
</html>