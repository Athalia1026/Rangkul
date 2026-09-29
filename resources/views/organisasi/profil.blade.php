@extends('layouts.organization', [
    'title' => 'Profil Panti',
    'activeNav' => 'profil'
])

@section('content')

{{-- =========================================================
    HERO PROFIL
========================================================= --}}
<section class="relative h-[500px] overflow-hidden">

    <img
        src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?q=80&w=2400&auto=format&fit=crop"
        alt="Profil Panti"
        class="absolute inset-0 w-full h-full object-cover"
    >

    <div class="absolute inset-0 bg-black/45"></div>

    <div class="relative z-10 h-full flex items-center">

        <div class="w-full org-container px-6 sm:px-8 lg:px-12">

            <h1
                class="text-white
                       text-[62px]
                       lg:text-[70px]
                       xl:text-[76px]
                       font-bold
                       leading-[1.08]"
            >
                Profil Panti
            </h1>

            <p
                class="mt-7
                       text-white/95
                       text-[28px]
                       lg:text-[31px]
                       font-medium"
            >
                Kelola Profil dan Informasi Panti
            </p>

        </div>

    </div>

</section>



{{-- =========================================================
    MAIN CONTENT
========================================================= --}}
<form
    id="profileForm"
    class="w-full
           org-container px-6 sm:px-8 lg:px-12
           py-20
           space-y-12"
