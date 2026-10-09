<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Akun Diubah</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6;">

    <h2>Password Akun Anda Telah Diubah</h2>

    <p>
        Halo, {{ $user->nama }},
    </p>

    <p>
        Password akun Rangkul Anda dengan email
        <strong>{{ $user->email }}</strong>
        baru saja diatur ulang oleh Super Admin.
    </p>

    <p>
        Silakan masuk kembali memakai password baru yang diberikan oleh Super Admin.
        Seluruh sesi login sebelumnya telah diakhiri.
    </p>

    <p>
        Jika Anda merasa tidak meminta perubahan ini, segera hubungi Super Admin.
    </p>

    <p>
        Salam,<br>
        Tim Rangkul
    </p>

</body>
</html>
