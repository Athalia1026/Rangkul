{{-- resources/views/layouts/public.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rangkul - Platform Donasi Terpercaya')</title>

    <!-- Google Font Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f5f7f4] text-[#000000] font-['Plus_Jakarta_Sans',sans-serif] antialiased flex flex-col items-center">

    <!-- Main Canvas Frame: 1200px Width -->
    <div class="w-full bg-[#F5F7F4] shadow-xl flex flex-col">

        <!-- NAVBAR -->
        <header class="w-full bg-white border-b border-gray-100 sticky top-0 z-30 shadow-2xs">
            <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                    <div class="flex flex-col leading-tight">
                        <img src="{{ asset('images/logo.png') }}" alt="Rangkul Logo" class="h-11 w-auto">
                    </div>
                </a>

                <nav class="hidden md:flex items-center gap-8 text-[15px] font-medium text-gray-700">
                    <a href="{{ url('/beranda') }}"
                        class="{{ request()->is('beranda') ? 'text-[#05522d] font-semibold' : 'text-gray-700 hover:text-[#05522d]' }}">Beranda</a>
                    <a href="{{ url('/search') }}"
                        class="{{ request()->is('search') ? 'text-[#05522d] font-semibold' : 'text-gray-700 hover:text-[#05522d]' }}">Cari</a>
                    <a href="{{ url('/') }}"
                        class="{{ request()->is('/') ? 'text-[#05522d] font-semibold' : 'text-gray-700 hover:text-[#05522d]' }}">Tentang Kami</a>
                </nav>

                <div class="hidden md:flex items-center gap-4">

                    @guest
                        {{-- Tampilan jika pengunjung BELUM login --}}
                        <a href="{{ route('login') }}"
                            class="px-6 py-2 rounded-lg bg-[#05522d] hover:bg-[#044023] text-white font-semibold text-[15px] transition-colors">Masuk</a>
                        <a href="{{ route('register') }}"
                            class="px-6 py-2 rounded-lg bg-[#d8f0e2] hover:bg-[#c4ebd3] text-[#05522d] font-semibold text-[15px] transition-colors">Daftar</a>
                    @endguest

                    @auth
                        {{-- Tampilan jika pengunjung SUDAH login --}}
                        <div class="flex items-center gap-6">

                            <!-- Ikon Notifikasi (Lonceng) -->
                            <button type="button"
                                class="relative p-1 text-gray-500 hover:text-[#05522d] transition-colors focus:outline-none"
                                aria-label="Notifikasi">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                                    stroke="currentColor" class="w-[26px] h-[26px]">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                <!-- Red Dot Indicator (Opsional: Muncul jika ada notif baru) -->
                                <span
                                    class="absolute top-1 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                            </button>

                            <!-- Foto Profil & Dropdown Trigger -->
                            <div class="relative flex items-center gap-3 border-l border-gray-200 pl-6">
                                <!-- Nama User (Opsional, bisa dihapus jika hanya ingin foto) -->
                                <span class="text-sm font-semibold text-gray-700 hidden lg:block">
                                    {{ Auth::user()->name }}
                                </span>

                                <!-- Avatar Profil -->
                                <button type="button" class="flex items-center focus:outline-none" aria-expanded="false"
                                    aria-haspopup="true">
                                    {{-- Logika Inovatif: Menggunakan UI-Avatars jika kolom foto di DB kosong --}}
                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-transparent hover:border-[#05522d] transition-all shadow-sm"
                                        src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=d8f0e2&color=05522d&bold=true' }}"
                                        alt="Profil {{ Auth::user()->name }}">
                                </button>
                            </div>

                        </div>
                    @endauth

                </div>
            </div>
        </header>

        <!-- MAIN CONTENT INJECTION -->
        <main>
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="w-full bg-[#05522d] text-white">
            <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-14">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
                    <div class="md:col-span-6 space-y-4">
                        <img src="{{ asset('images/logo-light.png') }}" alt="Rangkul Logo"
                            class="h-14 max-w-full w-auto">
                        <p class="text-[14px] text-gray-200 max-w-md">Rangkul hadir untuk menghubungkan kebaikan melalui
                            platform donasi yang aman, transparan, dan berdampak bagi mereka yang membutuhkan.</p>
                        <div class="flex items-center gap-3 pt-1">
                            <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer"
                                aria-label="Instagram Rangkul"
                                class="text-[#b7e4c7] hover:text-white transition-colors">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                    class="w-6 h-6" aria-hidden="true">
                                    <rect x="3" y="3" width="18" height="18" rx="5"></rect>
                                    <circle cx="12" cy="12" r="4"></circle>
                                    <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"></circle>
                                </svg>
                            </a>
                            <a href="https://www.tiktok.com/" target="_blank" rel="noopener noreferrer"
                                aria-label="TikTok Rangkul" class="text-[#b7e4c7] hover:text-white transition-colors">
                                <svg viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6" aria-hidden="true">
                                    <path
                                        d="M16.5 3c.3 1.8 1.3 3 3.5 3.2v3.1c-1.3 0-2.5-.3-3.5-.9v6.7c0 3.7-2.6 5.9-6 5.9-3.1 0-5.5-2.2-5.5-5.2 0-3.2 2.7-5.4 6-5.4.3 0 .7 0 1 .1v3.2c-.3-.1-.6-.2-1-.2-1.4 0-2.7.9-2.7 2.3 0 1.3 1 2.2 2.3 2.2 1.5 0 2.4-.9 2.4-2.8V3h3.5Z">
                                    </path>
                                </svg>
                            </a>
                            <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer"
                                aria-label="Facebook Rangkul" class="text-[#b7e4c7] hover:text-white transition-colors">
                                <svg viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6" aria-hidden="true">
                                    <path
                                        d="M13.5 21v-8h2.7l.4-3h-3.1V8.1c0-.9.3-1.6 1.7-1.6h1.8V3.8c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.3V10H7.5v3h2.8v8h3.2Z">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="md:col-span-3 space-y-2">
                        <h4 class="font-bold text-white">Navigasi</h4>
                        <ul class="text-[14px] text-gray-200 space-y-1">
                            <li><a href="{{ url('/beranda') }}">Beranda</a></li>
                            <li><a href="{{ url('/search') }}">Cari</a></li>
                        </ul>
                    </div>
                    <div class="md:col-span-3 space-y-2">
                        <h4 class="font-bold text-white">Informasi</h4>
                        <ul class="text-[14px] text-gray-200 space-y-1">
                            <li><a href="{{ url('/') }}">Tentang Kami</a></li>
                            <li><a href="{{ url('/kontak') }}">Kontak</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/15 bg-black/10 py-4 text-center text-[13px] text-gray-200">
                © 2026 Rangkul. Hak cipta dilindungi undang-undang.
            </div>
        </footer>

    </div>
    @stack('scripts')
</body>

</html>