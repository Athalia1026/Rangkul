@php
    $navItems = [
        'dashboard' => ['label' => 'Dashboard', 'route' => 'organisasi.dashboard'],
        'kampanye'  => ['label' => 'Kampanye',  'route' => 'organisasi.kampanye'],
        'donasi'    => ['label' => 'Donasi',    'route' => 'organisasi.donasi'],
        'kunjungan' => ['label' => 'Kunjungan', 'route' => 'organisasi.kunjungan'],
        'laporan'   => ['label' => 'Laporan',   'route' => 'organisasi.laporan'],
    ];

    $active = $activeNav ?? '';
    $orgName = auth()->user()?->organization?->nama_lembaga ?? auth()->user()?->nama ?? 'Organisasi';
    // Foto profil dari users.profile_photo (path storage atau URL penuh); jika kosong tampilkan inisial nama user.
    $profilePhotoUrl = auth()->user()?->profilePhotoUrl();
    $userInitials = collect(preg_split('/\s+/', trim(auth()->user()?->nama ?? $orgName)))
        ->filter()
        ->take(2)
        ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
        ->implode('');

    $hasUnreadNotification = auth()->check()
        && \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->exists();
@endphp

<header class="w-full bg-white border-b border-gray-100 sticky top-0 z-30 shadow-2xs">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">

        {{-- LOGO --}}
        <a href="{{ route('organisasi.dashboard') }}" class="flex items-center gap-2.5">
            <div class="flex flex-col leading-tight">
                <img src="{{ asset('images/logo.png') }}" alt="Rangkul Logo" class="h-11 w-auto">
            </div>
        </a>

        {{-- DESKTOP NAVIGATION --}}
        <nav class="hidden md:flex items-center gap-8 text-[15px] font-medium text-gray-700">
            @foreach ($navItems as $key => $item)
                <a href="{{ route($item['route']) }}"
                    class="{{ $active === $key ? 'text-[#05522d] font-semibold' : 'text-gray-700 hover:text-[#05522d]' }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>

        {{-- RIGHT SIDE --}}
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-6">

                {{-- Notifikasi --}}
                <a href="{{ route('organisasi.notifikasi') }}"
                    class="relative p-1 transition-colors {{ $active === 'notifikasi' ? 'text-[#05522d]' : 'text-gray-500 hover:text-[#05522d]' }}"
                    aria-label="Notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="w-[26px] h-[26px]">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    @if ($hasUnreadNotification)
                        <span class="absolute top-1 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    @endif
                </a>

                {{-- Profil --}}
                <a href="{{ route('organisasi.profil') }}"
                    class="relative flex items-center gap-3 border-l border-gray-200 pl-6">
                    @if ($profilePhotoUrl)
                        <img class="h-10 w-10 rounded-full object-cover border-2 transition-all shadow-sm {{ $active === 'profil' ? 'border-[#05522d]' : 'border-transparent hover:border-[#05522d]' }}"
                            src="{{ $profilePhotoUrl }}"
                            alt="Profil {{ $orgName }}">
                    @else
                        <span class="h-10 w-10 rounded-full flex items-center justify-center bg-[#d8f0e2] text-[#05522d] text-[14px] font-bold border-2 transition-all shadow-sm {{ $active === 'profil' ? 'border-[#05522d]' : 'border-transparent hover:border-[#05522d]' }}"
                            title="{{ $orgName }}" aria-label="Profil {{ $orgName }}">{{ $userInitials ?: 'O' }}</span>
                    @endif
                </a>

                {{-- Keluar --}}
                <form method="POST" action="{{ route('logout') }}"
                    onsubmit="localStorage.removeItem('auth_token'); localStorage.removeItem('auth_user');">
                    @csrf
                    <button type="submit" class="p-1 text-gray-500 hover:text-red-600 transition-colors"
                        aria-label="Keluar" title="Keluar">
                        <i class="fa-solid fa-right-from-bracket text-[20px]"></i>
                    </button>
                </form>

                {{-- MOBILE MENU --}}
                <details class="relative md:hidden">
                    <summary class="list-none cursor-pointer p-1 text-gray-700 hover:text-[#05522d]" aria-label="Menu">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                            stroke="currentColor" class="w-[26px] h-[26px]">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </summary>

                    <div class="absolute right-0 top-12 w-56 bg-white rounded-xl shadow-lg border border-gray-100 p-2 text-[15px] font-medium">
                        @foreach ($navItems as $key => $item)
                            <a href="{{ route($item['route']) }}"
                                class="block px-4 py-2.5 rounded-lg {{ $active === $key ? 'bg-[#d8f0e2] text-[#05522d] font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </details>

            </div>
        </div>
    </div>
</header>
