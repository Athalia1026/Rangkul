<nav class="bg-white border-b border-gray-200 px-8 py-3 flex items-center justify-between">

    <!-- LOGO -->
    <div class="flex items-center w-1/3">
        <img
            src="/images/logo.png"
            alt="Rangkul"
            class="h-10 w-auto"
        >
    </div>

    <!-- MENU -->
    <div class="flex gap-12 font-semibold text-sm">

        <!-- BERANDA -->
        <a
            href="/manager/home"
            class="{{ request()->is('manager/home')
                ? 'text-rangkul-green border-b-2 border-rangkul-green pb-1'
                : 'hover:text-green-700' }}"
        >
            Beranda
        </a>

        <!-- DAFTAR PENGGUNA -->
        <a
            href="/manager/daftaruser"
            class="{{ request()->is('manager/daftaruser')
                ? 'text-rangkul-green border-b-2 border-rangkul-green pb-1'
                : 'hover:text-green-700' }}"
        >
            Daftar Pengguna
        </a>

        <!-- LAPORAN TRANSAKSI -->
        <a
            href="/manager/detailtransaksi"
            class="{{ request()->is('manager/detailtransaksi')
                ? 'text-rangkul-green border-b-2 border-rangkul-green pb-1'
                : 'hover:text-green-700' }}"
        >
            Laporan Transaksi
        </a>

    </div>

    <!-- PROFILE -->
    <div class="flex items-center justify-end w-1/3 relative">

        <button
            type="button"
            id="profileButton"
            class="text-rangkul-green text-3xl cursor-pointer focus:outline-none"
        >
            <i class="fa-solid fa-circle-user"></i>
        </button>

        <!-- DROPDOWN -->
        <div
            id="profileDropdown"
            class="hidden absolute right-0 top-12 w-36 bg-white rounded-lg shadow-lg border border-gray-100 py-2 z-50"
        >
            <a
                href="/login"
                class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
            >
                <i class="fa-solid fa-right-from-bracket text-rangkul-green"></i>
                <span>Keluar</span>
            </a>
        </div>

    </div>

</nav>