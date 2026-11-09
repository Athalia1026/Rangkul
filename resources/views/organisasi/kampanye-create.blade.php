@extends('layouts.organization', [
    'title' => 'Tambah Kampanye',
    'activeNav' => 'kampanye'
])

@section('content')

<main class="w-full org-container px-6 sm:px-8 lg:px-12 pt-16 pb-24">
    <div class="max-w-[1100px] mx-auto">
        <a
            href="{{ route('organisasi.kampanye') }}"
            class="inline-flex items-center gap-3 text-[22px] text-gray-600 hover:text-[#08703F] transition"
        >
            <i class="fa-solid fa-arrow-left text-[16px]"></i>
            Kembali ke Kampanye
        </a>
    </div>

    <form
        method="POST"
        action="{{ route('organisasi.kampanye.store') }}"
        enctype="multipart/form-data"
        class="mt-9 max-w-[1100px] mx-auto bg-white rounded-[28px] shadow-md border border-gray-100 px-10 lg:px-14 py-12"
    >
        @csrf

        <h1 class="text-[40px] font-bold text-[#08703F] leading-tight">
            Tambah Kampanye
        </h1>
        <p class="mt-2 text-[21px] text-gray-500">
            Lengkapi informasi kampanye untuk diajukan ke Admin.
        </p>

        <div class="mt-10 space-y-8">
            <div>
                <label for="judul" class="block mb-3 text-[22px] font-medium text-gray-900">
                    Nama Kampanye <span class="text-red-500">*</span>
                </label>
                <input
                    id="judul"
                    name="judul"
                    type="text"
                    value="{{ old('judul') }}"
                    maxlength="255"
                    required
                    class="w-full h-[70px] bg-white border border-gray-300 rounded-[14px] px-6 text-[22px] text-gray-900 placeholder:text-gray-400 outline-none focus:border-[#08703F] focus:ring-2 focus:ring-[#08703F]/10 transition"
                >
                @error('judul')
                    <p class="mt-2 text-red-600" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="deskripsi" class="block mb-3 text-[22px] font-medium text-gray-900">
                    Deskripsi <span class="text-red-500">*</span>
                </label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="5"
                    required
                    class="w-full bg-white border border-gray-300 rounded-[14px] px-6 py-5 text-[22px] text-gray-900 placeholder:text-gray-400 outline-none focus:border-[#08703F] focus:ring-2 focus:ring-[#08703F]/10 transition"
                >{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-2 text-red-600" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="tanggal_mulai" class="block mb-3 text-[22px] font-medium text-gray-900">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="tanggal_mulai"
                        name="tanggal_mulai"
                        type="date"
                        value="{{ old('tanggal_mulai') }}"
                        required
                        class="w-full h-[70px] bg-white border border-gray-300 rounded-[14px] px-6 text-[22px] text-gray-900 outline-none focus:border-[#08703F] focus:ring-2 focus:ring-[#08703F]/10 transition"
                    >
                    @error('tanggal_mulai')
                        <p class="mt-2 text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_selesai" class="block mb-3 text-[22px] font-medium text-gray-900">
                        Tanggal Selesai <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="tanggal_selesai"
                        name="tanggal_selesai"
                        type="date"
                        value="{{ old('tanggal_selesai') }}"
                        required
                        class="w-full h-[70px] bg-white border border-gray-300 rounded-[14px] px-6 text-[22px] text-gray-900 outline-none focus:border-[#08703F] focus:ring-2 focus:ring-[#08703F]/10 transition"
                    >
                    @error('tanggal_selesai')
                        <p class="mt-2 text-red-600" role="alert">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="target_dana" class="block mb-3 text-[22px] font-medium text-gray-900">
                    Target Dana (Rp) <span class="text-red-500">*</span>
                </label>
                <input
                    id="target_dana"
                    name="target_dana"
                    type="number"
                    min="10000"
                    step="1000"
                    value="{{ old('target_dana') }}"
                    required
                    class="w-full h-[70px] bg-white border border-gray-300 rounded-[14px] px-6 text-[22px] text-gray-900 placeholder:text-gray-400 outline-none focus:border-[#08703F] focus:ring-2 focus:ring-[#08703F]/10 transition"
                >
                @error('target_dana')
                    <p class="mt-2 text-red-600" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="id_categories" class="block mb-3 text-[22px] font-medium text-gray-900">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select
                    id="id_categories"
                    name="id_categories"
                    required
                    class="w-full h-[70px] bg-white border border-gray-300 rounded-[14px] px-6 text-[22px] text-gray-900 outline-none focus:border-[#08703F] focus:ring-2 focus:ring-[#08703F]/10 transition"
                >
                    <option value="">Pilih kategori kampanye</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('id_categories') === $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('id_categories')
                    <p class="mt-2 text-red-600" role="alert">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="foto_cover" class="block mb-3 text-[22px] font-medium text-gray-900">
                    Foto Cover <span class="text-red-500">*</span>
                </label>
                <input
                    id="foto_cover"
                    name="foto_cover"
                    type="file"
                    accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                    required
                    class="block w-full text-[20px] text-gray-700 file:mr-5 file:rounded-[12px] file:border-0 file:bg-[#DDF0E9] file:px-5 file:py-3 file:font-semibold file:text-[#08703F] hover:file:bg-[#C8E8D9]"
                >
                <p class="mt-2 text-[18px] text-gray-500">Format JPG atau PNG, maksimal 2 MB.</p>
                @error('foto_cover')
                    <p class="mt-2 text-red-600" role="alert">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-10 flex flex-wrap justify-end gap-4">
            <a
                href="{{ route('organisasi.kampanye') }}"
                class="inline-flex items-center justify-center rounded-[14px] border border-gray-300 px-7 py-4 text-[20px] font-semibold text-gray-700 hover:bg-gray-50 transition"
            >
                Batal
            </a>
            <button
                type="submit"
                class="rounded-[14px] bg-[#08703F] px-8 py-4 text-[24px] font-semibold text-white hover:bg-[#065D35] transition"
            >
                Simpan Kampanye
            </button>
        </div>
    </form>
</main>

@endsection
