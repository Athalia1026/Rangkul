@extends('layouts.auth-card')

@section('title', 'Atur Ulang Password')

@section('content')
    <!-- Header -->
    <div class="text-center">
        <h1 class="text-[22px] sm:text-[24px] font-bold text-gray-950 tracking-tight">Atur Ulang Password</h1>
        <p class="mt-2 text-[13px] sm:text-[14px] text-gray-500 leading-relaxed">
            Masukkan Password baru Anda untuk mengamankan kembali akun Anda.
        </p>
    </div>

    <!-- FORM -->
    <form id="reset-form" class="mt-6" novalidate data-token="{{ $token }}" data-email="{{ $email }}">

        <!-- PASSWORD BARU -->
        <label for="password" class="block text-[13.5px] font-medium text-gray-950 mb-1.5">Password Baru</label>
        <div class="relative">
            <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password"
                placeholder="Masukkan password anda" aria-describedby="password-help"
                class="w-full px-4 py-2.5 sm:py-3 pr-11 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all" />
            <button type="button" data-toggle="password"
                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-950 transition-colors cursor-pointer p-1"
                aria-label="Tampilkan password"></button>
        </div>
        <p id="password-help" class="mt-1.5 text-[11.5px] text-gray-500 leading-relaxed">
            Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.
        </p>

        <!-- KONFIRMASI PASSWORD -->
        <label for="password_confirmation" class="block mt-4 text-[13.5px] font-medium text-gray-950 mb-1.5">Konfirmasi
            Password Baru</label>
        <div class="relative">
            <input type="password" id="password_confirmation" name="password_confirmation" required
                autocomplete="new-password" placeholder="Ulangi password anda" aria-describedby="confirm-error"
                class="w-full px-4 py-2.5 sm:py-3 pr-11 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all" />
            <button type="button" data-toggle="password_confirmation"
                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-950 transition-colors cursor-pointer p-1"
                aria-label="Tampilkan password"></button>
        </div>
        <p id="confirm-error" class="mt-1.5 min-h-[18px] text-[11.5px] font-medium text-red-600"></p>

        <p id="reset-message" role="status" class="hidden mt-1 text-center text-[12.5px] font-medium leading-relaxed text-red-600"></p>

        <!-- BUTTON -->
        <div class="mt-6 flex flex-col items-center">
            <button type="submit" id="reset-submit"
                class="w-[260px] sm:w-[270px] py-3.5 px-6 rounded-xl bg-[#065e38] hover:bg-[#044a2c] text-white font-bold text-[15px] transition-all shadow-sm hover:shadow-md active:scale-[0.98] cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                Simpan Password Baru
            </button>
            <a href="{{ route('login') }}"
                class="mt-3.5 text-[13px] text-gray-950 underline underline-offset-2 hover:text-[#065e38] transition-colors">
                Kembali ke Login
            </a>
        </div>
    </form>
@endsection

