const page = document.getElementById('profile-page');
if (page) {
    const form = document.getElementById('profile-form');
    const status = document.getElementById('profile-status');
    const saveButton = document.getElementById('profile-save');
    const cancelButton = document.getElementById('profile-cancel');
    const message = document.getElementById('profile-form-message');
    const photoInput = document.getElementById('profile-photo-input');
    const photoError = document.getElementById('profile-photo-error');
    const avatarImage = document.getElementById('profile-avatar-image');
    const avatarInitials = document.getElementById('profile-avatar-initials');
    const heading = document.getElementById('profile-name-heading');
    // Email hanya ditampilkan (readonly), tidak ikut disimpan.
    const fields = ['nama', 'no_telp', 'kota'];
    const MAX_PHOTO_SIZE = 2 * 1024 * 1024;
    let original = null;
    let originalPhotoUrl = null;
    let selectedPhoto = null;
    let previewUrl = null;

    async function api(path, options = {}) {
        const token = localStorage.getItem('auth_token');
        if (!token) { window.location.replace('/login'); throw new Error('Silakan masuk kembali.'); }
        const response = await fetch(path, { ...options, headers: { Accept: 'application/json', Authorization: `Bearer ${token}`, ...options.headers } });
        if (response.status === 401) { window.location.replace('/login'); throw new Error('Sesi berakhir.'); }
        const result = await response.json().catch(() => ({}));
        if (!response.ok) {
            const error = new Error(result.message || 'Data belum dapat diproses.');
            error.errors = result.errors || {};
            throw error;
        }
        return result;
    }
    const photoUrl = path => {
        if (!path) return null;
        const url = new URL(path.startsWith('http') ? path : `/storage/${path.replace(/^\//, '')}`, location.origin);
        return ['http:', 'https:'].includes(url.protocol) ? url.href : null;
    };
    const initials = name => (name || 'D').split(/\s+/).filter(Boolean).slice(0, 2).map(part => part[0]).join('').toUpperCase() || 'D';
    const input = name => form.elements.namedItem(name);
    const values = () => Object.fromEntries(fields.map(name => [name, input(name).value.trim()]));

    function showAvatar(url, name) {
        avatarInitials.textContent = initials(name);
        avatarInitials.hidden = Boolean(url);
        avatarImage.hidden = !url;
        if (url) avatarImage.src = url; else avatarImage.removeAttribute('src');
    }
    // Perbarui avatar & nama di navbar tanpa memuat ulang halaman.
    function syncNavbar(user, url) {
        document.getElementById('donor-avatar-button')?.setAttribute('title', user.nama);
        const navInitials = document.getElementById('donor-initials');
        const navImage = document.getElementById('donor-avatar-image');
        if (navInitials) { navInitials.textContent = initials(user.nama); navInitials.hidden = Boolean(url); }
        if (navImage && url) { navImage.src = url; navImage.hidden = false; }
    }
    function isDirty() {
        if (!original) return false;
        const current = values();
        return Boolean(selectedPhoto) || fields.some(name => current[name] !== original[name]);
    }
    function updateState() {
        const dirty = isDirty();
        saveButton.disabled = !dirty;
        cancelButton.hidden = !dirty;
        if (dirty && message.classList.contains('is-success')) setMessage('');
    }
    function setMessage(text, type = '') {
        message.textContent = text;
        message.className = 'profile-message' + (type ? ` is-${type}` : '');
    }
    function clearFieldErrors() {
        form.querySelectorAll('.profile-field-error').forEach(node => node.remove());
        fields.forEach(name => input(name).removeAttribute('aria-invalid'));
        photoError.textContent = '';
    }
    function showFieldErrors(errors) {
        Object.entries(errors).forEach(([name, messages]) => {
            if (name === 'profile_photo') { photoError.textContent = messages[0]; return; }
            const field = input(name);
            if (!field) return;
            field.setAttribute('aria-invalid', 'true');
            const note = document.createElement('span');
            note.className = 'profile-field-error';
            note.textContent = messages[0];
            field.after(note);
        });
    }
    function resetPhotoSelection() {
        selectedPhoto = null;
        photoInput.value = '';
        if (previewUrl) { URL.revokeObjectURL(previewUrl); previewUrl = null; }
    }
    // Label nama mengikuti tipe donatur; tampilan lainnya sama untuk semua tipe.
    const nameLabels = { komunitas: 'Nama Komunitas', perusahaan: 'Nama Perusahaan' };
    function fill(user) {
        document.getElementById('profile-name-label').textContent = nameLabels[user.donor?.tipe] || 'Nama';
        input('email').value = user.email || '';
        original = { nama: user.nama || '', no_telp: user.donor?.no_telp || '', kota: user.donor?.kota || '' };
        originalPhotoUrl = photoUrl(user.profile_photo);
        fields.forEach(name => { input(name).value = original[name]; });
        heading.textContent = original.nama;
        resetPhotoSelection();
        showAvatar(originalPhotoUrl, original.nama);
        clearFieldErrors();
        updateState();
    }

    form.addEventListener('input', event => {
        if (event.target.name === 'nama') heading.textContent = event.target.value.trim() || original.nama;
        if (event.target.getAttribute('aria-invalid')) {
            event.target.removeAttribute('aria-invalid');
            event.target.nextElementSibling?.classList.contains('profile-field-error') && event.target.nextElementSibling.remove();
        }
        updateState();
    });
    photoInput.addEventListener('change', () => {
        const file = photoInput.files[0];
        photoError.textContent = '';
        if (!file) return;
        if (!['image/jpeg', 'image/png'].includes(file.type)) { photoError.textContent = 'Foto harus berformat JPG atau PNG.'; photoInput.value = ''; return; }
        if (file.size > MAX_PHOTO_SIZE) { photoError.textContent = 'Ukuran foto maksimal 2 MB.'; photoInput.value = ''; return; }
        if (previewUrl) URL.revokeObjectURL(previewUrl);
        selectedPhoto = file;
        previewUrl = URL.createObjectURL(file);
        showAvatar(previewUrl, values().nama);
        updateState();
    });
    cancelButton.addEventListener('click', () => {
        fields.forEach(name => { input(name).value = original[name]; });
        heading.textContent = original.nama;
        resetPhotoSelection();
        showAvatar(originalPhotoUrl, original.nama);
        clearFieldErrors();
        setMessage('');
        updateState();
    });
    form.addEventListener('submit', async event => {
        event.preventDefault();
        if (!isDirty()) return;
        clearFieldErrors();
        const current = values();
        const empty = fields.filter(name => !current[name]);
        if (empty.length) {
            showFieldErrors(Object.fromEntries(empty.map(name => [name, ['Kolom ini wajib diisi.']])));
            return setMessage('Lengkapi data yang masih kosong.', 'error');
        }
        saveButton.disabled = true; cancelButton.disabled = true;
        saveButton.classList.add('is-busy'); saveButton.textContent = 'Menyimpan...';
        setMessage('');
        try {
            let photoPath = null;
            if (selectedPhoto) {
                const body = new FormData();
                body.append('profile_photo', selectedPhoto);
                photoPath = (await api('/api/profile/photo', { method: 'POST', body })).profile_photo_url;
                // Foto sudah tersimpan; jangan unggah ulang bila data teks gagal disimpan.
                originalPhotoUrl = photoUrl(photoPath);
                resetPhotoSelection();
            }
            const changedText = fields.some(name => current[name] !== original[name]);
            const user = changedText
                ? (await api('/api/profile', { method: 'PUT', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(current) })).data
                : (await api('/api/me')).user;
            fill(user);
            syncNavbar(user, originalPhotoUrl);
            setMessage('Perubahan profil berhasil disimpan.', 'success');
        } catch (error) {
            showFieldErrors(error.errors || {});
            setMessage(Object.keys(error.errors || {}).length ? 'Periksa kembali data yang ditandai.' : error.message, 'error');
            showAvatar(previewUrl || originalPhotoUrl, current.nama);
        } finally {
            saveButton.classList.remove('is-busy'); saveButton.textContent = 'Simpan Perubahan';
            cancelButton.disabled = false;
            updateState();
        }
    });

    // Keluar memakai alur logout navbar (cabut token API lalu akhiri sesi web).
    document.getElementById('profile-logout').addEventListener('click', event => {
        const logoutForm = document.getElementById('donor-logout-form');
        if (!logoutForm) return;
        event.currentTarget.disabled = true;
        logoutForm.requestSubmit();
    });

    api('/api/me').then(({ user }) => {
        if (user?.account_type !== 'donatur') return;
        fill(user);
        status.textContent = '';
        form.hidden = false;
        document.getElementById('profile-shortcuts').hidden = false;
    }).catch(error => { status.textContent = error.message; });
}
