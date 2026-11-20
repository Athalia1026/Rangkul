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
        <x-auth-background />

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
                        <input type="password" id="organizationPasswordInput" name="password" required minlength="8" pattern="(?=.*[A-Za-z])(?=.*[0-9]).{8,}" placeholder="Masukkan password Anda" oninput="validateOrganizationPassword(); validateOrganizationPasswordMatch()" class="w-full px-4 py-2.5 sm:py-3 pr-11 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all shadow-2xs">
                        <button type="button" onclick="toggleOrganizationPassword('organizationPasswordInput', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-950 transition-colors cursor-pointer p-1" aria-label="Tampilkan password">
                            <span class="eye-icon"></span>
                        </button>
                    </div>
                    <p id="organizationPasswordError" class="text-[12px] text-red-600 mt-1.5 font-medium leading-normal hidden">Password harus terdiri dari minimal 8 karakter, termasuk huruf dan angka.</p>
                </div>

                <div>
                    <label class="block text-[13.5px] font-medium text-gray-950 mb-1.5">Ulangi Kata Sandi</label>
                    <div class="relative">
                        <input type="password" id="organizationPasswordConfirmationInput" name="password_confirmation" required placeholder="Ulangi password Anda" oninput="validateOrganizationPasswordMatch()" class="w-full px-4 py-2.5 sm:py-3 pr-11 rounded-xl border border-gray-300 focus:border-[#05522d] focus:ring-2 focus:ring-[#05522d]/15 outline-none text-gray-950 placeholder-gray-500 text-[14px] bg-white transition-all shadow-2xs">
                        <button type="button" onclick="toggleOrganizationPassword('organizationPasswordConfirmationInput', this)" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-950 transition-colors cursor-pointer p-1" aria-label="Tampilkan password">
                            <span class="eye-icon"></span>
                        </button>
                    </div>
                    <p id="organizationPasswordMismatchError" class="text-[12px] text-red-600 mt-1.5 font-medium leading-normal hidden">Password dan konfirmasi password tidak cocok.</p>
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
                        ['name' => 'sk_operasional', 'label' => 'Upload SK Operasional', 'accept' => '.pdf,.jpg,.jpeg,.png', 'max_size_kb' => 2048],
                        ['name' => 'ktp_pj', 'label' => 'Upload KTP PIC', 'accept' => '.pdf,.jpg,.jpeg,.png', 'max_size_kb' => 2048],
                        ['name' => 'foto_kegiatan', 'label' => 'Upload Foto Kegiatan', 'accept' => '.jpg,.jpeg,.png,.webp', 'max_size_kb' => 3072],
                        ['name' => 'foto_bangunan', 'label' => 'Upload Tampak Depan Bangunan', 'accept' => '.jpg,.jpeg,.png,.webp', 'max_size_kb' => 3072],
                    ];
                @endphp
                @foreach ($uploads as $upload)
                    <div class="file-upload-item">
                        <label class="block text-[15px] font-medium text-gray-950 mb-1.5">{{ $upload['label'] }} <span class="text-red-600">(Wajib)</span></label>
                        <button type="button" class="file-picker group flex min-h-[76px] w-full flex-col items-center justify-center rounded-xl border-2 border-dashed border-[#207466] bg-[#effcf8] px-3 py-2 text-center hover:border-[#05522d] hover:bg-[#e3f8f0] transition-colors">
                            <span class="mb-1 flex h-8 w-8 items-center justify-center rounded-full bg-[#d6f0e2] text-[#05522d] group-hover:bg-[#c8ead8] transition-colors">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M16 16l-4-4-4 4M12 12v8" />
                                    <path d="M20 16.5a4.5 4.5 0 0 0-2.5-8.17A6 6 0 0 0 6.12 10 4 4 0 0 0 6 18h2" />
                                </svg>
                            </span>
                            <span class="text-[14px] font-bold text-gray-900">Pilih file untuk diunggah</span>
                            <span class="mt-1 text-[12px] text-gray-600">{{ strtoupper(str_replace('.', '', str_replace(',', ', ', $upload['accept']))) }} • Maks. {{ $upload['max_size_kb'] / 1024 }} MB</span>
                            <span class="mt-1 text-[13px] text-gray-700">Belum ada file dipilih</span>
                        </button>
                        <input type="file" name="{{ $upload['name'] }}" accept="{{ $upload['accept'] }}" data-max-size-kb="{{ $upload['max_size_kb'] }}" required aria-label="{{ $upload['label'] }}" class="sr-only" onchange="showSelectedFile(this)">
                        <p class="file-error hidden mt-1 text-[13px] font-medium text-red-600" role="alert"></p>
                        <div class="file-selected hidden mt-2 rounded-xl border border-gray-200 bg-white p-3">
                            <div class="flex min-w-0 items-center gap-3">
                                <div class="file-thumbnail flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-gray-100"></div>
                                <div class="min-w-0">
                                    <p class="file-name max-w-full truncate text-[14px] font-semibold text-gray-900"></p>
                                </div>
                            </div>
                            <div class="mt-3 grid w-full grid-cols-3 gap-2">
                                <button type="button" class="file-preview-open w-full rounded-lg bg-emerald-100 px-2 py-2 text-[13px] font-semibold text-emerald-800 hover:bg-emerald-200">Pratinjau</button>
                                <button type="button" class="file-replace w-full rounded-lg bg-blue-100 px-2 py-2 text-[13px] font-semibold text-blue-800 hover:bg-blue-200">Ganti file</button>
                                <button type="button" class="file-remove w-full rounded-lg bg-red-100 px-2 py-2 text-[13px] font-semibold text-red-800 hover:bg-red-200">Hapus file</button>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="pt-4 flex flex-col items-center">
                    <button type="submit" class="w-[260px] sm:w-[270px] py-3.5 px-6 rounded-xl bg-[#065e38] hover:bg-[#044a2c] text-white font-bold text-[15px] transition-all shadow-sm hover:shadow-md active:scale-98 cursor-pointer">Daftar</button>
                    <p class="mt-3.5 text-[13px] text-gray-950 text-center font-normal">Sudah memiliki akun? <a href="{{ route('login') }}" class="font-bold underline text-gray-950 hover:text-[#065e38] cursor-pointer transition-colors">Masuk Sekarang</a></p>
                </div>
            </form>
        </div>
    </main>

    <div id="file-preview-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/70 p-4" role="dialog" aria-modal="true" aria-labelledby="file-preview-title">
        <div class="w-full max-w-3xl rounded-xl bg-white p-4 shadow-xl">
            <div class="mb-3 flex items-center justify-between gap-3">
                <h2 id="file-preview-title" class="truncate text-sm font-bold text-gray-900"></h2>
                <button id="file-preview-close" type="button" class="shrink-0 rounded-lg px-3 py-1 text-sm font-semibold text-gray-600 hover:bg-gray-100" aria-label="Tutup pratinjau">Tutup</button>
            </div>
            <img id="file-preview-image" class="hidden mx-auto max-h-[75vh] max-w-full rounded-lg object-contain" alt="">
            <iframe id="file-preview-pdf" class="hidden h-[75vh] w-full rounded-lg border border-gray-200" title="Pratinjau dokumen PDF"></iframe>
        </div>
    </div>

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

        function validateOrganizationPasswordMatch() {
            const password = document.getElementById('organizationPasswordInput').value;
            const input = document.getElementById('organizationPasswordConfirmationInput');
            const mismatch = input.value.length > 0 && input.value !== password;
            document.getElementById('organizationPasswordMismatchError').classList.toggle('hidden', !mismatch);
            input.classList.toggle('!border-red-500', mismatch);
            input.setCustomValidity(mismatch ? 'Password dan konfirmasi password tidak cocok.' : '');
        }

        function selectOrganizationType(type, button) {
            document.getElementById('organizationTypeInput').value = type;
            document.querySelectorAll('.organization-type-btn').forEach(item => item.className = 'organization-type-btn py-2.5 px-3 rounded-xl text-[13px] font-bold bg-[#d6f0e2] text-gray-950 border border-transparent hover:bg-[#cbf0dc] cursor-pointer');
            button.className = 'organization-type-btn py-2.5 px-3 rounded-xl text-[13px] font-bold bg-[#d6f0e2] text-gray-950 border border-[#05522d]/40 shadow-xs cursor-pointer';
        }

        function showSelectedFile(input) {
            const container = input.closest('.file-upload-item');
            const picker = container.querySelector('.file-picker');
            const selectedFile = container.querySelector('.file-selected');
            const thumbnail = container.querySelector('.file-thumbnail');
            const previousUrl = input.dataset.previewUrl;
            const error = container.querySelector('.file-error');

            if (previousUrl) {
                URL.revokeObjectURL(previousUrl);
                delete input.dataset.previewUrl;
            }

            thumbnail.replaceChildren();
            const file = input.files[0];
            if (!file) {
                picker.classList.remove('hidden');
                selectedFile.classList.add('hidden');
                error.textContent = '';
                error.classList.add('hidden');
                return;
            }

            const maximumSizeKb = Number(input.dataset.maxSizeKb);
            if (file.size > maximumSizeKb * 1024) {
                input.value = '';
                picker.classList.remove('hidden');
                selectedFile.classList.add('hidden');
                error.textContent = `Ukuran file terlalu besar. Maksimal ${maximumSizeKb / 1024} MB. Silakan pilih file yang lebih kecil.`;
                error.classList.remove('hidden');
                return;
            }

            const previewUrl = URL.createObjectURL(file);
            input.dataset.previewUrl = previewUrl;
            error.textContent = '';
            error.classList.add('hidden');
            picker.classList.add('hidden');
            selectedFile.classList.remove('hidden');
            container.querySelector('.file-name').textContent = file.name;
            container.querySelector('.file-name').title = file.name;

            if (file.type.startsWith('image/') || /\.(jpe?g|png|webp)$/i.test(file.name)) {
                const image = document.createElement('img');
                image.src = previewUrl;
                image.alt = `Pratinjau ${file.name}`;
                image.className = 'h-full w-full object-cover';
                thumbnail.append(image);
            } else {
                const fileType = document.createElement('span');
                fileType.textContent = 'PDF';
                fileType.className = 'text-[12px] font-bold text-red-700';
                thumbnail.append(fileType);
            }
        }

        function openFilePreview(input) {
            const file = input.files[0];
            const previewUrl = input.dataset.previewUrl;
            if (!file || !previewUrl) return;

            const modal = document.getElementById('file-preview-modal');
            const image = document.getElementById('file-preview-image');
            const pdf = document.getElementById('file-preview-pdf');
            document.getElementById('file-preview-title').textContent = file.name;

            if (file.type.startsWith('image/') || /\.(jpe?g|png|webp)$/i.test(file.name)) {
                pdf.removeAttribute('src');
                pdf.classList.add('hidden');
                image.src = previewUrl;
                image.alt = `Pratinjau ${file.name}`;
                image.classList.remove('hidden');
            } else {
                image.removeAttribute('src');
                image.classList.add('hidden');
                pdf.src = previewUrl;
                pdf.title = `Pratinjau ${file.name}`;
                pdf.classList.remove('hidden');
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeFilePreview() {
            const modal = document.getElementById('file-preview-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('file-preview-image').removeAttribute('src');
            document.getElementById('file-preview-pdf').removeAttribute('src');
        }

        const organizationRegisterForm = document.getElementById('organizationRegisterForm');
        organizationRegisterForm.addEventListener('click', event => {
            const button = event.target.closest('.file-picker, .file-preview-open, .file-replace, .file-remove');
            if (!button) return;

            const container = button.closest('.file-upload-item');
            const input = container.querySelector('input[type="file"]');
            if (button.classList.contains('file-picker') || button.classList.contains('file-replace')) {
                input.click();
                return;
            }

            if (button.classList.contains('file-preview-open')) {
                openFilePreview(input);
                return;
            }

            input.value = '';
            showSelectedFile(input);
        });

        document.getElementById('file-preview-close').addEventListener('click', closeFilePreview);
        document.getElementById('file-preview-modal').addEventListener('click', event => {
            if (event.target.id === 'file-preview-modal') closeFilePreview();
        });
        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') closeFilePreview();
        });

        organizationRegisterForm.addEventListener('submit', event => {
            const submitButton = event.currentTarget.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Mengunggah dokumen...';
        });

        window.addEventListener('pagehide', () => {
            document.querySelectorAll('input[type="file"][data-preview-url]').forEach(input => {
                URL.revokeObjectURL(input.dataset.previewUrl);
            });
        });
    </script>
</body>
</html>
