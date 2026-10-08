<nav class="relative z-50 bg-white border-b border-gray-200 px-8 py-3 flex items-center justify-between">

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
            href="/staff/beranda"
            class="{{ request()->is('staff/beranda')
                ? 'text-rangkul-green border-b-2 border-rangkul-green/20 pb-1'
                : 'hover:text-green-700' }}"
        >
            Beranda
        </a>
    </div>

    <!-- PROFILE -->
    <div class="flex items-center justify-end w-1/3">

        <div class="relative">

            <button
                type="button"
                data-rangkul-profile-button
                class="text-rangkul-green text-3xl cursor-pointer focus:outline-none"
            >
                <i class="fa-solid fa-circle-user"></i>
            </button>

            <!-- DROPDOWN -->
            <div
                data-rangkul-profile-dropdown
                class="hidden absolute right-0 top-full mt-2 w-36 bg-white rounded-lg shadow-lg border border-gray-100 py-2 z-[9999]"
            >
                <a
                    href="/superadmin/logout"
                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                >
                    <i class="fa-solid fa-right-from-bracket text-rangkul-green"></i>
                    <span>Keluar</span>
                </a>
            </div>

        </div>

    </div>

</nav>

<script>
(function () {

    document.addEventListener('click', function (event) {

        const button = event.target.closest('[data-rangkul-profile-button]');
        const dropdown = document.querySelector('[data-rangkul-profile-dropdown]');

        if (!dropdown) {
            return;
        }

        // Kalau tombol profile diklik
        if (button) {
            event.stopPropagation();
            dropdown.classList.toggle('hidden');
            return;
        }

        // Kalau klik di luar profile
        if (!event.target.closest('[data-rangkul-profile-dropdown]')) {
            dropdown.classList.add('hidden');
        }

    });

})();
</script>