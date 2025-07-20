@extends('layouts.app', ['title' => 'Form Tambah Operator Desa', 'menu' => 'user'])
@section('content')
    <section class="tab-components">
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Form Tambah Operator Desa</h2>
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
                                        Tambah
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
                            <form action="{{ route('operatorDesa.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label>Password</label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="password" class="form-control" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('password')">
                                            👁️
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label>Konfirmasi Password</label>
                                    <div class="input-group">
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('password_confirmation')">
                                            👁️
                                        </button>
                                    </div>
                                </div>

                                <!-- Pilih Kecamatan -->
                                <div class="mb-3">
                                    <label>Pilih Kecamatan</label>
                                    <select name="idKec" id="kecamatanSelect" class="form-control" required>
                                        <option disabled {{ old('idKec') ? '' : 'selected' }}>-- Pilih Kecamatan --</option>
                                        @foreach ($kecamatan as $kec)
                                            <option value="{{ $kec->idKec }}"
                                                {{ old('idKec') == $kec->idKec ? 'selected' : '' }}>
                                                {{ $kec->namaKec }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Pilih Desa</label>
                                    <select name="idDesa" id="desaSelect" class="form-control" required>
                                        <option disabled selected>-- Pilih Desa --</option>
                                    </select>
                                </div>

                                <a href="{{ route('operatorDesa.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
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
        function loadDesa(kecId, selectedDesaId = null) {
            const desaSelect = document.getElementById('desaSelect');
            desaSelect.innerHTML = '<option>Memuat...</option>';

            fetch(`/getDesa-by-kecamatan/${kecId}`)
                .then(response => response.json())
                .then(data => {
                    desaSelect.innerHTML = '<option disabled selected>-- Pilih Desa --</option>';
                    data.forEach(d => {
                        const selected = (selectedDesaId && selectedDesaId == d.idDesa) ? 'selected' : '';
                        desaSelect.innerHTML +=
                            `<option value="${d.idDesa}" ${selected}>${d.namaDesa}</option>`;
                    });
                });
        }

        // Event saat user memilih kecamatan
        document.getElementById('kecamatanSelect').addEventListener('change', function() {
            const kecId = this.value;
            loadDesa(kecId);
        });

        // Saat halaman reload karena validasi error, muat ulang desa sesuai old('idKec')
        window.addEventListener('DOMContentLoaded', function() {
            const oldKecId = '{{ old('idKec') }}';
            const oldDesaId = '{{ old('idDesa') }}';
            if (oldKecId) {
                loadDesa(oldKecId, oldDesaId);
            }
        });
    </script>
    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
@endsection
