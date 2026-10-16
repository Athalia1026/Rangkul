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
        
        <!-- Lingkaran hijau transparan -->
        <x-auth-background />

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
                            oninput="validatePasswordRequirements(); validatePasswordMatch()"
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
                            oninput="validatePasswordMatch()"
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
                    <div class="relative" id="cityDropdown">
                        <input type="hidden" name="kota" id="cityInput" value="{{ old('kota') }}">
                        <input
                            type="text"
                            id="citySearch"
                            role="combobox"
                            aria-autocomplete="list"
                            aria-controls="cityList"
                            aria-expanded="false"
                            autocomplete="off"
                            required
                            value="{{ old('kota') }}"
                            placeholder="Ketik untuk mencari kota/kabupaten"
                            onfocus="toggleCityList(true)"
                            oninput="filterCities()"
                            class="w-full px-4 py-2.5 sm:py-3 pr-10 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all shadow-2xs"
                        />
                        <button
                            type="button"
                            tabindex="-1"
                            aria-label="Tampilkan daftar kota"
                            onmousedown="event.preventDefault()"
                            onclick="toggleCityList(); document.getElementById('citySearch').focus()"
                            class="absolute right-1.5 top-1/2 -translate-y-1/2 px-2 py-1 text-gray-600 text-xs cursor-pointer"
                        >
                            &#9662;
                        </button>
                        <div
                            id="cityList"
                            role="listbox"
                            class="hidden absolute z-30 left-0 right-0 mt-2 max-h-72 overflow-y-auto rounded-xl border border-gray-200 bg-white shadow-lg p-1.5 space-y-0.5"
                        >
                            <p id="cityEmpty" class="hidden px-4 py-3 text-[14px] text-gray-500">Kota tidak ditemukan</p>
                        </div>
                        <template id="cityOptionTemplate">
                            <button
                                type="button"
                                tabindex="-1"
                                role="option"
                                onmousedown="event.preventDefault()"
                                onclick="selectCity(this.dataset.value)"
                                class="city-option w-full text-left px-4 py-3 rounded-lg text-[14px] text-gray-950 outline-none cursor-pointer aria-selected:font-semibold data-[active=true]:bg-[#d6f0e2]"
                            ></button>
                        </template>
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

        function validatePasswordMatch() {
            const password = document.getElementById('passwordInput').value;
            const input = document.getElementById('confirmPasswordInput');
            const mismatch = input.value.length > 0 && input.value !== password;

            document.getElementById('passwordMismatchError').classList.toggle('hidden', !mismatch);
            input.classList.toggle('!border-red-500', mismatch);
            input.setCustomValidity(mismatch ? 'Password dan konfirmasi password tidak cocok.' : '');
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

        const citySearch = document.getElementById('citySearch');
        const cityList = document.getElementById('cityList');
        const cityEmpty = document.getElementById('cityEmpty');
        const cityOptionTemplate = document.getElementById('cityOptionTemplate');
        // Hanya sebagian hasil yang dirender agar DOM tetap ringan; sisanya muncul saat pengguna mengetik lebih spesifik
        const CITY_RESULT_LIMIT = 50;
        let cities = null;
        let citiesRequest = null;
        let activeCityIndex = -1;

        function normalizeCity(text) {
            return text.toLowerCase().replace(/\s+/g, ' ').trim();
        }

        // Data kabupaten/kota (file statis, di-cache browser) baru diambil saat kolom pertama kali dibuka
        function loadCities() {
            citiesRequest ??= fetch(@json(asset('data/kota-indonesia.json')))
                .then(response => response.json())
                .then(names => {
                    cities = names.map(name => ({
                        name,
                        full: normalizeCity(name),
                        base: normalizeCity(name.replace(/^(Kabupaten|Kota)( Administrasi)? /, '')),
                    }));
                })
                .catch(() => {
                    citiesRequest = null;
                });

            return citiesRequest;
        }

        // 0 = awalan nama cocok, 1 = awal kata cocok, 2 = mengandung teks, 3 = huruf berurutan (mis. "jkt" -> Jakarta), null = tidak cocok
        function cityMatchRank(city, query) {
            if (!query) return 0;
            if (city.base.startsWith(query) || city.full.startsWith(query)) return 0;
            if (city.base.includes(' ' + query)) return 1;
            if (city.full.includes(query)) return 2;

            let position = 0;
            for (const char of query.replace(/ /g, '')) {
                position = city.base.indexOf(char, position) + 1;
                if (!position) return null;
            }
            return 3;
        }

        function renderedCityOptions() {
            return [...cityList.querySelectorAll('.city-option')];
        }

        function setActiveCity(index, scroll = true) {
            const options = renderedCityOptions();
            activeCityIndex = options.length ? (index + options.length) % options.length : -1;

            options.forEach((option, i) => {
                option.dataset.active = i === activeCityIndex ? 'true' : 'false';
            });
            if (scroll) options[activeCityIndex]?.scrollIntoView({ block: 'nearest' });
        }

        function showCityMessage(message) {
            cityEmpty.textContent = message;
            cityEmpty.classList.toggle('hidden', !message);
        }

        function renderCities(query) {
            renderedCityOptions().forEach(option => option.remove());

            if (!cities) {
                showCityMessage(citiesRequest ? 'Memuat daftar kota...' : 'Gagal memuat daftar kota, coba lagi.');
                return;
            }

            const matches = cities
                .map(city => ({ city, rank: cityMatchRank(city, query) }))
                .filter(match => match.rank !== null)
                .sort((a, b) => a.rank - b.rank);

            const selected = document.getElementById('cityInput').value;
            const fragment = document.createDocumentFragment();

            matches.slice(0, CITY_RESULT_LIMIT).forEach(({ city }) => {
                const option = cityOptionTemplate.content.firstElementChild.cloneNode(true);
                option.dataset.value = city.name;
                option.textContent = city.name;
                option.setAttribute('aria-selected', city.name === selected ? 'true' : 'false');
                fragment.appendChild(option);
            });
            cityList.insertBefore(fragment, cityEmpty);

            if (!matches.length) {
                showCityMessage('Kota tidak ditemukan');
            } else if (matches.length > CITY_RESULT_LIMIT) {
                showCityMessage('Ketik nama kota untuk melihat hasil lainnya');
            } else {
                showCityMessage('');
            }

            setActiveCity(query ? 0 : renderedCityOptions().findIndex(option => option.dataset.value === selected));
        }

        async function toggleCityList(open) {
            const shouldOpen = open ?? cityList.classList.contains('hidden');

            cityList.classList.toggle('hidden', !shouldOpen);
            citySearch.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');

            if (!shouldOpen) {
                // Teks yang tidak cocok dengan kota mana pun dikembalikan ke pilihan terakhir
                citySearch.value = document.getElementById('cityInput').value;
                return;
            }

            renderCities('');
            if (!cities) {
                await loadCities();
                if (!cityList.classList.contains('hidden')) filterCities();
            }
        }

        function filterCities() {
            cityList.classList.remove('hidden');
            citySearch.setAttribute('aria-expanded', 'true');

            const query = normalizeCity(citySearch.value);
            const selected = document.getElementById('cityInput').value;
            // Saat kolom baru dibuka, teks berisi pilihan sebelumnya; tampilkan seluruh daftar, bukan hanya pilihan itu
            renderCities(query === normalizeCity(selected) ? '' : query);
        }

        function selectCity(name) {
            document.getElementById('cityInput').value = name;
            toggleCityList(false);
        }

        citySearch.addEventListener('keydown', event => {
            const isOpen = !cityList.classList.contains('hidden');

            if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault();
                if (!isOpen) toggleCityList(true);
                setActiveCity(activeCityIndex + (event.key === 'ArrowDown' ? 1 : -1));
            } else if (event.key === 'Enter' && isOpen) {
                event.preventDefault();
                const active = renderedCityOptions()[activeCityIndex];
                if (active) selectCity(active.dataset.value);
            } else if (event.key === 'Escape' && isOpen) {
                toggleCityList(false);
            }
        });

        // Sorotan mouse dan keyboard memakai satu state yang sama agar hanya satu opsi yang tersorot
        cityList.addEventListener('mousemove', event => {
            const option = event.target.closest('.city-option');
            if (option) setActiveCity(renderedCityOptions().indexOf(option), false);
        });

        citySearch.addEventListener('blur', () => toggleCityList(false));

        document.addEventListener('click', event => {
            if (!document.getElementById('cityDropdown').contains(event.target) && !cityList.classList.contains('hidden')) {
                toggleCityList(false);
            }
        });
    </script>
</body>
</html>