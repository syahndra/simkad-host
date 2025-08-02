<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email</title>
    <style>
        .footer {
            font-size: 12px;
            text-align: center;
            color: #aaa;
            margin-top: 30px;
        }
    </style>
</head>

<body style="font-family: Arial, sans-serif; background: #f8f9fa; padding: 20px;">
    <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; margin: auto;">
        <h2>Halo, {{ $nama }}</h2>
        <p>Akun Anda telah dibuat oleh administrator sistem kami.</p>
        <p>Untuk mengaktifkan akun dan memverifikasi alamat email Anda, silakan klik tombol di bawah ini:</p>
        <p style="text-align: center;">
            <a href="{{ $verificationUrl }}" style="padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;">Verifikasi Email</a>
        </p>
        <p>Jika Anda merasa tidak terkait dengan pembuatan akun ini, Anda bisa mengabaikan email ini.</p>
        <p>Salam hormat,<br>Tim Admin</p>
        <div class="footer">
            &copy; {{ date('Y') }} Sistem Monitoring Kios Adminduk Desa
        </div>
    </div>

</body>

</html>