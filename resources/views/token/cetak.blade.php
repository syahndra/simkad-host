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
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 30px;
        }

        .kop-surat {
            display: table;
            align-items: center;
            width: 100%;
            padding-bottom: 10px;
            margin-bottom: 20px;
            border-bottom: 3px double black;
        }

        .kop-logo {
            display: table-cell;
            width: 100px;
            vertical-align: middle;
            text-align: center;
        }

        .kop-logo img {
            width: 90px;
            height: auto;
        }

        .kop-teks {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .kop-teks h1,
        .kop-teks h2,
        .kop-teks p {
            margin: 0;
            line-height: 1.5;
        }

        .kop-teks h1 {
            font-size: 20px;
            font-weight: bold;
        }

        .kop-teks h2 {
            font-size: 18px;
            font-weight: bold;
        }

        .kop-teks p {
            font-size: 14px;
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

        table.isian td {
            padding: 6px 4px;
            vertical-align: top;
        }

        .label {
            width: 200px;
        }

        .colon {
            width: 10px;
            text-align: right;
        }

        .value {
            /* border-bottom: 1px dotted #000; */
            width: 100%;
        }
    </style>
</head>

<body>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <div class="kop-logo">
            <img src="assets/images/logo/logo-brebes.png" alt="Logo">
        </div>
        <div class="kop-teks">
            <h1>PEMERINTAH KABUPATEN BREBES</h1>
            <h2>DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL</h2>
            <p>Jl. Diponegoro No. 150 Telp. (0283) 671322 Brebes</p>
        </div>
    </div>

    <!-- JUDUL DOKUMEN -->
    <h5 style="text-align: center;" class="mb-4 fw-bold">
        BUKTI PENGAJUAN KIOS ADMINDUK DESA {{ strtoupper($desa->desa->namaDesa) }}
    </h5>

    <p class="mb-3">
        Dengan ini menyatakan bahwa telah dilakukan pengajuan pelayanan administrasi kependudukan melalui Kios Adminduk Desa. Berikut adalah rincian informasi terkait pengajuan dimaksud:
    </p>
    <!-- DAFTAR ISIAN -->
    <table class="isian">
        <tr>
            <td class="label">Tanggal Ajuan</td>
            <td class="colon">:</td>
            <td class="value">{{ $created_at }}</td>
        </tr>
        <tr>
            <td class="label">Nama Operator Desa</td>
            <td class="colon">:</td>
            <td class="value">{{ $desa->user->nama }}</td>
        </tr>
        <tr>
            <td class="label">Pelayanan</td>
            <td class="colon">:</td>
            <td class="value">{{ $layanan }}</td>
        </tr>
        <tr>
            <td class="label">Nama Pemohon</td>
            <td class="colon">:</td>
            <td class="value">{{ $nama }}</td>
        </tr>
        <tr>
            <td class="label">No KK Pemohon</td>
            <td class="colon">:</td>
            <td class="value">{{ $nokk }}</td>
        </tr>
        <tr>
            <td class="label">NIK Pemohon</td>
            <td class="colon">:</td>
            <td class="value">{{ $nik }} </td>
        </tr>
        <tr>
            <td class="label">RT/RW</td>
            <td class="colon">:</td>
            <td class="value">{{ $ajuan->rt }}/{{ $ajuan->rw }}</td>
        </tr>
        <tr>
            <td class="label">Kode Pengajuan</td>
            <td class="colon">:</td>
            <td class="value">{{ $jenis }}/{{ $token }}</td>
        </tr>
    </table>

    <!-- QR Code -->
    <div class="qr-wrapper">
        <img src="data:image/png;base64,{{ $barcode }}" alt="QR Code">
    </div>
    <div class="qr-text">
        Scan kode QR untuk memantau status pengajuan, atau kunjungi:<br>
        <a href="{{ route('cek.form') }}">
            {{ route('cek.form') }}
        </a>
    </div>
<br>
    <p class="mt-4" style="font-size: 13px;">
        <em>
            Harap simpan bukti ini dengan baik. Bukti ini diperlukan untuk keperluan verifikasi dan penelusuran status pengajuan pelayanan administrasi kependudukan.
        </em>
    </p>

</body>

</html>