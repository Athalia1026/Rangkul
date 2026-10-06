{{--
    Penjaga sesi login di browser (dipasang di <head>, berjalan sebelum script lain).
    Token login disimpan di localStorage yang tidak hilang saat browser ditutup, jadi masa
    berlakunya ditentukan oleh dua cookie yang ditanam halaman login:
    - rangkul_browser_session: cookie sesi, dihapus browser saat ditutup.
    - rangkul_remember: cookie 30 hari, hanya ada jika "Ingat saya" dicentang.
    Jika token masih ada tetapi kedua cookie sudah hilang, berarti browser sempat ditutup
    tanpa "Ingat saya" (atau masa ingat habis): token dibuang dan dicabut di server.
--}}
<script>
    (function () {
        try {
            var storage = window.localStorage;
            var token = storage.getItem('auth_token');
            var cookies = document.cookie.split('; ');
            var stillValid = cookies.indexOf('rangkul_browser_session=1') !== -1
                || cookies.indexOf('rangkul_remember=1') !== -1;

            if (!token || stillValid) {
                return;
            }

            storage.removeItem('auth_token');
            storage.removeItem('auth_user');
            storage.removeItem('auth_remember');

            fetch('/api/logout', {
                method: 'POST',
                headers: { Accept: 'application/json', Authorization: 'Bearer ' + token },
                keepalive: true
            }).catch(function () {});
        } catch (error) {
            // localStorage tidak tersedia (mode privat/diblokir): tidak ada yang perlu dibersihkan.
        }
    })();
</script>
