{{--
    Tombol Masuk/Daftar untuk pengunjung tanpa sesi web. Donatur/admin login lewat token API
    (localStorage), bukan sesi web, jadi status loginnya dibaca di browser dan tombol diganti
    avatar akun (token basi sudah dibuang x-auth-session-guard).
--}}
<div id="public-guest-actions" class="flex items-center gap-4">
    <a href="{{ route('login') }}"
        class="px-6 py-2 rounded-lg bg-[#05522d] hover:bg-[#044023] text-white font-semibold text-[15px] transition-colors">Masuk</a>
    <a href="{{ route('register') }}"
        class="px-6 py-2 rounded-lg bg-[#d8f0e2] hover:bg-[#c4ebd3] text-[#05522d] font-semibold text-[15px] transition-colors">Daftar</a>
</div>

<a id="public-user-actions" href="{{ route('donatur.beranda') }}" hidden
    class="items-center gap-3 rounded-full focus:outline-none" aria-label="Buka akun saya">
    <span id="public-user-name" class="text-sm font-semibold text-gray-700 hidden lg:block"></span>
    <img id="public-user-avatar" alt=""
        class="h-10 w-10 rounded-full object-cover border-2 border-transparent hover:border-[#05522d] transition-all shadow-sm">
</a>
<script>
    (function () {
        try {
            if (!localStorage.getItem('auth_token')) return;
            var user = JSON.parse(localStorage.getItem('auth_user') || '{}');
            var name = user.nama || user.name || 'Akun Saya';
            var homeByType = { organisasi: '{{ route('organisasi.dashboard') }}', admin: '/manager/home' };
            var link = document.getElementById('public-user-actions');

            link.href = homeByType[user.account_type] || link.href;
            document.getElementById('public-user-name').textContent = name;
            var avatar = document.getElementById('public-user-avatar');
            avatar.src = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(name) + '&background=d8f0e2&color=05522d&bold=true';
            avatar.alt = 'Profil ' + name;

            document.getElementById('public-guest-actions').style.display = 'none';
            link.hidden = false;
            link.style.display = 'flex';
        } catch (error) {
            // Data login tidak terbaca: tetap tampilkan tombol Masuk/Daftar.
        }
    })();
</script>
