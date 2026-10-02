const money = value => 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(value || 0));
function previewDonation() {
    try {
        const stored = JSON.parse(sessionStorage.getItem('rangkul_donation_preview') || 'null');
        if (stored) return stored;
    } catch (_) { /* Use sample data when no preview has been saved. */ }
    return { id: 'PREVIEW-DONASI', campaign: 'Bantuan Kebutuhan Anak Panti', nominal: 100000, fee: 3000, created_at: new Date().toISOString(), status: 'sudah_bayar' };
}
async function request(path, options = {}) {
    const token = localStorage.getItem('auth_token');
    if (!token) { location.replace('/login'); throw new Error('Silakan masuk dengan akun donatur.'); }
    const response = await fetch(path, { ...options, headers: { Accept: 'application/json', Authorization: `Bearer ${token}`, ...options.headers } });
    if (response.status === 401) { location.replace('/login'); throw new Error('Sesi berakhir.'); }
    const data = await response.json();
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
            if (form.dataset.preview === 'true') {
                sessionStorage.setItem('rangkul_donation_preview', JSON.stringify({ id: 'PREVIEW-DONASI', campaign: form.dataset.title, nominal: Number(amount.value), fee: Number(form.dataset.fee), created_at: new Date().toISOString(), status: 'sudah_bayar' }));
                location.assign('/donatur/pembayaran/preview');
                return;
            }
            const result = await request('/api/donations', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id_campaign: form.dataset.campaign, nominal: Number(amount.value), note: document.getElementById('checkout-note').value, anonim: document.getElementById('checkout-anonymous').checked }) });
            location.assign(`/donatur/pembayaran/${encodeURIComponent(result.donation.id)}`);
        } catch (error) { message.textContent = error.message; button.disabled = false; button.textContent = 'Lanjut ke Pembayaran'; }
    });
}
const payment = document.getElementById('checkout-payment');
if (payment && payment.dataset.id === 'preview') {
    const data = previewDonation();
    document.getElementById('payment-summary').textContent = `${data.campaign} • ${money(data.nominal + data.fee)}`;
    document.getElementById('payment-message').textContent = 'Mode preview — kode ini hanya ilustrasi, bukan untuk pembayaran.';
    document.getElementById('preview-qr').hidden = false;
    const back = payment.querySelector('.checkout-back');
    back.textContent = '← Kembali';
    back.onclick = event => { if (document.referrer.startsWith(location.origin + '/donatur/donasi/')) { event.preventDefault(); history.back(); } };
    const check = document.getElementById('payment-check');
    check.textContent = 'Selesai';
    check.onclick = () => location.assign('/donatur/donasi-berhasil/preview');
}
if (payment && payment.dataset.id !== 'preview') {
    const message = document.getElementById('payment-message');
    const check = document.getElementById('payment-check');
    const id = encodeURIComponent(payment.dataset.id);
    let busy = false, finished = false;
    async function status(manual = false) {
        if (busy || finished) return;
        busy = true; check.disabled = true;
        try {
            const data = await request(`/api/donations/${id}${manual ? '?refresh=1' : ''}`);
            if (data.status === 'sudah_bayar') { finished = true; location.replace(`/donatur/donasi-berhasil/${id}`); }
            else if (data.status === 'gagal') { finished = true; message.textContent = 'Pembayaran gagal atau kedaluwarsa. Silakan kembali ke kampanye untuk membuat donasi baru.'; }
            else if (manual) message.textContent = 'Pembayaran belum terkonfirmasi. Jika sudah membayar, tunggu sebentar lalu periksa kembali.';
            return data;
        } catch (error) { message.textContent = error.message; }
        finally { busy = false; check.disabled = finished; }
    }
    check.onclick = () => status(true);
    (async () => {
        const data = await status(); if (!data || finished) return;
        document.getElementById('payment-summary').textContent = `${data.campaign} • ${money(data.nominal + Number(data.fee || 0))}`;
        const fallback = document.getElementById('payment-provider-link');
        if (data.payment_url) {
            const url = new URL(data.payment_url);
            if (url.protocol === 'https:' && ['app.midtrans.com', 'app.sandbox.midtrans.com'].includes(url.hostname)) { fallback.href = url.href; fallback.hidden = false; }
        }
        if (!data.snap_token) { message.textContent = 'Sesi pembayaran untuk transaksi ini tidak tersedia. Silakan pilih kampanye untuk membuat donasi baru.'; return; }
        if (!payment.dataset.clientKey) { message.textContent = 'Gunakan tombol Buka Halaman Pembayaran untuk melanjutkan.'; return; }
        try {
            await new Promise((resolve, reject) => {
                if (window.snap) return resolve();
                const script = document.createElement('script'); script.src = payment.dataset.snapUrl; script.dataset.clientKey = payment.dataset.clientKey;
                script.onload = resolve; script.onerror = () => reject(new Error('Panel pembayaran gagal dimuat. Gunakan tombol Buka Halaman Pembayaran.')); document.head.append(script);
            });
            message.textContent = 'Selesaikan pembayaran melalui QRIS di bawah ini.';
            window.snap.embed(data.snap_token, { embedId: 'snap-container', onSuccess: () => status(true), onPending: () => status(true), onError: () => status(true) });
        } catch (error) { message.textContent = error.message; }
    })();
    const timer = setInterval(() => { if (!document.hidden && !finished) status(); }, 10000);
    window.addEventListener('pagehide', () => clearInterval(timer), { once: true });
}
const success = document.getElementById('checkout-success');
if (success) {
    (async () => {
        try {
            const preview = success.dataset.id === 'preview';
            const data = preview ? previewDonation() : await request(`/api/donations/${encodeURIComponent(success.dataset.id)}`);
            if (data.status !== 'sudah_bayar') { location.replace(`/donatur/pembayaran/${encodeURIComponent(data.id)}`); return; }
            document.getElementById('receipt-campaign').textContent = data.campaign;
            document.getElementById('receipt-amount').textContent = money(data.nominal);
            document.getElementById('receipt-id').textContent = data.id;
            document.getElementById('receipt-date').textContent = new Date(data.paid_at || data.created_at).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' });
            document.getElementById('success-message').hidden = !preview;
            if (preview) document.getElementById('success-message').textContent = 'Preview tampilan berhasil — tidak ada pembayaran atau donasi yang dicatat.';
            document.getElementById('success-content').hidden = false;
        } catch (error) { document.getElementById('success-message').textContent = error.message; }
    })();
}
