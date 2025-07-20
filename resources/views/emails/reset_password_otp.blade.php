<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password OTP</title>
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
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 6px;
            color: #e74c3c;
            margin: 20px 0;
            text-align: center;
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
        <h2>Hai, {{ $data['nama'] }}</h2>

        <div class="content">
            <p>Anda telah meminta untuk mereset password akun Anda.</p>
            <p>Gunakan kode OTP berikut untuk melanjutkan:</p>

            <div class="otp-code">
                {{ $data['otp'] }}
            </div>

            <p>Kode ini berlaku hingga pukul <strong>{{ $data['expired'] }}</strong>.</p>
            <p>Jangan bagikan kode ini kepada siapapun demi keamanan akun Anda.</p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Sistem Monitoring Kios Adminduk Desa
        </div>
    </div>
</body>
</html>
