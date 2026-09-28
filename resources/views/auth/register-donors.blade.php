<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Rangkul.com</title>
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
<body class="h-screen w-full bg-[#fcfdfd] text-[#000000] antialiased flex flex-row overflow-hidden relative selection:bg-[#d8f0e2] selection:text-[#05522d]">

    <!-- ============================================================ -->
    <!-- LEFT COLUMN: HERO BANNER (FIXED, NEVER MOVES ON SCROLL)     -->
    <!-- ============================================================ -->
    <aside
        aria-label="Rangkul Banner"
        class="relative w-[36%] sm:w-[40%] md:w-[44%] lg:w-[44%] xl:w-[44%] h-full shrink-0 overflow-hidden bg-gray-900 select-none z-20"
    >
        <!-- Children Image Area -->
        <img
            src="{{ asset('images/register.png') }}"
            alt="Anak-anak Rangkul"
            class="w-full h-full object-cover object-[center_20%] pointer-events-none"
        />

        <!-- Brand Logo Overlay -->
        <div class="absolute top-5 left-5 sm:top-7 sm:left-7 z-10">
            <a
                href="{{ url('/') }}"
                class="inline-flex cursor-pointer group text-left transition-opacity hover:opacity-90"
            >
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="Rangkul.com Donation Platform"
                    class="w-44 sm:w-52 h-auto object-contain origin-left group-hover:scale-[1.02] transition-transform"
                />
            </a>
        </div>
    </aside>

    <!-- ============================================================ -->
    <!-- RIGHT COLUMN: SCROLLABLE REGISTRATION FORM                   -->
    <!-- ============================================================ -->
    <main class="flex-1 h-full overflow-y-auto overflow-x-hidden relative isolate flex flex-col items-center pt-8 sm:pt-12 pb-14 px-4 sm:px-8 md:px-10 lg:px-14 xl:px-20 scroll-smooth">
        
        <!-- Subtle Hint Lingkaran Blur Tosca (Background Tetap Bersih & Dominan) -->
        <div class="absolute -top-10 -right-10 z-0 w-60 h-60 rounded-full blur-[55px] pointer-events-none" style="background-color: rgba(72, 202, 154, 0.16);"></div>
        <div class="absolute bottom-16 left-6 sm:left-12 z-0 w-56 h-56 rounded-full blur-[50px] pointer-events-none" style="background-color: rgba(58, 191, 141, 0.18);"></div>
        <div class="absolute -bottom-10 -right-10 z-0 w-64 h-64 rounded-full blur-[60px] pointer-events-none" style="background-color: rgba(86, 212, 166, 0.14);"></div>
        <div class="absolute left-[-2.5rem] top-[36%] z-0 hidden h-24 w-24 rounded-full blur-[35px] pointer-events-none lg:block" style="background-color: rgba(72, 202, 154, 0.12);"></div>
        <div class="absolute right-[-2rem] top-[52%] z-0 hidden h-20 w-20 rounded-full blur-[30px] pointer-events-none lg:block" style="background-color: rgba(58, 191, 141, 0.14);"></div>

        <!-- Form Card Container -->
        <div class="w-full max-w-[450px] mx-auto z-10">
            
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-7">
                <h1 class="text-[27px] sm:text-[31px] font-bold text-gray-950 tracking-tight leading-tight">
                    Bergabung Bersama Rangkul
                </h1>
                <p class="text-[13px] sm:text-[14px] text-gray-600 mt-2 leading-relaxed max-w-[430px] mx-auto">
                    Daftarkan akun Anda untuk mulai berdonasi, mengajukan kunjungan, dan memantau setiap kontribusi yang telah Anda berikan.
                </p>
            </div>

            <!-- Form -->
            <form action="{{ route('register.store') }}" method="POST" class="space-y-4" id="registerForm">
                @csrf
                
                <!-- 1. Nama -->
                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">
                        Nama
                    </label>
                    <input
                        type="text"
                        name="nama"
                        required
                        value="{{ old('nama') }}"
                        class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 text-[14px] bg-white transition-all shadow-2xs"
                    />
                </div>

                <!-- 2. Email -->
                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        required
                        value="{{ old('email') }}"
                        placeholder="contoh@email.com"
                        class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all shadow-2xs"
                    />
                </div>

                <!-- 3. Password -->
                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">
                        Password
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="passwordInput"
                            name="password"
                            required
                            minlength="8"
                            pattern="(?=.*[A-Za-z])(?=.*[0-9]).{8,}"
                            placeholder="Masukkan password anda"
                            oninput="validatePasswordRequirements()"
                            class="w-full px-4 py-2.5 sm:py-3 pr-11 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all shadow-2xs"
                        />
                        <button
                            type="button"
                            onclick="togglePassword('passwordInput', this)"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-950 transition-colors cursor-pointer p-1"
                            aria-label="Tampilkan password"
                        >
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2.5 12s3.5-5 9.5-5 9.5 5 9.5 5-3.5 5-9.5 5-9.5-5-9.5-5Z" />
                                <circle cx="12" cy="12" r="2.5" />
                            </svg>
                        </button>
                    </div>
                    <p id="passwordRequirementsError" class="text-[12px] text-red-600 mt-1.5 font-medium leading-normal hidden">
                        Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.
                    </p>
                </div>

                <!-- 4. Ulangi Password -->
                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">
                        Ulangi Password
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="confirmPasswordInput"
                            name="password_confirmation"
                            required
                            placeholder="Ulangi password anda"
                            class="w-full px-4 py-2.5 sm:py-3 pr-11 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all shadow-2xs"
                        />
                        <button
                            type="button"
                            onclick="togglePassword('confirmPasswordInput', this)"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-950 transition-colors cursor-pointer p-1"
                            aria-label="Tampilkan password"
                        >
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2.5 12s3.5-5 9.5-5 9.5 5 9.5 5-3.5 5-9.5 5-9.5-5-9.5-5Z" />
                                <circle cx="12" cy="12" r="2.5" />
                            </svg>
                        </button>
                    </div>
                    <p id="passwordMismatchError" class="text-[12px] text-red-600 mt-1.5 font-medium leading-normal hidden">
                        Password dan konfirmasi password tidak cocok.
                    </p>
                </div>

                <!-- 5. Nomor HP -->
                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">
                        Nomor HP
                    </label>
                    <input
                        type="tel"
                        name="no_telp"
                        required
                        value="{{ old('no_telp') }}"
                        placeholder="Contoh : 0891000012938"
                        class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all shadow-2xs"
                    />
                </div>

                <!-- 6. Kota -->
                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">
                        Kota
                    </label>
                    <div class="relative">
                        <select
                            name="kota"
                            class="w-full px-4 py-2.5 sm:py-3 pr-10 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 text-[14px] bg-white transition-all shadow-2xs appearance-none cursor-pointer"
                        >
                            <option value="" disabled {{ old('kota') ? '' : 'selected' }}></option>
                            <option value="Surabaya" {{ old('kota') == 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
                            <option value="Jakarta" {{ old('kota') == 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                            <option value="Bandung" {{ old('kota') == 'Bandung' ? 'selected' : '' }}>Bandung</option>
                            <option value="Yogyakarta" {{ old('kota') == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                            <option value="Semarang" {{ old('kota') == 'Semarang' ? 'selected' : '' }}>Semarang</option>
                            <option value="Medan" {{ old('kota') == 'Medan' ? 'selected' : '' }}>Medan</option>
                            <option value="Makassar" {{ old('kota') == 'Makassar' ? 'selected' : '' }}>Makassar</option>
                            <option value="Malang" {{ old('kota') == 'Malang' ? 'selected' : '' }}>Malang</option>
                            <option value="Denpasar" {{ old('kota') == 'Denpasar' ? 'selected' : '' }}>Denpasar</option>
                            <option value="Palembang" {{ old('kota') == 'Palembang' ? 'selected' : '' }}>Palembang</option>
                        </select>
                        <div class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-600 text-xs">
                            &#9662;
                        </div>
                    </div>
                </div>

                <!-- 7. Bergabung Sebagai -->
                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-2">
                        Bergabung Sebagai
                    </label>
                    <!-- Default to 'Individu', but respect old input if validation fails -->
                    <input type="hidden" name="tipe" id="roleInput" value="{{ old('tipe', 'individu') }}">
                    <div class="grid grid-cols-3 gap-2.5 sm:gap-3">
                        <button
                            type="button"
                            onclick="selectRole('individu', this)"
                            class="role-btn py-2.5 px-3 sm:px-4 rounded-xl text-[13.5px] sm:text-[14px] font-bold {{ old('tipe', 'individu') == 'individu' ? 'bg-[#d6f0e2] border-[#05522d]/40 shadow-xs' : 'bg-[#d6f0e2] border-transparent hover:bg-[#cbf0dc]' }} text-gray-950 border cursor-pointer"
                        >
                            Individu
                        </button>
                        <button
                            type="button"
                            onclick="selectRole('komunitas', this)"
                            class="role-btn py-2.5 px-3 sm:px-4 rounded-xl text-[13.5px] sm:text-[14px] font-bold {{ old('tipe') == 'komunitas' ? 'bg-[#d6f0e2] border-[#05522d]/40 shadow-xs' : 'bg-[#d6f0e2] border-transparent hover:bg-[#cbf0dc]' }} text-gray-950 border cursor-pointer"
                        >
                            Komunitas
                        </button>
                        <button
                            type="button"
                            onclick="selectRole('perusahaan', this)"
                            class="role-btn py-2.5 px-3 sm:px-4 rounded-xl text-[13.5px] sm:text-[14px] font-bold {{ old('tipe') == 'perusahaan' ? 'bg-[#d6f0e2] border-[#05522d]/40 shadow-xs' : 'bg-[#d6f0e2] border-transparent hover:bg-[#cbf0dc]' }} text-gray-950 border cursor-pointer"
                        >
                            Perusahaan
                        </button>
                    </div>
                </div>

                <!-- 8. Action Button & Switch Link -->
                <div class="pt-4 flex flex-col items-center">
                    <button
                        type="submit"
                        class="w-[260px] sm:w-[270px] py-3.5 px-6 rounded-xl bg-[#065e38] hover:bg-[#044a2c] text-white font-bold text-[15px] transition-all shadow-sm hover:shadow-md active:scale-98 cursor-pointer"
                    >
                        Daftar
                    </button>

                    <p class="mt-3.5 text-[13px] text-gray-950 text-center font-normal">
                        Sudah memiliki akun? 
                        <a
                            href="{{ route('login') }}"
                            class="font-bold underline text-gray-950 hover:text-[#065e38] cursor-pointer transition-colors"
                        >
                            Masuk Sekarang
                        </a>
                    </p>
                </div>

            </form>
        </div>
    </main>

    <script>
        const passwordRequirementMessage = 'Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.';

        function validatePasswordRequirements() {
            const input = document.getElementById('passwordInput');
            const error = document.getElementById('passwordRequirementsError');
            const password = input.value;
            const isValid = password.length >= 8 && /[A-Za-z]/.test(password) && /[0-9]/.test(password);

            error.classList.toggle('hidden', password.length === 0 || isValid);
            input.setCustomValidity(password.length > 0 && !isValid ? passwordRequirementMessage : '');

            return isValid;
        }

        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const isHidden = input.type === 'password';

            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }

            button.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
            button.innerHTML = isHidden
            
                ? `<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 12s3.5-5 9.5-5 9.5 5 9.5 5-3.5 5-9.5 5-9.5-5-9.5-5Z" /><circle cx="12" cy="12" r="2.5" /><path d="m4 4 16 16"/></svg>`
                : `<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 12s3.5-5 9.5-5 9.5 5 9.5 5-3.5 5-9.5 5-9.5-5-9.5-5Z" /><circle cx="12" cy="12" r="2.5"  /></svg>`;
        }

        function selectRole(role, btnElement) {
            document.getElementById('roleInput').value = role;
            document.querySelectorAll('.role-btn').forEach(btn => {
                btn.className = 'role-btn py-2.5 px-3 sm:px-4 rounded-xl text-[13.5px] sm:text-[14px] font-bold bg-[#d6f0e2] text-gray-950 border border-transparent hover:bg-[#cbf0dc] cursor-pointer';
            });
            btnElement.className = 'role-btn py-2.5 px-3 sm:px-4 rounded-xl text-[13.5px] sm:text-[14px] font-bold bg-[#d6f0e2] text-gray-950 border border-[#05522d]/40 shadow-xs cursor-pointer';
        }
    </script>
</body>
</html>