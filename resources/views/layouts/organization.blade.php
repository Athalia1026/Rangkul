<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard organisasi Rangkul untuk mengelola kampanye, donasi, kunjungan, dan laporan.">
    <title>{{ $title ?? 'Organisasi' }} - Rangkul.com</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#F5F7F4] text-gray-800 antialiased min-h-screen flex flex-col">
    <div class="w-full bg-[#F5F7F4] flex flex-col min-h-screen">
        @include('components.organization-navbar', ['activeNav' => $activeNav ?? null])

        <div class="flex-grow">
            @yield('content')
        </div>

        @include('components.organization-footer')
    </div>

    @stack('scripts')
</body>
</html>
