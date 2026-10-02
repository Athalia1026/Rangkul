@extends('layouts.auth-card')

@section('title', 'Lupa Password')

@section('content')
    <!-- Kembali ke Login -->
    <a href="{{ route('login') }}"
        class="inline-flex items-center gap-2 text-[13.5px] text-gray-950 hover:text-[#065e38] transition-colors">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M20 12H4m7-7-7 7 7 7" />
        </svg>
        Kembali ke Login
    </a>

    <!-- Header -->
    <h1 class="mt-6 text-[22px] sm:text-[24px] font-bold text-gray-950 tracking-tight">Lupa Password?</h1>
    <p class="mt-2 text-[13px] sm:text-[14px] text-gray-950 leading-relaxed">
        Masukkan email Anda yang terdaftar dan kami akan mengirimkan instruksi untuk mengatur ulang Password Anda.
    </p>

    <!-- FORM -->
    <form id="forgot-form" class="mt-6" novalidate>

        <label for="email" class="block text-[13.5px] font-semibold text-gray-950 mb-1.5">Email</label>
        <input type="email" id="email" name="email" required autocomplete="email" placeholder="contoh@email.com"
            aria-describedby="forgot-message"
            class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all" />

        <p id="forgot-message" role="status" class="hidden mt-2 text-[12.5px] font-medium leading-relaxed text-red-600"></p>

        <div class="mt-7 flex justify-center">
            <button type="submit" id="forgot-submit"
                class="inline-flex items-center justify-center gap-3 w-[260px] sm:w-[270px] py-3.5 px-6 rounded-xl bg-[#065e38] hover:bg-[#044a2c] text-white font-bold text-[15px] transition-all shadow-sm hover:shadow-md active:scale-[0.98] cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                <span id="forgot-submit-text">Kirim Instruksi</span>
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M2.5 3.5 22 12 2.5 20.5 6.8 12 2.5 3.5Z" />
                </svg>
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        const form = document.getElementById('forgot-form');
        const email = document.getElementById('email');
        const message = document.getElementById('forgot-message');
        const button = document.getElementById('forgot-submit');
        const buttonText = document.getElementById('forgot-submit-text');

        function showError(text) {
            message.textContent = text;
            message.classList.remove('hidden');
        }

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            message.classList.add('hidden');

            if (!email.value.trim() || !email.checkValidity()) {
                showError('Masukkan alamat email yang valid.');
                email.focus();
                return;
            }

            button.disabled = true;
            buttonText.textContent = 'Mengirim...';

            try {
                const response = await fetch('/api/forgot-password', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email: email.value.trim() })
                });
                const result = await response.json().catch(() => ({}));

                if (response.status === 429) {
                    throw new Error('Terlalu banyak permintaan. Silakan coba lagi dalam 1 menit.');
                }
                if (!response.ok) {
                    throw new Error(Object.values(result.errors || {}).flat()[0] || result.message || 'Gagal mengirimkan instruksi. Silakan coba lagi.');
                }

                // Email disimpan di sessionStorage (bukan URL) untuk ditampilkan & dipakai kirim ulang di halaman cek email.
                try {
                    sessionStorage.setItem('rangkul_reset_email', email.value.trim());
                    sessionStorage.setItem('rangkul_reset_sent_at', String(Date.now()));
                } catch (_) { /* halaman cek email tetap tampil tanpa alamat email */ }

                window.location.assign('{{ route('password.sent') }}');
            } catch (error) {
                showError(error.message);
                button.disabled = false;
                buttonText.textContent = 'Kirim Instruksi';
            }
        });
    </script>
@endpush
