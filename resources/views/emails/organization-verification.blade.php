<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Organisasi</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">

    <h2>
        @if ($organization->verification_status === 'disetujui')
            Organisasi Anda Telah Disetujui
        @else
            Verifikasi Organisasi Ditolak
        @endif
    </h2>

    <p>
        Halo, {{ $organization->user->nama }},
    </p>

    @if ($organization->verification_status === 'disetujui')

        <p>
            Selamat! Organisasi
            <strong>{{ $organization->nama_lembaga }}</strong>
            telah berhasil diverifikasi dan disetujui oleh admin.
        </p>

        <p>
            Sekarang organisasi Anda dapat menggunakan fitur-fitur
            yang tersedia pada platform.
        </p>

        <p>
            Terima kasih telah mendaftarkan organisasi Anda bersama kami.
        </p>

    @else

        <p>
            Mohon maaf, pendaftaran organisasi
            <strong>{{ $organization->nama_lembaga }}</strong>
            belum dapat disetujui.
        </p>

        @if ($organization->alasan_penolakan)
            <p>
                <strong>Alasan penolakan:</strong>
            </p>

            <p>
                {{ $organization->alasan_penolakan }}
            </p>
        @endif

        <p>
            Silakan periksa kembali informasi atau dokumen yang telah
            dikirimkan dan lakukan perbaikan sesuai dengan alasan penolakan.
        </p>

    @endif

    <p>
        Salam,<br>
        Tim Rangkul
    </p>

</body>
</html>