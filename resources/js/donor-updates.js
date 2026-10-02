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
    let realItems = [], preview = false;
    function render(items) {
        list.replaceChildren();
        const groups = new Map();
        const today = new Date(), yesterday = new Date(); yesterday.setDate(yesterday.getDate() - 1);
        items.sort((a, b) => new Date(b.date) - new Date(a.date)).forEach(item => {
            const date = new Date(item.date);
            const label = date.toDateString() === today.toDateString() ? 'Hari Ini' : date.toDateString() === yesterday.toDateString() ? 'Kemarin' : fullDate(item.date);
            if (!groups.has(label)) { const section = node('section', null, 'notification-group'); section.append(node('h2', label)); list.append(section); groups.set(label, section); }
            const link = node('a', null, 'notification-item'); link.href = item.href;
            const copy = node('div', null, 'notification-copy'); copy.append(node('h3', item.title), node('p', item.description));
            const time = node('time', date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })); time.dateTime = date.toISOString();
            link.append(icon(item.type), copy, time); groups.get(label).append(link);
        });
        if (!items.length) list.append(node('p', 'Belum ada notifikasi. Perkembangan donasi dan kunjungan Anda akan tampil di sini.', 'updates-empty'));
    }
    document.getElementById('notification-preview').onclick = event => {
        preview = !preview;
        event.currentTarget.textContent = preview ? 'Kembali ke notifikasi saya' : 'Lihat contoh tampilan';
        message.textContent = preview ? 'Preview desain — notifikasi berikut adalah contoh.' : '';
        const today = new Date(); today.setHours(10, 0, 0, 0);
        const morning = new Date(today); morning.setHours(8);
        const yesterday = new Date(today); yesterday.setDate(yesterday.getDate() - 1); yesterday.setHours(18);
        render(preview ? [
            { type: 'donation', title: 'Donasi Berhasil Disalurkan', description: 'Donasi Anda sebesar Rp 100.000 untuk kampanye “Bantuan Kebutuhan Pangan Anak Panti” telah berhasil disalurkan.', date: today, href: '/donatur/penyaluran/preview' },
            { type: 'visit', title: 'Jadwal Kunjungan Dikonfirmasi', description: 'Jadwal kunjungan Anda ke Panti Asuhan Kasih Bunda telah dikonfirmasi.', date: morning, href: '/donatur/riwayat' },
            { type: 'campaign', title: 'Kampanye Baru Tersedia', description: 'Ada kampanye yang mungkin Anda minati: “Renovasi Ruang Belajar yang Layak”.', date: yesterday, href: '/donatur/cari' },
        ] : realItems);
    };
    api('/api/donors/activities').then(({ data }) => {
        realItems = data.riwayat_donasi.filter(item => item.status_asli === 'sudah_bayar').map(item => ({ type: 'donation', title: item.display_status === 'sudah_disalurkan' ? 'Laporan Penyaluran Tersedia' : 'Donasi Berhasil', description: `${rupiah(item.amount)} untuk kampanye “${item.campaign_name}”. ${item.display_status === 'sudah_disalurkan' ? 'Lihat laporan penggunaan dana kampanye dari panti.' : 'Terima kasih atas kebaikan Anda.'}`, date: item.distributed_at || item.paid_at || item.created_at, href: item.display_status === 'sudah_disalurkan' ? `/donatur/penyaluran/${encodeURIComponent(item.id)}` : '/donatur/riwayat' }));
        data.riwayat_kunjungan.forEach(item => realItems.push({ type: 'visit', title: { terkirim: 'Pengajuan Kunjungan Terkirim', dikonfirmasi: 'Jadwal Kunjungan Dikonfirmasi', ditolak: 'Pengajuan Kunjungan Ditolak', selesai: 'Kunjungan Selesai' }[item.status] || 'Informasi Kunjungan', description: `${item.organization_name} — jadwal ${fullDate(item.date)}, ${(item.time || '').slice(0, 5)}. ${item.response || ''}`, date: item.updated_at, href: '/donatur/riwayat' }));
        if (!preview) { message.textContent = ''; render(realItems); }
    }).catch(error => { if (!preview) message.textContent = error.message; });
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
