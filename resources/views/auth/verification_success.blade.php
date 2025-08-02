<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email Berhasil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Tambahkan link Bootstrap jika ingin -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #f4f4f4;">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        <h2 class="text-success mb-3">✅ Verifikasi Email Berhasil</h2>
                        <p class="fs-5">Halo <strong>{{ $nama }}</strong>,</p>
                        <p class="mb-4">Email <strong>{{ $email }}</strong> Anda telah berhasil diverifikasi.</p>
                        <p class="mb-4">Silakan lanjutkan ke halaman login untuk mulai menggunakan akun Anda.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Login Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
