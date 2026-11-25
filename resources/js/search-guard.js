// Pencarian publik butuh kata kunci, sedangkan pencarian donatur boleh kosong
// untuk membuka halaman hasil dengan kategori penggalangan dana dan organisasi.
document.addEventListener('submit', event => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || !form.matches('[data-search-form]')) return;

    const input = form.querySelector('[name="q"]');
    if (!input || input.value.trim() !== '' || form.hasAttribute('data-allow-empty-search')) return;

    event.preventDefault();
    input.value = '';
    input.focus();
});
