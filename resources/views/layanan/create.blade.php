@extends('layouts.app', ['title' => 'Form Tambah Layanan', 'menu' => 'layanan'])
@section('content')
    <section class="tab-components">
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Form Tambah Layanan</h2>
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
                                    <li class="breadcrumb-item"><a>Layanan</a></li>
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
                                <div class="alert alert-danger">
                                    <strong>Terjadi kesalahan!</strong>
                                    <ul class="mb-0 mt-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form action="{{ route('layanan.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label>Nama Layanan</label>
                                    <input type="text" name="namaLayanan" class="form-control"
                                        value="{{ old('namaLayanan') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label>Jenis</label>
                                    <select name="jenis" class="form-select" required>
                                        <option value="" disabled selected>Pilih Jenis</option>
                                        <option value="dafduk" {{ old('jenis') == 'dafduk' ? 'selected' : '' }}>Dafduk
                                        </option>
                                        <option value="capil" {{ old('jenis') == 'capil' ? 'selected' : '' }}>Capil</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>Akses Verifikasi</label>
                                    <select name="aksesVer" class="form-select" required>
                                        <option value="" disabled selected>Pilih Akses</option>
                                        <option value="dinasDafduk" {{ old('aksesVer') == 'dinasDafduk' ? 'selected' : '' }}>Dinas Dafduk</option>
                                        <option value="dinasCapil" {{ old('aksesVer') == 'dinasCapil' ? 'selected' : '' }}>
                                            Dinas Capil</option>
                                        <option value="kecamatan" {{ old('aksesVer') == 'kecamatan' ? 'selected' : '' }}>
                                            Kecamatan</option>
                                    </select>
                                </div>

                                <a href="{{ route('layanan.index') }}" class="btn btn-secondary">Kembali</a>
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