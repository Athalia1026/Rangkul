const money = value => 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(value || 0));
async function request(path, options = {}) {
    const token = localStorage.getItem('auth_token');
    if (!token) { location.replace('/login'); throw new Error('Silakan masuk dengan akun donatur.'); }
    const response = await fetch(path, { ...options, headers: { Accept: 'application/json', Authorization: `Bearer ${token}`, ...options.headers } });
    if (response.status === 401) { location.replace('/login'); throw new Error('Sesi berakhir.'); }
    const data = await response.json().catch(() => ({}));
    if (!response.ok) throw new Error(Object.values(data.errors || {}).flat().join(' ') || data.message || 'Pembayaran belum dapat diproses.');
    return data;
}
const form = document.getElementById('checkout-form');
if (form) {
    const amount = document.getElementById('checkout-amount');
    function update() {
        document.getElementById('checkout-subtotal').textContent = money(amount.value);
        document.getElementById('checkout-total').textContent = money(Number(amount.value) + Number(form.dataset.fee));
        form.querySelectorAll('[data-amount]').forEach(button => button.setAttribute('aria-pressed', String(Number(button.dataset.amount) === Number(amount.value))));
    }
    form.querySelectorAll('[data-amount]').forEach(button => button.addEventListener('click', () => { amount.value = button.dataset.amount; update(); }));
    amount.addEventListener('input', update); update();
    form.addEventListener('submit', async event => {
        event.preventDefault();
        const button = form.querySelector('[type=submit]'), message = document.getElementById('checkout-message');
        if (button.disabled) return;
        button.disabled = true; button.textContent = 'Menyiapkan pembayaran...'; message.textContent = '';
        try {
            const result = await request('/api/donations', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id_campaign: form.dataset.campaign, nominal: Number(amount.value), note: document.getElementById('checkout-note').value, anonim: document.getElementById('checkout-anonymous').checked }) });
            location.assign(`/donatur/pembayaran/${encodeURIComponent(result.donation.id)}`);
        } catch (error) { message.textContent = error.message; button.disabled = false; button.textContent = 'Lanjut ke Pembayaran'; }
    });
}
const payment = document.getElementById('checkout-payment');
if (payment) {
    const message = document.getElementById('payment-message');
    const check = document.getElementById('payment-check');
    const open = document.getElementById('payment-open');
    const retry = document.getElementById('payment-retry');
    const id = encodeURIComponent(payment.dataset.id);
    let busy = false, finished = false, snapToken = null;
    function stop(text, campaignId) {
        finished = true; message.textContent = text;
        open.hidden = true; check.hidden = true;
        document.getElementById('payment-provider-link').hidden = true;
        if (campaignId) { retry.href = `/donatur/donasi/${encodeURIComponent(campaignId)}`; retry.hidden = false; }
    }
    // refresh=1 meminta backend menanyakan status langsung ke Midtrans, sehingga
    // status tetap terbarui walaupun notifikasi webhook tidak sampai (mis. di localhost).
    async function status(refresh = false, manual = false) {
        if (busy || finished) return;
        busy = true; check.disabled = true;
        try {
            const data = await request(`/api/donations/${id}${refresh ? '?refresh=1' : ''}`);
            if (data.status === 'sudah_bayar') { finished = true; location.replace(`/donatur/donasi-berhasil/${id}`); }
            else if (data.status === 'gagal') stop('Pembayaran gagal atau kedaluwarsa. Silakan buat donasi baru.', data.campaign_id);
            else if (manual) message.textContent = 'Pembayaran belum terkonfirmasi. Jika sudah membayar, tunggu sebentar lalu periksa kembali.';
            return data;
        } catch (error) { message.textContent = error.message; }
        finally { busy = false; check.disabled = finished; }
    }
    function loadSnap() {
        return new Promise((resolve, reject) => {
            if (window.snap) return resolve();
            const script = document.createElement('script'); script.src = payment.dataset.snapUrl; script.dataset.clientKey = payment.dataset.clientKey;
            script.onload = resolve; script.onerror = () => reject(new Error('Halaman pembayaran gagal dimuat. Gunakan tombol Buka Halaman Pembayaran di Tab Baru.')); document.head.append(script);
        });
    }
    function pay() {
        if (!snapToken || !window.snap || finished) return;
        window.snap.pay(snapToken, {
            onSuccess: () => { message.textContent = 'Pembayaran diterima, memverifikasi...'; status(true); },
            onPending: () => { message.textContent = 'Menunggu pembayaran Anda. Status akan diperbarui otomatis setelah pembayaran selesai.'; status(true); },
            onError: () => { message.textContent = 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.'; status(true); },
            onClose: () => { if (!finished) message.textContent = 'Halaman pembayaran ditutup. Tekan Bayar Sekarang untuk melanjutkan pembayaran.'; },
        });
    }
    check.onclick = () => status(true, true);
    open.onclick = pay;
    (async () => {
        const data = await status(true); if (!data || finished) return;
        document.getElementById('payment-summary').textContent = `${data.campaign} • Total ${money(data.nominal + Number(data.fee || 0))}`;
        const fallback = document.getElementById('payment-provider-link');
        if (data.payment_url) {
            const url = new URL(data.payment_url);
            if (url.protocol === 'https:' && ['app.midtrans.com', 'app.sandbox.midtrans.com'].includes(url.hostname)) { fallback.href = url.href; fallback.hidden = false; }
        }
        if (!data.snap_token) { stop('Sesi pembayaran untuk transaksi ini tidak tersedia. Silakan buat donasi baru.', data.campaign_id); return; }
        if (!payment.dataset.clientKey) { message.textContent = 'Gunakan tombol Buka Halaman Pembayaran di Tab Baru untuk melanjutkan.'; return; }
        try {
            await loadSnap();
            snapToken = data.snap_token;
            open.hidden = false;
            message.textContent = 'Selesaikan pembayaran melalui halaman pembayaran Midtrans.';
            pay();
        } catch (error) { message.textContent = error.message; }
    })();
    const timer = setInterval(() => { if (!document.hidden && !finished) status(true); }, 15000);
    window.addEventListener('pagehide', () => clearInterval(timer), { once: true });
}
const success = document.getElementById('checkout-success');
if (success) {
    (async () => {
        try {
            const data = await request(`/api/donations/${encodeURIComponent(success.dataset.id)}`);
            if (data.status !== 'sudah_bayar') { location.replace(`/donatur/pembayaran/${encodeURIComponent(data.id)}`); return; }
            document.getElementById('receipt-campaign').textContent = data.campaign;
            document.getElementById('receipt-amount').textContent = money(data.nominal);
            document.getElementById('receipt-id').textContent = data.id;
            document.getElementById('receipt-date').textContent = new Date(data.paid_at || data.created_at).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' });
            document.getElementById('success-message').hidden = true;
            document.getElementById('success-content').hidden = false;
        } catch (error) { document.getElementById('success-message').textContent = error.message; }
    })();
}
