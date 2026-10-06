@php
    $fontDir = str_replace('\\', '/', resource_path('fonts/plus-jakarta-sans'));
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $format = fn ($value) => $value ? \Carbon\Carbon::parse($value)->locale('id')->translatedFormat('d F Y, H:i') . ' WIB' : '-';
    $date = fn ($value) => $value ? \Carbon\Carbon::parse($value)->locale('id')->translatedFormat('d F Y') : '-';
    $logoPath = public_path('images/logo.png');
    $logo = is_file($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
    $taxPercent = rtrim(rtrim(number_format($pricing['tax_rate'] * 100, 2, ',', ''), '0'), ',');
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @font-face { font-family: 'Plus Jakarta Sans'; font-weight: normal; font-style: normal; src: url('{{ $fontDir }}/PlusJakartaSans-Regular.ttf') format('truetype'); }
        @font-face { font-family: 'Plus Jakarta Sans'; font-weight: bold; font-style: normal; src: url('{{ $fontDir }}/PlusJakartaSans-Bold.ttf') format('truetype'); }
        @page { margin: 0; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #1b2e26; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 9px; word-spacing: .18em; }
        .page { padding: 26px 28px 24px; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
        .header td { vertical-align: middle; }
        .logo { height: 34px; }
        .issued { color: #6b7d74; font-size: 7px; margin-top: 4px; }
        .badge-paid { display: inline-block; border: 1.5px solid #087443; border-radius: 12px; background: #e3f4ea; color: #087443; padding: 4px 10px; font-size: 8px; font-weight: bold; }
        .method { color: #6b7d74; font-size: 7px; margin-top: 4px; }
        .divider { height: 3px; background: #087443; margin: 14px 0 16px; border-radius: 2px; }
        .hero { background: #f2f5fa; border-left: 4px solid #087443; border-radius: 0 8px 8px 0; padding: 14px; }
        .hero td { vertical-align: middle; }
        .hero h1 { margin: 0; font-size: 17px; color: #10251c; }
        .hero-sub { color: #6b7d74; font-size: 8px; margin-top: 3px; }
        .trx-label { color: #6b7d74; font-size: 6.5px; text-align: right; margin-bottom: 3px; }
        .trx { background: #fff; border: 1px solid #d5dde8; border-radius: 5px; padding: 5px 7px; font-size: 7px; color: #087443; font-weight: bold; text-align: right; }
        .section-title { font-size: 11px; font-weight: bold; color: #10251c; margin: 18px 0 8px; }
        .marker { display: inline-block; width: 8px; height: 8px; background: #087443; border-radius: 2px; margin-right: 6px; }
        .info { background: #f2f5fa; border-radius: 8px; padding: 12px 14px 4px; }
        .info td { width: 50%; padding-bottom: 10px; }
        .label { color: #6b7d74; font-size: 6.5px; margin-bottom: 3px; }
        .value { font-size: 9px; font-weight: bold; color: #10251c; }
        .summary { background: #f2f5fa; border-radius: 8px; padding: 4px 14px; }
        .summary td { padding: 9px 0; border-bottom: 1px solid #dde3ec; font-size: 9px; color: #3e5148; }
        .summary tr:last-child td { border-bottom: 0; }
        .summary td.amount { text-align: right; font-weight: bold; color: #10251c; }
        .total { border-top: 2px solid #d5dde8; margin-top: 10px; padding: 10px 14px 0; }
        .total td { vertical-align: middle; }
        .total-label { font-size: 7px; font-weight: bold; color: #10251c; }
        .total-sub { font-size: 6.5px; color: #6b7d74; margin-top: 2px; }
        .total-amount { text-align: right; font-size: 18px; font-weight: bold; color: #087443; }
        .footer { margin-top: 18px; color: #8a9891; font-size: 6.5px; text-align: center; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="page">
        <table class="header">
            <tr>
                <td>
                    @if($logo)<img class="logo" src="{{ $logo }}" alt="Rangkul.com">@else<strong>Rangkul.com</strong>@endif
                    <div class="issued">Platform Donasi &amp; Transparansi Sosial &bull; Diterbitkan pada: {{ $format(now()) }}</div>
                </td>
                <td style="text-align: right;">
                    <span class="badge-paid">LUNAS</span>
                    <div class="method">Metode: Midtrans Payment Gateway</div>
                </td>
            </tr>
        </table>
        <div class="divider"></div>

        <div class="hero">
            <table>
                <tr>
                    <td>
                        <h1>Invoice Langganan Premium</h1>
                        <div class="hero-sub">Akun Premium Perusahaan Rangkul</div>
                    </td>
                    <td style="width: 44%;">
                        <div class="trx-label">ID Transaksi:</div>
                        <div class="trx">{{ $subscription->transaction_id }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section-title"><span class="marker"></span>Data Perusahaan</div>
        <div class="info">
            <table>
                <tr>
                    <td><div class="label">Nama Perusahaan</div><div class="value">{{ $company?->donor?->user?->nama ?? '-' }}</div></td>
                    <td><div class="label">NPWP</div><div class="value">{{ $company?->NPWP ?? '-' }}</div></td>
                </tr>
                <tr>
                    <td><div class="label">Nama PIC</div><div class="value">{{ $company?->nama_pic ?? '-' }} &middot; {{ $company?->jabatan ?? '-' }}</div></td>
                    <td><div class="label">Email Korporat</div><div class="value">{{ $company?->email_korporat ?? '-' }}</div></td>
                </tr>
                <tr>
                    <td><div class="label">Tanggal Pembayaran</div><div class="value">{{ $format($subscription->paid_at) }}</div></td>
                    <td><div class="label">Masa Berlaku</div><div class="value">{{ $date($subscription->started_at) }} &ndash; {{ $date($subscription->expired_at) }}</div></td>
                </tr>
            </table>
        </div>

        <div class="section-title"><span class="marker"></span>Ringkasan Pemesanan</div>
        <div class="summary">
            <table>
                <tr><td>Akun Premium Perusahaan ({{ $pricing['duration_months'] }} bulan)</td><td class="amount">{{ $money($pricing['price']) }}</td></tr>
                <tr><td>Biaya Layanan</td><td class="amount">{{ $money($pricing['service_fee']) }}</td></tr>
                <tr><td>Pajak (PPN {{ $taxPercent }}%)</td><td class="amount">{{ $money($pricing['tax']) }}</td></tr>
            </table>
        </div>
        <div class="total">
            <table>
                <tr>
                    <td><div class="total-label">TOTAL BIAYA</div><div class="total-sub">Sudah termasuk pajak</div></td>
                    <td class="total-amount">{{ $money($pricing['total']) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            Dokumen ini dibuat otomatis oleh sistem Rangkul sebagai bukti pembayaran langganan premium<br>
            yang sah dan tidak memerlukan tanda tangan.
        </div>
    </div>
</body>
</html>
