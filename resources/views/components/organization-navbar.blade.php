<header class="bg-white border-b border-gray-100 w-full sticky top-0 z-50">

    <div
        class="w-full
               px-8 sm:px-10 lg:px-16 xl:px-20 2xl:px-24
               h-[124px]
               flex items-center justify-between"
    >

        {{-- =====================================================
            LOGO
        ===================================================== --}}
        <a
            href="{{ route('organisasi.dashboard') }}"
            class="flex items-center gap-4 shrink-0"
        >

            <div
                class="w-[68px]
                       h-[68px]
                       rounded-full
                       bg-[#08703F]
                       text-white
                       flex items-center
                       justify-center"
            >
                <i class="fa-solid fa-hand-holding-heart text-[30px]"></i>
            </div>


            <div class="hidden sm:block">

                <p class="text-[34px] leading-none font-bold text-[#08703F]">
                    Rangkul.com
                </p>

                <p class="mt-1 text-[15px] text-gray-500">
                    Donation Platform
                </p>

            </div>

        </a>



        {{-- =====================================================
            DESKTOP NAVIGATION
        ===================================================== --}}
        <nav
            class="hidden lg:flex
                   items-center
                   gap-10 xl:gap-14
                   text-[22px] xl:text-[23px]
                   font-semibold"
        >

            <a
                href="{{ route('organisasi.dashboard') }}"
                class="relative py-4 transition
                       {{ ($activeNav ?? '') === 'dashboard'
                            ? 'text-[#08703F]'
                            : 'text-gray-900 hover:text-[#08703F]' }}"
            >
                Dashboard

                @if (($activeNav ?? '') === 'dashboard')
                    <span
                        class="absolute
                               left-0 right-0
                               -bottom-1
                               h-[4px]
                               bg-[#08703F]
                               rounded-full"
                    ></span>
                @endif
            </a>


            <a
                href="{{ route('organisasi.kampanye') }}"
                class="relative py-4 transition
                       {{ ($activeNav ?? '') === 'kampanye'
                            ? 'text-[#08703F]'
                            : 'text-gray-900 hover:text-[#08703F]' }}"
            >
                Kampanye

                @if (($activeNav ?? '') === 'kampanye')
                    <span
                        class="absolute
                               left-0 right-0
                               -bottom-1
                               h-[4px]
                               bg-[#08703F]
                               rounded-full"
                    ></span>
                @endif
            </a>


            <a
                href="{{ route('organisasi.donasi') }}"
                class="relative py-4 transition
                       {{ ($activeNav ?? '') === 'donasi'
                            ? 'text-[#08703F]'
                            : 'text-gray-900 hover:text-[#08703F]' }}"
            >
                Donasi

                @if (($activeNav ?? '') === 'donasi')
                    <span
                        class="absolute
                               left-0 right-0
                               -bottom-1
                               h-[4px]
                               bg-[#08703F]
                               rounded-full"
                    ></span>
                @endif
            </a>


            <a
                href="{{ route('organisasi.kunjungan') }}"
                class="relative py-4 transition
                       {{ ($activeNav ?? '') === 'kunjungan'
                            ? 'text-[#08703F]'
                            : 'text-gray-900 hover:text-[#08703F]' }}"
            >
                Kunjungan

                @if (($activeNav ?? '') === 'kunjungan')
                    <span
                        class="absolute
                               left-0 right-0
                               -bottom-1
                               h-[4px]
                               bg-[#08703F]
                               rounded-full"
                    ></span>
                @endif
            </a>


            <a
                href="{{ route('organisasi.laporan') }}"
                class="relative py-4 transition
                       {{ ($activeNav ?? '') === 'laporan'
                            ? 'text-[#08703F]'
                            : 'text-gray-900 hover:text-[#08703F]' }}"
            >
                Laporan

                @if (($activeNav ?? '') === 'laporan')
                    <span
                        class="absolute
                               left-0 right-0
                               -bottom-1
                               h-[4px]
                               bg-[#08703F]
                               rounded-full"
                    ></span>
                @endif
            </a>

        </nav>



        {{-- =====================================================
            RIGHT SIDE
        ===================================================== --}}
        <div class="flex items-center gap-7">

            {{-- =================================================
                NOTIFICATION - BISA DIKLIK
            ================================================= --}}
            <a
                href="{{ route('organisasi.notifikasi') }}"
                title="Notifikasi"
                class="relative
                       w-[58px]
                       h-[58px]
                       rounded-full
                       flex items-center
                       justify-center
                       text-gray-700
                       hover:text-[#08703F]
                       hover:bg-[#EAF7F1]
                       transition"
            >
                <i class="fa-regular fa-bell text-[31px]"></i>

                {{-- DOT NOTIFIKASI --}}
                <span
                    class="absolute
                           top-[9px]
                           right-[10px]
                           w-[10px]
                           h-[10px]
                           rounded-full
                           bg-red-500
                           border-2
                           border-white"
                ></span>
            </a>



            {{-- =================================================
                PROFILE
            ================================================= --}}
            <a
                href="{{ route('organisasi.profil') }}"
                title="Profil Panti"
                class="w-[68px]
                       h-[68px]
                       rounded-full
                       overflow-hidden
                       border-[3px]
                       transition
                       {{ ($activeNav ?? '') === 'profil'
                            ? 'border-[#08703F] ring-4 ring-[#08703F]/10'
                            : 'border-gray-200 hover:border-[#08703F]' }}"
            >

                <img
                    src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=300&auto=format&fit=crop"
                    alt="Profile"
                    class="w-full h-full object-cover"
                >

            </a>



            {{-- =================================================
                MOBILE MENU
            ================================================= --}}
            <details class="relative lg:hidden">

                <summary
                    class="list-none
                           cursor-pointer
                           text-[#08703F]
                           text-[30px]"
                >
                    <i class="fa-solid fa-bars"></i>
                </summary>


                <div
                    class="absolute
                           right-0
                           top-[55px]
                           w-[290px]
                           bg-white
                           rounded-[18px]
                           shadow-xl
                           border border-gray-100
                           p-5"
                >

                    <div class="flex flex-col text-[20px] font-semibold">

                        <a
                            href="{{ route('organisasi.dashboard') }}"
                            class="px-5 py-4 rounded-xl
                                   {{ ($activeNav ?? '') === 'dashboard'
                                        ? 'bg-[#EAF7F1] text-[#08703F]'
                                        : 'text-gray-800 hover:bg-gray-50' }}"
                        >
                            Dashboard
                        </a>


                        <a
                            href="{{ route('organisasi.kampanye') }}"
                            class="px-5 py-4 rounded-xl
                                   {{ ($activeNav ?? '') === 'kampanye'
                                        ? 'bg-[#EAF7F1] text-[#08703F]'
                                        : 'text-gray-800 hover:bg-gray-50' }}"
                        >
                            Kampanye
                        </a>


                        <a
                            href="{{ route('organisasi.donasi') }}"
                            class="px-5 py-4 rounded-xl
                                   {{ ($activeNav ?? '') === 'donasi'
                                        ? 'bg-[#EAF7F1] text-[#08703F]'
                                        : 'text-gray-800 hover:bg-gray-50' }}"
                        >
                            Donasi
                        </a>


                        <a
                            href="{{ route('organisasi.kunjungan') }}"
                            class="px-5 py-4 rounded-xl
                                   {{ ($activeNav ?? '') === 'kunjungan'
                                        ? 'bg-[#EAF7F1] text-[#08703F]'
                                        : 'text-gray-800 hover:bg-gray-50' }}"
                        >
                            Kunjungan
                        </a>


                        <a
                            href="{{ route('organisasi.laporan') }}"
                            class="px-5 py-4 rounded-xl
                                   {{ ($activeNav ?? '') === 'laporan'
                                        ? 'bg-[#EAF7F1] text-[#08703F]'
                                        : 'text-gray-800 hover:bg-gray-50' }}"
                        >
                            Laporan
                        </a>


                        <div class="h-px bg-gray-200 my-3"></div>


                        <a
                            href="{{ route('organisasi.notifikasi') }}"
                            class="px-5 py-4 rounded-xl
                                   text-gray-800
                                   hover:bg-gray-50"
                        >
                            <i class="fa-regular fa-bell mr-3"></i>
                            Notifikasi
                        </a>


                        <a
                            href="{{ route('organisasi.profil') }}"
                            class="px-5 py-4 rounded-xl
                                   {{ ($activeNav ?? '') === 'profil'
                                        ? 'bg-[#EAF7F1] text-[#08703F]'
                                        : 'text-gray-800 hover:bg-gray-50' }}"
                        >
                            <i class="fa-regular fa-user mr-3"></i>
                            Profil Panti
                        </a>

                    </div>

                </div>

            </details>

        </div>

    </div>

</header>