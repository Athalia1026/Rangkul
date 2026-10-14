<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Undangan Akun {{ $invitation->roleLabel() }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">

    <h2>Undangan Pendaftaran Akun {{ $invitation->roleLabel() }}</h2>

    <p>Halo,</p>

    <p>
        Super Admin Rangkul mengundang Anda untuk bergabung sebagai
        <strong>{{ $invitation->roleLabel() }}</strong>.
        Silakan lengkapi pendaftaran memakai email
        <strong>{{ $invitation->email }}</strong> melalui tombol di bawah ini.
    </p>

    <p>
        <a href="{{ $url }}"
           style="display: inline-block; padding: 10px 20px; background-color: #086538; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold;">
            Daftar Sekarang
        </a>
    </p>

    <p>
        Link ini hanya bisa dipakai satu kali dan berlaku sampai
        <strong>{{ $invitation->expires_at->timezone(config('app.timezone'))->translatedFormat('d F Y H:i') }}</strong>.
        Jangan bagikan link ini kepada orang lain.
    </p>

    <p>
        Jika tombol tidak berfungsi, salin link berikut ke browser Anda:<br>
        <span style="word-break: break-all;">{{ $url }}</span>
    </p>

    <p>
        Jika Anda tidak merasa menerima undangan ini, abaikan email ini.
    </p>

    <p>
        Salam,<br>
        Tim Rangkul
    </p>

</body>
</html>
