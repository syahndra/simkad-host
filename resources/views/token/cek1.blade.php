<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cek Pengajuan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f1f5fb;
    }
    .form-container {
      background-color: white;
      padding: 30px;
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
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
    <div class="row mb-3">
      <label for="token" class="col-sm-2 col-form-label">Kode. Pengajuan</label>
      <div class="col-sm-3">
        <input type="text" class="form-control" id="token" placeholder="Masukkan token...">
      </div>
      <div class="col-sm-2">
        <button class="btn btn-cari w-100" onclick="cari()">🔍 CARI</button>
      </div>
    </div>

    <!-- Hasil Ajuan -->
    <div id="hasil" style="display: none;">
      <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Tanggal Ajuan</label>
        <div class="col-sm-10"><div class="form-control-plaintext" id="tgl">-</div></div>
      </div>
      <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Pelayanan</label>
        <div class="col-sm-10"><div class="form-control-plaintext" id="layanan">-</div></div>
      </div>
      <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Nama</label>
        <div class="col-sm-10"><div class="form-control-plaintext" id="nama">-</div></div>
      </div>
      <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">No KK</label>
        <div class="col-sm-10"><div class="form-control-plaintext" id="kk">-</div></div>
      </div>
      <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">NIK</label>
        <div class="col-sm-10"><div class="form-control-plaintext" id="nik">-</div></div>
      </div>
      <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">No Akta</label>
        <div class="col-sm-10"><div class="form-control-plaintext" id="akta">-</div></div>
      </div>
      <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Operator Desa</label>
        <div class="col-sm-10"><div class="form-control-plaintext" id="opdes">-</div></div>
      </div>
      <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Status Ajuan</label>
        <div class="col-sm-10"><div class="form-control-plaintext" id="status">-</div></div>
      </div>

      <!-- Tombol Dokumen -->
      <div class="text-center mt-4">
        <button class="btn btn-view px-4" onclick="viewDocument()">View Document</button>
      </div>
    </div>
  </div>
</div>

<script>
  function cari() {
    const token = document.getElementById("token").value.trim();

    const dummy = {
      token: "df46hfey",
      tgl: "2025-06-10",
      layanan: "KK",
      nama: "People1",
      kk: "3329123456789101",
      nik: "3329123456789101",
      akta: "1234567890",
      opdes: "Operator A",
      status: "Selesai"
    };

    if (token === dummy.token) {
      document.getElementById("tgl").textContent = dummy.tgl;
      document.getElementById("layanan").textContent = dummy.layanan;
      document.getElementById("nama").textContent = dummy.nama;
      document.getElementById("kk").textContent = dummy.kk;
      document.getElementById("nik").textContent = dummy.nik;
      document.getElementById("akta").textContent = dummy.akta;
      document.getElementById("opdes").textContent = dummy.opdes;
      document.getElementById("status").textContent = dummy.status;
      document.getElementById("hasil").style.display = "block";
    } else {
      alert("Token tidak ditemukan.");
      document.getElementById("hasil").style.display = "none";
    }
  }

  function viewDocument() {
    window.open("dokumen/df46hfey.pdf", "_blank");
  }
</script>

</body>
</html>
