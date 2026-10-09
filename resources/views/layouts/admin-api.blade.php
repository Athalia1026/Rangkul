{{--
    Helper bersama halaman manajer dan super admin: memastikan yang membuka halaman adalah akun admin
    dengan peran yang sesuai ($area: 'manager' atau 'superadmin'), memanggil /api/admin/* dengan token
    login (localStorage), dan logout. Pembatasan sebenarnya tetap di server (CheckIsAdmin & CheckAdminRole).
--}}
<script>
window.RangkulAdmin = (function () {
    const LOGIN_URL = @json(route('login'));
    const AREA = @json($area);
    const HOME_BY_ACCOUNT_TYPE = {
        organisasi: @json(route('organisasi.dashboard')),
        donatur: @json(route('donatur.beranda'))
    };
    const HOME_BY_ADMIN_AREA = {
        manager: @json(route('manager.home')),
        superadmin: @json(route('superadmin.home'))
    };

    function readUser() {
        try {
            return JSON.parse(localStorage.getItem('auth_user') || 'null');
        } catch (error) {
            return null;
        }
    }

    // Super admin memakai halaman /superadmin, manajer dan staf memakai halaman /manager.
    function adminAreaOf(user) {
        return user && user.admin && user.admin.tipe === 'super admin' ? 'superadmin' : 'manager';
    }

    function clearAuth() {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('auth_user');
        localStorage.removeItem('auth_remember');
        document.cookie = 'rangkul_browser_session=; path=/; max-age=0; SameSite=Lax';
        document.cookie = 'rangkul_remember=; path=/; max-age=0; SameSite=Lax';
    }

    let redirecting = false;

    function redirectTo(url) {
        redirecting = true;
        window.location.replace(url);
    }

    function goToLogin() {
        redirectTo(LOGIN_URL);
    }

    const token = localStorage.getItem('auth_token');
    const user = readUser();

    if (!token) {
        goToLogin();
    } else if (user && user.account_type !== 'admin') {
        redirectTo(HOME_BY_ACCOUNT_TYPE[user.account_type] || LOGIN_URL);
    } else if (user && adminAreaOf(user) !== AREA) {
        redirectTo(HOME_BY_ADMIN_AREA[adminAreaOf(user)]);
    }

    async function request(path, options = {}) {
        // Halaman sedang dialihkan: jangan memuat data atau menampilkan pesan galat.
        if (redirecting) {
            return new Promise(() => {});
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
