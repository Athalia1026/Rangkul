const notifications = document.getElementById('notifications-page');
const distribution = document.getElementById('distribution-page');
const rupiah = value => 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(value || 0));
const fullDate = value => value ? new Date(value).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : 'Belum tersedia';
function node(tag, text, className) {
    const element = document.createElement(tag);
    if (text != null) element.textContent = text;
    if (className) element.className = className;
    return element;
}
async function api(path) {
    const token = localStorage.getItem('auth_token');
    if (!token) { location.replace('/login'); throw new Error('Silakan masuk dengan akun donatur.'); }
    const response = await fetch(path, { headers: { Accept: 'application/json', Authorization: `Bearer ${token}` } });
    if (response.status === 401) location.replace('/login');
    if (!response.ok) throw new Error(response.status === 404 ? 'Data penyaluran tidak ditemukan untuk donasi Anda.' : 'Data belum dapat dimuat. Silakan muat ulang halaman.');
    return response.json();
}
function icon(type) {
    const span = node('span', null, `notification-icon ${type}`);
    span.setAttribute('aria-hidden', 'true');
    const paths = { donation: '<circle cx="12" cy="12" r="9"/><path d="m7 12 3 3 7-7"/>', visit: '<rect x="4" y="5" width="16" height="16" rx="2"/><path d="M8 3v5m8-5v5M4 11h16"/>', campaign: '<path d="m4 10 13-5v14L4 14zm3 5 2 6m11-12 2-2m-2 8 2 2"/>' };
    span.innerHTML = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">${paths[type]}</svg>`;
    return span;
}
if (notifications) {
    const list = document.getElementById('notification-list');
    const message = document.getElementById('updates-message');
    const readAllButton = document.getElementById('notification-read-all');
    const moreButton = document.getElementById('notification-more');
    const types = { donation: 'donation', distribution: 'donation', visit: 'visit', subscription: 'campaign' };
    let items = [], nextPage = null, unread = 0;

    async function send(path, method) {
        const token = localStorage.getItem('auth_token');
        if (!token) { location.replace('/login'); throw new Error('Silakan masuk kembali.'); }
        const response = await fetch(path, { method, headers: { Accept: 'application/json', Authorization: `Bearer ${token}` } });
        if (response.status === 401) location.replace('/login');
        if (!response.ok) throw new Error('Notifikasi belum dapat diperbarui.');
        return response.json();
    }
    // Tujuan klik berdasarkan jenis referensi notifikasi dari backend.
    function target(item) {
        const id = encodeURIComponent(item.reference_id || '');
        if (item.reference_type === 'distribution') return `/donatur/penyaluran/${id}`;
        if (item.reference_type === 'visit') return `/donatur/kunjungan/${id}`;
        if (item.reference_type === 'donation') return '/donatur/riwayat';
        return null;
    }
    function setUnread(count) {
        unread = Math.max(0, count);
        readAllButton.hidden = unread === 0;
        window.dispatchEvent(new CustomEvent('donor:notifications-unread', { detail: unread }));
    }
    async function markRead(item) {
        if (item.is_read) return;
        item.is_read = true; setUnread(unread - 1);
        try { await send(`/api/notifications/${encodeURIComponent(item.id)}/read`, 'PATCH'); } catch (_) { /* status baca tidak menghalangi navigasi */ }
    }
    function render() {
        list.replaceChildren();
        const groups = new Map();
        const today = new Date(), yesterday = new Date(); yesterday.setDate(yesterday.getDate() - 1);
        items.forEach(item => {
            const date = new Date(item.created_at);
            const label = date.toDateString() === today.toDateString() ? 'Hari Ini' : date.toDateString() === yesterday.toDateString() ? 'Kemarin' : fullDate(item.created_at);
            if (!groups.has(label)) { const section = node('section', null, 'notification-group'); section.append(node('h2', label)); list.append(section); groups.set(label, section); }
            const href = target(item);
            const entry = node(href ? 'a' : 'button', null, 'notification-item' + (item.is_read ? '' : ' is-unread'));
            if (href) entry.href = href; else entry.type = 'button';
            const copy = node('div', null, 'notification-copy'); copy.append(node('h3', item.judul), node('p', item.deskripsi));
            const time = node('time', date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })); time.dateTime = date.toISOString();
            if (!item.is_read) entry.append(node('span', 'Belum dibaca', 'sr-only'));
            entry.append(icon(types[item.reference_type] || 'campaign'), copy, time);
            entry.addEventListener('click', async event => {
                if (item.is_read) return;
                if (!href) { await markRead(item); render(); return; }
                event.preventDefault();
                await markRead(item);
                location.assign(href);
            });
            groups.get(label).append(entry);
        });
        if (!items.length) list.append(node('p', 'Belum ada notifikasi. Perkembangan donasi dan kunjungan Anda akan tampil di sini.', 'updates-empty'));
        moreButton.hidden = !nextPage;
    }
    async function load(page = 1) {
        const result = await api(`/api/notifications?per_page=20&page=${page}`);
        const pageData = result.data;
        items = page === 1 ? pageData.data : items.concat(pageData.data);
        nextPage = pageData.current_page < pageData.last_page ? pageData.current_page + 1 : null;
        setUnread(result.unread_count);
        message.textContent = '';
        render();
    }
    readAllButton.onclick = async () => {
        readAllButton.disabled = true;
        try {
            await send('/api/notifications/read-all', 'PATCH');
            items.forEach(item => { item.is_read = true; });
            setUnread(0); render();
        } catch (error) { message.textContent = error.message; }
        finally { readAllButton.disabled = false; }
    };
    moreButton.onclick = async () => {
        moreButton.disabled = true; moreButton.textContent = 'Memuat...';
        try { await load(nextPage); } catch (error) { message.textContent = error.message; }
        finally { moreButton.disabled = false; moreButton.textContent = 'Muat notifikasi sebelumnya'; }
    };
    load().catch(error => { message.textContent = error.message; });
}
if (distribution) {
    const content = document.getElementById('distribution-content');
    const message = document.getElementById('updates-message');
    const previewButton = document.getElementById('distribution-preview');
    let actual = null;
    function render(data, preview = false) {
        content.replaceChildren();
        message.textContent = preview ? 'Preview desain — informasi dan dokumentasi berikut hanya contoh, bukan laporan transaksi Anda.' : '';
        const heading = node('header', null, 'distribution-heading'); heading.append(node('h1', data.campaign || 'Detail Penyaluran'), node('span', preview ? 'Contoh Penyaluran' : data.reports.length ? 'Sudah Disalurkan' : 'Menunggu Penyaluran', 'distribution-badge')); content.append(heading);
        if (!data.reports.length) content.append(node('p', 'Panti belum memiliki bukti penyaluran yang disetujui untuk kampanye ini. Laporan penggunaan dana akan muncul setelah tersedia.', 'updates-empty'));
        data.reports.forEach(report => {
            const section = node('section', null, 'distribution-report'), summary = node('dl', null, 'distribution-summary');
            [['Tanggal Penyaluran', fullDate(report.date)], ['Panti Asuhan', data.organization || '—'], ['Total Penggunaan', rupiah(report.total)]].forEach(([label, value]) => { const cell = node('div'); cell.append(node('dt', label), node('dd', value)); summary.append(cell); });
            section.append(summary, node('h2', 'Deskripsi Penyaluran'), node('p', report.description || 'Rincian penggunaan dana tercantum pada dokumentasi di bawah.', 'distribution-description'));
            if (report.needs) { const needs = node('ul', null, 'distribution-needs'); report.needs.forEach(text => needs.append(node('li', text))); section.append(needs); }
            section.append(node('h2', 'Dokumentasi Penyaluran'));
            const gallery = node('div', null, 'distribution-gallery');
            report.proofs.forEach(proof => {
                const url = new URL(proof.url, location.origin); if (!['https:', 'http:'].includes(url.protocol)) return;
                const link = node('a'); link.href = url.href; link.target = '_blank'; link.rel = 'noopener noreferrer';
                const figure = node('figure');
                if (/\.(png|jpe?g|webp|gif|svg)$/i.test(url.pathname)) {
                    const image = node('img'); image.src = url.href; image.alt = proof.description || 'Dokumentasi penyaluran'; image.loading = 'lazy'; figure.append(image);
                } else figure.append(node('span', 'Buka Dokumen ↗', 'document-file'));
                figure.append(node('figcaption', proof.description || 'Lihat bukti penyaluran')); link.append(figure); gallery.append(link);
            });
            section.append(gallery); content.append(section);
        });
        if (!preview && data.reports.length) content.append(node('p', 'Total penggunaan merupakan laporan dana kampanye, bukan nominal donasi pribadi Anda.', 'checkout-help'));
    }
    function showPreview() {
        render({ campaign: actual?.campaign || 'Bantuan Kebutuhan Pangan Anak Panti', organization: actual?.organization || 'Panti Asuhan Kasih Bunda', reports: [{ date: new Date().toISOString(), total: 5000000, description: 'Dana donasi digunakan untuk memenuhi kebutuhan konsumsi harian 50 anak selama satu bulan. Penyaluran dilakukan dengan membeli berbagai kebutuhan pangan bergizi, meliputi:', needs: ['Beras (50 Kg)', 'Ikan (15 Kg)', 'Susu (48 Kotak)', 'Telur Ayam (300 Butir)', 'Sayuran Segar (30 Kg)', 'Minyak (10 Liter)', 'Daging Ayam (20 Kg)', 'Buah-buahan (20 Kg)', 'Bumbu Dapur (1 Paket)'], proofs: ['beras', 'ikan', 'telur'].map(name => ({ url: `/images/demo-${name}.svg`, description: `Ilustrasi ${name} — contoh dokumentasi` })) }] }, true);
        previewButton.hidden = true;
    }
    previewButton.onclick = showPreview;
    if (distribution.dataset.id === 'preview') showPreview();
    else api(`/api/donors/distributions/${encodeURIComponent(distribution.dataset.id)}`).then(data => { actual = data; render(data); previewButton.hidden = data.reports.length > 0; }).catch(error => { message.textContent = error.message; });
}
