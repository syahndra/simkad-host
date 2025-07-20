<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Notifikasi Pengajuan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
        }

        .content p {
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 12px;
            color: #333;
        }

        .token {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin: 20px 0;
        }

        .button {
            display: inline-block;
            background-color: #28a745;
            color: #ffffff;
            padding: 12px 20px;
            margin-top: 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }

        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Pengajuan Baru dari {{ $data['nama'] }}</h2>
        </div>

        <div class="content">
            <p class="token">Token Ajuan: {{ $data['jenis'] }}/{{ $data['token'] }}</p>
            <p><strong>Layanan:</strong> {{ $data['layanan'] }}</p>
            <p><strong>Nama:</strong> {{ $data['nama'] }}</p>
            <p><strong>Tanggal Ajuan:</strong> {{ \Carbon\Carbon::parse($data['created_at'])->format('d M Y H:i') }}</p>
            <p>Untuk cek status pengajuan, klik tombol berikut:</p>

            <a href="{{ route('cek.pengajuan', ['jenis' => $data['jenis'], 'token' => $data['token'] ]) }}" class="button">
                Cek Status Pengajuan
            </a>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Sistem Pelayanan Adminduk Desa. Semua hak dilindungi.
        </div>
    </div>
</body>

</html>
