<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ asset('assets/images/Lambang_Kabupaten_Brebes.png') }}" type="image/x-icon" />
    <title>Cek Pengajuan | Sistem Monitoring Kios Adminduk Desa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5fb;
        }

        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            max-width: 1000px;
            width: 100%;
            margin: 40px auto;
        }

        .form-control-plaintext {
            background-color: #e9e9e9;
            border-radius: 10px;
            padding: 10px 15px;
        }

        .btn-cari {
            background-color: #1500ff;
            color: white;
        }

        .btn-view {
            background-color: #71c69e;
            color: white;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="form-container">
            <h5 class="text-center mb-4 fw-bold">PENCARIAN DOKUMEN PENGAJUAN KIOS ADMINDUK DESA</h5>

            <!-- Form Pencarian -->
            <form method="GET" action="" onsubmit="return goToResult();">
                <div class="row mb-3">
                    <label for="search" class="col-sm-2 col-form-label">Kode. Pengajuan</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="search" placeholder="cth: capil/abc123"
                            required>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-cari w-100">🔍 CARI</button>
                    </div>
                </div>
            </form>

            @if ($data)
                <!-- Hasil Ajuan -->
                <div id="hasil">
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Tanggal Ajuan</label>
                        <div class="col-sm-10">
                            <div class="form-control-plaintext">{{ $data['tgl'] ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Pelayanan</label>
                        <div class="col-sm-10">
                            <div class="form-control-plaintext">{{ $data['layanan'] ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <div class="form-control-plaintext">{{ $data['nama'] ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">No KK</label>
                        <div class="col-sm-10">
                            <div class="form-control-plaintext">{{ $data['kk'] ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">NIK</label>
                        <div class="col-sm-10">
                            <div class="form-control-plaintext">{{ $data['nik'] ?? '-' }}</div>
                        </div>
                    </div>
                    @if ($jenis === 'capil')
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">No Akta</label>
                            <div class="col-sm-10">
                                <div class="form-control-plaintext">{{ $ajuan->noAkta ?? '-' }}</div>
                            </div>
                        </div>
                    @endif
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Operator Desa</label>
                        <div class="col-sm-10">
                            <div class="form-control-plaintext">{{ $data['opdes'] ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Status Ajuan</label>
                        <div class="col-sm-10">
                            <div class="form-control-plaintext">{{ $data['status'] ?? '-' }}</div>
                        </div>
                    </div>
                    @if (Str::lower($data['status'] ?? '') === 'dalam antrian')
                        <div class="mb-3 row">
                            <label class="col-sm-2 col-form-label">Antrian Ke</label>
                            <div class="col-sm-10">
                                <div class="form-control-plaintext">{{ $data['antrian'] ?? '-' }}</div>
                            </div>
                        </div>
                    @endif

                    <!-- Tombol Dokumen -->
                    @if ($ajuan->finalDokumen)
                        <div class="text-center mt-4">
                            <a href="{{ asset($ajuan->finalDokumen->filePath) }}" target="_blank"
                                class="btn btn-view px-4">View Document</a>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script>
        function goToResult() {
            const input = document.getElementById("search").value.trim();
            if (!input.includes("/")) {
                alert("Format salah. Gunakan format: capil/token atau dafduk/token");
                return false;
            }
            const [jenis, token] = input.split("/");
            if (!jenis || !token) {
                alert("Mohon isi format lengkap: jenis/token");
                return false;
            }
            window.location.href = `/cek-pengajuan/${jenis}/${token}`;
            return false;
        }

        // Autofill jika buka dengan URL token
        window.onload = function() {
            const jenis = "{{ $jenis }}";
            const token = "{{ $token }}";
            if (jenis && token) {
                document.getElementById("search").value = jenis + "/" + token;
            }
        }
    </script>

</body>

</html>
