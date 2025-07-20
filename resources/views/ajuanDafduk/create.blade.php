@extends('layouts.app', ['title' => 'Tambah Ajuan', 'menu' => 'ajuanDafduk'])
@section('content')
    <section class="tab-components">
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Tambah Pengajuan Dafduk</h2>
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
                                    <li class="breadcrumb-item"><a>Pengajuan Dafduk</a></li>
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
                            <form action="{{ route('ajuanDafduk.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label>Operator Desa</label>
                                    <input type="hidden" name="idOpdes" value="{{ $operatorDesa->idOpdes }}">
                                    <input type="text" class="form-control"
                                        value="{{ $operatorDesa->user->nama }} - {{ $operatorDesa->desa->namaDesa }} ({{ $operatorDesa->desa->kecamatan->namaKec }})"
                                        disabled>
                                </div>

                                <div class="mb-3">
                                    <label>Layanan</label>
                                    <select name="idLayanan" class="form-control" required>
                                        <option value="">-- Pilih Layanan --</option>
                                        @foreach ($layanan as $l)
                                            <option value="{{ $l->idLayanan }}">{{ $l->namaLayanan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>No KK</label>
                                    <input type="text" name="noKK" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>NIK</label>
                                    <input type="text" name="nik" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Link GDrive (Opsional)</label>
                                    <input type="url" name="linkBerkas" class="form-control">
                                    <small class="text-muted">digunakan hanya pada saat SIAK Terpusat mengalami kendala pada UPLOAD DOKUMEN atau ketika diperlukan</small>
                                </div>

                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>

                                <input type="hidden" name="statAjuan" value="dalam antrian">
                                <a href="{{ route('ajuanDafduk.index') }}" class="btn btn-secondary">Kembali</a>
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
