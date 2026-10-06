const registerPage = document.getElementById('premium-register-page');
const successPage = document.getElementById('premium-success-page');
const dashboardPage = document.getElementById('premium-dashboard-page');

const rupiah = value => 'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(value || 0));
const rupiahShort = value => 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(value || 0));
const wait = ms => new Promise(resolve => setTimeout(resolve, ms));

function token() {
    const value = localStorage.getItem('auth_token');
    if (!value) { location.replace('/login'); throw new Error('Silakan masuk kembali.'); }
    return value;
}
async function api(path, options = {}) {
    const response = await fetch(path, { ...options, headers: { Accept: 'application/json', Authorization: `Bearer ${token()}`, ...options.headers } });
    if (response.status === 401) { location.replace('/login'); throw new Error('Sesi berakhir.'); }
    const result = await response.json().catch(() => ({}));
    if (!response.ok) {
        const error = new Error(result.message || 'Data belum dapat diproses. Silakan coba lagi.');
        error.status = response.status; error.errors = result.errors || {};
        throw error;
    }
    return result;
}
async function download(path, filename, button) {
    const label = button.innerHTML; button.disabled = true;
    try {
        const response = await fetch(path, { headers: { Accept: 'application/pdf', Authorization: `Bearer ${token()}` } });
        if (response.status === 401) { location.replace('/login'); return; }
        if (!response.ok) throw new Error('Dokumen belum dapat diunduh. Silakan coba lagi.');
        const url = URL.createObjectURL(await response.blob());
        const link = document.createElement('a'); link.href = url; link.download = filename; link.click();
        setTimeout(() => URL.revokeObjectURL(url), 1000);
        return true;
    } finally { button.disabled = false; button.innerHTML = label; }
}

