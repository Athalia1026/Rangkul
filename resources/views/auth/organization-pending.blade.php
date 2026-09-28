<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Verifikasi - Rangkul.com</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen w-full bg-[#fcfdfd] text-[#000000] antialiased flex flex-row overflow-hidden relative">
    <aside aria-label="Rangkul Banner" class="relative w-[36%] sm:w-[40%] md:w-[44%] lg:w-[44%] xl:w-[44%] h-screen shrink-0 overflow-hidden bg-gray-900 select-none">
        <img src="{{ asset('images/register.png') }}" alt="Anak-anak Rangkul" class="w-full h-full object-cover object-[center_20%] pointer-events-none">
        <div class="absolute inset-0 bg-white/10 pointer-events-none"></div>
    </aside>

    <main class="relative flex-1 min-h-screen overflow-hidden flex items-center justify-center px-8 sm:px-12 lg:px-20">
        <div class="absolute -top-10 -right-10 w-60 h-60 rounded-full blur-[55px] pointer-events-none" style="background-color: rgba(72, 202, 154, 0.16);"></div>
        <div class="absolute bottom-[-5rem] left-[18%] w-64 h-64 rounded-full blur-[60px] pointer-events-none" style="background-color: rgba(58, 191, 141, 0.18);"></div>
        <div class="absolute top-[28%] right-[-3rem] w-52 h-52 rounded-full blur-[55px] pointer-events-none" style="background-color: rgba(86, 212, 166, 0.14);"></div>

        <a href="{{ url('/') }}" class="absolute top-7 right-8 sm:top-9 sm:right-12 z-10 transition-opacity hover:opacity-80">
            <img src="{{ asset('images/logo.png') }}" alt="Rangkul.com Donation Platform" class="w-44 sm:w-52 h-auto object-contain">
        </a>

        <section class="relative z-10 max-w-[560px] text-center">
            <div class="mx-auto mb-7 flex h-16 w-16 items-center justify-center rounded-full bg-[#d6f0e2] text-[#05522d]">
                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 6v6l4 2" />
                    <circle cx="12" cy="12" r="8.5" />
                </svg>
            </div>
            <h1 class="text-[30px] sm:text-[36px] font-bold tracking-tight leading-tight text-gray-950">Terima Kasih Telah Mendaftar</h1>
            <p class="mt-3 text-[18px] sm:text-[20px] text-gray-600 leading-relaxed">Silahkan tunggu email dari Rangkul</p>
            <a href="{{ route('login') }}" class="mt-8 inline-flex rounded-xl bg-[#065e38] px-7 py-3 text-[14px] font-bold text-white shadow-sm transition hover:bg-[#044a2c]">Kembali ke Login</a>
        </section>
    </main>
</body>
</html>
