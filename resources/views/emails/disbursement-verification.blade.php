<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Status Pencairan Dana</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2>
        @if ($disbursement->status === 'diterima')
            Pencairan Dana Anda Telah Disetujui
        @else
            Pencairan Dana Anda Ditolak
        @endif
    </h2>

    <p>Halo, {{ $disbursement->campaign?->organization?->user?->nama ?? 'tim organisasi' }},</p>

    @if ($disbursement->status === 'diterima')
        <p>
            Permohonan pencairan dana untuk campaign
            <strong>{{ $disbursement->campaign?->judul }}</strong>
            telah disetujui oleh admin.
        </p>
        <p>
            Nominal yang dicairkan: <strong>Rp {{ number_format($disbursement->nominal_dicairkan ?? $disbursement->nominal_diajukan, 0, ',', '.') }}</strong>
        </p>
    @else
        <p>
            Permohonan pencairan dana untuk campaign
            <strong>{{ $disbursement->campaign?->judul }}</strong>
            belum dapat disetujui.
        </p>

        @if ($disbursement->alasan_tolak)
            <p><strong>Alasan penolakan:</strong></p>
            <p>{{ $disbursement->alasan_tolak }}</p>
        @endif
    @endif

    <p>Salam,<br>Tim Rangkul</p>
</body>
</html>
