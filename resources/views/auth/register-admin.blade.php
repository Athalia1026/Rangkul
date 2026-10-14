<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Administrator - Rangkul.com</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome Icons CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Font Plus Jakarta Sans -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap');
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #F5F7F4; 
        }
    </style>
</head>
<body class="text-gray-800 min-h-screen flex flex-col justify-between">

    <!-- MAIN CONTAINER FORMULIR REGISTRASI                      -->
    <main class="w-full max-w-xl mx-auto px-5 py-8 sm:py-12 my-auto">

        <!-- Notifikasi Pesan Sukses / Error Laravel -->
        @if(session('success'))
            <div class="mb-5 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                <div>
                    <span class="font-bold">Berhasil!</span> {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 rounded-2xl bg-red-50 border border-red-200 p-4 text-xs text-red-800">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                    <span>Harap periksa kembali isian formulir:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- KARTU FORMULIR PENDAFTARAN -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            
            <!-- Header Kartu -->
            @php
            $roleParam = strtolower(request()->query('role', ''));

            $roleLabel = match ($roleParam) {
                'staff' => 'Staff',
                'manager' => 'Manager',
                default => 'Administrator',
            };

            $tokenParam = request()->query('token', '');
        @endphp

            <div class="bg-gradient-to-r from-[#086538] to-[#0d4a32] px-8 py-4 text-white">
                <div class="flex items-center justify-between mb-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#faecc8] text-[#086538] shadow-xs">
                        <i class="fa-solid fa-envelope-open-text text-[11px]"></i>
                        <span>Undangan Khusus Admin</span>
                    </span>
                    <span class="text-xs bg-white/15 px-3.5 py-1 rounded-full text-emerald-100 font-semibold border border-white/10">
                        Posisi: <strong>{{ $roleLabel }}</strong>
                    </span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight">Pendaftaran Akun {{ $roleLabel }}</h1>
                <p class="text-xs text-emerald-100/90 mt-1">
                    Silakan lengkapi data diri Anda untuk mengaktifkan akun administrator Rangkul.com
                </p>
            </div>

            <!-- Formulir POST ke Route Auth Laravel -->
            <form action="#" method="POST" class="px-8 py-4 space-y-4" onsubmit="event.preventDefault();">                
                @csrf
                
                <!-- Token & Role Masuk ke Input Hidden (Otomatis Masuk ke Database) -->
                <input type="hidden" name="token" value="{{ old('token', $tokenParam) }}">
                <input type="hidden" name="role" value="{{ old('role', $roleLabel) }}">

                <!-- Badge Indikator Token Valid -->
                <div class="rounded-xl bg-emerald-50 border border-emerald-200/80 p-3.5 flex items-start gap-3 text-xs text-emerald-900">
                    <div class="w-5 h-5 rounded-full bg-[#086538] text-white flex items-center justify-center shrink-0 mt-0.5 text-[10px] font-bold">
                        ✓
                    </div>
                    <div>
                        <p class="font-bold text-[#086538]">Tautan Undangan Terverifikasi</p>
                        <p class="text-emerald-800/80 text-[11px] mt-0.5 leading-relaxed">
                            Token undangan telah terhubung secara aman dengan sistem database untuk hak akses <strong>{{ $roleLabel }}</strong>.
                        </p>
                    </div>
                </div>

                <!-- 1. Nama Lengkap -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                            <i class="fa-solid fa-user text-xs"></i>
                        </span>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            required 
                            value="{{ old('name') }}" 
                            placeholder="Contoh: Rian Pratama" 
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#086538]"
                        >
                    </div>
                    @error('name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 2. Alamat Email -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                            <i class="fa-solid fa-envelope text-xs"></i>
                        </span>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            required 
                            value="{{ old('email', request('email')) }}" 
                            placeholder="Contoh: rian@rangkul.com" 
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#086538]"
                        >
                    </div>
                    @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 3. Nomor Telepon -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                        Nomor Telepon <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                            <i class="fa-solid fa-phone text-xs"></i>
                        </span>
                        <input 
                            type="tel" 
                            name="phone" 
                            id="phone" 
                            required 
                            value="{{ old('phone', request('phone')) }}" 
                            placeholder="Contoh: 081234567890" 
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#086538]"
                        >
                    </div>
                    @error('phone')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 4. Password & Konfirmasi Password (Grid 2 Kolom) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                required 
                                minlength="6" 
                                placeholder="Min. 6 karakter" 
                                class="w-full pl-4 pr-10 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#086538]"
                            >
                            <button 
                                type="button" 
                                onclick="togglePasswordVisibility('password', 'iconPass')" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-700 text-xs cursor-pointer"
                            >
                                <i id="iconPass" class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation" 
                                required 
                                minlength="6" 
                                placeholder="Ulangi password" 
                                class="w-full pl-4 pr-10 py-2.5 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#086538]"
                            >
                            <button 
                                type="button" 
                                onclick="togglePasswordVisibility('password_confirmation', 'iconConfirm')" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-700 text-xs cursor-pointer"
                            >
                                <i id="iconConfirm" class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 5. Persetujuan Pakta Integritas / Kode Etik -->
                <label class="flex items-start gap-3 pt-1 cursor-pointer select-none">
                    <input 
                        type="checkbox" 
                        name="agreement" 
                        id="agreement" 
                        required 
                        class="mt-1 h-4 w-4 rounded border-gray-300 text-[#086538] focus:ring-[#086538]"
                    >
                    <span class="text-xs text-gray-600 leading-relaxed">
                        Saya menyatakan bahwa data yang diisi adalah benar, dan saya bersedia mematuhi seluruh kode etik, kerahasiaan data pengguna, serta pakta integritas operasional sebagai <strong>{{ $roleLabel }}</strong> di <strong>Rangkul.com</strong>.
                    </span>
                </label>

                <!-- Tombol Submit -->
                <div class="pt-3">
                    <button 
                        type="submit" 
                        id="btnSubmit" 
                        class="w-full py-3.5 bg-[#086538] hover:bg-[#064e2b] text-white font-bold text-sm rounded-xl shadow-md transition active:scale-98 flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <i class="fa-solid fa-user-check"></i>
                        <span>Daftar & Aktifkan Akun {{ $roleLabel }}</span>
                    </button>
                </div>
            </form>

            <!-- Footer Bantuan -->
            <div class="bg-gray-50 border-t border-gray-100 px-8 py-4 text-center text-xs text-gray-500">
                Punya pertanyaan seputar undangan pendaftaran ini? Hubungi Super Admin di <strong>admin@rangkul.com</strong>
            </div>
        </div>
    </main>

    <!-- ======================================================== -->
    <!-- FOOTER AUTH                                              -->
    <!-- ======================================================== -->
    <footer class="text-center py-6 text-xs text-gray-400 border-t border-gray-200/50 bg-white/50">
        &copy; {{ date('Y') }} Rangkul.com — Donation Platform. Hak Cipta Dilindungi.
    </footer>

    <!-- ======================================================== -->
    <!-- SCRIPT TOGGLE LIHAT / SEMBUNYIKAN PASSWORD               -->
    <!-- ======================================================== -->
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fa-solid fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fa-solid fa-eye';
            }
        }

        function bukaHalamanRegister() {
    const role = document.getElementById('input-role').value;

    const url = new URL(
        "{{ route('register.admin.form') }}",
        window.location.origin
    );

    url.searchParams.set('role', role);

    window.location.href = url.toString();
}
    </script>
</body>
</html>