@php
    $fontDir = str_replace('\\', '/', resource_path('fonts/plus-jakarta-sans'));
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $date = fn ($value) => $value ? \Carbon\Carbon::parse($value)->locale('id')->translatedFormat('j M Y') : '-';
    $logoPath = public_path('images/logo.png');
    $logo = is_file($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
    $stats = $report['stats'];
    $growth = $stats['pertumbuhan_persen'];
    // Ikon SVG sebagai data URI: font PDF tidak memiliki simbol ikon.
    $icon = fn (string $color, string $body) => 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="' . $color . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $body . '</svg>');
    $icons = [
        'green' => $icon('#0b5d36', '<rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="3"/>'),
        'yellow' => $icon('#a37a17', '<path d="M3 14h3l4 3h6a2 2 0 0 0 0-4h-4M3 20h3l3 1h7l5-4"/><circle cx="16" cy="6" r="3"/>'),
        'red' => $icon('#b42318', '<path d="M12 21s-8-5.1-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 5.9-8 11-8 11Z"/>'),
        'blue' => $icon('#1d4f73', '<circle cx="12" cy="7.5" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>'),
        'document' => $icon('#0b5d36', '<path d="M6 2h8l5 5v15H6Z"/><path d="M14 2v5h5"/>'),
        'gallery' => $icon('#0b5d36', '<rect x="5" y="5" width="16" height="15" rx="2"/><path d="M3 17V4a1 1 0 0 1 1-1h13"/><path d="m8 17 4-4 3 3 2-2 3 3"/>'),
    ];
    // Ikon badge status berisi warna, jadi dibuat tanpa stroke bawaan.
    $badgeIcon = fn (string $color, string $body) => 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">' . str_replace('currentColor', $color, $body) . '</svg>');
    $statusIcons = [
        'sudah_bayar' => $badgeIcon('#10314a', '<circle cx="12" cy="12" r="10" fill="currentColor"/><path d="m8 12 3 3 5-6" stroke="#fff" stroke-width="2.2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>'),
        'sudah_disalurkan' => $badgeIcon('#0b5d36', '<rect x="3" y="4" width="18" height="17" rx="3" fill="currentColor"/><path d="M8 10h8M8 14h8M12 7v11" stroke="#fff" stroke-width="2" stroke-linecap="round"/>'),
    ];
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @font-face { font-family: 'Plus Jakarta Sans'; font-weight: normal; font-style: normal; src: url('{{ $fontDir }}/PlusJakartaSans-Regular.ttf') format('truetype'); }
        @font-face { font-family: 'Plus Jakarta Sans'; font-weight: bold; font-style: normal; src: url('{{ $fontDir }}/PlusJakartaSans-Bold.ttf') format('truetype'); }
        @font-face { font-family: 'Plus Jakarta Sans'; font-weight: normal; font-style: italic; src: url('{{ $fontDir }}/PlusJakartaSans-Italic.ttf') format('truetype'); }
        @page { margin: 26px 30px 30px; }
        * { box-sizing: border-box; }
        /* DomPDF membaca spasi Plus Jakarta Sans terlalu sempit; tambah jarak antar kata. */
        body { margin: 0; color: #171b19; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 11px; word-spacing: .18em; }
        table { width: 100%; border-collapse: collapse; }
        td, th { vertical-align: top; }
        .logo { height: 30px; }
        .head td { vertical-align: bottom; }
        h1 { margin: 10px 0 3px; font-size: 24px; color: #0b5d36; }
        .period { color: #52665a; font-size: 12px; }
        .company { text-align: right; font-size: 12px; font-weight: bold; }
        .company img { width: 34px; height: 34px; border-radius: 17px; margin-bottom: 4px; }
        .rule { height: 2px; background: #0b5d36; margin: 12px 0 16px; }
        .section-title { margin: 0 0 10px; font-size: 14.5px; color: #0b5d36; }
        .marker { width: 13px; height: 13px; margin-right: 5px; vertical-align: -2px; }
        .panel { background: #f2f5f3; border-radius: 8px; padding: 12px 14px; margin-bottom: 16px; }
        .stats td { width: 25%; padding: 2px 10px; border-left: 1px solid #b9c4be; }
        .stats td:first-child { border-left: 0; padding-left: 2px; }
        .icon { display: block; box-sizing: content-box; width: 14px; height: 14px; padding: 5px; border-radius: 5px; }
        .icon img { display: block; width: 14px; height: 14px; }
        .icon.green { background: #d9efe3; color: #0b5d36; } .icon.yellow { background: #fcf3d8; color: #a37a17; }
        .icon.red { background: #fde3e1; color: #b42318; } .icon.blue { background: #e1f0fb; color: #1d4f73; }
        .growth { float: right; background: #d9efe3; color: #0b5d36; border-radius: 8px; padding: 2px 6px; font-size: 8.5px; font-weight: bold; }
        .growth.down { background: #fde3e1; color: #b42318; }
        .stat-label { margin-top: 10px; color: #3f4a44; font-size: 9px; }
        .stat-value { margin-top: 4px; font-size: 13px; font-weight: bold; line-height: 1.35; }
        .detail th { padding: 7px 4px; border-top: 1px solid #c9cfcc; border-bottom: 1px solid #c9cfcc; text-align: left; font-size: 9px; font-weight: normal; color: #3f4a44; }
        .detail td { padding: 8px 4px; border-bottom: 1px solid #d5dbd8; vertical-align: middle; font-size: 11px; }
        .detail tr:last-child td { border-bottom: 0; }
        .campaign { font-weight: bold; font-size: 11px; }
        .org { margin-top: 2px; color: #3f4a44; font-size: 9px; }
        .amount { font-weight: bold; white-space: nowrap; }
        .badge { display: inline-block; border-radius: 8px; padding: 3px 7px; font-size: 8.5px; white-space: nowrap; }
        .badge img { width: 8px; height: 8px; margin-right: 3px; vertical-align: -1px; }
        .badge.sudah_bayar { background: #e8f4fc; color: #10314a; }
        .badge.sudah_disalurkan { background: #d9efe3; color: #0b5d36; }
        .gallery td { width: 25%; padding: 0 5px; }
        .gallery td:first-child { padding-left: 0; } .gallery td:last-child { padding-right: 0; }
        .photo { width: 100%; height: 74px; border-radius: 6px; overflow: hidden; background: #dce9e1; }
        .photo img { width: 100%; height: 74px; }
        .caption { margin-top: 5px; font-size: 9px; font-weight: bold; }
        .caption-sub { margin-top: 1px; font-size: 8px; font-style: italic; color: #3f4a44; }
        .empty { color: #6b7280; font-size: 9.5px; padding: 6px 0; }
        .conclusion p { margin: 0; font-size: 11px; line-height: 1.6; }
        .accent { color: #0b5d36; font-weight: bold; }
    </style>
</head>
<body>
    @if($logo)<img class="logo" src="{{ $logo }}" alt="Rangkul.com">@endif
    <table class="head">
        <tr>
            <td>
                <h1>Laporan Dampak CSR</h1>
                <div class="period">Periode: {{ $report['period']['label'] }}</div>
            </td>
            <td class="company">
                @if($report['company']['logo'])<img src="{{ $report['company']['logo'] }}" alt=""><br>@endif
                {{ $report['company']['nama'] }}
            </td>
        </tr>
    </table>
    <div class="rule"></div>

    <h2 class="section-title"><img class="marker" src="{{ $icons['document'] }}" alt="">Ringkasan Donasi</h2>
    <div class="panel">
        <table class="stats">
            <tr>
                <td>
                    @if($growth !== null)<span class="growth {{ $growth < 0 ? 'down' : '' }}">{{ $growth >= 0 ? '+' : '' }}{{ str_replace('.', ',', $growth) }}%</span>@endif
                    <div class="icon green"><img src="{{ $icons['green'] }}" alt=""></div>
                    <div class="stat-label">Total Donasi</div>
                    <div class="stat-value">{{ $money($stats['total_donasi']) }}</div>
                </td>
                <td>
                    <div class="icon yellow"><img src="{{ $icons['yellow'] }}" alt=""></div>
                    <div class="stat-label">Donasi Tersalurkan</div>
                    <div class="stat-value">{{ $money($stats['total_tersalurkan']) }}</div>
                </td>
                <td>
                    <div class="icon red"><img src="{{ $icons['red'] }}" alt=""></div>
                    <div class="stat-label">Penggalangan Dana</div>
                    <div class="stat-value">{{ $stats['kampanye_didukung'] }} Didukung<br>{{ $stats['kampanye_tersalurkan'] }} Tersalurkan</div>
                </td>
                <td>
                    <div class="icon blue"><img src="{{ $icons['blue'] }}" alt=""></div>
                    <div class="stat-label">Total Penerima Manfaat</div>
                    <div class="stat-value">{{ number_format($stats['penerima_manfaat'], 0, ',', '.') }}<br>Anak</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="panel">
        <h2 class="section-title">Detail Penyaluran Donasi</h2>
        <table class="detail">
            <thead>
                <tr><th style="width: 44%;">Campaign / Panti Asuhan</th><th style="width: 16%;">Tanggal</th><th style="width: 20%;">Nominal</th><th style="width: 20%; text-align: center;">Status</th></tr>
            </thead>
            <tbody>
                @forelse($report['donasi'] as $item)
                    <tr>
                        <td><div class="campaign">{{ $item['kampanye'] }}</div><div class="org">{{ $item['organisasi'] }}</div></td>
                        <td>{{ $date($item['tanggal']) }}</td>
                        <td class="amount">{{ $money($item['nominal']) }}</td>
                        <td style="text-align: center;"><span class="badge {{ $item['status'] }}"><img src="{{ $statusIcons[$item['status']] }}" alt="">{{ $item['status'] === 'sudah_disalurkan' ? 'Sudah Disalurkan' : 'Sudah Dibayar' }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty">Belum ada donasi yang dibayar pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h2 class="section-title"><img class="marker" src="{{ $icons['gallery'] }}" alt="">Dokumentasi &amp; Bukti</h2>
    @if(count($report['dokumentasi']))
        <table class="gallery">
            <tr>
                @foreach($report['dokumentasi'] as $item)
                    <td>
                        <div class="photo"><img src="{{ $item['url'] }}" alt=""></div>
                        <div class="caption">{{ \Illuminate\Support\Str::limit($item['judul'], 40) }}</div>
                        <div class="caption-sub">{{ $item['organisasi'] }}</div>
                    </td>
                @endforeach
                @for($i = count($report['dokumentasi']); $i < 4; $i++)<td></td>@endfor
            </tr>
        </table>
    @else
        <p class="empty">Belum ada dokumentasi penyaluran yang disetujui pada periode ini.</p>
    @endif

    <div class="panel conclusion" style="margin-top: 16px;">
        <h2 class="section-title">Kesimpulan</h2>
                <p>Selama periode pelaporan, perusahaan telah memberikan kontribusi sosial melalui dukungan terhadap berbagai campaign di Rangkul. Dari kontribusi tersebut, sebanyak <span class="accent">{{ $money($stats['total_donasi']) }} telah diberikan</span> dengan <span class="accent">{{ $money($stats['total_tersalurkan']) }} telah tersalurkan</span> melalui <span class="accent">{{ $stats['kampanye_didukung'] }} campaign yang didukung</span>, dengan <span class="accent">{{ $stats['kampanye_tersalurkan'] }} campaign telah tersalurkan</span> dan memberikan manfaat kepada <span class="accent">{{ number_format($stats['penerima_manfaat'], 0, ',', '.') }} penerima manfaat</span>. Kontribusi ini menjadi bagian dari upaya perusahaan dalam menciptakan dampak sosial yang berkelanjutan.</p>
    </div>
</body>
</html>
