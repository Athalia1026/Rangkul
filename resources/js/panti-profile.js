const dialog = document.getElementById('panti-visit-dialog');
if (dialog) {
    const form = document.getElementById('panti-visit-form');
    const feedback = document.getElementById('panti-visit-feedback');
    let previousOverflow;
    document.getElementById('panti-visit-open').addEventListener('click', () => {
        previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        dialog.showModal();
    });
    dialog.querySelector('.panti-dialog-close').addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', (event) => {
        const rect = dialog.getBoundingClientRect();
        if (event.target === dialog && (event.clientX < rect.left || event.clientX > rect.right || event.clientY < rect.top || event.clientY > rect.bottom)) dialog.close();
    });
    dialog.addEventListener('close', () => { document.body.style.overflow = previousOverflow; });
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const button = form.querySelector('[type=submit]');
        button.disabled = true;
        button.textContent = 'Mengirim...';
        feedback.hidden = true;
        try {
            const token = localStorage.getItem('auth_token');
            if (!token) throw new Error('Silakan masuk dengan akun donatur terlebih dahulu.');
            const response = await fetch(form.dataset.endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', Authorization: `Bearer ${token}` },
                body: JSON.stringify({ ...Object.fromEntries(new FormData(form)), id_organisasi: form.dataset.organization }),
            });
            const result = await response.json();
            if (!response.ok) throw new Error(Object.values(result.errors || {}).flat().join(' ') || result.message || 'Pengajuan belum berhasil. Silakan coba lagi.');
            feedback.textContent = 'Pengajuan berhasil dikirim. Tunggu konfirmasi dari panti melalui Riwayat kunjungan.';
            form.reset();
            button.textContent = 'Pengajuan Terkirim';
        } catch (error) {
            feedback.textContent = error.message || 'Koneksi bermasalah. Silakan coba lagi.';
            button.disabled = false;
            button.textContent = 'Kirim Pengajuan';
        }
        feedback.hidden = false;
    });
}
