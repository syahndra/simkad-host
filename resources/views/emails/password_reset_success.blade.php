<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password Berhasil</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 500px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        h2 {
            color: #2c3e50;
        }

        .otp-code {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 4px;
            color: #2980b9;
            margin: 20px 0;
            text-align: center;
            background-color: #ecf0f1;
            padding: 12px;
            border-radius: 8px;
        }

        .content {
            font-size: 15px;
            color: #333;
            line-height: 1.6;
        }

        .footer {
            font-size: 12px;
            text-align: center;
            color: #aaa;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Hai,</h2>

        <div class="content">
            <p>Password untuk akun dengan email <strong>{{ $userEmail }}</strong> berhasil direset.</p>
            <p>Berikut adalah password baru Anda:</p>

            <div class="otp-code">
                {{ $newPassword }}
            </div>

            <p>Segera login dan ubah password Anda jika ini bukan Anda yang melakukannya.</p>
            <p>Terima kasih</p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Sistem Monitoring Kios Adminduk Desa
        </div>
    </div>
</body>
</html>