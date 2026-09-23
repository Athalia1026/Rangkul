{{-- resources/views/layouts/footeronly.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rangkul - Platform Donasi Terpercaya')</title>
    <!-- Google Font Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f5f7f4] text-[#000000] font-['Plus_Jakarta_Sans',sans-serif] antialiased flex flex-col items-center">

    <div class="w-full max-w-[1200px] bg-[#F5F7F4] shadow-xl flex flex-col">
    <!-- MAIN CONTENT INJECTION -->
    <main class="w-full flex-1">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="w-full bg-[#05522d] text-white">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
                <div class="md:col-span-6 space-y-4">
                    <img src="{{ asset('images/logo-light.png') }}" alt="Rangkul Logo" class="h-14 max-w-full w-auto">
                    <p class="text-[14px] text-gray-200 max-w-md">Rangkul hadir untuk menghubungkan kebaikan melalui platform donasi yang aman, transparan, dan berdampak bagi mereka yang membutuhkan.</p>
                    <div class="flex items-center gap-3 pt-1">
                        <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram Rangkul" class="text-[#b7e4c7] hover:text-white transition-colors">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-6 h-6" aria-hidden="true">
                                <rect x="3" y="3" width="18" height="18" rx="5"/>
                                <circle cx="12" cy="12" r="4"/>
                                <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
                            </svg>
                        </a>
                        <a href="https://www.tiktok.com/" target="_blank" rel="noopener noreferrer" aria-label="TikTok Rangkul" class="text-[#b7e4c7] hover:text-white transition-colors">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6" aria-hidden="true">
                                <path d="M16.5 3c.3 1.8 1.3 3 3.5 3.2v3.1c-1.3 0-2.5-.3-3.5-.9v6.7c0 3.7-2.6 5.9-6 5.9-3.1 0-5.5-2.2-5.5-5.2 0-3.2 2.7-5.4 6-5.4.3 0 .7 0 1 .1v3.2c-.3-.1-.6-.2-1-.2-1.4 0-2.7.9-2.7 2.3 0 1.3 1 2.2 2.3 2.2 1.5 0 2.4-.9 2.4-2.8V3h3.5Z"/>
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook Rangkul" class="text-[#b7e4c7] hover:text-white transition-colors">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6" aria-hidden="true">
                                <path d="M13.5 21v-8h2.7l.4-3h-3.1V8.1c0-.9.3-1.6 1.7-1.6h1.8V3.8c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.3V10H7.5v3h2.8v8h3.2Z"/>
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
            © {{ date('Y') }} Rangkul. Hak cipta dilindungi undang-undang.
        </div>
    </footer>

    @stack('scripts')
    </div>
</body>
</html>

