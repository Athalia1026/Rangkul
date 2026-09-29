@use('App\Support\OrgFormat')
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 24px 28px 28px; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #19382d; font-family: DejaVu Sans, sans-serif; font-size: 9px; }
        .page { border: 1px solid #087443; background: #fff; padding: 24px 26px 26px; }
        .brand { width: 100%; border-bottom: 2px solid #087443; padding-bottom: 14px; }
        .brand-logo { height: 36px; width: auto; }
        .report-head { width: 100%; padding: 16px 0 13px; }
        .report-title { color: #087443; font-size: 20px; font-weight: bold; margin: 0 0 5px; }
        .period { color: #697970; font-size: 9px; }
        .org { text-align: right; color: #1b3028; font-size: 9px; font-weight: bold; }
        .org-name { color: #087443; font-size: 12px; margin-bottom: 5px; }
        .section-title { color: #087443; font-size: 12px; font-weight: bold; margin: 0 0 10px; }
        .summary { width: 100%; border-collapse: collapse; background: #f0f5f2; margin: 0 0 18px; }
        .summary td { width: 25%; padding: 11px 10px; border-right: 1px solid #b9c9c0; vertical-align: top; }
        .summary td:last-child { border-right: 0; }
        .metric-label { color: #52675d; font-size: 8px; margin-bottom: 4px; }
        .metric-value { color: #172c23; font-size: 12px; font-weight: bold; }
        .data { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        .data th { color: #53665d; font-size: 8px; font-weight: normal; text-align: left; border-top: 1px solid #ccd8d1; border-bottom: 1px solid #ccd8d1; padding: 7px 3px; }
        .data td { color: #1b2e26; border-bottom: 1px solid #ccd8d1; padding: 7px 3px; }
        .empty { color: #697970; text-align: center; padding: 12px 3px; }
    </style>
</head>
<body>
    <div class="page">
        <table class="brand"><tr><td>
            <img class="brand-logo" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Rangkul">
        </td></tr></table>

        <table class="report-head">
            <tr>
                <td>
                    <p class="report-title">Laporan Organisasi</p>
                    <div class="period">
                        Periode:
                        {{ $filters['start'] ? OrgFormat::longDate($filters['start']) : 'Awal' }}
                        &ndash;
                        {{ $filters['end'] ? OrgFormat::longDate($filters['end']) : OrgFormat::longDate(now()) }}
                        <br>
                        Campaign: {{ $campaignName ?? 'Semua campaign' }}
                    </div>
                </td>
                <td class="org">
                    <div class="org-name">{{ $organization->nama_lembaga }}</div>
                    {{ $organization->kota }}<br>
                    Dicetak {{ OrgFormat::longDate(now()) }}
                </td>
            </tr>
        </table>

        <table class="summary">
            <tr>
                <td>
                    <div class="metric-label">Total Uang Terkumpul</div>
                    <div class="metric-value">{{ OrgFormat::rupiah($stats['total_donasi']) }}</div>
                </td>
                <td>
                    <div class="metric-label">Total Pencairan</div>
                    <div class="metric-value">{{ OrgFormat::rupiah($stats['total_pencairan']) }}</div>
                </td>
                <td>
                    <div class="metric-label">Saldo Tersisa</div>
                    <div class="metric-value">{{ OrgFormat::rupiah($stats['saldo_tersisa']) }}</div>
                </td>
                <td>
                    <div class="metric-label">Total Donatur</div>
                    <div class="metric-value">{{ $stats['total_donatur'] }} Orang</div>
                </td>
            </tr>
        </table>

        <p class="section-title">Detail Donasi Masuk</p>
        <table class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Donatur</th>
                    <th>Campaign</th>
                    <th>Nominal</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($donations as $donation)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $donation->anonim ? 'Anonim' : ($donation->donor?->user?->nama ?? '-') }}</td>
                        <td>{{ $donation->campaign?->judul ?? '-' }}</td>
                        <td>{{ OrgFormat::rupiah($donation->nominal) }}</td>
                        <td>{{ OrgFormat::date($donation->paid_at, 'd/m/Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Belum ada donasi masuk pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>

        <p class="section-title">Riwayat Pencairan</p>
        <table class="data">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Campaign</th>
                    <th>Alokasi</th>
                    <th>Nominal</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($disbursements as $disbursement)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $disbursement->campaign?->judul ?? '-' }}</td>
                        <td>{{ $disbursement->alokasi_dana }}</td>
                        <td>{{ OrgFormat::rupiah($disbursement->nominal_dicairkan ?? $disbursement->nominal_diajukan) }}</td>
                        <td>{{ OrgFormat::date($disbursement->created_at, 'd/m/Y') }}</td>
                        <td>{{ OrgFormat::statusLabel('disbursement', $disbursement->status) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">Belum ada riwayat pencairan pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
