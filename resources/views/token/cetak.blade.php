<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bukti Pengajuan | Sistem Monitoring Kios Adminduk Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #ffffff;
            font-family: sans-serif;
            padding: 30px;
        }

        .form-control-plaintext {
            background-color: #e9e9e9;
            border-radius: 10px;
            padding: 10px 15px;
        }

        .qr-wrapper {
            width: 200px;
            margin: 40px auto 0;
            text-align: center;
        }

        .qr-wrapper img {
            width: 120px;
            height: 120px;
        }

        .qr-text {
            text-align: center;
            font-size: 13px;
            margin-top: 10px;
            word-break: break-word;
        }
    </style>
</head>

<body>

    <h5 class="text-center mb-4 fw-bold">DETAIL PENGAJUAN KIOS ADMINDUK DESA {{ strtoupper($desa->desa->namaDesa) }}</h5>

    <div id="hasil">
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Token</label>
            <div class="col-sm-10">
                <div class="form-control-plaintext">{{ $jenis }}/{{ $token }}</div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Tanggal Ajuan</label>
            <div class="col-sm-10">
                <div class="form-control-plaintext">{{ $created_at }}</div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Pelayanan</label>
            <div class="col-sm-10">
                <div class="form-control-plaintext">{{ $layanan }}</div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Nama</label>
            <div class="col-sm-10">
                <div class="form-control-plaintext">{{ $nama }}</div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">No KK</label>
            <div class="col-sm-10">
                <div class="form-control-plaintext">{{ $nokk }}</div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">NIK</label>
            <div class="col-sm-10">
                <div class="form-control-plaintext">{{ $nik }}</div>
            </div>
        </div>
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Operator Desa</label>
            <div class="col-sm-10">
                <div class="form-control-plaintext">{{ $desa->user->nama }}</div>
            </div>
        </div>
    </div>

    <div class="qr-wrapper">
        <img src="data:image/png;base64,{{ $barcode }}" alt="QR Code">
    </div>
    <div class="qr-text">
        Scan untuk cek status, atau kunjungi link berikut:{{ route('cek.pengajuan', ['jenis' => $jenis, 'token' => $token]) }}
    </div>


</body>

</html>