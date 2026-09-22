<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Ditolak - Rangkul.com</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-screen w-full bg-[#fcfdfd] text-[#000000] antialiased flex flex-row overflow-hidden relative">
    <aside class="relative w-[36%] sm:w-[40%] md:w-[44%] lg:w-[44%] xl:w-[44%] h-screen shrink-0 overflow-hidden bg-gray-900 select-none">
        <img src="{{ asset('images/register.png') }}" alt="Anak-anak Rangkul" class="w-full h-full object-cover object-[center_20%] pointer-events-none">
    </aside>
    <main class="relative flex-1 min-h-screen overflow-hidden flex items-center justify-center px-8 sm:px-12 lg:px-20">
        <div class="absolute -top-10 -right-10 w-60 h-60 rounded-full blur-[55px] pointer-events-none" style="background-color: rgba(72, 202, 154, 0.16);"></div>
        <div class="absolute bottom-[-5rem] left-[18%] w-64 h-64 rounded-full blur-[60px] pointer-events-none" style="background-color: rgba(58, 191, 141, 0.18);"></div>
        <a href="{{ url('/') }}" class="absolute top-7 right-8 sm:top-9 sm:right-12 z-10 transition-opacity hover:opacity-80"><img src="{{ asset('images/logo.png') }}" alt="Rangkul.com Donation Platform" class="w-44 sm:w-52 h-auto object-contain"></a>
        <section class="relative z-10 max-w-[560px] text-center">
            <div class="mx-auto mb-7 flex h-16 w-16 items-center justify-center rounded-full bg-red-100 text-red-600">
                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="8.5" /><path d="m9 9 6 6M15 9l-6 6" /></svg>
            </div>
            <h1 class="text-[30px] sm:text-[36px] font-bold tracking-tight leading-tight text-gray-950">Registrasi Organisasi Ditolak</h1>
            <p class="mt-3 text-[18px] sm:text-[20px] text-gray-600 leading-relaxed">Silakan perbaiki data atau dokumen yang ditolak.</p>
            @if ($user->organization->alasan_penolakan)
                <p class="mx-auto mt-4 max-w-[480px] rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-left text-[13px] leading-relaxed text-red-700"><strong>Alasan Admin:</strong> {{ $user->organization->alasan_penolakan }}</p>
            @endif
            @if ($bankAccount?->status_verifikasi === 'ditolak')
                <p class="mx-auto mt-3 max-w-[480px] rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-left text-[13px] leading-relaxed text-red-700"><strong>Rekening bank ditolak.</strong> Data rekening perlu diperbaiki dan dikirim ulang untuk diverifikasi.</p>
            @endif
            <p class="mx-auto mt-4 max-w-[470px] text-[13px] sm:text-[14px] leading-relaxed text-gray-500">Data sebelumnya akan tetap tersedia pada formulir registrasi ulang. Dokumen yang ditolak perlu diperbaiki dan diunggah kembali.</p>
            <a href="{{ route('organization.resubmit.form', ['email' => $user->email]) }}" class="mt-8 inline-flex rounded-xl bg-[#065e38] px-7 py-3 text-[14px] font-bold text-white shadow-sm transition hover:bg-[#044a2c]">Registrasi Ulang</a>
        </section>
    </main>
</body>
</html>
