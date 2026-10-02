const page = document.getElementById('visit-page');
if (page) {
    const content = document.getElementById('visit-content'), message = document.getElementById('visit-message');
    const mode = page.dataset.mode;
    const statusNames = { terkirim: 'Terkirim', dikonfirmasi: 'Dikonfirmasi', ditolak: 'Ditolak', selesai: 'Selesai' };
    const urls = [];
    function el(tag, text, cls) { const element = document.createElement(tag); if (text != null) element.textContent = text; if (cls) element.className = cls; return element; }
    function link(text, href, secondary = false) { const a = el('a', text, `visit-btn${secondary ? ' secondary' : ''}`); a.href = href; return a; }
    function image(url, alt) { const img = el('img'); img.src = url; img.alt = alt; img.loading = 'lazy'; return img; }
    function panel(title, text, green = false) { const box = el('section', null, `visit-panel${green ? ' green' : ''}`); box.append(el('h2', title)); if (text) box.append(el('p', text)); return box; }
    async function api(path, options = {}) {
        const token = localStorage.getItem('auth_token');
        if (!token) { location.replace('/login'); throw new Error('Silakan masuk sebagai donatur.'); }
        const response = await fetch(path, { ...options, headers: { Accept: 'application/json', Authorization: `Bearer ${token}`, ...options.headers } });
        if (response.status === 401) location.replace('/login');
        const data = await response.json();
        if (!response.ok) throw new Error(Object.values(data.errors || {}).flat().join(' ') || (response.status === 404 ? 'Kunjungan tidak ditemukan untuk akun Anda.' : data.message) || 'Data belum dapat diproses.');
        return data.data;
    }
    function renderForm(org, visit = null) {
        if (visit && visit.status !== 'terkirim') { location.replace(`/donatur/kunjungan/${encodeURIComponent(visit.id)}`); return; }
        const banner = el('div', null, 'visit-banner');
        banner.append(image(org.galleries?.[0]?.image_url || '/images/hero/hero_children.jpg', org.nama_lembaga));
        const caption = el('div'); caption.append(el('h1', org.nama_lembaga), el('p', org.kota)); banner.append(caption);
        const form = el('form', null, 'visit-form'), fields = el('div', null, 'visit-fields');
        function field(labelText, name, type, value) {
            const label = el('label', labelText), input = el('input'); input.type = type; input.name = name; input.required = true; input.value = value || ''; label.append(input); fields.append(label); return input;
        }
        field('Tanggal Kunjungan', 'tanggal_kunjungan', 'date', visit?.tanggal_kunjungan?.slice(0, 10)).min = page.dataset.today;
        field('Waktu Kunjungan', 'waktu_kunjungan', 'time', visit?.waktu_kunjungan?.slice(0, 5));
        const count = field('Jumlah Orang', 'pengunjung', 'number', visit?.pengunjung); count.min = 1; count.placeholder = 'Masukkan jumlah orang';
        const label = el('label', 'Catatan / Pesan', 'visit-note'), note = el('textarea'); note.name = 'pesan_donatur'; note.maxLength = 255; note.rows = 3; note.value = visit?.pesan_donatur || ''; note.placeholder = 'Tuliskan catatan atau pesan Anda di sini...'; label.append(note);
        const feedback = el('p'); feedback.setAttribute('role', 'status');
        const actions = el('div', null, 'visit-actions'); actions.append(link('← Kembali', visit ? '/donatur/riwayat' : `/donatur/panti/${encodeURIComponent(org.id)}`, true));
        const button = el('button', visit ? 'Simpan Perubahan' : 'Jadwalkan Kunjungan', 'visit-btn'); button.type = 'submit'; actions.append(button); form.append(fields, label, feedback, actions); content.append(banner, form);
        form.onsubmit = async event => {
            event.preventDefault(); if (button.disabled) return;
            button.disabled = true; feedback.textContent = 'Menyimpan pengajuan...';
            try {
                const payload = Object.fromEntries(new FormData(form)); if (!visit) payload.id_organisasi = org.id;
                const saved = await api(visit ? `/api/visits/${encodeURIComponent(visit.id)}` : '/api/visits', { method: visit ? 'PUT' : 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
                location.assign(`/donatur/kunjungan/${encodeURIComponent(saved.id)}`);
            } catch (error) { feedback.textContent = error.message; button.disabled = false; }
        };
    }
    function renderDetail(visit) {
        const org = visit.organization || {}, header = el('header', null, 'visit-heading'), title = el('div');
        title.append(el('h1', 'Detail Kunjungan'), el('p', 'Lihat informasi lengkap mengenai jadwal kunjungan, status persetujuan, dan detail kunjungan Anda.'));
        const badge = el('span', statusNames[visit.status] || visit.status, 'activity-badge'); badge.dataset.status = visit.status; header.append(title, badge); content.append(header);
        const grid = el('div', null, 'visit-detail-grid'), left = el('div'), right = el('aside');
        if (visit.status === 'ditolak') left.append(panel('Alasan Penolakan Kunjungan', visit.pesan_organisasi || 'Panti belum memberikan alasan penolakan.', true));
        const info = panel('Informasi Kunjungan'), details = el('dl', null, 'visit-information');
        [['Panti Asuhan', org.nama_lembaga || 'Panti'], ['Tanggal', new Date(visit.tanggal_kunjungan).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })], ['Waktu', (visit.waktu_kunjungan || '').slice(0, 5)], ['Jumlah Peserta', `${visit.pengunjung} Orang`]].forEach(([name, value]) => { const cell = el('div'); cell.append(el('dt', name), el('dd', value)); details.append(cell); }); info.append(details); left.append(info);
        if (visit.status !== 'ditolak') left.append(panel('Catatan Panti Asuhan', visit.pesan_organisasi || 'Menunggu tanggapan dari panti.', true));
        const notes = panel('Catatan / Pesan', visit.pesan_donatur || 'Tidak ada catatan tambahan.', true);
        (visit.status === 'selesai' || visit.status === 'ditolak' ? left : right).append(notes);
        let uploadForm = null;
        if (visit.status === 'dikonfirmasi') {
            uploadForm = el('form'); uploadForm.id = 'visit-upload-form';
            const box = panel('Unggah Dokumentasi Kegiatan'); box.id = 'dokumentasi';
            const drop = el('label', null, 'visit-upload');
            const icon = el('span', null, 'visit-upload-icon');
            icon.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 16l-4-4-4 4M12 12v8" /><path d="M20 16.5a4.5 4.5 0 0 0-2.5-8.17A6 6 0 0 0 6.12 10 4 4 0 0 0 6 18h2" /></svg>';
            drop.append(icon, el('strong', 'Pilih foto untuk diunggah', 'visit-upload-title'), el('span', 'JPG, PNG • Maks. 2MB per foto, hingga 5 foto', 'visit-upload-hint'), el('span', 'Klik atau tarik dan lepas foto ke sini', 'visit-upload-hint'));
            // Input disembunyikan; validasi jumlah dilakukan di onsubmit agar tidak bentrok dengan validasi bawaan browser.
            const input = el('input'); input.type = 'file'; input.accept = 'image/jpeg,image/png'; input.multiple = true; input.hidden = true; input.setAttribute('aria-label', 'Pilih dokumentasi kegiatan'); drop.append(input);
            const chosen = el('p', null, 'visit-upload-selected'), previews = el('div', null, 'visit-gallery'), feedback = el('p'); feedback.setAttribute('role', 'status'); let selected = [];
            function select(files) {
                selected = []; chosen.textContent = ''; previews.replaceChildren(); urls.splice(0).forEach(URL.revokeObjectURL);
                const entries = [...files];
                if (entries.length > 5 || entries.some(file => !['image/jpeg', 'image/png'].includes(file.type) || file.size > 2 * 1024 * 1024)) { feedback.textContent = 'Pilih maksimal 5 foto JPG/PNG dengan ukuran maksimal 2 MB per foto.'; input.value = ''; return; }
                selected = entries; feedback.textContent = '';
                if (entries.length) chosen.textContent = `${entries.length} foto dipilih`;
                entries.forEach(file => { const url = URL.createObjectURL(file); urls.push(url); const figure = el('figure'); figure.append(image(url, file.name), el('figcaption', file.name)); previews.append(figure); });
            }
            input.onchange = () => select(input.files);
            drop.ondragover = event => { event.preventDefault(); drop.classList.add('is-dragover'); };
            drop.ondragleave = () => drop.classList.remove('is-dragover');
            drop.ondrop = event => { event.preventDefault(); drop.classList.remove('is-dragover'); input.files = event.dataTransfer.files; select(input.files); };
            box.append(drop, chosen, previews, feedback); uploadForm.append(box); left.append(uploadForm);
            uploadForm.onsubmit = async event => {
                event.preventDefault(); if (!selected.length) { feedback.textContent = 'Pilih foto dokumentasi terlebih dahulu.'; return; }
                const button = document.getElementById('visit-upload-submit'); if (button.disabled) return; button.disabled = true;
                feedback.textContent = 'Mengunggah dokumentasi...';
                try {
                    const body = new FormData(); selected.forEach(file => body.append('dokumentasi[]', file));
                    await api(`/api/visits/${encodeURIComponent(visit.id)}/documentation`, { method: 'POST', body }); location.reload();
                } catch (error) { feedback.textContent = error.message; button.disabled = false; }
            };
        }
        if (visit.status === 'selesai') {
            const box = panel('Dokumentasi Kegiatan', null, true), gallery = el('div', null, 'visit-gallery');
            (visit.documents || []).forEach(doc => { const a = el('a'); a.href = `/storage/${doc.lokasi_file}`; a.target = '_blank'; a.rel = 'noopener noreferrer'; a.append(image(a.href, 'Dokumentasi kegiatan kunjungan')); gallery.append(a); });
            box.append(gallery.children.length ? gallery : el('p', 'Belum ada foto dokumentasi.')); right.append(box);
        } else {
            const address = [org.alamat, org.kota].filter(Boolean).join(', '), map = el('a', null, 'visit-map'); map.href = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`; map.target = '_blank'; map.rel = 'noopener noreferrer'; map.append(el('strong', '⌖ Lokasi Kegiatan'), el('p', address || 'Alamat belum tersedia'), el('span', 'Buka di Google Maps ↗')); right.append(map);
            const profile = panel(org.nama_lembaga || 'Profil Panti', org.deskripsi || 'Lihat informasi panti dan kegiatan lainnya.', true); profile.append(link('Lihat Profil Panti', `/donatur/panti/${encodeURIComponent(visit.id_organisasi)}`)); right.append(profile);
        }
        grid.append(left, right); content.append(grid);
        const actions = el('div', null, 'visit-actions'); actions.append(link('← Kembali', '/donatur/riwayat', true));
        if (visit.status === 'terkirim') actions.append(link('Edit Kunjungan', `/donatur/kunjungan/${encodeURIComponent(visit.id)}/edit`));
        if (visit.status === 'ditolak') actions.append(link('Jadwalkan Ulang', `/donatur/kunjungan/jadwalkan/${encodeURIComponent(visit.id_organisasi)}`));
        if (uploadForm) { const submit = el('button', 'Simpan Dokumentasi', 'visit-btn'); submit.type = 'submit'; submit.id = 'visit-upload-submit'; submit.setAttribute('form', uploadForm.id); actions.append(submit); }
        content.append(actions);
        if (location.hash === '#dokumentasi') document.getElementById('dokumentasi')?.scrollIntoView({ block: 'center' });
    }
    (async () => {
        try {
            if (mode === 'create') renderForm(await api(`/api/organizations/${encodeURIComponent(page.dataset.organization)}/profile`));
            else { const visit = await api(`/api/visits/${encodeURIComponent(page.dataset.id)}`); mode === 'edit' ? renderForm(visit.organization, visit) : renderDetail(visit); }
            message.textContent = '';
        } catch (error) { message.textContent = error.message; content.append(link('Kembali ke Riwayat', '/donatur/riwayat', true)); }
    })();
    window.addEventListener('pagehide', () => urls.forEach(URL.revokeObjectURL), { once: true });
}
