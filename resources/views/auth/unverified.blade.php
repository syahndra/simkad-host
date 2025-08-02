<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Belum Terverifikasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="card shadow-sm p-4">
            <div class="card-body">
                <h4 class="card-title text-warning">Email Anda Belum Terverifikasi</h4>
                <p class="card-text">Kami mendeteksi bahwa akun dengan email <strong>{{ session('email') }}</strong> belum melakukan verifikasi email.</p>
                <p class="card-text">Silakan cek kotak masuk Gmail Anda, atau cek folder <strong>Spam/Promosi</strong>. Jika tidak menemukan email verifikasi, silakan hubungi admin.</p>
                <a href="/" class="btn btn-primary mt-3">Kembali ke Halaman Utama</a>
            </div>
        </div>
    </div>

</body>
</html>
