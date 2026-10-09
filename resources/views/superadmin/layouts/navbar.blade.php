@include('layouts.admin-navbar', [
    'area' => 'superadmin',
    'homeRoute' => 'superadmin.home',
    'navLabel' => 'Navigasi super admin',
    'navItems' => [
        ['label' => 'Beranda', 'route' => 'superadmin.home', 'active' => ['superadmin.home']],
        ['label' => 'Daftar Pengguna', 'route' => 'superadmin.daftarpengguna', 'active' => ['superadmin.daftarpengguna', 'superadmin.detailinformasi']],
        ['label' => 'Laporan Transaksi', 'route' => 'superadmin.laporantransaksi', 'active' => ['superadmin.laporantransaksi']],
    ],
])
