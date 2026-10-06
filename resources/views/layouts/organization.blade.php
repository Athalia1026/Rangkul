<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-auth-session-guard />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Dashboard organisasi Rangkul untuk mengelola kampanye, donasi, kunjungan, dan laporan.">
    <title>{{ $title ?? 'Organisasi' }} - Rangkul.com</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Konten halaman organisasi didesain dengan ukuran px yang terlalu besar (pas di zoom 70%),
         jadi konten diskalakan ke 0.7 agar setara dengan halaman donatur di zoom 100%.
         Navbar & footer berada di luar skala karena sudah memakai ukuran donatur. --}}
    <style>
        .org-scale {
            zoom: 0.7;
        }

        /* Lebar konten 1200px seperti halaman donatur (dibagi 0.7 karena ikut terkena zoom). */
        .org-container {
            width: 100%;
            max-width: calc(1200px / 0.7);
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body class="bg-[#F5F7F4] text-gray-800 font-['Plus_Jakarta_Sans',sans-serif] antialiased min-h-screen flex flex-col">
    @include('components.organization-navbar', ['activeNav' => $activeNav ?? null])

    <div class="org-scale w-full flex-grow">
        @yield('content')
    </div>

    @include('components.organization-footer')

    {{-- Pesan sukses/gagal: dari session (redirect) atau dari window.orgFlash() (fetch). --}}
    <div id="org-flash"
        class="{{ session('success') || session('error') ? '' : 'hidden' }} fixed top-24 right-6 z-50 max-w-sm rounded-xl px-5 py-4 text-[15px] font-medium shadow-lg {{ session('error') ? 'bg-red-600 text-white' : 'bg-[#05522d] text-white' }}"
        role="status">{{ session('success') ?? session('error') }}</div>

    <script>
        window.orgFlash = function (message, type = 'success') {
            const flash = document.getElementById('org-flash');
            flash.textContent = message;
            flash.classList.remove('hidden', 'bg-red-600', 'bg-[#05522d]');
            flash.classList.add(type === 'error' ? 'bg-red-600' : 'bg-[#05522d]');
            clearTimeout(window.orgFlashTimer);
            window.orgFlashTimer = setTimeout(() => flash.classList.add('hidden'), 5000);
        };

        // Kirim request ke controller (sesi web + CSRF). Melempar Error berisi pesan validasi pertama jika gagal.
        window.orgRequest = async function (url, { method = 'POST', body = null } = {}) {
            const isFormData = body instanceof FormData;

            if (isFormData && method !== 'POST') {
                body.append('_method', method);
                method = 'POST';
            }

            const response = await fetch(url, {
                method,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    ...(body && !isFormData ? { 'Content-Type': 'application/json' } : {}),
                },
                body: body && !isFormData ? JSON.stringify(body) : body,
            });

            const result = await response.json().catch(() => ({}));

            if (!response.ok) {
                const firstError = result.errors ? Object.values(result.errors)[0][0] : null;
                throw new Error(firstError || result.message || 'Terjadi kesalahan. Silakan coba lagi.');
            }

            return result;
        };

        if (!document.getElementById('org-flash').classList.contains('hidden')) {
            window.orgFlashTimer = setTimeout(() => document.getElementById('org-flash').classList.add('hidden'), 5000);
        }
    </script>

    @stack('scripts')
</body>
</html>
