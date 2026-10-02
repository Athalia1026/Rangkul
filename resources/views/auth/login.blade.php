<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Rangkul.com</title>

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
    class="h-screen w-full bg-[#fcfdfd] text-[#000000] antialiased flex flex-row overflow-hidden relative selection:bg-[#d8f0e2] selection:text-[#05522d]">

    <!-- ============================================================ -->
    <!-- LEFT COLUMN                                                -->
    <!-- ============================================================ -->

    <aside aria-label="Rangkul Banner"
        class="relative w-[36%] sm:w-[40%] md:w-[44%] lg:w-[44%] xl:w-[44%] h-full shrink-0 overflow-hidden bg-gray-900 select-none z-20">

        <!-- Background Image -->
        <img src="{{ asset('images/register.png') }}" alt="Anak-anak Rangkul"
            class="w-full h-full object-cover object-[center_20%] pointer-events-none" />

        <!-- Logo -->
        <div class="absolute top-5 left-5 sm:top-7 sm:left-7 z-10">

            <a href="{{ url('/') }}"
                class="inline-flex cursor-pointer group text-left transition-opacity hover:opacity-90">
                <img src="{{ asset('images/logo.png') }}" alt="Rangkul.com Donation Platform"
                    class="w-44 sm:w-52 h-auto object-contain origin-left group-hover:scale-[1.02] transition-transform" />
            </a>

        </div>

    </aside>


    <!-- ============================================================ -->
    <!-- RIGHT COLUMN                                               -->
    <!-- ============================================================ -->

    <main
        class="flex-1 h-full overflow-y-auto overflow-x-hidden relative isolate flex flex-col items-center pt-8 sm:pt-12 pb-14 px-4 sm:px-8 md:px-10 lg:px-14 xl:px-20 scroll-smooth">

        <!-- Soft Background Decoration -->
        <x-auth-background />

        <!-- ============================================================ -->
        <!-- LOGIN CONTAINER                                            -->
        <!-- ============================================================ -->

        <div class="w-full max-w-[450px] mx-auto z-10">

            <!-- Header -->
            <div class="text-center mb-6 sm:mb-7">

                <h1 class="text-[27px] sm:text-[31px] font-bold text-gray-950 tracking-tight leading-tight">
                    Selamat Datang Kembali!
                </h1>

                <p class="text-[13px] sm:text-[14px] text-gray-600 mt-2 leading-relaxed max-w-[430px] mx-auto">
                    Masuk ke akun Anda dan terus berikan dukungan terbaik
                    bagi panti asuhan yang membutuhkan.
                </p>

            </div>


            <!-- Error Message -->
            <div id="login-error" role="alert"
                class="hidden mb-4 px-4 py-3 rounded-xl border border-red-200 bg-red-50 text-red-700 text-[12.5px] font-medium leading-relaxed">
            </div>


            <!-- ============================================================ -->
            <!-- FORM                                                       -->
            <!-- ============================================================ -->

            <form id="login-form" action="{{ route('login.store') }}" method="POST" class="space-y-4">

                <!-- EMAIL -->
                <div>

                    <label for="email" class="block text-[13.5px] font-medium text-gray-950 mb-1.5">
                        Email
                    </label>

                    <input type="email" id="email" name="email" required placeholder="contoh@email.com"
                        autocomplete="email"
                        class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all" />

                </div>


                <!-- PASSWORD -->
                <div>

                    <div class="flex items-center justify-between mb-1.5">

                        <label for="passwordInput" class="block text-[13.5px] font-medium text-gray-950">
                            Password
                        </label>

                    </div>


                    <div class="relative">

                        <input type="password" id="passwordInput" name="password" required
                            placeholder="Masukkan password anda" autocomplete="current-password"
                            class="w-full px-4 py-2.5 sm:py-3 pr-11 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all" />


                        <!-- Toggle Password -->
                        <button type="button" onclick="togglePassword('passwordInput', this)"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-950 transition-colors cursor-pointer p-1"
                            aria-label="Tampilkan password">

                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2.5 12s3.5-5 9.5-5 9.5 5 9.5 5-3.5 5-9.5 5-9.5-5-9.5-5Z" />

                                <circle cx="12" cy="12" r="2.5" />
                            </svg>

                        </button>

                    </div>

                    <div class="mt-2 text-right">
                        <a href="#"
                            class="text-[11px] sm:text-[11.5px] font-medium text-gray-500 hover:text-[#065e38] transition-colors">
                            Lupa password?
                        </a>
                    </div>

                </div>


                <!-- BUTTON -->
                <div class="pt-4 flex flex-col items-center">

                    <button type="submit"
                        class="login-button w-[260px] sm:w-[270px] py-3.5 px-6 rounded-xl bg-[#065e38] hover:bg-[#044a2c] text-white font-bold text-[15px] transition-all shadow-sm hover:shadow-md active:scale-[0.98] cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                        Masuk
                    </button>


                    <!-- REGISTER LINK -->
                    <p class="mt-3.5 text-[13px] text-gray-950 text-center font-normal">
                        Belum memiliki akun?

                        <a href="{{ route('register') }}"
                            class="font-bold underline text-gray-950 hover:text-[#065e38] cursor-pointer transition-colors">
                            Daftar sekarang
                        </a>
                    </p>

                </div>

            </form>

        </div>

    </main>


    <!-- ============================================================ -->
    <!-- SCRIPT                                                     -->
    <!-- ============================================================ -->

    <script>
        /*
        |--------------------------------------------------------------------------
        | TOGGLE PASSWORD
        |--------------------------------------------------------------------------
        */

        const eyeIcon = `
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M2.5 12s3.5-5 9.5-5 9.5 5 9.5 5-3.5 5-9.5 5-9.5-5-9.5-5Z" />
                <circle cx="12" cy="12" r="2.5" />
            </svg>`;

        const eyeOffIcon = `
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M2.5 12s3.5-5 9.5-5 9.5 5 9.5 5-3.5 5-9.5 5-9.5-5-9.5-5Z" />
                <circle cx="12" cy="12" r="2.5" />
                <path d="m4 4 16 16" />
            </svg>`;

        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';
            button.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
            button.innerHTML = isHidden ? eyeOffIcon : eyeIcon;
        }


        /*
        |--------------------------------------------------------------------------
        | LOGIN
        |--------------------------------------------------------------------------
        */

        document.getElementById('login-form').addEventListener('submit', async function (event) {
            event.preventDefault();

            const button = this.querySelector('.login-button');
            const errorMessage = document.getElementById('login-error');
            const originalText = button.textContent.trim();
            const email = document.getElementById('email').value;

            errorMessage.classList.add('hidden');
            errorMessage.textContent = '';

            button.disabled = true;
            button.textContent = 'Memproses...';

            try {
                const response = await fetch('{{ route('login.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email,
                        password: document.getElementById('passwordInput').value
                    })
                });

                const result = await response.json().catch(() => ({}));

                if (result.code === 'ORGANIZATION_UNVERIFIED') {
                    window.location.href = '{{ route('organization.pending') }}';
                    return;
                }

                if (result.code === 'ORGANIZATION_REJECTED') {
                    window.location.href = '{{ route('organization.rejected') }}' + '?email=' + encodeURIComponent(email);
                    return;
                }

                if (!response.ok) {
                    throw new Error(result.message || 'Login gagal. Silakan periksa kembali data Anda.');
                }

                const authToken = result.access_token || result.token;

                if (!authToken) {
                    throw new Error('Token login tidak diterima dari server.');
                }

                localStorage.setItem('auth_token', authToken);
                localStorage.setItem('auth_user', JSON.stringify(result.user));

                const redirectByAccountType = {
                    organisasi: '{{ route('organisasi.dashboard') }}',
                    admin: '/manager/home',
                    donatur: '{{ route('donatur.beranda') }}'
                };

                window.location.href = redirectByAccountType[result.user?.account_type] || '{{ route('donatur.beranda') }}';
            } catch (error) {
                errorMessage.textContent = error.message;
                errorMessage.classList.remove('hidden');
                button.disabled = false;
                button.textContent = originalText;
            }
        });
    </script>

</body>

</html>