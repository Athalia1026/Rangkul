@include('layouts.admin-navbar', [
    'area' => 'staff',
    'homeRoute' => 'staff.beranda',
    'navLabel' => 'Navigasi staf',
    'navItems' => [
        ['label' => 'Beranda', 'route' => 'staff.beranda', 'active' => ['staff.beranda', 'staff.pengajuandanauser', 'staff.ajuanpencairandana']],
    ],
])
