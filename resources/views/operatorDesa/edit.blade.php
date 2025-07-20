@extends('layouts.app', ['title' => 'Form Edit Operator Desa', 'menu' => 'user'])
@section('content')
    <section class="tab-components">
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Form Edit Operator Desa</h2>
                        </div>
                    </div>
                    <!-- end col -->
                    <div class="col-md-6">
                        <div class="breadcrumb-wrapper">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item"><a>Manajemen Operator Desa</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Edit
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- ========== title-wrapper end ========== -->

            <!-- ========== form-elements-wrapper start ========== -->
            <div class="form-elements-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-style mb-30" style="min-height: 650px; overflow: auto;">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Terjadi kesalahan!</strong>
                                    <ul class="mb-0 mt-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <form action="{{ route('operatorDesa.update', $op->idOpdes) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="mb-3">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" value="{{ $op->user->nama }}"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $op->user->email }}"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label>Password (kosongkan jika tidak diubah)</label>
                                    <input type="password" name="password" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label>Pilih Kecamatan</label>
                                    <select id="kecamatanSelect" class="form-control" required>
                                        <option disabled>-- Pilih Kecamatan --</option>
                                        @foreach ($kecamatan as $kec)
                                            <option value="{{ $kec->idKec }}"
                                                {{ $op->desa->idKec == $kec->idKec ? 'selected' : '' }}>
                                                {{ $kec->namaKec }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Pilih Desa</label>
                                    <select name="idDesa" id="desaSelect" class="form-control" required>
                                        @foreach ($daftarDesa as $des)
                                            <option value="{{ $des->idDesa }}"
                                                {{ $des->idDesa == $op->desa->idDesa ? 'selected' : '' }}>
                                                {{ $des->namaDesa }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <a href="{{ route('operatorDesa.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-success">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- ========== form-elements-wrapper end ========== -->
        </div>
        <!-- end container -->
    </section>
@endsection

@section('scripts')
    <script>
        document.getElementById('kecamatanSelect').addEventListener('change', function() {
            const kecId = this.value;
            const desaSelect = document.getElementById('desaSelect');
            desaSelect.innerHTML = '<option>Memuat...</option>';

            fetch(`/getDesa-by-kecamatan/${kecId}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Periksa data yang diterima
                    desaSelect.innerHTML = '<option disabled selected>-- Pilih Desa --</option>';
                    data.forEach(d => {
                        desaSelect.innerHTML += `<option value="${d.idDesa}">${d.namaDesa}</option>`;
                    });
                });
        });
    </script>
@endsection
