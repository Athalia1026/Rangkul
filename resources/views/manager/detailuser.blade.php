<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-auth-session-guard />

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

    @include('manager.layouts.navbar')

    <!-- MAIN CONTENT -->
    <main class="w-full max-w-[1200px] mx-auto px-8 py-5 space-y-6">

        <!-- TOMBOL KEMBALI -->
        <div>
            <a
                href="/manager/daftaruser"
                class="inline-flex items-center gap-2 bg-[#d1e7dd] hover:bg-green-200 text-gray-800 px-6 py-2 rounded-lg shadow-sm font-bold text-sm transition active:scale-95"
            >
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>


        <div id="detailuser-error" class="hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-center text-sm text-red-700"></div>

        <!-- INFORMASI ASRAMA -->
        <section class="w-full max-w-[1200px] mx-auto bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="grid grid-cols-1 md:grid-cols-2">

                <!-- FOTO -->
                <div id="fotoOrganisasi" class="h-[320px] bg-gray-100 flex items-center justify-center text-gray-400">
                    <i class="fa-regular fa-image text-6xl"></i>
                </div>

                <!-- INFORMASI -->
                <div class="px-8 py-4 flex flex-col">

                    <h1 id="namaOrganisasi" class="text-2xl font-bold text-gray-900 mb-8">
                        Memuat...
                    </h1>

                    <div class="grid grid-cols-2 gap-y-7">

                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Jenis
                            </p>
                            <p id="jenisOrganisasi" class="text-lg font-bold">
                                -
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Status
                            </p>
                            <p id="statusOrganisasi" class="text-lg font-bold text-orange-400">
                                -
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Jumlah Anak Asuh
                            </p>
                            <p id="jumlahAnak" class="text-lg font-bold">
                                -
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Tanggal Daftar
                            </p>
                            <p id="tanggalDaftar" class="text-lg font-bold">
                                -
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

                                <p id="alamatOrganisasi" class="text-sm text-gray-600 leading-relaxed">
                                    -
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

                                <p id="kotaOrganisasi" class="text-sm text-gray-600">
                                    -
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

                                <p id="kontakOrganisasi" class="text-sm text-gray-600 leading-relaxed">
                                    -
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

                                <p id="emailOrganisasi" class="text-sm text-gray-600 break-all">
                                    -
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

                                <p id="deskripsiOrganisasi" class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                                    -
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

                        <tbody id="dokumenRows" class="text-sm">

                            <tr class="border-t border-gray-200">
                                <td colspan="2" class="px-6 py-5 text-center text-gray-500">
                                    Memuat data...
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </section>
        </div>

    </main>

    <script>

        const { request, escapeHtml } = window.RangkulAdmin;
        const organizationId = @json($organizationId);

        const STATUS_TAMPIL = {
            menunggu: { label: 'Menunggu', className: 'text-orange-400' },
            disetujui: { label: 'Disetujui', className: 'text-rangkul-green' },
            ditolak: { label: 'Ditolak', className: 'text-rangkul-red' }
        };

        function setText(id, value) {
            document.getElementById(id).textContent = value || value === 0 ? value : '-';
        }

        function renderFoto(url) {
            if (!url) {
                return;
            }

            const foto = document.getElementById('fotoOrganisasi');
            foto.className = 'h-[320px]';
            foto.innerHTML = `
                <img
                    src="${escapeHtml(url)}"
                    alt="Foto Organisasi"
                    class="w-full h-full object-cover"
                >
            `;
        }

        function renderDokumen(documents, bankAccount) {
            const rows = documents.map((dokumen) => `
                <tr class="border-t border-gray-200">

                    <td class="px-6 py-5">
                        ${escapeHtml(dokumen.label)}
                    </td>

                    <td class="px-6 py-5">
                        <a
                            href="${escapeHtml(dokumen.url)}"
                            download="${escapeHtml(dokumen.file_name)}"
                            title="${escapeHtml(dokumen.file_name)}"
                            class="inline-flex items-center gap-2 bg-[#d1e7dd] px-4 py-2 rounded-lg w-[200px] hover:bg-[#c3dfd3] transition cursor-pointer"
                        >
                            <span class="truncate">${escapeHtml(dokumen.file_name)}</span>
                        </a>
                    </td>

                </tr>
            `);

            rows.push(`
                <tr class="border-t border-gray-200">

                    <td class="px-6 py-5">
                        Rekening Lembaga
                    </td>

                    <td class="px-6 py-5">
                        <span class="inline-flex items-center gap-2 bg-[#d1e7dd] px-4 py-2 rounded-lg w-[200px]">
                            ${bankAccount
                                ? `(${escapeHtml(bankAccount.bank)}) ${escapeHtml(bankAccount.no_rekening)}`
                                : 'Belum ada rekening'}
                        </span>
                    </td>

                </tr>
            `);

            document.getElementById('dokumenRows').innerHTML = rows.join('');
        }

        function renderOrganisasi(data) {
            const status = STATUS_TAMPIL[data.verification_status] || { label: data.verification_status, className: 'text-gray-700' };
            const statusElement = document.getElementById('statusOrganisasi');

            setText('namaOrganisasi', data.name);
            setText('jenisOrganisasi', data.type);
            statusElement.textContent = status.label;
            statusElement.className = 'text-lg font-bold ' + status.className;
            setText('jumlahAnak', data.jumlah_anak);
            setText('tanggalDaftar', data.registered_at);

            setText('alamatOrganisasi', data.address);
            setText('kotaOrganisasi', data.city);
            document.getElementById('kontakOrganisasi').innerHTML = [data.phone, data.contact_name]
                .filter(Boolean)
                .map(escapeHtml)
                .join('<br>') || '-';
            setText('emailOrganisasi', data.email);
            setText('deskripsiOrganisasi', data.description);

            renderFoto(data.photo_url);
            renderDokumen(data.documents, data.bank_account);
        }

        async function loadOrganisasi() {
            const error = document.getElementById('detailuser-error');

            try {
                const { data } = await request('/api/admin/users/organizations/' + encodeURIComponent(organizationId));
                error.classList.add('hidden');
                renderOrganisasi(data);
            } catch (exception) {
                error.textContent = exception.message;
                error.classList.remove('hidden');
                setText('namaOrganisasi', '-');
                document.getElementById('dokumenRows').innerHTML = '';
            }
        }

        loadOrganisasi();

    </script>

</body>
</html>