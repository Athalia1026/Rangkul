<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 24px 28px 28px; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #f4f7f5; color: #19382d; font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        .page { border: 1px solid #087443; background: #fff; padding: 24px 26px 26px; }
        .brand { width: 100%; border-bottom: 2px solid #087443; padding-bottom: 14px; }
        .brand-mark { display: inline-block; width: 28px; height: 28px; border-radius: 50%; background: #087443; color: #fff; text-align: center; line-height: 28px; font-size: 15px; font-weight: bold; }
        .brand-name { display: inline-block; vertical-align: top; margin: 1px 0 0 7px; color: #087443; font-size: 12px; font-weight: bold; }
        .brand-subtitle { display: block; color: #6b7d74; font-size: 6px; font-weight: normal; }
        .report-head { width: 100%; padding: 16px 0 13px; }
        .report-title { color: #087443; font-size: 20px; font-weight: bold; margin: 0 0 5px; }
        .period { color: #697970; font-size: 9px; }
        .company { text-align: right; color: #1b3028; font-size: 9px; font-weight: bold; }
        .company-name { color: #087443; font-size: 12px; margin-bottom: 5px; }
        .section-title { color: #087443; font-size: 12px; font-weight: bold; margin: 0 0 10px; }
        .section-title .icon { display: inline-block; border: 2px solid #087443; width: 12px; height: 13px; margin-right: 7px; vertical-align: -2px; }
        .summary { width: 100%; border-collapse: separate; border-spacing: 0; background: #f0f5f2; border-radius: 8px; margin: 0 0 18px; }
        .summary td { width: 25%; height: 70px; padding: 11px 10px; border-right: 1px solid #b9c9c0; vertical-align: top; }
        .summary td:last-child { border-right: 0; }
        .metric-label { color: #52675d; font-size: 8px; margin: 7px 0 4px; }
        .metric-value { color: #172c23; font-size: 12px; font-weight: bold; }
        .metric-note { color: #087443; font-size: 7px; margin-top: 3px; }
        .metric-icon { color: #087443; font-size: 13px; font-weight: bold; }
        .panel { background: #f4f7f5; border-radius: 8px; padding: 14px 14px 9px; margin-bottom: 18px; }
        .data { width: 100%; border-collapse: collapse; }
        .data th { color: #53665d; font-size: 7px; font-weight: normal; text-align: left; border-top: 1px solid #ccd8d1; border-bottom: 1px solid #ccd8d1; padding: 7px 3px; }
        .data td { color: #1b2e26; border-bottom: 1px solid #ccd8d1; padding: 8px 3px; vertical-align: middle; }
        .data th:nth-child(1), .data td:nth-child(1) { width: 46%; }
        .data th:nth-child(2), .data td:nth-child(2) { width: 17%; }
        .data th:nth-child(3), .data td:nth-child(3) { width: 19%; }
        .data th:nth-child(4), .data td:nth-child(4) { width: 18%; }
        .campaign { font-weight: bold; font-size: 8px; }
        .recipient { color: #697970; font-size: 7px; margin-top: 2px; }
        .amount { font-weight: bold; font-size: 8px; }
        .status { display: inline-block; border-radius: 9px; padding: 4px 6px; background: #dff2e9; color: #087443; font-size: 7px; white-space: nowrap; }
        .gallery { width: 100%; border-collapse: separate; border-spacing: 5px 0; margin: -5px; }
        .gallery td { width: 25%; vertical-align: top; padding: 0 5px; }
        .photo { height: 72px; background: #dce9e1; border-radius: 7px; overflow: hidden; text-align: center; color: #087443; }
        .photo img { width: 100%; height: 72px; object-fit: cover; }
        .photo-empty { padding-top: 27px; font-size: 8px; }
        .caption { color: #1d3228; font-size: 7px; font-weight: bold; line-height: 1.25; margin-top: 5px; }
        .caption-sub { color: #6b7d74; font-size: 6px; font-style: italic; margin-top: 2px; }
        .conclusion { background: #f0f5f2; border-radius: 8px; padding: 13px 14px; margin-top: 18px; }
        .conclusion h3 { color: #087443; font-size: 11px; margin: 0 0 8px; }
        .conclusion p { color: #53665d; font-size: 8px; line-height: 1.5; margin: 0; }
        .accent { color: #087443; font-weight: bold; }
    </style>
</head>
<body>
    <div class="page">
        <table class="brand"><tr><td>
            <span class="brand-mark">R</span><span class="brand-name">Rangkul.com<span class="brand-subtitle">Donation Platform</span></span>
        </td></tr></table>

        <table class="report-head">
            <tr>
                <td><h1 class="report-title">Laporan Dampak CSR</h1><div class="period">Periode: {{ optional($subscription?->started_at)->translatedFormat('F Y') ?? now()->translatedFormat('F Y') }} - {{ optional($subscription?->expired_at)->translatedFormat('F Y') ?? now()->translatedFormat('F Y') }}</div></td>
                <td class="company"><div class="company-name">{{ $company?->donor?->user?->nama ?? 'Perusahaan Premium' }}</div>{{ $company?->email_korporat ?? '-' }}</td>
            </tr>
        </table>

        <h2 class="section-title"><span class="icon"></span>Ringkasan Donasi</h2>
        <table class="summary"><tr>
            <td><div class="metric-icon">▣</div><div class="metric-label">Total Donasi</div><div class="metric-value">Rp {{ number_format($total, 0, ',', '.') }}</div><div class="metric-note">Donasi terverifikasi</div></td>
            <td><div class="metric-icon">↗</div><div class="metric-label">Donasi Tersalurkan</div><div class="metric-value">Rp {{ number_format($total, 0, ',', '.') }}</div><div class="metric-note">{{ $donations->count() }} penyaluran</div></td>
            <td><div class="metric-icon">♡</div><div class="metric-label">Penggalangan Dana</div><div class="metric-value">{{ $campaignCount }}</div><div class="metric-note">Kampanye didukung</div></td>
            <td><div class="metric-icon">♟</div><div class="metric-label">Total Penerima Manfaat</div><div class="metric-value">-</div><div class="metric-note">Data belum tersedia</div></td>
        </tr></table>

        <div class="panel">
            <h2 class="section-title">Detail Penyaluran Donasi</h2>
            <table class="data">
                <thead><tr><th>Campaign / Panti Asuhan</th><th>Tanggal</th><th>Nominal</th><th>Status</th></tr></thead>
                <tbody>
                @forelse ($donations as $donation)
                    <tr>
                        <td><div class="campaign">{{ $donation->campaign?->judul ?? '-' }}</div><div class="recipient">{{ $donation->campaign?->deskripsi ? \Illuminate\Support\Str::limit(strip_tags($donation->campaign->deskripsi), 36) : 'Program sosial Rangkul' }}</div></td>
                        <td>{{ optional($donation->paid_at)->format('d M Y') }}</td>
                        <td class="amount">Rp {{ number_format($donation->nominal, 0, ',', '.') }}</td>
                        <td><span class="status">✓ Sudah Dibayar</span></td>
                    </tr>
                @empty
                    <tr><td colspan="4">Belum ada donasi yang berhasil.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <h2 class="section-title"><span class="icon"></span>Dokumentasi &amp; Bukti</h2>
        <table class="gallery"><tr>
            @foreach ($donations->take(4) as $donation)
                <td><div class="photo">
                    @if ($donation->campaign?->foto_cover && file_exists(public_path('storage/' . $donation->campaign->foto_cover)))
                        <img src="{{ public_path('storage/' . $donation->campaign->foto_cover) }}" alt="">
                    @else
                        <div class="photo-empty">Dokumentasi<br>kampanye</div>
                    @endif
                </div><div class="caption">{{ \Illuminate\Support\Str::limit($donation->campaign?->judul ?? 'Program sosial', 32) }}</div><div class="caption-sub">Rangkul Impact</div></td>
            @endforeach
            @for ($index = $donations->take(4)->count(); $index < 4; $index++)
                <td><div class="photo"><div class="photo-empty">Dokumentasi<br>kampanye</div></div><div class="caption">Belum tersedia</div><div class="caption-sub">Rangkul Impact</div></td>
            @endfor
        </tr></table>

        <div class="conclusion">
            <h3>Kesimpulan</h3>
            <p>Selama periode pelaporan, perusahaan telah memberikan kontribusi sosial melalui dukungan terhadap <span class="accent">{{ $campaignCount }} kampanye</span> di Rangkul. Total donasi yang tercatat sebesar <span class="accent">Rp {{ number_format($total, 0, ',', '.') }}</span> melalui <span class="accent">{{ $donations->count() }} penyaluran</span>. Kontribusi ini menjadi bagian dari upaya perusahaan dalam menciptakan dampak sosial yang berkelanjutan.</p>
        </div>
    </div>
</body>
