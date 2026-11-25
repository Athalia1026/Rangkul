@include('layouts.admin-navbar', [
    'area' => 'manager',
    'homeRoute' => 'manager.home',
    'navLabel' => 'Navigasi manajer',
    'navItems' => [
        ['label' => 'Beranda', 'route' => 'manager.home', 'active' => ['manager.home', 'manager.detailpengajuan']],
        ['label' => 'Daftar Pengguna', 'route' => 'manager.daftaruser', 'active' => ['manager.daftaruser', 'manager.detailuser']],
        ['label' => 'Laporan Transaksi', 'route' => 'manager.laporantransaksi', 'active' => ['manager.laporantransaksi']],
    ],
])
