<footer class="bg-[#075B3C] text-white mt-auto">

    <div class="w-full px-8 sm:px-10 lg:px-16 xl:px-20 2xl:px-24 py-20">

        <div
            class="grid grid-cols-1
                   md:grid-cols-[2.4fr_1fr_1fr]
                   gap-14 lg:gap-24 xl:gap-28"
        >

            {{-- =================================================
                BRAND
            ================================================= --}}
            <div>

                <a
                    href="{{ route('organisasi.dashboard') }}"
                    class="inline-flex items-center gap-5"
                >

                    <span
                        class="w-[68px] h-[68px]
                               rounded-full
                               bg-white
                               text-[#0E6B53]
                               flex items-center justify-center"
                    >
                        <i class="fa-solid fa-hand-holding-heart text-[26px]"></i>
                    </span>


                    <span class="text-[34px] font-bold">
                        Rangkul.com
                    </span>

                </a>


                <p
                    class="mt-8
                           text-[22px]
                           text-white/85
                           leading-[1.7]
                           max-w-[760px]"
                >
                    Rangkul hadir untuk menghubungkan kebaikan melalui
                    platform donasi yang aman, transparan, dan berdampak
                    bagi mereka yang membutuhkan.
                </p>


                <div class="flex gap-8 mt-9 text-[32px] text-white/90">

                    <i class="fa-brands fa-instagram"></i>

                    <i class="fa-brands fa-tiktok"></i>

                    <i class="fa-brands fa-facebook"></i>

                </div>

            </div>



            {{-- =================================================
                NAVIGATION
            ================================================= --}}
            <div>

                <h4 class="text-[27px] font-semibold mb-8">
                    Navigasi
                </h4>


                <ul class="space-y-5 text-[22px] text-white/80">

                    <li>
                        <a
                            href="{{ route('organisasi.dashboard') }}"
                            class="hover:text-white transition"
                        >
                            Beranda
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('organisasi.kampanye') }}"
                            class="hover:text-white transition"
                        >
                            Kampanye
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('organisasi.donasi') }}"
                            class="hover:text-white transition"
                        >
                            Donasi
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('organisasi.kunjungan') }}"
                            class="hover:text-white transition"
                        >
                            Kunjungan
                        </a>
                    </li>

                    <li>
                        Laporan
                    </li>

                </ul>

            </div>



            {{-- =================================================
                INFORMATION
            ================================================= --}}
            <div>

                <h4 class="text-[27px] font-semibold mb-8">
                    Informasi
                </h4>


                <ul class="space-y-5 text-[22px] text-white/80">

                    <li>
                        Tentang Kami
                    </li>

                    <li>
                        Kontak
                    </li>

                    <li>
                        Kebijakan Privasi
                    </li>

                    <li>
                        Syarat dan Ketentuan
                    </li>

                </ul>

            </div>

        </div>


        <div
            class="text-center
                   text-[17px]
                   text-white/70
                   mt-16"
        >
            &copy; {{ date('Y') }} Rangkul. Hak cipta dilindungi undang-undang.
        </div>

    </div>

</footer>