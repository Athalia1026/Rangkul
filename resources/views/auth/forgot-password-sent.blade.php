@extends('layouts.auth-card')

@section('title', 'Cek Email Anda')

@section('content')
    <div class="text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#d6f0e2] text-[#05522d]">
            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="3" y="5" width="18" height="14" rx="2" />
                <path d="m3.5 6.5 8.5 6 8.5-6" />
            </svg>
        </div>

        <h1 class="mt-5 text-[22px] sm:text-[24px] font-bold text-gray-950 tracking-tight">Cek Email Anda</h1>
        <p class="mt-2 text-[13px] sm:text-[14px] text-gray-600 leading-relaxed">
            Kami telah mengirimkan link untuk mengatur ulang password ke
            <span id="sent-email" class="font-semibold text-gray-950">email Anda</span>.
        </p>
    </div>

    <ol class="mt-6 space-y-2.5 rounded-xl bg-[#effcf8] px-5 py-4 text-[13px] text-gray-800 leading-relaxed list-decimal list-inside">
        <li>Buka kotak masuk email Anda.</li>
        <li>Klik tombol <strong>Reset Password</strong> pada email dari Rangkul.</li>
        <li>Masukkan password baru Anda.</li>
    </ol>

    <p class="mt-4 text-[12px] text-gray-500 leading-relaxed text-center">
        Tidak menemukan email? Periksa folder spam atau promosi. Link berlaku selama 60 menit.
    </p>

    <p id="resend-message" role="status" class="hidden mt-3 text-center text-[12.5px] font-medium leading-relaxed"></p>

    <div class="mt-6 flex flex-col items-center">
        <button type="button" id="resend-button"
            class="w-[260px] sm:w-[270px] py-3.5 px-6 rounded-xl bg-[#065e38] hover:bg-[#044a2c] text-white font-bold text-[15px] transition-all shadow-sm hover:shadow-md active:scale-[0.98] cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
            Kirim Ulang Email
        </button>
        <a href="{{ route('login') }}"
            class="mt-3.5 text-[13px] text-gray-950 underline underline-offset-2 hover:text-[#065e38] transition-colors">
            Kembali ke Login
        </a>
    </div>
@endsection

@push('scripts')
    <script>
        const COOLDOWN_SECONDS = 60;
        const button = document.getElementById('resend-button');
        const message = document.getElementById('resend-message');
        let email = null, sentAt = 0, timer = null;

        try {
            email = sessionStorage.getItem('rangkul_reset_email');
            sentAt = Number(sessionStorage.getItem('rangkul_reset_sent_at') || 0);
        } catch (_) { /* tanpa sessionStorage, tombol kirim ulang diarahkan ke halaman lupa password */ }

        if (email) document.getElementById('sent-email').textContent = email;

        function showMessage(text, success = false) {
            message.textContent = text;
            message.classList.remove('hidden', 'text-red-600', 'text-[#05522d]');
            message.classList.add(success ? 'text-[#05522d]' : 'text-red-600');
        }

        // Tombol kirim ulang dikunci 60 detik sejak email terakhir dikirim.
        function startCooldown() {
            clearInterval(timer);
            const tick = () => {
                const remaining = Math.ceil((sentAt + COOLDOWN_SECONDS * 1000 - Date.now()) / 1000);
                if (remaining > 0) {
                    button.disabled = true;
                    button.textContent = `Kirim Ulang (${remaining} detik)`;
                } else {
                    clearInterval(timer);
                    button.disabled = false;
                    button.textContent = 'Kirim Ulang Email';
                }
            };
            tick();
            timer = setInterval(tick, 1000);
        }
        startCooldown();

        button.addEventListener('click', async () => {
            if (!email) {
                window.location.assign('{{ route('password.request') }}');
                return;
            }

            button.disabled = true;
            button.textContent = 'Mengirim...';
            message.classList.add('hidden');

            try {
                const response = await fetch('/api/forgot-password', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email })
                });
                const result = await response.json().catch(() => ({}));

                if (response.status === 429) {
                    throw new Error('Terlalu banyak permintaan. Silakan coba lagi dalam 1 menit.');
                }
                if (!response.ok) {
                    throw new Error(Object.values(result.errors || {}).flat()[0] || result.message || 'Gagal mengirim ulang email.');
                }

                sentAt = Date.now();
                try { sessionStorage.setItem('rangkul_reset_sent_at', String(sentAt)); } catch (_) { }
                showMessage('Email berhasil dikirim ulang. Silakan cek kotak masuk Anda.', true);
            } catch (error) {
                showMessage(error.message);
                sentAt = Date.now();
            }
            startCooldown();
        });
    </script>
@endpush
