const page = document.getElementById('password-page');
if (page) {
    const form = document.getElementById('password-form');
    const status = document.getElementById('password-status');
    const submit = form.querySelector('[type=submit]');
    const field = name => form.elements.namedItem(name);
    const MISMATCH = 'Password dan konfirmasi password tidak cocok.';

    function setError(name, text) {
        const input = field(name);
        document.getElementById(`${name}-error`).textContent = text || '';
        if (text) input.setAttribute('aria-invalid', 'true'); else input.removeAttribute('aria-invalid');
    }
    function setStatus(text, type = '') {
        status.textContent = text;
        status.className = 'password-status' + (type ? ` is-${type}` : '');
    }
    // Pesan tidak cocok muncul langsung saat konfirmasi sudah diisi.
    function checkMatch() {
        const confirmation = field('password_confirmation').value;
        setError('password_confirmation', confirmation && confirmation !== field('password').value ? MISMATCH : '');
    }

    form.querySelectorAll('.password-toggle').forEach(button => button.addEventListener('click', () => {
        const input = button.previousElementSibling;
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        button.setAttribute('aria-pressed', String(show));
        button.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
    }));
    field('current_password').addEventListener('input', () => setError('current_password', ''));
    field('password').addEventListener('input', () => { setError('password', ''); checkMatch(); });
    field('password_confirmation').addEventListener('input', checkMatch);

    form.addEventListener('submit', async event => {
        event.preventDefault();
        setStatus('');
        const data = Object.fromEntries(new FormData(form));
        let invalid = false;
        if (!data.current_password) { setError('current_password', 'Password saat ini wajib diisi.'); invalid = true; }
        if (!data.password) { setError('password', 'Password baru wajib diisi.'); invalid = true; }
        else if (data.password.length < 8 || !/[A-Za-z]/.test(data.password) || !/\d/.test(data.password)) {
            setError('password', 'Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.'); invalid = true;
        }
        if (!data.password_confirmation) { setError('password_confirmation', 'Konfirmasi password wajib diisi.'); invalid = true; }
        else if (data.password_confirmation !== data.password) { setError('password_confirmation', MISMATCH); invalid = true; }
        if (invalid) { form.querySelector('[aria-invalid="true"]')?.focus(); return; }

        const token = localStorage.getItem('auth_token');
        if (!token) { window.location.replace('/login'); return; }
        submit.disabled = true; submit.textContent = 'Menyimpan...';
        try {
            const response = await fetch('/api/profile/change-password', {
                method: 'PUT',
                headers: { Accept: 'application/json', 'Content-Type': 'application/json', Authorization: `Bearer ${token}` },
                body: JSON.stringify(data),
            });
            if (response.status === 401) { window.location.replace('/login'); return; }
            const result = await response.json().catch(() => ({}));
            if (response.status === 422) {
                const errors = result.errors || {};
                // Laravel menaruh error "confirmed" di field password; tampilkan di bawah konfirmasi.
                if (errors.password?.[0] === MISMATCH) { setError('password_confirmation', MISMATCH); delete errors.password; }
                Object.entries(errors).forEach(([name, messages]) => field(name) && setError(name, messages[0]));
                form.querySelector('[aria-invalid="true"]')?.focus();
                return;
            }
            if (!response.ok) throw new Error(result.message || 'Password belum dapat diperbarui. Silakan coba lagi.');
            form.reset();
            setStatus('Password berhasil diperbarui. Mengalihkan ke profil...', 'success');
            setTimeout(() => window.location.assign('/donatur/profil'), 1500);
        } catch (error) {
            setStatus(error.message, 'error');
        } finally {
            submit.disabled = false; submit.textContent = 'Simpan Perubahan';
        }
    });
}
