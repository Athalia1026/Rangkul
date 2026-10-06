@php
    $fontDir = str_replace('\\', '/',resource_path('fonts/plus-jakarta-sans'));
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $format = fn ($value) => $value ? \Carbon\Carbon::parse($value)->locale('id')->translatedFormat('d F Y, H:i') . ' WIB' : '-';
    $logoPath = public_path('images/logo.png');
    $logo = is_file($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @font-face { font-family: 'Plus Jakarta Sans'; font-weight: normal; font-style: normal; src: url('{{ $fontDir }}/PlusJakartaSans-Regular.ttf') format('truetype'); }
        @font-face { font-family: 'Plus Jakarta Sans'; font-weight: bold; font-style: normal; src: url('{{ $fontDir }}/PlusJakartaSans-Bold.ttf') format('truetype'); }
        @font-face { font-family: 'Plus Jakarta Sans'; font-weight: normal; font-style: italic; src: url('{{ $fontDir }}/PlusJakartaSans-Italic.ttf') format('truetype'); }
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

        .hero { background: #f2f5fa; border-left: 4px solid #087443; border-radius: 0 8px 8px 0; padding: 14px 14px; }
        .hero td { vertical-align: middle; }
        .hero h1 { margin: 0; font-size: 17px; color: #10251c; }
        .hero-sub { color: #6b7d74; font-size: 8px; margin-top: 3px; }
        .trx-label { color: #6b7d74; font-size: 6.5px; text-align: right; margin-bottom: 3px; }
        .trx { background: #fff; border: 1px solid #d5dde8; border-radius: 5px; padding: 5px 7px; font-size: 6.5px; color: #087443; font-weight: bold; text-align: right; }

        .section-title { font-size: 11px; font-weight: bold; color: #10251c; margin: 18px 0 8px; }
        .marker { display: inline-block; width: 8px; height: 8px; background: #087443; border-radius: 2px; margin-right: 6px; }

        .info { background: #f2f5fa; border-radius: 8px; padding: 12px 14px 4px; }
        .info td { width: 50%; padding-bottom: 10px; }
        .label { color: #6b7d74; font-size: 6.5px; margin-bottom: 3px; }
        .value { font-size: 9px; font-weight: bold; color: #10251c; }
        .anon { display: inline-block; background: #e1e6ee; color: #52665a; border-radius: 8px; padding: 1px 6px; font-size: 6px; font-weight: normal; margin-left: 4px; }

        .quote { background: #e3f4ea; border-left: 4px solid #087443; border-radius: 0 8px 8px 0; padding: 12px 14px; margin-top: 14px; }
        .quote-label { color: #087443; font-size: 6.5px; font-weight: bold; margin-bottom: 5px; }
        .quote-text { font-size: 9.5px; font-style: italic; color: #10251c; line-height: 1.5; }
        .quote-mark { width: 40px; text-align: right; vertical-align: bottom; font-size: 42px; line-height: 30px; font-weight: bold; color: #b5dec6; }

        .summary { background: #f2f5fa; border-radius: 8px; padding: 4px 14px; }
        .summary td { padding: 9px 0; border-bottom: 1px solid #dde3ec; font-size: 9px; color: #3e5148; }
        .summary td.amount { text-align: right; font-weight: bold; color: #10251c; }
        .total { border-top: 2px solid #d5dde8; margin-top: 10px; padding: 10px 14px 0; }
        .total td { vertical-align: middle; }
        .total-label { font-size: 7px; font-weight: bold; color: #10251c; letter-spacing: .3px; }
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
                    <span class="badge-paid"><span style="font-family: DejaVu Sans, sans-serif;">&#10003;</span> LUNAS</span>
                    <div class="method">Metode: Midtrans Payment Gateway</div>
                </td>
            </tr>
        </table>
        <div class="divider"></div>

        <div class="hero">
            <table>
                <tr>
                    <td>
                        <h1>Bukti Pembayaran Donasi</h1>
                        <div class="hero-sub">Tanda bukti kontribusi sukarela terverifikasi sistem</div>
                    </td>
                    <td style="width: 46%;">
                        <div class="trx-label">Nomor Transaksi:</div>
                        <div class="trx">{{ $invoiceId }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section-title"><span class="marker"></span>Informasi Donasi</div>
        <div class="info">
            <table>
                <tr>
                    <td>
                        <div class="label">Nama Donatur</div>
                        <div class="value">{{ $donation->donor?->user?->nama ?? '-' }}@if($donation->anonim)<span class="anon">Anonim Publik</span>@endif</div>
                    </td>
                    <td>
                        <div class="label">Nama Kampanye</div>
                        <div class="value">{{ $donation->campaign?->judul ?? '-' }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="label">Lembaga / Panti Penerima</div>
                        <div class="value">{{ $donation->campaign?->organization?->nama_lembaga ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="label">Kanal Pembayaran</div>
                        <div class="value">Midtrans Payment Gateway</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="label">Tanggal Donasi Dimulai</div>
                        <div class="value">{{ $format($donation->created_at) }}</div>
                    </td>
                    <td>
                        <div class="label">Tanggal Berhasil Diverifikasi</div>
                        <div class="value">{{ $format($donation->paid_at) }}</div>
                    </td>
                </tr>
            </table>
        </div>

        @if($donation->note)
            <div class="quote">
                <table>
                    <tr>
                        <td>
                            <div class="quote-label">DOA &amp; PESAN DONATUR</div>
                            <div class="quote-text">&ldquo;{{ $donation->note }}&rdquo;</div>
                        </td>
                        <td class="quote-mark">&rdquo;</td>
                    </tr>
                </table>
            </div>
        @endif

        <div class="section-title"><span class="marker"></span>Ringkasan Pembayaran</div>
        <div class="summary">
            <table>
                <tr><td>Nominal Donasi Bersih</td><td class="amount">{{ $money($donation->nominal) }}</td></tr>
                <tr><td style="border-bottom: 0;">Biaya Layanan &amp; Pemeliharaan Platform</td><td class="amount" style="border-bottom: 0;">{{ $money($fee) }}</td></tr>
            </table>
        </div>
        <div class="total">
            <table>
                <tr>
                    <td>
                        <div class="total-label">TOTAL PEMBAYARAN BERHASIL</div>
                        <div class="total-sub">Termasuk biaya layanan platform</div>
                    </td>
                    <td class="total-amount">{{ $money($total) }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            Terima kasih atas kebaikan Anda. Dokumen ini dibuat otomatis oleh sistem Rangkul<br>
            sebagai bukti pembayaran donasi yang sah dan tidak memerlukan tanda tangan.
        </div>
    </div>
</body>
</html>
