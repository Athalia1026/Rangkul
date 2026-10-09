{{--
    Navbar bersama halaman manajer dan super admin. Gaya mengikuti navbar organisasi
    (x-organization-navbar) dan donatur (x-donor-navbar).
    Variabel: $area ('manager' | 'superadmin'), $homeRoute, $navLabel, dan
    $navItems (label, route, active = daftar nama route yang menandai menu aktif).
    Nama dan foto akun diisi lewat script di bawah karena admin login memakai token API, bukan sesi web.
--}}

{{-- Font yang sama dengan navbar organisasi/donatur (termasuk bobot 500 untuk menu) --}}
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<header class="w-full bg-white border-b border-gray-100 sticky top-0 z-30 shadow-sm">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">

        {{-- LOGO --}}
        <a href="{{ route($homeRoute) }}" class="flex items-center gap-2.5" aria-label="Rangkul Beranda">
            <div class="flex flex-col leading-tight">
                <img src="{{ asset('images/logo.png') }}" alt="Rangkul Logo" class="h-11 w-auto">
            </div>
        </a>

        {{-- DESKTOP NAVIGATION --}}
        <nav class="hidden md:flex items-center gap-8 text-[15px] font-medium text-gray-700" aria-label="{{ $navLabel }}">
            @foreach ($navItems as $item)
                @php $isActive = request()->routeIs(...$item['active']); @endphp
                <a href="{{ route($item['route']) }}"
                    @if($isActive) aria-current="page" @endif
                    class="{{ $isActive ? 'text-[#05522d] font-semibold' : 'text-gray-700 hover:text-[#05522d]' }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>

        {{-- RIGHT SIDE --}}
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-6">

                {{-- Profil (inisial / foto akun admin yang login) --}}
                <div class="border-l border-gray-200 pl-6">
                    <span id="admin-avatar" title="Admin" aria-label="Akun admin"
                        class="h-10 w-10 rounded-full overflow-hidden flex items-center justify-center bg-[#d8f0e2] text-[#05522d] text-[14px] font-bold border-2 border-transparent shadow-sm">
                        <span id="admin-initials">A</span>
                        <img id="admin-avatar-image" class="h-full w-full object-cover" hidden alt="">
                    </span>
                </div>

                {{-- Keluar: cabut token API admin lalu kembali ke halaman login --}}
                <button type="button" data-rangkul-logout
                    class="p-1 text-gray-500 hover:text-red-600 transition-colors disabled:opacity-60 disabled:cursor-wait"
                    aria-label="Keluar" title="Keluar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" class="w-5 h-5" aria-hidden="true">
                        <path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 192 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l210.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128zM160 96c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 32C43 32 0 75 0 128L0 384c0 53 43 96 96 96l64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l64 0z"/>
                    </svg>
                </button>

                {{-- MOBILE MENU --}}
                <details class="relative md:hidden">
                    <summary class="list-none cursor-pointer p-1 text-gray-700 hover:text-[#05522d]" aria-label="Menu">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                            stroke="currentColor" class="w-[26px] h-[26px]" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </summary>

                    <div class="absolute right-0 top-12 w-56 bg-white rounded-xl shadow-lg border border-gray-100 p-2 text-[15px] font-medium">
                        @foreach ($navItems as $item)
                            @php $isActive = request()->routeIs(...$item['active']); @endphp
                            <a href="{{ route($item['route']) }}"
                                class="block px-4 py-2.5 rounded-lg {{ $isActive ? 'bg-[#d8f0e2] text-[#05522d] font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </details>

            </div>
        </div>
    </div>
</header>

@include('layouts.admin-api', ['area' => $area])

<script>
(function () {

    // Isi avatar dari data akun yang disimpan halaman login
    try {
        const user = JSON.parse(localStorage.getItem('auth_user') || 'null');
        const name = (user && user.nama) || 'Admin';
        const initials = name.trim().split(/\s+/).slice(0, 2).map((word) => word.charAt(0).toUpperCase()).join('');

        document.getElementById('admin-initials').textContent = initials || 'A';
        document.getElementById('admin-avatar').title = name;

        if (user && user.profile_photo) {
            const image = document.getElementById('admin-avatar-image');

            image.src = /^https?:\/\//.test(user.profile_photo)
                ? user.profile_photo
                : '/storage/' + user.profile_photo.replace(/^\/+/, '');
            image.alt = name;
            image.hidden = false;
            document.getElementById('admin-initials').hidden = true;
        }
    } catch (error) {
        // Data akun tidak terbaca: tetap tampilkan inisial bawaan.
    }

    document.addEventListener('click', function (event) {

        const logoutButton = event.target.closest('[data-rangkul-logout]');

        if (logoutButton) {
            event.preventDefault();
            logoutButton.disabled = true;
            window.RangkulAdmin.logout();
        }

    });

})();
</script>
