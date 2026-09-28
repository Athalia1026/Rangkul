<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Organisasi - Rangkul.com</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-screen w-full bg-[#fcfdfd] text-[#000000] antialiased flex flex-row overflow-hidden relative selection:bg-[#d8f0e2] selection:text-[#05522d]">
    <aside aria-label="Rangkul Banner" class="relative w-[36%] sm:w-[40%] md:w-[44%] lg:w-[44%] xl:w-[44%] h-full shrink-0 overflow-hidden bg-gray-900 select-none z-20">
        <img src="{{ asset('images/register.png') }}" alt="Anak-anak Rangkul" class="w-full h-full object-cover object-[center_20%] pointer-events-none">
        <div class="absolute top-5 left-5 sm:top-7 sm:left-7 z-10">
            <a href="{{ url('/') }}" class="inline-flex cursor-pointer group text-left transition-opacity hover:opacity-90">
                <img src="{{ asset('images/logo.png') }}" alt="Rangkul.com Donation Platform" class="w-44 sm:w-52 h-auto object-contain origin-left group-hover:scale-[1.02] transition-transform">
            </a>
        </div>
    </aside>

    <main class="flex-1 h-full overflow-y-auto overflow-x-hidden relative isolate flex flex-col items-center pt-8 sm:pt-12 pb-14 px-4 sm:px-8 md:px-10 lg:px-14 xl:px-20 scroll-smooth">
        <div class="absolute -top-10 -right-10 z-0 w-60 h-60 rounded-full blur-[55px] pointer-events-none" style="background-color: rgba(72, 202, 154, 0.16);"></div>
        <div class="absolute bottom-16 left-6 sm:left-12 z-0 w-56 h-56 rounded-full blur-[50px] pointer-events-none" style="background-color: rgba(58, 191, 141, 0.18);"></div>
        <div class="absolute -bottom-10 -right-10 z-0 w-64 h-64 rounded-full blur-[60px] pointer-events-none" style="background-color: rgba(86, 212, 166, 0.14);"></div>
        <div class="absolute left-[-2.5rem] top-[36%] z-0 hidden h-24 w-24 rounded-full blur-[35px] pointer-events-none lg:block" style="background-color: rgba(72, 202, 154, 0.12);"></div>
        <div class="absolute right-[-2rem] top-[52%] z-0 hidden h-20 w-20 rounded-full blur-[30px] pointer-events-none lg:block" style="background-color: rgba(58, 191, 141, 0.14);"></div>

        <div class="w-full max-w-[450px] mx-auto z-10">
            <div class="text-center mb-6 sm:mb-7">
                <h1 class="text-[27px] sm:text-[31px] font-bold text-gray-950 tracking-tight leading-tight">Bergabung Bersama Rangkul</h1>
                <p class="text-[13px] sm:text-[14px] text-gray-600 mt-2 leading-relaxed max-w-[430px] mx-auto">Daftarkan akun organisasi Anda dan segera terbitkan campaign Anda!</p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-[12px] text-red-700">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.organization.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="organizationRegisterForm">
                @csrf

                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">Nama Penanggung Jawab</label>
                    <input type="text" name="nama" required value="{{ old('nama') }}" class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 text-[14px] bg-white transition-all shadow-2xs">
                </div>

                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">Nama Organisasi/Lembaga</label>
                    <input type="text" name="nama_lembaga" required value="{{ old('nama_lembaga') }}" class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 text-[14px] bg-white transition-all shadow-2xs">
                </div>

                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">Email</label>
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="contoh@email.com" class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all shadow-2xs">
                </div>

                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <input type="password" id="organizationPasswordInput" name="password" required minlength="8" pattern="(?=.*[A-Za-z])(?=.*[0-9]).{8,}" placeholder="Masukkan password Anda" oninput="validateOrganizationPassword()" class="w-full px-4 py-2.5 sm:py-3 pr-11 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all shadow-2xs">
                        <button type="button" onclick="toggleOrganizationPassword('organizationPasswordInput', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-950 transition-colors cursor-pointer p-1" aria-label="Tampilkan password">
                            <span class="eye-icon"></span>
                        </button>
                    </div>
                    <p id="organizationPasswordError" class="text-[12px] text-red-600 mt-1.5 font-medium leading-normal hidden">Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.</p>
                </div>

                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">Ulangi Kata Sandi</label>
                    <div class="relative">
                        <input type="password" id="organizationPasswordConfirmationInput" name="password_confirmation" required placeholder="Ulangi password Anda" class="w-full px-4 py-2.5 sm:py-3 pr-11 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all shadow-2xs">
                        <button type="button" onclick="toggleOrganizationPassword('organizationPasswordConfirmationInput', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-950 transition-colors cursor-pointer p-1" aria-label="Tampilkan password">
                            <span class="eye-icon"></span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">Lokasi/Kota</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <input type="text" name="kota" required value="{{ old('kota') }}" placeholder="Kota" class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 text-[14px] bg-white transition-all shadow-2xs">
                        <input type="text" name="alamat" required value="{{ old('alamat') }}" placeholder="Alamat lengkap" class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 text-[14px] bg-white transition-all shadow-2xs">
                    </div>
                </div>

                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">Nomor HP</label>
                    <input type="tel" name="no_telp" required value="{{ old('no_telp') }}" placeholder="Contoh : 0891000012938" class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all shadow-2xs">
                </div>

                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">Nama Bank</label>
                    <input type="text" name="bank" required value="{{ old('bank') }}" placeholder="Masukkan nama bank" class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all shadow-2xs">
                </div>

                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">Nomor Rekening</label>
                    <input type="text" name="no_rekening" required value="{{ old('no_rekening') }}" placeholder="Masukkan nomor rekening" class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all shadow-2xs">
                </div>

                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">Nama Pemilik Rekening</label>
                    <input type="text" name="pemilik_rekening" required value="{{ old('pemilik_rekening') }}" placeholder="Masukkan nama pemilik rekening" class="w-full px-4 py-2.5 sm:py-3 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all shadow-2xs">
                </div>

                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">Pilih Organisasi</label>
                    <div class="grid grid-cols-2 gap-2.5 sm:gap-3">
                        @foreach (['Panti Asuhan', 'Sekolah'] as $organizationType)
                            <button type="button" onclick="selectOrganizationType('{{ $organizationType }}', this)" class="organization-type-btn py-2.5 px-3 rounded-xl text-[13px] font-bold {{ old('tipe', 'Panti Asuhan') === $organizationType ? 'bg-[#d6f0e2] border-[#05522d]/40 shadow-xs' : 'bg-[#d6f0e2] border-transparent hover:bg-[#cbf0dc]' }} text-gray-950 border cursor-pointer">{{ $organizationType }}</button>
                        @endforeach
                    </div>
                    <input type="hidden" name="tipe" id="organizationTypeInput" value="{{ old('tipe', 'Panti Asuhan') }}">
                </div>

                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">Deskripsi Organisasi</label>
                    <textarea name="deskripsi" required rows="3" placeholder="Jelaskan profil singkat organisasi Anda" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all shadow-2xs">{{ old('deskripsi') }}</textarea>
                </div>

                @php
                    $uploads = [
                        ['name' => 'sk_operasional', 'label' => 'Upload SK Operasional', 'accept' => '.pdf,.jpg,.jpeg,.png'],
                        ['name' => 'ktp_pj', 'label' => 'Upload KTP PIC', 'accept' => '.pdf,.jpg,.jpeg,.png'],
                        ['name' => 'foto_kegiatan', 'label' => 'Upload Foto Kegiatan', 'accept' => '.jpg,.jpeg,.png,.webp'],
                        ['name' => 'foto_bangunan', 'label' => 'Upload Tampak Depan Bangunan', 'accept' => '.jpg,.jpeg,.png,.webp'],
                    ];
                @endphp
                @foreach ($uploads as $upload)
                    <div>
                        <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">{{ $upload['label'] }} <span class="text-red-600">(Wajib)</span></label>
                        <label class="group flex min-h-[116px] cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-[#207466] bg-[#effcf8] px-4 py-4 text-center hover:border-[#05522d] hover:bg-[#e3f8f0] transition-colors">
                            <span class="mb-2 flex h-11 w-11 items-center justify-center rounded-full bg-[#d6f0e2] text-[#05522d] group-hover:bg-[#c8ead8] transition-colors">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M16 16l-4-4-4 4M12 12v8" />
                                    <path d="M20 16.5a4.5 4.5 0 0 0-2.5-8.17A6 6 0 0 0 6.12 10 4 4 0 0 0 6 18h2" />
                                </svg>
                            </span>
                            <span class="text-[12.5px] font-bold text-gray-900">Pilih file untuk diunggah</span>
                            <span class="mt-1 text-[10.5px] text-gray-600">{{ strtoupper(str_replace('.', '', str_replace(',', ', ', $upload['accept']))) }} • Maks. {{ str_contains($upload['name'], 'foto_') ? '3MB' : '2MB' }}</span>
                            <input type="file" name="{{ $upload['name'] }}" accept="{{ $upload['accept'] }}" required class="hidden" onchange="showSelectedFile(this)">
                        </label>
                        <p class="selected-file mt-1.5 min-h-[18px] text-[11px] font-medium text-[#05522d]"></p>
                    </div>
                @endforeach

                <div class="pt-4 flex flex-col items-center">
                    <button type="submit" class="w-[260px] sm:w-[270px] py-3.5 px-6 rounded-xl bg-[#065e38] hover:bg-[#044a2c] text-white font-bold text-[15px] transition-all shadow-sm hover:shadow-md active:scale-98 cursor-pointer">Daftar</button>
                    <p class="mt-3.5 text-[13px] text-gray-950 text-center font-normal">Sudah memiliki akun? <a href="{{ route('login') }}" class="font-bold underline text-gray-950 hover:text-[#065e38] cursor-pointer transition-colors">Masuk Sekarang</a></p>
                </div>
            </form>
        </div>
    </main>

    <script>
        const eyeIcon = (crossed = false) => `<svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 12s3.5-5 9.5-5 9.5 5 9.5 5-3.5 5-9.5 5-9.5-5-9.5-5Z" /><circle cx="12" cy="12" r="2.5" />${crossed ? '<path d="m4 4 16 16" />' : ''}</svg>`;
        document.querySelectorAll('.eye-icon').forEach(icon => icon.innerHTML = eyeIcon());

        function toggleOrganizationPassword(inputId, button) {
            const input = document.getElementById(inputId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            button.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
            button.querySelector('.eye-icon').innerHTML = eyeIcon(isHidden);
        }

        function validateOrganizationPassword() {
            const input = document.getElementById('organizationPasswordInput');
            const error = document.getElementById('organizationPasswordError');
            const valid = input.value.length >= 8 && /[A-Za-z]/.test(input.value) && /[0-9]/.test(input.value);
            error.classList.toggle('hidden', input.value.length === 0 || valid);
            input.setCustomValidity(input.value.length > 0 && !valid ? 'Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.' : '');
        }

        function selectOrganizationType(type, button) {
            document.getElementById('organizationTypeInput').value = type;
            document.querySelectorAll('.organization-type-btn').forEach(item => item.className = 'organization-type-btn py-2.5 px-3 rounded-xl text-[13px] font-bold bg-[#d6f0e2] text-gray-950 border border-transparent hover:bg-[#cbf0dc] cursor-pointer');
            button.className = 'organization-type-btn py-2.5 px-3 rounded-xl text-[13px] font-bold bg-[#d6f0e2] text-gray-950 border border-[#05522d]/40 shadow-xs cursor-pointer';
        }

        function showSelectedFile(input) {
            const label = input.closest('label').parentElement.querySelector('.selected-file');
            label.textContent = input.files.length ? `File dipilih: ${input.files[0].name}` : '';
        }
    </script>
</body>
</html>
