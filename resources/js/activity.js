const page = document.getElementById('activity-page');
if (page) {
    const money = value => 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(value || 0));
    const date = value => value ? new Date(value).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '—';
    const labels = { belum_bayar: 'Belum Dibayar', sudah_bayar: 'Sudah Dibayar', sudah_disalurkan: 'Sudah Disalurkan', terkirim: 'Terkirim', dikonfirmasi: 'Dikonfirmasi', ditolak: 'Ditolak', selesai: 'Selesai' };
    const filters = { donations: 'all', visits: 'all' };
    let data = null;
    const dialog = document.getElementById('activity-dialog');
    const content = document.getElementById('activity-dialog-content');
    let previousOverflow;
    function el(tag, text, className) {
        const node = document.createElement(tag);
        if (text !== null && text !== undefined) node.textContent = text;
        if (className) node.className = className;
        return node;
    }
    function action(text, callback, primary = false) {
        const button = el('button', text, 'activity-action' + (primary ? ' primary' : ''));
        button.type = 'button'; button.addEventListener('click', callback); return button;
    }
    function open(title) {
        document.getElementById('activity-dialog-title').textContent = title;
        content.replaceChildren();
        previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden'; dialog.showModal();
    }
    dialog.querySelector('.panti-dialog-close').onclick = () => dialog.close();
    dialog.addEventListener('close', () => { document.body.style.overflow = previousOverflow; });
    dialog.addEventListener('click', event => {
        const r = dialog.getBoundingClientRect();
        if (event.target === dialog && (event.clientX < r.left || event.clientX > r.right || event.clientY < r.top || event.clientY > r.bottom)) dialog.close();
    });
    async function api(path, options = {}) {
        const token = localStorage.getItem('auth_token');
        if (!token) { window.location.replace('/login'); throw new Error('Silakan masuk kembali.'); }
        const response = await fetch(path, { ...options, headers: { Accept: 'application/json', Authorization: `Bearer ${token}`, ...options.headers } });
        if (response.status === 401) { window.location.replace('/login'); throw new Error('Sesi berakhir.'); }
        const result = await response.json();
        if (!response.ok) throw new Error(Object.values(result.errors || {}).flat().join(' ') || result.message || 'Data belum dapat diproses.');
        return result;
    }
    function fileLink(url, text) {
        const link = el('a', text);
        const parsed = new URL(url, location.origin);
        if (!['http:', 'https:'].includes(parsed.protocol)) return el('p', 'Berkas tidak tersedia.');
        link.href = parsed.href; link.target = '_blank'; link.rel = 'noopener noreferrer'; return link;
    }
    function receipt(item) {
        const lines = ['RANGKUL — RINGKASAN DONASI', `ID: ${item.invoice_id}`, `Kampanye: ${item.campaign_name}`, `Organisasi: ${item.organization_name}`, `Tanggal: ${date(item.created_at)}`, `Nominal donasi: ${money(item.amount)}`, `Status: ${labels[item.display_status] || item.display_status}`, '', 'Ringkasan berdasarkan riwayat akun Rangkul. Bukan kuitansi dari penyedia pembayaran.'];
        const url = URL.createObjectURL(new Blob([lines.join('\r\n')], { type: 'text/plain;charset=utf-8' }));
        const link = el('a'); link.href = url; link.download = `ringkasan-donasi-${item.id}.txt`; link.click();
        setTimeout(() => URL.revokeObjectURL(url), 1000);
    }
    function visitDetails(item) {
        open('Detail Kunjungan');
        content.append(el('p', item.organization_name, 'font-bold'), el('p', `${date(item.date)} • ${(item.time || '').slice(0, 5)} • ${item.jumlah_orang} orang`), el('p', `Status: ${labels[item.status] || item.status}`));
        if (item.message) content.append(el('p', `Pesan Anda: ${item.message}`));
        if (item.response) content.append(el('p', `Pesan panti: ${item.response}`));
        (item.documents || []).forEach((url, index) => content.append(fileLink(url, `Lihat dokumentasi ${index + 1}`)));
    }
    function visitForm(item, upload = false) {
        open(upload ? 'Upload Dokumentasi' : 'Edit Kunjungan');
        content.append(el('p', item.organization_name));
        const form = el('form');
        function field(title, name, type, value) {
            const label = el('label', title), input = el('input');
            input.name = name; input.type = type; input.required = true;
            if (value !== undefined) input.value = value;
            label.append(input); form.append(label); return input;
        }
        if (upload) {
            content.append(el('p', 'Unggah foto JPG atau PNG, maksimal 2 MB per foto. Kunjungan akan ditandai selesai setelah dokumentasi dikirim.'));
            const input = field('Foto kegiatan', 'dokumentasi[]', 'file'); input.accept = 'image/jpeg,image/png'; input.multiple = true;
        } else {
            field('Tanggal', 'tanggal_kunjungan', 'date', item.date).min = page.dataset.today;
            field('Waktu', 'waktu_kunjungan', 'time', (item.time || '').slice(0, 5));
            field('Jumlah pengunjung', 'pengunjung', 'number', item.jumlah_orang).min = 1;
            const input = field('Pesan untuk panti (opsional)', 'pesan_donatur', 'text', item.message || ''); input.required = false; input.maxLength = 255;
        }
        const status = el('p'); status.setAttribute('role', 'status');
        const submit = el('button', upload ? 'Kirim Dokumentasi' : 'Simpan Perubahan', 'panti-primary'); submit.type = 'submit';
        form.append(status, submit); content.append(form);
        form.onsubmit = async event => {
            event.preventDefault(); submit.disabled = true; status.textContent = 'Menyimpan...';
            try {
                const body = new FormData(form);
                await api(`/api/visits/${encodeURIComponent(item.id)}${upload ? '/documentation' : ''}`, upload ? { method: 'POST', body } : { method: 'PUT', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(Object.fromEntries(body)) });
                dialog.close(); await load();
            } catch (error) { status.textContent = error.message; submit.disabled = false; }
        };
    }
    function card(item, visit) {
        const card = el('article', null, 'activity-card');
        const image = el('img', null, 'activity-cover'); image.src = (visit ? item.image : item.campaign_image) || '/images/hero/hero_children.jpg'; image.alt = visit ? item.organization_name : item.campaign_name; image.loading = 'lazy';
        image.onerror = () => { image.onerror = null; image.src = '/images/hero/hero_children.jpg'; };
        const body = el('div', null, 'activity-content'), top = el('div', null, 'activity-top'), heading = el('div');
        const title = el(visit ? 'a' : 'h3', visit ? item.organization_name : item.campaign_name, 'activity-title');
        if (visit) title.href = `/donatur/panti/${encodeURIComponent(item.organization_id)}`;
        heading.append(title, el('p', visit ? item.lokasi : item.organization_name, 'activity-org'));
        if (!visit) heading.append(el('p', `ID: ${item.invoice_id} • ${date(item.created_at)}`, 'activity-meta'));
        const status = visit ? item.status : item.display_status;
        const badge = el('span', labels[status] || status.replaceAll('_', ' '), 'activity-badge'); badge.dataset.status = status;
        top.append(heading, badge); body.append(top);
        const bottom = el('div', null, 'activity-bottom'), info = el('div'), actions = el('div', null, 'activity-actions');
        if (visit) {
            info.className = 'activity-visit-stats';
            const time = el('div'), count = el('div');
            time.append(el('span', 'Tanggal & Waktu', 'activity-label'), el('p', `${date(item.date)}, ${(item.time || '').slice(0, 5)}`));
            count.append(el('span', 'Jumlah Orang', 'activity-label'), el('p', `${item.jumlah_orang} Orang`)); info.append(time, count);
            actions.append(action('Lihat Detail', () => location.assign(`/donatur/kunjungan/${encodeURIComponent(item.id)}`)));
            if (status === 'terkirim') actions.append(action('Edit Kunjungan', () => location.assign(`/donatur/kunjungan/${encodeURIComponent(item.id)}/edit`)));
            if (status === 'dikonfirmasi') actions.append(action('Upload Dokumentasi', () => location.assign(`/donatur/kunjungan/${encodeURIComponent(item.id)}#dokumentasi`)));
        } else {
            info.append(el('span', 'Nominal Donasi', 'activity-label'), el('p', money(item.amount), 'activity-amount'));
            if (item.status_asli === 'sudah_bayar') actions.append(action('Unduh Ringkasan Donasi', () => receipt(item)));
            else actions.append(action('Lanjutkan Pembayaran', () => { location.assign(`/donatur/pembayaran/${encodeURIComponent(item.id)}`); }));
            if (item.status_asli === 'sudah_bayar') actions.append(action('Lihat Bukti Penyaluran', () => { location.assign(`/donatur/penyaluran/${encodeURIComponent(item.id)}`); }, true));
        }
        bottom.append(info, actions); body.append(bottom); card.append(image, body); return card;
    }
    function render() {
        if (!data) return;
        for (const [kind, listId, records] of [['donations', 'activity-donations', data.riwayat_donasi], ['visits', 'activity-visit-list', data.riwayat_kunjungan]]) {
            const list = document.getElementById(listId);
            const items = records.filter(item => filters[kind] === 'all' || (kind === 'donations' ? item.display_status : item.status) === filters[kind]);
            list.replaceChildren(...items.map(item => card(item, kind === 'visits')));
            if (!items.length) list.append(el('p', `Belum ada riwayat ${kind === 'visits' ? 'kunjungan' : 'donasi'}${filters[kind] === 'all' ? '.' : ' dengan status ini.'}`, 'activity-empty'));
        }
    }
    async function load() {
        const status = document.getElementById('activity-status'), retry = document.getElementById('activity-retry');
        status.textContent = 'Memuat riwayat aktivitas...'; retry.hidden = true;
        try {
            data = (await api('/api/donors/activities')).data;
            document.getElementById('activity-count').textContent = `${data.stats.total_donasi_kali} kali`;
            document.getElementById('activity-total').textContent = money(data.stats.total_nominal_rp);
            document.getElementById('activity-visits').textContent = `${data.stats.total_kunjungan} kali`;
            status.textContent = ''; render();
        } catch (error) { status.textContent = error.message; retry.hidden = false; }
    }
    document.querySelectorAll('[data-filter]').forEach(group => group.addEventListener('click', event => {
        const button = event.target.closest('button'); if (!button) return;
        filters[group.dataset.filter] = button.dataset.value;
        group.querySelectorAll('button').forEach(item => item.setAttribute('aria-pressed', String(item === button))); render();
    }));
    document.getElementById('activity-retry').onclick = load;
    load();
}