if (registerPage) {
    const form = document.getElementById('premium-form');
    const payButton = document.getElementById('premium-pay');
    const checkButton = document.getElementById('premium-check');
    const message = document.getElementById('premium-form-message');
    const fields = ['nama_pic', 'jabatan', 'nomor_pic', 'email_korporat', 'NPWP'];
    const input = name => form.elements.namedItem(name);
    let pending = null; // { id, snapToken } transaksi yang belum dibayar pada sesi ini

    const setMessage = (text, type = '') => { message.textContent = text; message.className = 'profile-message' + (type ? ` is-${type}` : ''); };
    function setError(name, text) {
        const field = input(name);
        field.parentElement.querySelector('.profile-field-error')?.remove();
        if (!text) { field.removeAttribute('aria-invalid'); return; }
        field.setAttribute('aria-invalid', 'true');
        const note = document.createElement('span'); note.className = 'profile-field-error'; note.textContent = text;
        field.after(note);
    }
    form.addEventListener('input', event => { if (event.target.name) { setError(event.target.name, ''); pending = null; } });

    function loadSnap() {
        return new Promise((resolve, reject) => {
            if (window.snap) return resolve();
            const script = document.createElement('script');
            script.src = registerPage.dataset.snapUrl; script.dataset.clientKey = registerPage.dataset.clientKey;
            script.onload = resolve; script.onerror = () => reject(new Error('Halaman pembayaran belum dapat dimuat. Periksa koneksi Anda.'));
            document.head.append(script);
        });
    }
    // Midtrans bisa butuh beberapa detik untuk mengonfirmasi; cek beberapa kali sebelum menyerah.
    async function waitUntilActive(id, attempts = 8) {
        for (let attempt = 0; attempt < attempts; attempt++) {
            const { data } = await api(`/api/premium/subscriptions/${encodeURIComponent(id)}?refresh=1`);
            if (data.is_active) { location.replace(`/donatur/premium/berhasil/${encodeURIComponent(id)}`); return true; }
            if (data.status === 'nonaktif') { pending = null; setMessage('Pembayaran gagal atau kedaluwarsa. Silakan lakukan pembayaran ulang.', 'error'); return false; }
            await wait(3000);
        }
        setMessage('Pembayaran belum terkonfirmasi. Tekan "Cek Status Pembayaran" setelah Anda menyelesaikan pembayaran.');
        checkButton.hidden = false;
        return false;
    }
    function openSnap() {
        window.snap.pay(pending.snapToken, {
            onSuccess: () => { setMessage('Pembayaran diterima, memverifikasi...'); waitUntilActive(pending.id).catch(error => setMessage(error.message, 'error')); },
            onPending: () => { setMessage('Menunggu pembayaran Anda. Status akan diperbarui setelah pembayaran selesai.'); checkButton.hidden = false; waitUntilActive(pending.id).catch(() => {}); },
            onError: () => { pending = null; setMessage('Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.', 'error'); },
            onClose: () => { setMessage('Halaman pembayaran ditutup. Tekan "Lanjutkan Pembayaran" untuk membayar.'); checkButton.hidden = false; },
        });
    }

    form.addEventListener('submit', async event => {
        event.preventDefault();
        setMessage('');
        const data = Object.fromEntries(fields.map(name => [name, input(name).value.trim()]));
        const empty = fields.filter(name => !data[name]);
        empty.forEach(name => setError(name, 'Kolom ini wajib diisi.'));
        if (empty.length) { input(empty[0]).focus(); return; }

        payButton.disabled = true; payButton.textContent = 'Memproses...';
        try {
            await loadSnap();
            if (!pending) {
                const { data: result } = await api('/api/premium/register', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
                pending = { id: result.subscription.id, snapToken: result.snap_token };
            }
            openSnap();
        } catch (error) {
            Object.entries(error.errors || {}).forEach(([name, messages]) => input(name) && setError(name, messages[0]));
            if (error.status === 409) { location.replace('/donatur/dashboard'); return; }
            setMessage(Object.keys(error.errors || {}).length ? 'Periksa kembali data yang ditandai.' : error.message, 'error');
        } finally { payButton.disabled = false; payButton.textContent = 'Lanjutkan Pembayaran'; }
    });
    checkButton.addEventListener('click', async () => {
        if (!pending) return;
        checkButton.disabled = true; setMessage('Memeriksa status pembayaran...');
        try { await waitUntilActive(pending.id, 1); } catch (error) { setMessage(error.message, 'error'); }
        finally { checkButton.disabled = false; }
    });

    Promise.all([api('/api/premium/status'), api('/api/me')]).then(([status, me]) => {
        if (status.is_premium) { location.replace('/donatur/dashboard'); return; }
        const pricing = status.pricing;
        form.querySelectorAll('[data-price]').forEach(node => { node.textContent = rupiah(pricing[node.dataset.price]); });
        document.getElementById('premium-tax-label').textContent = `Pajak (PPN ${Math.round(pricing.tax_rate * 100)}%)`;
        // Isi otomatis dari data perusahaan yang pernah dikirim sebelumnya.
        const company = status.data || {};
        input('nama_pic').value ||= company.nama_pic || '';
        input('jabatan').value ||= company.jabatan || '';
        input('nomor_pic').value ||= company.nomor_pic || me.user?.donor?.no_telp || '';
        input('email_korporat').value ||= company.email_korporat || me.user?.email || '';
        input('NPWP').value ||= company.NPWP || '';
    }).catch(error => setMessage(error.message, 'error'));
}

if (successPage) {
    const id = successPage.dataset.id;
    const status = document.getElementById('premium-success-status');
    const content = document.getElementById('premium-success-content');
    const pendingView = document.getElementById('premium-pending');
    let timer = null;

    async function check() {
        const { data } = await api(`/api/premium/subscriptions/${encodeURIComponent(id)}?refresh=1`);
        status.textContent = '';
        if (data.is_active) {
            clearInterval(timer);
            pendingView.hidden = true; content.hidden = false;
            document.getElementById('premium-duration').textContent = `${data.duration_days} Hari`;
            document.getElementById('premium-transaction').textContent = data.transaction_id;
            window.dispatchEvent(new CustomEvent('donor:premium-active'));
        } else {
            content.hidden = true; pendingView.hidden = false;
            timer ??= setInterval(() => { if (!document.hidden) check().catch(() => {}); }, 10000);
        }
    }
    document.getElementById('premium-recheck').addEventListener('click', event => {
        event.currentTarget.disabled = true;
        check().catch(error => { status.textContent = error.message; }).finally(() => { event.currentTarget.disabled = false; });
    });
    document.getElementById('premium-invoice').addEventListener('click', async event => {
        const note = document.getElementById('premium-invoice-message');
        note.textContent = '';
        try { await download(`/api/premium/subscriptions/${encodeURIComponent(id)}/invoice`, `invoice-premium-${document.getElementById('premium-transaction').textContent}.pdf`, event.currentTarget); }
        catch (error) { note.textContent = error.message; note.className = 'profile-message premium-success-note is-error'; }
    });
    check().catch(error => { status.textContent = error.status === 404 ? 'Transaksi premium tidak ditemukan.' : error.message; });
}

if (dashboardPage) {
    const status = document.getElementById('dashboard-status');
    const message = document.getElementById('dashboard-message');
    const content = document.getElementById('dashboard-content');
    const periodButton = document.getElementById('period-button');
    const periodList = document.getElementById('period-list');
    const chart = document.getElementById('trend-chart');
    const SVG = 'http://www.w3.org/2000/svg';
    const shortDate = value => new Date(value).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    // Ringkas untuk label sumbu/titik: Rp 1,5 jt, Rp 250 rb.
    const compact = value => {
        const n = Number(value || 0);
        if (n >= 1e9) return `Rp ${(n / 1e9).toLocaleString('id-ID', { maximumFractionDigits: 1 })} M`;
        if (n >= 1e6) return `Rp ${(n / 1e6).toLocaleString('id-ID', { maximumFractionDigits: 1 })} jt`;
        if (n >= 1e3) return `Rp ${(n / 1e3).toLocaleString('id-ID', { maximumFractionDigits: 0 })} rb`;
        return `Rp ${n}`;
    };
    let data = null;
    let metric = 'frekuensi';
    let currentPeriod = new URLSearchParams(location.search).get('period') || '';

    function el(tag, attrs = {}, text) {
        const node = tag.startsWith('svg:') ? document.createElementNS(SVG, tag.slice(4)) : document.createElement(tag);
        Object.entries(attrs).forEach(([key, value]) => node.setAttribute(key, value));
        if (text != null) node.textContent = text;
        return node;
    }
    // Batas atas sumbu Y yang dibulatkan agar garis bantu jatuh di angka rapi.
    function niceMax(value) {
        if (metric === 'frekuensi') return Math.max(5, Math.ceil(value / 5) * 5);
        if (value <= 0) return 1000000;
        const step = Math.pow(10, Math.floor(Math.log10(value / 4)));
        const nice = [1, 2, 2.5, 5, 10].map(m => m * step).find(m => m * 4 >= value);
        return nice * 4;
    }

    function renderChart() {
        chart.replaceChildren();
        const points = data.tren;
        const values = points.map(point => point[metric]);
        const format = metric === 'frekuensi' ? value => String(value) : compact;
        const W = 1000, H = 300, right = 20, top = 28, bottom = 32;
        const left = metric === 'frekuensi' ? 36 : 72;
        const max = niceMax(Math.max(...values));
        const x = index => left + (points.length === 1 ? 0 : index * (W - left - right) / (points.length - 1));
        const y = value => top + (1 - value / max) * (H - top - bottom);
        const svg = el('svg:svg', { viewBox: `0 0 ${W} ${H}`, role: 'img', 'aria-label': `Tren ${metric === 'frekuensi' ? 'frekuensi' : 'total'} donasi ${data.period.label}` });

        const grid = el('svg:g', { class: 'grid' });
        const axis = el('svg:g', { class: 'axis' });
        for (let i = 0; i <= 4; i++) {
            const value = max * i / 4;
            grid.append(el('svg:line', { x1: left, x2: W - right, y1: y(value), y2: y(value) }));
            axis.append(el('svg:text', { x: left - 10, y: y(value) + 4, 'text-anchor': 'end' }, format(value)));
        }
        points.forEach((point, index) => axis.append(el('svg:text', { x: x(index), y: H - 8, 'text-anchor': 'middle' }, point.bulan)));
        const line = points.map((point, index) => `${index ? 'L' : 'M'}${x(index)},${y(point[metric])}`).join(' ');
        const area = `${line} L${x(points.length - 1)},${y(0)} L${x(0)},${y(0)} Z`;
        svg.append(grid, el('svg:path', { d: area, fill: '#e3f6ec' }), el('svg:path', { d: line, fill: 'none', stroke: '#1f9d63', 'stroke-width': 2, 'stroke-linejoin': 'round' }), axis);

        const crosshair = el('svg:line', { class: 'crosshair', y1: top, y2: y(0), visibility: 'hidden' });
        svg.append(crosshair);
        points.forEach((point, index) => {
            svg.append(el('svg:circle', { cx: x(index), cy: y(point[metric]), r: 4.5, fill: '#fff', stroke: '#1f9d63', 'stroke-width': 2 }));
            svg.append(el('svg:text', { class: 'point-label', x: x(index), y: y(point[metric]) - 12, 'text-anchor': 'middle' }, format(point[metric])));
        });

        // Area hover selebar kolom bulan, jauh lebih besar dari titiknya.
        const tooltip = el('div', { class: 'dashboard-tooltip' });
        tooltip.hidden = true;
        const column = (W - left - right) / Math.max(points.length - 1, 1);
        const hide = () => { crosshair.setAttribute('visibility', 'hidden'); tooltip.hidden = true; };
        points.forEach((point, index) => {
            const hit = el('svg:rect', { class: 'hit', x: x(index) - column / 2, y: top, width: column, height: y(0) - top, tabindex: 0, 'aria-label': `${point.bulan}: ${point.frekuensi} donasi, ${rupiahShort(point.total)}` });
            const show = () => {
                crosshair.setAttribute('x1', x(index));
                crosshair.setAttribute('x2', x(index));
                crosshair.setAttribute('visibility', 'visible');
                const [year, month] = point.periode.split('-');
                const freq = el('p', {}, 'Frekuensi ');
                freq.append(el('b', {}, `${point.frekuensi} donasi`));
                const total = el('p', {}, 'Total ');
                total.append(el('b', {}, rupiahShort(point.total)));
                tooltip.replaceChildren(el('strong', {}, new Date(year, month - 1).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' })), freq, total);
                const box = svg.getBoundingClientRect();
                const scale = box.width / W;
                tooltip.style.left = `${Math.min(Math.max(x(index) * scale, 85), box.width - 85)}px`;
                tooltip.style.top = `${y(point[metric]) * scale}px`;
                tooltip.hidden = false;
            };
            hit.addEventListener('pointerenter', show);
            hit.addEventListener('focus', show);
            hit.addEventListener('pointerleave', hide);
            hit.addEventListener('blur', hide);
            svg.append(hit);
        });
        chart.append(svg, tooltip);
        if (values.every(value => value === 0)) chart.append(el('p', { class: 'dashboard-empty' }, 'Belum ada donasi yang dibayar pada periode ini.'));

        document.querySelector('#trend-table tbody').replaceChildren(...points.map(point => {
            const row = el('tr');
            row.append(el('td', {}, point.bulan), el('td', {}, String(point.frekuensi)), el('td', {}, rupiahShort(point.total)));
            return row;
        }));
    }

    const STATUS_LABELS = { belum_bayar: 'Belum Dibayar', sudah_bayar: 'Sudah Dibayar', sudah_disalurkan: 'Sudah Disalurkan' };
    const STATUS_ICONS = {
        belum_bayar: '<circle cx="12" cy="12" r="10" fill="currentColor"/><path d="M12 7v5l3 2" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round"/>',
        sudah_bayar: '<circle cx="12" cy="12" r="10" fill="currentColor"/><path d="m8 12 3 3 5-6" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>',
        sudah_disalurkan: '<rect x="3" y="4" width="18" height="17" rx="3" fill="currentColor"/><path d="M8 10h8M8 14h8M12 7v11" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/>',
    };
    function showError(error) {
        message.textContent = error.message;
        message.className = 'profile-message dashboard-message is-error';
    }
    function renderTable() {
        const body = document.getElementById('recent-body');
        body.replaceChildren();
        if (!data.riwayat.length) {
            const row = el('tr');
            row.append(el('td', { colspan: 5, class: 'dashboard-empty' }, 'Belum ada donasi pada periode ini.'));
            body.append(row);
            return;
        }
        data.riwayat.forEach(item => {
            const row = el('tr');
            const campaign = el('td');
            campaign.append(el('strong', {}, item.kampanye), el('small', {}, item.organisasi));
            const badge = el('span', { class: `dashboard-badge ${item.status}` });
            badge.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">${STATUS_ICONS[item.status]}</svg>`;
            badge.append(STATUS_LABELS[item.status]);
            const statusCell = el('td');
            statusCell.append(badge);
            const actions = el('td', { class: 'actions' });
            if (item.status === 'belum_bayar') {
                actions.append(el('a', { href: `/donatur/pembayaran/${encodeURIComponent(item.id)}` }, 'Bayar'));
            } else {
                const receipt = el('button', { type: 'button' }, 'Bukti Bayar');
                receipt.addEventListener('click', () => {
                    message.textContent = '';
                    download(`/api/donors/donations/${encodeURIComponent(item.id)}/receipt`, `bukti-pembayaran-${item.id}.pdf`, receipt).catch(showError);
                });
                actions.append(receipt);
                if (item.status === 'sudah_disalurkan') actions.append(el('a', { href: `/donatur/penyaluran/${encodeURIComponent(item.id)}` }, 'Bukti Penyaluran'));
            }
            row.append(campaign, el('td', {}, shortDate(item.tanggal)), el('td', { class: 'amount' }, rupiahShort(item.nominal)), statusCell, actions);
            body.append(row);
        });
    }

    function renderStats() {
        const stats = data.stats;
        document.getElementById('stat-total').textContent = rupiahShort(stats.total_donasi);
        document.getElementById('stat-campaigns').textContent = stats.jumlah_kampanye;
        document.getElementById('stat-organizations').textContent = stats.jumlah_organisasi;
        document.getElementById('stat-beneficiaries').textContent = stats.penerima_manfaat.toLocaleString('id-ID');
        const growth = document.getElementById('stat-growth');
        growth.hidden = stats.pertumbuhan_persen === null;
        if (stats.pertumbuhan_persen === null) return;
        const up = stats.pertumbuhan_persen >= 0;
        growth.className = 'dashboard-growth' + (up ? '' : ' is-down');
        growth.title = 'Dibanding periode sebelumnya';
        growth.innerHTML = `${up ? '+' : ''}${stats.pertumbuhan_persen.toLocaleString('id-ID')}% <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="${up ? 'M4 17l6-6 4 4 6-7M14 8h6v6' : 'M4 7l6 6 4-4 6 7M14 16h6v-6'}"/></svg>`;
    }

    function renderPeriods() {
        document.getElementById('period-label').textContent = data.period.label;
        periodList.replaceChildren(...data.periods.map(period => {
            const option = el('li', { role: 'option', id: `period-${period.key}`, 'aria-selected': String(period.key === data.period.key), 'data-key': period.key }, period.label);
            option.addEventListener('click', () => choosePeriod(period.key));
            return option;
        }));
        periodButton.disabled = false;
    }
    function setActive(option) {
        periodList.querySelectorAll('.is-active').forEach(node => node.classList.remove('is-active'));
        if (!option) return;
        option.classList.add('is-active');
        option.scrollIntoView({ block: 'nearest' });
        periodList.setAttribute('aria-activedescendant', option.id);
    }
    function togglePeriods(open) {
        periodList.hidden = !open;
        periodButton.setAttribute('aria-expanded', String(open));
        if (open) {
            setActive(periodList.querySelector('[aria-selected="true"]') || periodList.firstElementChild);
            periodList.focus();
        }
    }
    function choosePeriod(key) {
        togglePeriods(false);
        periodButton.focus();
        if (key !== data.period.key) load(key);
    }
    periodButton.addEventListener('click', () => togglePeriods(periodList.hidden));
    periodList.addEventListener('keydown', event => {
        const options = [...periodList.children];
        const active = periodList.querySelector('.is-active');
        const index = options.indexOf(active);
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            event.preventDefault();
            setActive(options[Math.min(Math.max(index + (event.key === 'ArrowDown' ? 1 : -1), 0), options.length - 1)]);
        } else if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            if (active) choosePeriod(active.dataset.key);
        } else if (event.key === 'Escape') {
            togglePeriods(false);
            periodButton.focus();
        } else if (event.key === 'Tab') {
            togglePeriods(false);
        }
    });
    document.addEventListener('click', event => { if (!event.target.closest('.dashboard-period')) togglePeriods(false); });

    document.querySelectorAll('[data-metric]').forEach(button => button.addEventListener('click', () => {
        metric = button.dataset.metric;
        document.querySelectorAll('[data-metric]').forEach(item => item.setAttribute('aria-pressed', String(item === button)));
        if (data) renderChart();
    }));
    // Export membuka pratinjau laporan dulu; PDF diunduh dari halaman pratinjau.
    document.getElementById('dashboard-export').addEventListener('click', () => {
        const key = data?.period.key || currentPeriod;
        location.assign(`/donatur/dashboard/laporan${key ? `?period=${encodeURIComponent(key)}` : ''}`);
    });
    let resizeFrame;
    window.addEventListener('resize', () => {
        cancelAnimationFrame(resizeFrame);
        resizeFrame = requestAnimationFrame(() => { if (data) renderChart(); });
    });

    async function load(period = currentPeriod) {
        if (!data) status.textContent = 'Memuat dashboard...';
        content.setAttribute('aria-busy', 'true');
        try {
            const result = await api(`/api/premium/dashboard${period ? `?period=${encodeURIComponent(period)}` : ''}`);
            data = result.data;
            currentPeriod = data.period.key;
            const url = new URL(location.href);
            url.searchParams.set('period', currentPeriod);
            history.replaceState(null, '', url);
            status.textContent = '';
            content.hidden = false;
            renderPeriods();
            renderStats();
            renderChart();
            renderTable();
        } catch (error) {
            status.textContent = error.status === 403 ? 'Dashboard hanya tersedia untuk perusahaan dengan akun premium aktif.' : error.message;
        } finally {
            content.removeAttribute('aria-busy');
        }
    }
    load();
}

const reportPage = document.getElementById('report-preview-page');
if (reportPage) {
    const period = new URLSearchParams(location.search).get('period') || '';
    const status = document.getElementById('report-status');
    const message = document.getElementById('report-message');
    const downloadButton = document.getElementById('report-download');
    const query = period ? `?period=${encodeURIComponent(period)}` : '';
    const node = (tag, text, className) => {
        const element = document.createElement(tag);
        if (text != null) element.textContent = text;
        if (className) element.className = className;
        return element;
    };
    const STATUS = {
        sudah_bayar: ['Sudah Dibayar', '<circle cx="12" cy="12" r="10" fill="currentColor"/><path d="m8 12 3 3 5-6" stroke="#fff" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>'],
        sudah_disalurkan: ['Sudah Disalurkan', '<rect x="3" y="4" width="18" height="17" rx="3" fill="currentColor"/><path d="M8 10h8M8 14h8M12 7v11" stroke="#fff" stroke-width="1.8" stroke-linecap="round"/>'],
    };
    document.getElementById('report-back').href = `/donatur/dashboard${query}`;

    function render(report) {
        const stats = report.stats;
        document.getElementById('report-period').textContent = report.period.label;
        document.getElementById('report-company-name').textContent = report.company.nama;
        const logo = document.getElementById('report-company-logo');
        if (report.company.logo) { logo.src = report.company.logo; logo.hidden = false; }
        document.getElementById('report-total').textContent = rupiahShort(stats.total_donasi);
        document.getElementById('report-distributed').textContent = rupiahShort(stats.total_tersalurkan);
        document.getElementById('report-supported').textContent = `${stats.kampanye_didukung} Didukung`;
        document.getElementById('report-campaigns-distributed').textContent = `${stats.kampanye_tersalurkan} Tersalurkan`;
        document.getElementById('report-beneficiaries').textContent = stats.penerima_manfaat.toLocaleString('id-ID');
        const growth = document.getElementById('report-growth');
        if (stats.pertumbuhan_persen !== null) {
            const up = stats.pertumbuhan_persen >= 0;
            growth.className = 'dashboard-growth' + (up ? '' : ' is-down');
            growth.textContent = `${up ? '+' : ''}${stats.pertumbuhan_persen.toLocaleString('id-ID')}%`;
            growth.title = 'Dibanding periode sebelumnya';
            growth.hidden = false;
        }

        const rows = document.getElementById('report-rows');
        rows.replaceChildren();
        if (!report.donasi.length) {
            const row = node('tr'); const cell = node('td', 'Belum ada donasi yang dibayar pada periode ini.', 'dashboard-empty');
            cell.colSpan = 4; row.append(cell); rows.append(row);
        }
        report.donasi.forEach(item => {
            const row = node('tr');
            const campaign = node('td'); campaign.append(node('strong', item.kampanye), node('small', item.organisasi));
            const [label, icon] = STATUS[item.status];
            const badge = node('span', null, `dashboard-badge ${item.status}`);
            badge.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">${icon}</svg>`;
            badge.append(label);
            const statusCell = node('td'); statusCell.append(badge);
            const date = item.tanggal ? new Date(item.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
            row.append(campaign, node('td', date), node('td', rupiahShort(item.nominal), 'amount'), statusCell);
            rows.append(row);
        });

        const gallery = document.getElementById('report-gallery');
        gallery.replaceChildren();
        if (!report.dokumentasi.length) gallery.append(node('p', 'Belum ada dokumentasi penyaluran yang disetujui pada periode ini.', 'report-empty'));
        report.dokumentasi.forEach(item => {
            const figure = node('figure');
            const image = node('img'); image.src = item.url; image.alt = item.judul; image.loading = 'lazy';
            const caption = node('figcaption', item.judul); caption.append(node('span', item.organisasi));
            figure.append(image, caption); gallery.append(figure);
        });

        // Kalimat kesimpulan; angka-angka penting ditebalkan seperti di PDF.
        const conclusion = document.getElementById('report-conclusion');
        const strong = text => node('strong', text);
        conclusion.replaceChildren(
            'Selama periode pelaporan, perusahaan telah memberikan kontribusi sosial melalui dukungan terhadap berbagai campaign di Rangkul. Dari kontribusi tersebut, sebanyak ',
            strong(`${rupiahShort(stats.total_donasi)} telah diberikan`), ' dengan ',
            strong(`${rupiahShort(stats.total_tersalurkan)} telah tersalurkan`), ' melalui ',
            strong(`${stats.kampanye_didukung} campaign yang didukung`), ', dengan ',
            strong(`${stats.kampanye_tersalurkan} campaign telah tersalurkan`), ' dan memberikan manfaat kepada ',
            strong(`${stats.penerima_manfaat.toLocaleString('id-ID')} penerima manfaat`),
            '. Kontribusi ini menjadi bagian dari upaya perusahaan dalam menciptakan dampak sosial yang berkelanjutan.',
        );
    }

    downloadButton.addEventListener('click', () => {
        message.textContent = '';
        download(`/api/premium/dashboard/export${query}`, `laporan-dampak-csr-${period || 'periode'}.pdf`, downloadButton).catch(error => {
            message.textContent = error.message;
            message.className = 'profile-message dashboard-message is-error';
        });
    });

    api(`/api/premium/dashboard/report${query}`).then(({ data }) => {
        render(data);
        status.textContent = '';
        document.getElementById('report-sheet').hidden = false;
        downloadButton.disabled = false;
    }).catch(error => {
        status.textContent = error.status === 403 ? 'Laporan hanya tersedia untuk perusahaan dengan akun premium aktif.' : error.message;
    });
}
