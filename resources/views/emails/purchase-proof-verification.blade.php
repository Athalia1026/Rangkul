<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Status Bukti Pengeluaran</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2>
        @if ($proof->status === 'diterima')
            Bukti Pengeluaran Anda Telah Disetujui
        @else
            Bukti Pengeluaran Anda Ditolak
        @endif
    </h2>

    <p>Halo, {{ $proof->fundDisbursement?->campaign?->organization?->user?->nama ?? 'tim organisasi' }},</p>

    @if ($proof->status === 'diterima')
        <p>
            Bukti pengeluaran untuk pencairan dana campaign
            <strong>{{ $proof->fundDisbursement?->campaign?->judul }}</strong>
            telah diterima dan disetujui oleh admin.
        </p>
    @else
        <p>
            Bukti pengeluaran untuk campaign
            <strong>{{ $proof->fundDisbursement?->campaign?->judul }}</strong>
            ditolak oleh admin.
        </p>

        @if ($proof->alasan_tolak)
            <p><strong>Catatan:</strong></p>
            <p>{{ $proof->alasan_tolak }}</p>
        @endif
    @endif

    <p>Salam,<br>Tim Rangkul</p>
</body>
</html>
