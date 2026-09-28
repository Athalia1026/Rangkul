<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Ulang Organisasi - Rangkul.com</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="h-screen w-full bg-[#fcfdfd] text-[#000000] antialiased flex flex-row overflow-hidden relative selection:bg-[#d8f0e2] selection:text-[#05522d]">
    <aside class="relative w-[36%] sm:w-[40%] md:w-[44%] lg:w-[44%] xl:w-[44%] h-full shrink-0 overflow-hidden bg-gray-900 select-none z-20">
        <img src="{{ asset('images/register.png') }}" alt="Anak-anak Rangkul" class="w-full h-full object-cover object-[center_20%] pointer-events-none">
        <div class="absolute top-5 left-5 sm:top-7 sm:left-7 z-10"><a href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}" alt="Rangkul.com Donation Platform" class="w-44 sm:w-52 h-auto object-contain"></a></div>
    </aside>
    <main class="flex-1 h-full overflow-y-auto overflow-x-hidden relative isolate flex flex-col items-center pt-8 sm:pt-12 pb-14 px-4 sm:px-8 md:px-10 lg:px-14 xl:px-20">
        <div class="absolute -top-10 -right-10 z-0 w-60 h-60 rounded-full blur-[55px] pointer-events-none" style="background-color: rgba(72, 202, 154, .16)"></div>
        <div class="absolute bottom-16 left-6 sm:left-12 z-0 w-56 h-56 rounded-full blur-[50px] pointer-events-none" style="background-color: rgba(58, 191, 141, .18)"></div>
        <div class="w-full max-w-[450px] mx-auto z-10">
            <div class="text-center mb-6 sm:mb-7">
                <h1 class="text-[27px] sm:text-[31px] font-bold text-gray-950 tracking-tight leading-tight">Registrasi Ulang Organisasi</h1>
                <p class="text-[13px] sm:text-[14px] text-gray-600 mt-2 leading-relaxed">Perbaiki data atau dokumen yang ditolak, lalu kirimkan kembali untuk diverifikasi.</p>
            </div>
            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-[12px] text-red-700"><ul class="space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            <form action="{{ route('organization.resubmit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="email" value="{{ $user->email }}">
                <div><label class="field-label">Nama Penanggung Jawab</label><input type="text" name="nama" value="{{ old('nama', $user->nama) }}" readonly class="field-input bg-gray-100"></div>
                <div><label class="field-label">Nama Organisasi/Lembaga</label><input type="text" name="nama_lembaga" required value="{{ old('nama_lembaga', $user->organization->nama_lembaga) }}" class="field-input"></div>
                <div><label class="field-label">Kata Sandi</label><input type="password" name="password" required placeholder="Masukkan password akun Anda" class="field-input"></div>
                <div><label class="field-label">Email</label><input type="email" value="{{ $user->email }}" readonly class="field-input bg-gray-100"></div>
                <div><label class="field-label">Lokasi/Kota</label><div class="grid grid-cols-1 sm:grid-cols-2 gap-3"><input type="text" name="kota" required value="{{ old('kota', $user->organization->kota) }}" placeholder="Kota" class="field-input"><input type="text" name="alamat" required value="{{ old('alamat', $user->organization->alamat) }}" placeholder="Alamat lengkap" class="field-input"></div></div>
                <div><label class="field-label">Nomor HP</label><input type="tel" name="no_telp" required value="{{ old('no_telp', $user->organization->no_telp) }}" class="field-input"></div>
                <div>
                    <label class="field-label">Nama Bank @if ($bankAccount?->status_verifikasi === 'ditolak')<span class="text-red-600">(Ditolak)</span>@endif</label>
                    @if ($bankAccount?->status_verifikasi === 'ditolak')
                        <p class="mb-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[11px] leading-relaxed text-red-700"><strong>Rekening bank ditolak.</strong> Silakan periksa dan perbaiki data rekening, lalu submit ulang.</p>
                    @endif
                    <input type="text" name="bank" required value="{{ old('bank', $bankAccount?->bank) }}" class="field-input">
                </div>
                <div><label class="field-label">Nomor Rekening</label><input type="text" name="no_rekening" required value="{{ old('no_rekening', $bankAccount?->no_rekening) }}" class="field-input"></div>
                <div><label class="field-label">Nama Pemilik Rekening</label><input type="text" name="pemilik_rekening" required value="{{ old('pemilik_rekening', $bankAccount?->pemilik_rekening) }}" class="field-input"></div>
                <div><label class="field-label">Jenis Organisasi</label><select name="tipe" required class="field-input"><option value="Panti Asuhan" {{ old('tipe', $user->organization->tipe) === 'Panti Asuhan' ? 'selected' : '' }}>Panti Asuhan</option><option value="Sekolah" {{ old('tipe', $user->organization->tipe) === 'Sekolah' ? 'selected' : '' }}>Sekolah</option><option value="Yayasan" {{ old('tipe', $user->organization->tipe) === 'Yayasan' ? 'selected' : '' }}>Yayasan</option><option value="Komunitas" {{ old('tipe', $user->organization->tipe) === 'Komunitas' ? 'selected' : '' }}>Komunitas</option></select></div>
                <div><label class="field-label">Deskripsi Organisasi</label><textarea name="deskripsi" required rows="3" class="field-input">{{ old('deskripsi', $user->organization->deskripsi) }}</textarea></div>
                @php
                    $uploads = [
                        ['key' => 'sk', 'name' => 'sk_operasional', 'label' => 'SK Operasional', 'accept' => '.pdf,.jpg,.jpeg,.png'],
                        ['key' => 'ktp', 'name' => 'ktp_pj', 'label' => 'KTP PIC', 'accept' => '.pdf,.jpg,.jpeg,.png'],
                        ['key' => 'kegiatan', 'name' => 'foto_kegiatan', 'label' => 'Foto Kegiatan', 'accept' => '.jpg,.jpeg,.png,.webp'],
                        ['key' => 'bangunan', 'name' => 'foto_bangunan', 'label' => 'Tampak Depan Bangunan', 'accept' => '.jpg,.jpeg,.png,.webp'],
                    ];
                @endphp
                @foreach ($uploads as $upload)
                    @php $document = $documents->get($upload['key']); @endphp
                    <div>
                        <label class="field-label">{{ $upload['label'] }} @if ($document?->status === 'ditolak')<span class="text-red-600">(Wajib diperbaiki)</span>@endif</label>
                        @if ($document?->status === 'ditolak' && $document->alasan_penolakan)
                            <p class="mb-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[11px] leading-relaxed text-red-700"><strong>Alasan penolakan:</strong> {{ $document->alasan_penolakan }}<br>Dokumen ini harus diunggah ulang.</p>
                        @endif
                        <label class="upload-box">
                            <span class="upload-preview flex min-h-[62px] items-center justify-center text-[11px] text-gray-500">@if ($document && str_ends_with(strtolower($document->lokasi_file), 'pdf'))<span>Dokumen saat ini: {{ $document->nama_file }}</span>@elseif ($document)<img src="{{ asset('storage/' . $document->lokasi_file) }}" alt="{{ $upload['label'] }}" class="max-h-20 max-w-full rounded-lg object-contain">@else<span>Belum ada dokumen</span>@endif</span>
                            <span class="mt-2 text-[12px] font-bold text-[#05522d]">Pilih file pengganti</span>
                            <span class="text-[10px] text-gray-600">{{ strtoupper(str_replace('.', '', str_replace(',', ', ', $upload['accept']))) }}</span>
                            <input type="file" name="{{ $upload['name'] }}" accept="{{ $upload['accept'] }}" @if ($document?->status === 'ditolak') required @endif class="hidden" onchange="previewFile(this)">
                        </label>
                        <p class="selected-file mt-1 text-[11px] font-medium text-[#05522d]"></p>
                    </div>
                @endforeach
                <div class="pt-4 flex flex-col items-center"><button type="submit" class="w-[260px] sm:w-[270px] py-3.5 px-6 rounded-xl bg-[#065e38] hover:bg-[#044a2c] text-white font-bold text-[15px] transition-all shadow-sm hover:shadow-md">Kirim Registrasi Ulang</button><a href="{{ route('login') }}" class="mt-3.5 text-[13px] font-bold underline text-gray-950">Kembali ke Login</a></div>
            </form>
        </div>
    </main>
    <style>
        .field-label { display:block; margin-bottom:.375rem; font-size:13.5px; font-weight:500; color:#030712; }
        .field-input { width:100%; padding:.625rem 1rem; border:1px solid #d1d5db; border-radius:.75rem; outline:none; font-size:14px; background:#fff; }
        .field-input:focus { border-color:#05522d; box-shadow:0 0 0 2px rgba(5,82,45,.15); }
        .upload-box { display:flex; min-height:120px; cursor:pointer; flex-direction:column; align-items:center; justify-content:center; border:2px dashed #207466; border-radius:.75rem; background:#effcf8; padding:1rem; text-align:center; }
        .upload-box:hover { background:#e3f8f0; }
    </style>
    <script>
        function previewFile(input) {
            const box = input.closest('.upload-box');
            const preview = box.querySelector('.upload-preview');
            const selected = box.parentElement.querySelector('.selected-file');
            const file = input.files[0];
            if (!file) return;
            selected.textContent = `File baru dipilih: ${file.name}`;
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = event => preview.innerHTML = `<img src="${event.target.result}" alt="Preview dokumen" class="max-h-20 max-w-full rounded-lg object-contain">`;
                reader.readAsDataURL(file);
            } else {
                preview.textContent = `File PDF dipilih: ${file.name}`;
            }
        }
    </script>
</body>
</html>