>

    {{-- =====================================================
        FOTO DAN IDENTITAS
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[28px]
               shadow-md
               border border-gray-100
               px-10
               lg:px-12
               py-12"
    >

        {{-- TITLE --}}
        <div class="flex items-center gap-5">

            <div
                class="w-[60px]
                       h-[60px]
                       rounded-[15px]
                       bg-[#DDF0E9]
                       text-[#08703F]
                       flex items-center justify-center"
            >
                <i class="fa-solid fa-camera text-[28px]"></i>
            </div>


            <h2
                class="text-[38px]
                       lg:text-[42px]
                       font-bold
                       text-gray-950"
            >
                Foto dan Identitas Panti
            </h2>

        </div>



        {{-- =================================================
            FOTO + FORM
        ================================================= --}}
        <div
            class="grid grid-cols-1
                   lg:grid-cols-[420px_1fr]
                   gap-12
                   mt-11"
        >

            {{-- FOTO UPLOAD --}}
            @php($profilePhotoUrl = $user->profilePhotoUrl())
            <div>

                <label
                    for="fotoPanti"
                    class="relative
                           w-full
                           h-[470px]
                           overflow-hidden
                           bg-[#ECFAF7]
                           border-2
                           border-[#7B9F94]
                           rounded-[22px]
                           flex
                           flex-col
                           items-center
                           justify-center
                           cursor-pointer
                           hover:bg-[#E2F6F1]
                           transition"
                >

                    <img
                        id="fotoPreview"
                        src="{{ $profilePhotoUrl }}"
                        alt="Foto profil {{ $organization->nama_lembaga }}"
                        class="{{ $profilePhotoUrl ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover"
                    >

                    {{-- Lapisan petunjuk: selalu tampil jika belum ada foto, muncul saat hover jika sudah ada --}}
                    <div
                        id="fotoHint"
                        class="absolute inset-0
                               flex flex-col items-center justify-center
                               transition
                               {{ $profilePhotoUrl ? 'bg-black/45 opacity-0 hover:opacity-100 text-white' : '' }}"
                    >

                    <div
                        class="relative
                               w-[80px]
                               h-[80px]
                               rounded-full
                               bg-[#D6F0E7]
                               text-[#08703F]
                               flex
                               items-center
                               justify-center"
                    >
                        <i class="fa-solid fa-cloud-arrow-up text-[38px]"></i>
                    </div>


                    <p
                        id="fotoTitle"
                        class="relative
                               mt-6
                               text-[28px]
                               font-bold
                               {{ $profilePhotoUrl ? 'text-white' : 'text-gray-950' }}"
                    >
                        {{ $profilePhotoUrl ? 'Ganti Foto Profil' : 'Unggah Foto Profil' }}
                    </p>


                    <p
                        id="fotoCaption"
                        class="relative
                               mt-3
                               px-8
                               text-center
                               text-[20px]
                               {{ $profilePhotoUrl ? 'text-white/90' : 'text-gray-500' }}"
                    >
                        Klik untuk memilih file dengan tipe JPG, PNG (maks. 2 MB).
                    </p>

                    </div>


                    <input
                        id="fotoPanti"
                        type="file"
                        accept=".jpg,.jpeg,.png"
                        class="hidden"
                    >

                </label>

            </div>



            {{-- IDENTITAS --}}
            <div class="space-y-8">

                {{-- NAMA --}}
                <div>

                    <label
                        for="namaPanti"
                        class="block
                               mb-4
                               text-[24px]
                               font-semibold
                               text-[#08703F]"
                    >
                        Nama Panti
                    </label>

                    <input
                        id="namaPanti"
                        name="nama_lembaga"
                        type="text"
                        value="{{ $organization->nama_lembaga }}"
                        required
                        class="w-full
                               h-[82px]
                               bg-white
                               border border-gray-300
                               rounded-[16px]
                               px-6
                               text-[23px]
                               text-gray-900
                               outline-none
                               focus:border-[#08703F]
                               focus:ring-2
                               focus:ring-[#08703F]/10"
                    >

                </div>



                {{-- EMAIL --}}
                <div>

                    <label
                        for="emailPanti"
                        class="block
                               mb-4
                               text-[24px]
                               font-semibold
                               text-[#08703F]"
                    >
                        Email
                    </label>

                    <input
                        id="emailPanti"
                        type="email"
                        value="{{ $user->email }}"
                        readonly
                        class="w-full
                               h-[82px]
                               bg-[#F3F5F4]
                               border border-gray-300
                               rounded-[16px]
                               px-6
                               text-[23px]
                               text-gray-900
                               outline-none
                               focus:border-[#08703F]
                               focus:ring-2
                               focus:ring-[#08703F]/10"
                    >

                </div>



                {{-- TELEPON --}}
                <div>

                    <label
                        for="teleponPanti"
                        class="block
                               mb-4
                               text-[24px]
                               font-semibold
                               text-[#08703F]"
                    >
                        No.telepon
                    </label>

                    <input
                        id="teleponPanti"
                        name="no_telp"
                        type="text"
                        value="{{ $organization->no_telp }}"
                        required
                        class="w-full
                               h-[82px]
                               bg-white
                               border border-gray-300
                               rounded-[16px]
                               px-6
                               text-[23px]
                               text-gray-900
                               outline-none
                               focus:border-[#08703F]
                               focus:ring-2
                               focus:ring-[#08703F]/10"
                    >

                </div>



                {{-- ALAMAT --}}
                <div>

                    <label
                        for="alamatPanti"
                        class="block
                               mb-4
                               text-[24px]
                               font-semibold
                               text-[#08703F]"
                    >
                        Alamat panti
                    </label>

                    <input
                        id="alamatPanti"
                        name="alamat"
                        type="text"
                        value="{{ $organization->alamat }}"
                        required
                        class="w-full
                               h-[82px]
                               bg-white
                               border border-gray-300
                               rounded-[16px]
                               px-6
                               text-[23px]
                               text-gray-900
                               outline-none
                               focus:border-[#08703F]
                               focus:ring-2
                               focus:ring-[#08703F]/10"
                    >

                </div>

            </div>

        </div>



        {{-- =================================================
            DESKRIPSI
        ================================================= --}}
        <div class="mt-12">

            <div class="flex items-center gap-4 mb-5">

                <div
                    class="w-[52px]
                           h-[52px]
                           rounded-[13px]
                           bg-[#EEF2F1]
                           text-gray-700
                           flex items-center justify-center"
                >
                    <i class="fa-regular fa-rectangle-list text-[25px]"></i>
                </div>


                <label
                    for="deskripsiPanti"
                    class="text-[30px]
                           lg:text-[34px]
                           font-bold
                           text-gray-950"
                >
                    Deskripsi Panti
                </label>

            </div>


            <textarea
                id="deskripsiPanti"
                name="deskripsi"
                required
                class="w-full
                       h-[330px]
                       bg-white
                       border border-gray-300
                       rounded-[18px]
                       px-7
                       py-6
                       text-[23px]
                       text-gray-900
                       leading-relaxed
                       outline-none
                       resize-none
                       focus:border-[#08703F]
                       focus:ring-2
                       focus:ring-[#08703F]/10"
            >{{ $organization->deskripsi }}</textarea>

        </div>

    </section>



    {{-- =====================================================
        INFORMASI TAMBAHAN
    ===================================================== --}}
    <section
        class="bg-white
               rounded-[28px]
               shadow-md
               border border-gray-100
               px-10
               lg:px-12
               py-12"
    >

        {{-- TITLE --}}
        <div class="flex items-center gap-5">

            <div
                class="w-[60px]
                       h-[60px]
                       rounded-full
                       bg-[#08703F]
                       text-white
                       flex items-center justify-center"
            >
                <i class="fa-solid fa-plus text-[28px]"></i>
            </div>


            <h2
                class="text-[38px]
                       lg:text-[42px]
                       font-bold
                       text-gray-950"
            >
                Informasi Tambahan
            </h2>

        </div>



        {{-- FORM GRID --}}
        <div
            class="grid grid-cols-1
                   lg:grid-cols-2
                   gap-x-16
                   gap-y-9
                   mt-11"
        >

            {{-- =================================================
                JUMLAH ANAK
            ================================================= --}}
            <div>

                <label
                    for="jumlahAnak"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Jumlah Anak
                </label>


                <div class="flex items-center gap-5">

                    <div class="relative w-[300px]">

                        <input
                            id="jumlahAnak"
                            name="jumlah_anak"
                            type="number"
                            min="0"
                            value="{{ $organization->jumlah_anak }}"
                            class="w-full
                                   h-[82px]
                                   bg-white
                                   border border-gray-300
                                   rounded-[16px]
                                   px-6
                                   pr-16
                                   text-[23px]
                                   text-gray-900
                                   outline-none
                                   focus:border-[#08703F]
                                   focus:ring-2
                                   focus:ring-[#08703F]/10"
                        >

                        <i
                            class="fa-solid fa-children
                                   absolute
                                   right-6
                                   top-1/2
                                   -translate-y-1/2
                                   text-[24px]
                                   text-gray-900"
                        ></i>

                    </div>


                    <span
                        class="text-[26px]
                               font-medium
                               text-gray-900"
                    >
                        Orang
                    </span>

                </div>

            </div>



            {{-- =================================================
                TAHUN BERDIRI
            ================================================= --}}
            <div>

                <label
                    for="tahunBerdiri"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Tahun Berdiri
                </label>


                <input
                    id="tahunBerdiri"
                    name="tahun_berdiri"
                    type="number"
                    min="1"
                    max="{{ now()->year }}"
                    value="{{ $organization->tahun_berdiri }}"
                    placeholder="Masukan tahun"
                    class="w-full
                           h-[82px]
                           bg-white
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           text-[23px]
                           text-gray-900
                           placeholder:text-[#A9CEC0]
                           outline-none
                           focus:border-[#08703F]
                           focus:ring-2
                           focus:ring-[#08703F]/10"
                >

            </div>



            {{-- =================================================
                NAMA BANK
            ================================================= --}}
            <div>

                <label
                    for="namaBank"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Nama Bank
                </label>


                <input
                    id="namaBank"
                    type="text"
                    readonly
                    value="{{ $bankAccount?->bank ?? '-' }}"
                    class="w-full
                           h-[82px]
                           bg-[#F3F5F4]
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           text-[23px]
                           text-gray-900
                           outline-none"
                >

            </div>



            {{-- =================================================
                NOMOR REKENING
            ================================================= --}}
            <div>

                <label
                    for="nomorRekening"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Nomor Rekening
                </label>


                <input
                    id="nomorRekening"
                    type="text"
                    readonly
                    value="{{ $bankAccount?->no_rekening ?? '-' }}"
                    class="w-full
                           h-[82px]
                           bg-[#F3F5F4]
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           text-[23px]
                           text-gray-900
                           outline-none
                           focus:border-[#08703F]
                           focus:ring-2
                           focus:ring-[#08703F]/10"
                >

            </div>



            {{-- =================================================
                NAMA PEMILIK REKENING
            ================================================= --}}
            <div>

                <label
                    for="pemilikRekening"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Nama Pemilik Rekening
                </label>


                <input
                    id="pemilikRekening"
                    type="text"
                    readonly
                    value="{{ $bankAccount?->pemilik_rekening ?? '-' }}"
                    class="w-full
                           h-[82px]
                           bg-[#F3F5F4]
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           text-[23px]
                           text-gray-900
                           outline-none
                           focus:border-[#08703F]
                           focus:ring-2
                           focus:ring-[#08703F]/10"
                >

            </div>



            {{-- =================================================
                STATUS
            ================================================= --}}
            <div>

                <label
                    for="statusPanti"
                    class="block
                           mb-4
                           text-[24px]
                           font-semibold
                           text-[#08703F]"
                >
                    Status
                </label>


                <input
                    id="statusPanti"
                    type="text"
                    readonly
                    value="{{ $organization->verification_status === 'disetujui' ? 'Terverifikasi' : ucfirst($organization->verification_status) }}"
                    class="w-full
                           h-[82px]
                           bg-[#F3F5F4]
                           border border-gray-300
                           rounded-[16px]
                           px-6
                           text-[23px]
                           text-gray-900
                           outline-none"
                >

            </div>

        </div>



        {{-- =================================================
            BUTTON SIMPAN
        ================================================= --}}
        <p class="mt-10 text-[20px] text-gray-500">
            Email dan data rekening tidak dapat diubah dari halaman ini karena memerlukan verifikasi ulang oleh admin.
        </p>

        <div class="flex justify-end mt-14">

            <button
                type="submit"
                id="saveProfile"
                class="min-w-[220px]
                       h-[76px]
                       bg-[#08703F]
                       hover:bg-[#065D35]
                       text-white
                       rounded-[18px]
                       text-[24px]
                       font-semibold
                       transition"
            >
                Simpan
            </button>

        </div>

    </section>

</form>

@endsection


@push('scripts')
<script>
    try {
        if (sessionStorage.getItem('profileSaved')) {
            sessionStorage.removeItem('profileSaved');
            orgFlash('Profil berhasil disimpan.');
        }
    } catch (e) {}

    document.getElementById('profileForm').addEventListener('submit', async function (event) {
        event.preventDefault();

        const button = document.getElementById('saveProfile');
        const payload = Object.fromEntries(
            ['nama_lembaga', 'no_telp', 'alamat', 'deskripsi', 'jumlah_anak', 'tahun_berdiri']
                .map((field) => [field, this.elements[field].value.trim()])
                .map(([field, value]) => [field, value === '' && ['jumlah_anak', 'tahun_berdiri'].includes(field) ? null : value])
        );

        button.disabled = true;

        try {
            if (photoInput.files[0]) {
                const photoData = new FormData();
                photoData.append('profile_photo', photoInput.files[0]);
                await orgRequest('{{ route('organisasi.profil.foto') }}', { body: photoData });
            }

            await orgRequest('{{ route('organisasi.profil.update') }}', { method: 'PUT', body: payload });

            // Muat ulang agar foto di navbar ikut diperbarui; pesan sukses ditampilkan setelah reload.
            try { sessionStorage.setItem('profileSaved', '1'); } catch (e) {}
            window.location.reload();
        } catch (error) {
            orgFlash(error.message, 'error');
        } finally {
            button.disabled = false;
        }
    });


    const photoInput = document.getElementById('fotoPanti');

    // Pratinjau foto yang dipilih; file baru diunggah saat tombol Simpan ditekan.
    photoInput.addEventListener('change', function () {
        const file = this.files[0];

        if (!file) {
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            orgFlash('Ukuran foto maksimal 2 MB.', 'error');
            this.value = '';
            return;
        }

        const preview = document.getElementById('fotoPreview');
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');

        const hint = document.getElementById('fotoHint');
        hint.classList.add('bg-black/45', 'opacity-0', 'hover:opacity-100', 'text-white');
        document.getElementById('fotoTitle').classList.replace('text-gray-950', 'text-white');
        document.getElementById('fotoTitle').textContent = 'Ganti Foto Profil';
        document.getElementById('fotoCaption').classList.replace('text-gray-500', 'text-white/90');

        orgFlash('Foto dipilih. Tekan Simpan untuk menyimpan perubahan.');
    });
</script>
@endpush