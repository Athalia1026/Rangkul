<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-auth-session-guard />
    <title>Transfer Dana</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F5F7F4; }
    </style>
</head>
<body class="min-h-screen text-gray-800">
    @php($isStaff = $isStaff ?? false)
    @if ($isStaff)
        @include('staff.layouts.navbar')
    @else
        @include('manager.layouts.navbar')
    @endif

    <main class="mx-auto max-w-4xl space-y-6 px-4 py-8 sm:px-6">
        <a href="{{ $isStaff ? route('staff.beranda') : route('manager.home') }}" class="inline-flex items-center gap-2 font-semibold text-[#086538] hover:underline">
            <span aria-hidden="true">&larr;</span> Kembali ke beranda
        </a>

        <header>
            <p class="text-sm font-bold uppercase tracking-wide text-[#086538]">Pencairan dana</p>
            <h1 class="mt-1 text-3xl font-bold">Transfer ke rekening organisasi</h1>
            <p class="mt-2 text-gray-600">Setelah manager menyetujui pengajuan, lakukan transfer melalui bank lalu catat bukti transfer di halaman ini.</p>
        </header>

        <div id="pageError" role="alert" class="hidden rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-700"></div>
        <div id="pageNotice" role="status" class="hidden rounded-xl border px-5 py-4"></div>

        <section id="transferDetails" class="hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start">
                <div>
                    <h2 id="organizationName" class="text-xl font-bold"></h2>
                    <p id="campaignTitle" class="mt-1 text-sm text-gray-500"></p>
                </div>
                <span id="transferStatus" class="inline-flex w-fit rounded-full bg-amber-100 px-4 py-2 text-sm font-bold text-amber-800"></span>
            </div>

            <dl class="mt-7 grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-sm text-gray-500">Nominal transfer</dt>
                    <dd id="amount" class="mt-1 text-2xl font-bold text-[#086538]"></dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Nama alokasi</dt>
                    <dd id="allocation" class="mt-1 font-semibold"></dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm text-gray-500">Rekening tujuan yang disetujui</dt>
                    <dd id="destinationAccount" class="mt-1 font-semibold"></dd>
                </div>
            </dl>
        </section>

        <section id="transferFormSection" class="hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
                Catat transfer hanya setelah transaksi berhasil di bank. Setelah disimpan, organisasi akan menerima notifikasi bahwa dana telah ditransfer.
            </div>

            <form id="transferForm" class="space-y-5">
                <div>
                    <label for="bukti_transfer" class="mb-2 block text-sm font-semibold">Bukti transfer <span class="text-red-600">*</span></label>
                    <input id="bukti_transfer" name="bukti_transfer" type="file" accept=".jpg,.jpeg,.png,.pdf" required
                        class="block w-full rounded-lg border border-gray-300 p-3 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-[#e6f4eb] file:px-4 file:py-2 file:font-semibold file:text-[#086538]">
                    <p class="mt-1 text-xs text-gray-500">Format JPG, PNG, atau PDF; ukuran maksimal 2 MB.</p>
                </div>
                <div>
                    <label for="referensi_transfer" class="mb-2 block text-sm font-semibold">Nomor referensi transfer <span class="font-normal text-gray-500">(opsional)</span></label>
                    <input id="referensi_transfer" name="referensi_transfer" type="text" maxlength="255"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-[#086538] focus:outline-none focus:ring-2 focus:ring-green-100">
                </div>
                <div>
                    <label for="catatan" class="mb-2 block text-sm font-semibold">Catatan <span class="font-normal text-gray-500">(opsional)</span></label>
                    <textarea id="catatan" name="catatan" rows="3"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:border-[#086538] focus:outline-none focus:ring-2 focus:ring-green-100"></textarea>
                </div>
                <button id="submitTransfer" type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-[#086538] px-6 py-3 font-bold text-white transition hover:bg-[#064e2b] disabled:cursor-wait disabled:opacity-60">
                    Simpan Bukti dan Selesaikan Transfer
                </button>
            </form>
        </section>

        <section id="completedTransfer" class="hidden rounded-2xl border border-emerald-200 bg-emerald-50 p-6 text-emerald-900">
            <h2 class="text-lg font-bold">Transfer sudah dicatat</h2>
            <p id="completedSummary" class="mt-2 text-sm leading-6"></p>
        </section>
    </main>

    <script>
        const { request, formatRupiah } = window.RangkulAdmin;
        const disbursementId = @json($disbursementId);
        const isStaff = @json($isStaff);
        const detailUrl = @json($isStaff ? route('staff.beranda') : route('manager.detailpengajuan', $disbursementId));
        const endpoint = '/api/admin/disbursements/' + encodeURIComponent(disbursementId);
        const errorBox = document.getElementById('pageError');
        const noticeBox = document.getElementById('pageNotice');

        function showError(message) {
            errorBox.textContent = message;
            errorBox.classList.remove('hidden');
        }

        async function loadDisbursement() {
            try {
                const { data } = await request(endpoint);
                document.getElementById('organizationName').textContent = data.organization_name;
                document.getElementById('campaignTitle').textContent = data.campaign_title;
                document.getElementById('amount').textContent = formatRupiah(data.approved_amount);
                document.getElementById('allocation').textContent = data.alokasi_dana || '-';

                const account = data.destination_account || {};
                document.getElementById('destinationAccount').textContent = account.no_rekening
                    ? `${account.bank || '-'} — ${account.no_rekening} a.n. ${account.pemilik_rekening || '-'}`
                    : 'Rekening tujuan belum tersedia';
                document.getElementById('transferDetails').classList.remove('hidden');

                if (data.status === 'diterima' && data.transfer_status === 'menunggu_transfer_manual') {
                    document.getElementById('transferStatus').textContent = 'Menunggu transfer';
                    document.getElementById('transferFormSection').classList.remove('hidden');
                    return;
                }

                if (data.status === 'diterima' && data.transfer_status === 'selesai') {
                    document.getElementById('transferStatus').textContent = 'Transfer selesai';
                    document.getElementById('transferStatus').className = 'inline-flex w-fit rounded-full bg-emerald-100 px-4 py-2 text-sm font-bold text-emerald-800';
                    document.getElementById('completedSummary').textContent =
                        `Transfer ${formatRupiah(data.approved_amount)} sudah tercatat.`;
                    document.getElementById('completedTransfer').classList.remove('hidden');
                    return;
                }

                document.getElementById('transferStatus').textContent = data.status === 'ditolak' ? 'Pengajuan ditolak' : 'Belum disetujui';
                noticeBox.textContent = data.status === 'ditolak'
                    ? 'Pengajuan ini ditolak sehingga transfer tidak dapat dicatat.'
                    : 'Pengajuan harus disetujui terlebih dahulu sebelum transfer dapat dicatat.';
                noticeBox.className = 'rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-amber-900';
                noticeBox.classList.remove('hidden');
                if (data.status === 'menunggu') {
                    if (!isStaff) {
                        const link = document.createElement('a');
                        link.href = detailUrl;
                        link.textContent = 'Kembali ke detail pengajuan untuk memverifikasi';
                        link.className = 'mt-3 inline-block font-semibold underline';
                        noticeBox.appendChild(link);
                    }
                }
            } catch (error) {
                showError(error.message);
            }
        }

        document.getElementById('transferForm').addEventListener('submit', async (event) => {
            event.preventDefault();
            errorBox.classList.add('hidden');

            const button = document.getElementById('submitTransfer');
            button.disabled = true;
            button.textContent = 'Menyimpan...';

            try {
                const body = new FormData(event.currentTarget);
                const result = await request(endpoint + '/manual-transfer', { method: 'POST', body });
                document.getElementById('transferFormSection').classList.add('hidden');
                document.getElementById('transferStatus').textContent = 'Transfer selesai';
                document.getElementById('transferStatus').className = 'inline-flex w-fit rounded-full bg-emerald-100 px-4 py-2 text-sm font-bold text-emerald-800';
                document.getElementById('completedSummary').textContent = result.message;
                document.getElementById('completedTransfer').classList.remove('hidden');
                noticeBox.textContent = 'Transfer telah dicatat. Organisasi sudah menerima notifikasi.';
                noticeBox.className = 'rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-900';
                noticeBox.classList.remove('hidden');
                button.textContent = 'Transfer tersimpan';
            } catch (error) {
                showError(error.message);
                button.disabled = false;
                button.textContent = 'Simpan Bukti dan Selesaikan Transfer';
            }
        });

        loadDisbursement();
    </script>
</body>
</html>
