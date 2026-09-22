<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Peran Pendaftaran - Rangkul.com</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen w-full bg-[#fcfdfd] text-[#000000] antialiased flex flex-col items-center justify-center p-6 sm:p-10 relative overflow-hidden selection:bg-[#d8f0e2] selection:text-[#05522d]">

    <!-- Subtle Hint Lingkaran Blur Tosca Sesuai Gambar Referensi -->
    <div class="absolute -top-16 right-[28%] w-80 h-80 rounded-full bg-[#48ca9a]/18 blur-[70px] pointer-events-none"></div>
    <div class="absolute top-1/2 -left-20 -translate-y-1/2 w-96 h-96 rounded-full bg-[#52d2a4]/22 blur-[80px] pointer-events-none"></div>
    <div class="absolute top-[18%] -right-16 w-80 h-80 rounded-full bg-[#3abf8d]/18 blur-[75px] pointer-events-none"></div>
    <div class="absolute -bottom-20 right-10 w-96 h-96 rounded-full bg-[#40c797]/16 blur-[85px] pointer-events-none"></div>

    <!-- Optional Top Left Navigation back to Home -->
    <div class="absolute top-6 left-6 sm:top-8 sm:left-8 z-30">
        <a
            href="{{ url('/') }}"
            class="inline-flex items-center gap-2 text-[13px] text-gray-600 hover:text-gray-950 font-medium transition-colors cursor-pointer"
        >
            &larr; Kembali ke Beranda
        </a>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-[820px] mx-auto flex flex-col items-center z-10 py-6">
        
        <!-- Header Section -->
        <div class="text-center mb-10 sm:mb-12">
            <h1 class="text-[28px] sm:text-[34px] font-bold text-gray-950 tracking-tight leading-tight">
                Bergabung Bersama Rangkul
            </h1>
            <p class="text-[13.5px] sm:text-[15px] text-gray-600 mt-2.5 max-w-[480px] mx-auto leading-relaxed">
                Pilih peran yang sesuai untuk mulai terhubung dan berkontribusi bersama Rangkul.
            </p>
        </div>

        <!-- Two Role Cards Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 w-full max-w-[760px]">
            
            <!-- Card 1: Daftar sebagai Donatur -->
            <div class="bg-white rounded-2xl p-8 sm:p-9 flex flex-col items-center text-center shadow-[0_10px_35px_-8px_rgba(0,0,0,0.06)] border border-gray-100 hover:shadow-xl transition-all duration-200">
                <!-- Soft Green Avatar Circle with User Icon -->
                <div class="w-14 h-14 rounded-full bg-[#d6f0e2] flex items-center justify-center mb-5 text-[#05522d]">
                    <svg class="w-6 h-6 fill-[#05522d]" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>

                <!-- Title -->
                <h2 class="text-[19px] sm:text-[20px] font-bold text-[#05522d] mb-2.5">
                    Daftar sebagai Donatur
                </h2>

                <!-- Description -->
                <p class="text-[13px] sm:text-[13.5px] text-gray-600 leading-relaxed mb-7 flex-1 max-w-[280px]">
                    Salurkan kebaikan Anda dan dukung berbagai kebutuhan sosial melalui donasi yang tepat sasaran.
                </p>

                <!-- CTA Button (Menggunakan Route Helper Laravel) -->
                <a
                    href="{{ route('register-donors') }}"
                    class="w-full py-3 px-6 rounded-xl bg-[#065e38] hover:bg-[#044a2c] text-white font-bold text-[14.5px] transition-all shadow-sm hover:shadow-md active:scale-98 text-center block"
                >
                    Gabung sebagai Donatur
                </a>
            </div>

            <!-- Card 2: Daftar sebagai Organisasi -->
            <div class="bg-white rounded-2xl p-8 sm:p-9 flex flex-col items-center text-center shadow-[0_10px_35px_-8px_rgba(0,0,0,0.06)] border border-gray-100 hover:shadow-xl transition-all duration-200">
                <!-- Soft Blue Avatar Circle with User Icon -->
                <div class="w-14 h-14 rounded-full bg-[#e1f0fa] flex items-center justify-center mb-5 text-[#0d3b59]">
                    <svg class="w-6 h-6 fill-[#0d3b59]" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>

                <!-- Title -->
                <h2 class="text-[19px] sm:text-[20px] font-bold text-[#0d3b59] mb-2.5">
                    Daftar sebagai Organisasi
                </h2>

                <!-- Description -->
                <p class="text-[13px] sm:text-[13.5px] text-gray-600 leading-relaxed mb-7 flex-1 max-w-[280px]">
                    Daftarkan organisasi dan buat kampanye penggalangan dana untuk kebutuhan panti atau sekolah.
                </p>

                <!-- CTA Button (Menggunakan Route Helper Laravel) -->
                <a
                    href="{{ route('register-organizations') }}"
                    class="w-full py-3 px-6 rounded-xl bg-[#0d3b59] hover:bg-[#08293e] text-white font-bold text-[14.5px] transition-all shadow-sm hover:shadow-md active:scale-98 text-center block"
                >
                    Gabung sebagai Organisasi
                </a>
            </div>

        </div>

    </div>

</body>
</html>