@section('after')
    <!-- POP UP: PASSWORD BERHASIL DIUBAH -->
    <div id="success-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 px-4"
        role="dialog" aria-modal="true" aria-labelledby="success-title" aria-describedby="success-text">
        <div class="w-full max-w-[400px] rounded-2xl bg-white px-6 py-8 sm:px-8 text-center shadow-xl">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#d6f0e2] text-[#05522d]">
                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m5 12.5 4.5 4.5L19 7.5" />
                </svg>
            </div>
            <h2 id="success-title" class="mt-5 text-[20px] font-bold text-gray-950 tracking-tight">Password Berhasil Diubah</h2>
            <p id="success-text" class="mt-2 text-[13.5px] text-gray-600 leading-relaxed">
                Silakan masuk menggunakan password baru Anda. Anda akan diarahkan ke halaman login dalam
                <span id="success-countdown" class="font-bold text-[#05522d]">5</span> detik.
            </p>
            <a href="{{ route('login') }}" id="success-login"
                class="mt-6 inline-flex justify-center w-[220px] py-3 px-6 rounded-xl bg-[#065e38] hover:bg-[#044a2c] text-white font-bold text-[14px] transition-all shadow-sm hover:shadow-md">
                Masuk Sekarang
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const eyeIcon = (crossed = false) => `
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12 5C6.5 5 2.7 9.1 1.5 12c1.2 2.9 5 7 10.5 7s9.3-4.1 10.5-7C21.3 9.1 17.5 5 12 5Zm0 11.2A4.2 4.2 0 1 1 12 7.8a4.2 4.2 0 0 1 0 8.4Zm0-6.4a2.2 2.2 0 1 0 0 4.4 2.2 2.2 0 0 0 0-4.4Z" />
                ${crossed ? '<path d="M3.3 2.3 21.7 20.7l-1.4 1.4L1.9 3.7z" />' : ''}
            </svg>`;

        document.querySelectorAll('[data-toggle]').forEach(button => {
            const input = document.getElementById(button.dataset.toggle);
            button.innerHTML = eyeIcon();
            button.addEventListener('click', () => {
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                button.innerHTML = eyeIcon(isHidden);
                button.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
            });
        });

        const LOGIN_URL = '{{ route('login') }}';
        const REDIRECT_SECONDS = 5;
        const form = document.getElementById('reset-form');
        const password = document.getElementById('password');
        const confirmation = document.getElementById('password_confirmation');
        const confirmError = document.getElementById('confirm-error');
        const message = document.getElementById('reset-message');
        const submit = document.getElementById('reset-submit');
        const MISMATCH = 'Password dan konfirmasi password tidak cocok.';

        function showError(text) {
            message.textContent = text;
            message.classList.remove('hidden');
        }

        function setFieldError(input, hasError) {
            input.classList.toggle('border-red-500', hasError);
            input.classList.toggle('border-gray-300', !hasError);
            input.setAttribute('aria-invalid', String(hasError));
        }

        // Tampilkan pesan tidak cocok segera setelah pengguna mulai mengisi konfirmasi.
        function checkMatch() {
            const mismatch = confirmation.value !== '' && confirmation.value !== password.value;
            confirmError.textContent = mismatch ? MISMATCH : '';
            setFieldError(confirmation, mismatch);
        }
        password.addEventListener('input', checkMatch);
        confirmation.addEventListener('input', checkMatch);

        function showSuccess() {
            const modal = document.getElementById('success-modal');
            const countdown = document.getElementById('success-countdown');
            let remaining = REDIRECT_SECONDS;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            document.getElementById('success-login').focus();

            const timer = setInterval(() => {
                remaining -= 1;
                countdown.textContent = String(Math.max(remaining, 0));
                if (remaining <= 0) {
                    clearInterval(timer);
                    window.location.replace(LOGIN_URL);
                }
            }, 1000);
        }

        if (!form.dataset.token || !form.dataset.email) {
            showError('Link reset password tidak valid. Silakan minta link baru melalui halaman Lupa Password.');
            submit.disabled = true;
        }

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            message.classList.add('hidden');

            if (password.value.length < 8 || !/[A-Za-z]/.test(password.value) || !/[0-9]/.test(password.value)) {
                setFieldError(password, true);
                showError('Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.');
                password.focus();
                return;
            }
            setFieldError(password, false);

            if (!confirmation.value || confirmation.value !== password.value) {
                confirmError.textContent = MISMATCH;
                setFieldError(confirmation, true);
                confirmation.focus();
                return;
            }

            submit.disabled = true;
            submit.textContent = 'Menyimpan...';

            try {
                const response = await fetch('/api/reset-password', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        token: form.dataset.token,
                        email: form.dataset.email,
                        password: password.value,
                        password_confirmation: confirmation.value
                    })
                });
                const result = await response.json().catch(() => ({}));

                if (response.status === 429) {
                    throw new Error('Terlalu banyak percobaan. Silakan coba lagi dalam 1 menit.');
                }
                if (!response.ok) {
                    throw new Error(Object.values(result.errors || {}).flat()[0] || result.message || 'Password belum dapat diperbarui. Silakan coba lagi.');
                }

                // Token reset sudah terpakai: kunci form, kembalikan teks tombol, lalu tampilkan pop up berhasil.
                submit.textContent = 'Simpan Password Baru';
                form.querySelectorAll('input, button').forEach(element => element.disabled = true);
                try { sessionStorage.removeItem('rangkul_reset_email'); sessionStorage.removeItem('rangkul_reset_sent_at'); } catch (_) { }
                showSuccess();
            } catch (error) {
                showError(error.message);
                submit.disabled = false;
                submit.textContent = 'Simpan Password Baru';
            }
        });
    </script>
@endpush
