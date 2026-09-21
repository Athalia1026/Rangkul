{{-- resources/views/company-profile.blade.php --}}
@extends('layouts.public')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rangkul - Platform Donasi Terpercaya | Company Profile</title>

    <!-- PANGGIL VITE DI SINI -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


@section('content')
    <div class="bg-[#F5F7F4]">
    <!-- HERO & STATS -->
    <section id="beranda" class="w-full max-w-[1200px] mx-auto ">
        <div class="relative w-full min-h-[500px] flex items-center overflow-hidden bg-[#1f2937]">
            <div class="absolute inset-0">
                <img src="{{ asset('images/hero/hero_children.jpg') }}" alt="Hero" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/60 to-black/30"></div>
            </div>
            <div class="relative z-10 w-full max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-20 text-white">
                <div class="max-w-2xl">
                    <h1 class="text-[36px] sm:text-[42px] font-bold tracking-tight leading-[1.2] mb-5">
                        Rangkul Kebaikan, Ciptakan Dampak Nyata
                    </h1>
                    <p class="text-[17px] text-gray-100 font-normal leading-relaxed mb-8">
                        Rangkul menghubungkan donatur dengan berbagai organisasi sosial melalui platform yang aman,
                        transparan, dan mudah digunakan.
                    </p>
                    <a href="{{ route('login') }}"
                        class="inline-block px-8 py-3.5 border-2 border-white text-white font-bold text-[17px] rounded-xl hover:bg-white hover:text-[#05522d] transition-all">
                        Mulai Berdonasi
                    </a>
                </div>
            </div>
        </div>

        <div class="w-full bg-[#207466] text-white py-5">
            <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center divide-y md:divide-y-0 md:divide-x divide-white/20">
                    <div class="py-2"><span class="text-[22px] font-bold">1000+ Anak Terbantu</span></div>
                    <div class="py-2"><span class="text-[22px] font-bold">500+ Organisasi Terdaftar</span></div>
                    <div class="py-2"><span class="text-[22px] font-bold">300+ Kampanye Tersalurkan</span></div>
                </div>
            </div>
        </div>
    </section>

    <!-- VISI & MISI -->
    <section id="tentang" class="w-full max-w-[1200px] mx-auto px-8 py-16 bg-[#f5f7f4]">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">
                <!-- Visi Card -->
                <div
                    class="bg-gradient-to-br from-white via-[#f4fbf6] to-[#d8f0e2] rounded-[20px] p-8 md:p-9 border border-[#c4e8d0] shadow-[0_8px_30px_rgba(5,82,45,0.06)]">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-1.5 h-7 bg-[#9a7313] rounded-full inline-block"></span>
                        <h2 class="text-[24px] font-bold text-[#05522d]">Visi Kami</h2>
                    </div>
                    <p class="text-[16px] sm:text-[17px] text-gray-700 leading-relaxed">
                        Menjadi jembatan kebaikan yang paling dipercaya dan memudahkan jutaan orang untuk saling membantu,
                        memberdayakan masyarakat, dan menciptakan masa depan yang lebih baik.
                    </p>
                </div>

                <!-- Misi Card -->
                <div
                    class="bg-gradient-to-br from-white via-[#f4fbf6] to-[#d8f0e2] rounded-[20px] p-8 md:p-9 border border-[#c4e8d0] shadow-[0_8px_30px_rgba(5,82,45,0.06)]">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-1.5 h-7 bg-[#9a7313] rounded-full inline-block"></span>
                        <h2 class="text-[24px] font-bold text-[#05522d]">Misi Kami</h2>
                    </div>
                    <ul class="space-y-3 text-[15px] sm:text-[16px] text-gray-700">
                        <li class="flex items-start gap-3">
                            <span class="w-2 h-2 rounded-full bg-[#207466] mt-2 shrink-0"></span>
                            <span>Menyediakan platform donasi yang transparan, aman, dan mudah digunakan</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-2 h-2 rounded-full bg-[#207466] mt-2 shrink-0"></span>
                            <span>Memperluas jangkauan bantuan sosial ke seluruh pelosok yang membutuhkan</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-2 h-2 rounded-full bg-[#207466] mt-2 shrink-0"></span>
                            <span>Membangun kolaborasi dengan organisasi sosial terverifikasi dan kredibel</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="w-2 h-2 rounded-full bg-[#207466] mt-2 shrink-0"></span>
                            <span>Mengedukasi masyarakat tentang pentingnya kepedulian sosial berkelanjutan</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section class="w-full py-16 bg-white overflow-hidden">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 mb-8 text-center">
            <h2 class="text-[28px] sm:text-[32px] font-bold text-[#05522d]">
                Cerita dari Mereka yang Merasakan Manfaatnya
            </h2>
        </div>
        <div class="w-full relative py-4">
            <div class="testimonial-track flex w-max select-none">
                @php
                    $loopItems = [
                        [
                            'name' => 'Andi Pratama',
                            'org' => 'Panti Asuhan Kasih Bunda',
                            'photo' => 'images/hero/andi-pratama.jpg',
                            'quote' => 'Rangkul memudahkan kami menjangkau lebih banyak donatur. Prosesnya transparan dan sangat membantu kebutuhan anak-anak di panti.'
                        ],
                        [
                            'name' => 'Siti Nurhaliza',
                            'org' => 'SD Harapan Bangsa',
                            'photo' => 'images/hero/siti-nurhaliza.jpg',
                            'quote' => 'Melalui Rangkul, penggalangan dana untuk renovasi ruang belajar menjadi lebih mudah dan mendapat dukungan dari banyak pihak.'
                        ],
                        [
                            'name' => 'Budi Santoso',
                            'org' => 'Panti Asuhan Cahaya Harapan',
                            'photo' => 'images/hero/budi-santoso.jpg',
                            'quote' => 'Platform ini membantu kami mengelola kampanye dengan lebih rapi dan meningkatkan kepercayaan para donatur.'
                        ],
                        [
                            'name' => 'Maria Angelina',
                            'org' => 'Panti Asuhan Pelita Kasih',
                            'photo' => 'images/hero/maria-angelina.jpg',
                            'quote' => 'Rangkul menjadi jembatan yang mempertemukan organisasi kami dengan para donatur yang ingin memberikan dampak nyata.'
                        ]
                    ]
                @endphp
                @foreach([$loopItems, $loopItems] as $groupIndex => $items)
                    <div class="testimonial-group flex gap-6 pr-6" @if ($groupIndex === 1) aria-hidden="true" @endif>
                        @foreach($items as $item)
                            <div
                                class="w-[340px] sm:w-[380px] shrink-0 bg-white rounded-[20px] p-7 border border-gray-200 shadow-sm flex flex-col justify-between">
                                <p class="text-gray-700 italic leading-[1.7] min-h-[85px]">"{{ $item['quote'] }}"</p>
                                <div class="flex items-center gap-3.5 pt-4 border-t border-gray-100">
                                    <div class="w-11 h-11 rounded-full overflow-hidden shrink-0">
                                        <img src="{{ asset($item['photo']) }}" alt="{{ $item['name'] }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h4 class="text-[15px] font-bold text-gray-900">{{ $item['name'] }}</h4>
                                        <p class="text-[13px] text-gray-500">{{ $item['org'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- TRANSPARANSI -->
    <section class="w-full py-16 bg-[#d8f0e2]">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-6 space-y-6">
                    <h2 class="text-[28px] sm:text-[34px] font-bold text-[#05522d]">
                        Setiap Donasi Dapat Dipantau
                    </h2>
                    <p class="text-[16px] text-gray-800 leading-relaxed">
                        Kami mendefinisikan ulang makna kepercayaan. Melalui Rangkul, setiap rupiah yang disumbangkan dapat
                        dilacak perjalanannya hingga sampai ke tangan penerima.
                    </p>
                    <div class="space-y-4 pt-2">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-6 h-6 rounded-full bg-[#05522d] text-white flex items-center justify-center shrink-0 mt-1">
                                ✓</div>
                            <div>
                                <h3 class="text-[17px] font-bold text-gray-900">Lacak Status Donasi</h3>
                                <p class="text-[14px] text-gray-700 mt-1">Pantau status donasi dari pembayaran hingga
                                    penyaluran kepada organisasi</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="w-6 h-6 rounded-full bg-[#05522d] text-white flex items-center justify-center shrink-0 mt-1">
                                ✓</div>
                            <div>
                                <h3 class="text-[17px] font-bold text-gray-900">Akses Laporan Penyaluran</h3>
                                <p class="text-[14px] text-gray-700 mt-1">Lihat laporan dan bukti penyaluran donasi secara
                                    transparan</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-6 flex justify-center lg:justify-end">
                    <div class="rounded-[24px] overflow-hidden shadow-xl border-4 border-white max-w-[480px] w-full">
                        <img src="{{ asset('images/hero/students_peace.jpg') }}" alt="Siswa tersenyum"
                            class="w-full h-[340px] object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="w-full py-16 bg-[#f5f7f4]">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <h2 class="text-[30px] font-bold text-gray-950">FAQ</h2>
                <p class="text-[18px] text-gray-800 font-medium mt-1">Pertanyaan Umum Seputar Layanan Kami</p>
            </div>
            <div class="max-w-3xl mx-auto space-y-4">
                <details class="bg-white border-2 border-[#207466] rounded-[16px] p-5 cursor-pointer" open>
                    <summary class="font-semibold text-gray-900 text-[17px]">Bagaimana cara berdonasi melalui Rangkul?
                    </summary>
                    <p class="mt-3 text-gray-700 text-[15px] border-t pt-3">Pilih campaign yang ingin didukung, tentukan
                        nominal donasi, lakukan pembayaran melalui metode yang tersedia, lalu pantau status donasi hingga
                        proses penyaluran.</p>
                </details>
                <details class="bg-white border-2 border-[#207466] rounded-[16px] p-5 cursor-pointer">
                    <summary class="font-semibold text-gray-900 text-[17px]">Apakah donasi saya aman dan transparan?
                    </summary>
                    <p class="mt-3 text-gray-700 text-[15px] border-t pt-3">Ya. Setiap donasi dapat dipantau melalui riwayat
                        donasi serta dilengkapi dengan laporan dan bukti penyaluran dari organisasi penerima.</p>
                </details>
                <details class="bg-white border-2 border-[#207466] rounded-[16px] p-5 cursor-pointer">
                    <summary class="font-semibold text-gray-900 text-[17px]">Apakah saya bisa berdonasi secara anonim?
                    </summary>
                    <p class="mt-3 text-gray-700 text-[15px] border-t pt-3">Bisa. Anda dapat memilih opsi donasi anonim
                        sehingga identitas Anda tidak ditampilkan kepada publik.</p>
                </details>
                <details class="bg-white border-2 border-[#207466] rounded-[16px] p-5 cursor-pointer">
                    <summary class="font-semibold text-gray-900 text-[17px]">Bagaimana cara organisasi bergabung dengan
                        Rangkul?</summary>
                    <p class="mt-3 text-gray-700 text-[15px] border-t pt-3">Organisasi dapat mendaftar melalui halaman
                        registrasi, melengkapi data yang diperlukan, lalu menunggu proses verifikasi sebelum mulai membuat
                        campaign.</p>
                </details>
            </div>
        </div>
    </section>
    </div>
@endsection