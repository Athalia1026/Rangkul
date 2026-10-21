// Form pencarian ([data-search-form]) tidak dikirim bila kata kunci kosong / hanya spasi:
// tombol Cari tidak melakukan apa-apa selain mengarahkan kursor ke kolom pencarian.
document.addEventListener('submit', event => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || !form.matches('[data-search-form]')) return;

    const input = form.querySelector('[name="q"]');
    if (!input || input.value.trim() !== '') return;

    event.preventDefault();
    input.value = '';
    input.focus();
});
