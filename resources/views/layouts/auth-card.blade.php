{{-- Layout kartu di tengah untuk halaman lupa / atur ulang password. Ukuran teks & input mengikuti halaman login. --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title') - Rangkul.com</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body
    class="min-h-screen w-full bg-[#f5f7f4] text-[#000000] antialiased flex items-center justify-center px-4 py-10 selection:bg-[#d8f0e2] selection:text-[#05522d]">

    <main class="w-full max-w-[480px] bg-white rounded-2xl shadow-2xs px-6 py-8 sm:px-9 sm:py-9">
        @yield('content')
    </main>

    @yield('after')

    @stack('scripts')
</body>

</html>
