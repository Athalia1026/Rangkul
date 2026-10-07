{{--
    Helper bersama halaman manajer: memastikan yang membuka halaman adalah akun admin,
    memanggil /api/admin/* dengan token login (localStorage), dan logout.
--}}
<script>
window.RangkulAdmin = (function () {
    const LOGIN_URL = @json(route('login'));
    const HOME_BY_ACCOUNT_TYPE = {
        organisasi: @json(route('organisasi.dashboard')),
        donatur: @json(route('donatur.beranda'))
    };

    function readUser() {
        try {
            return JSON.parse(localStorage.getItem('auth_user') || 'null');
        } catch (error) {
            return null;
        }
    }

    function clearAuth() {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
        localStorage.removeItem('auth_remember');
        document.cookie = 'rangkul_browser_session=; path=/; max-age=0; SameSite=Lax';
        document.cookie = 'rangkul_remember=; path=/; max-age=0; SameSite=Lax';
    }

    function goToLogin() {
        window.location.replace(LOGIN_URL);
    }

    const token = localStorage.getItem('auth_token');
    const user = readUser();

    if (!token) {
        goToLogin();
    } else if (user && user.account_type !== 'admin') {
        window.location.replace(HOME_BY_ACCOUNT_TYPE[user.account_type] || LOGIN_URL);
    }

    async function request(path, options = {}) {
        if (!token) {
            goToLogin();
            throw new Error('Silakan masuk sebagai admin.');
        }

        const headers = {
            Accept: 'application/json',
            Authorization: 'Bearer ' + token,
            ...(options.body ? { 'Content-Type': 'application/json' } : {})
        };

        const response = await fetch(path, {
            method: options.method || 'GET',
            headers,
            body: options.body ? JSON.stringify(options.body) : undefined
        });

        if (response.status === 401) {
            clearAuth();
            goToLogin();
            throw new Error('Sesi berakhir. Silakan masuk kembali.');
        }

        const result = await response.json().catch(() => ({}));

        if (!response.ok) {
            const validationMessage = result.errors ? Object.values(result.errors).flat()[0] : null;
            throw new Error(validationMessage || result.message || 'Data gagal dimuat. Silakan coba kembali.');
        }

        return result;
    }

    async function logout() {
        try {
            await fetch('/api/logout', {
                method: 'POST',
                headers: { Accept: 'application/json', Authorization: 'Bearer ' + token },
                keepalive: true
            });
        } catch (error) {
            // Token tetap dihapus dari browser walaupun pencabutan token di server gagal.
        }

        clearAuth();
        goToLogin();
    }

    function formatRupiah(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID', { maximumFractionDigits: 0 });
    }

    function escapeHtml(value) {
        return String(value ?? '-').replace(/[&<>'"]/g, (character) => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;'
        }[character]));
    }

    return { request, logout, formatRupiah, escapeHtml };
})();
</script>
