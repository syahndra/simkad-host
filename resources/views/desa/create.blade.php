@extends('layouts.app', ['title' => 'Form Tambah Desa', 'menu' => 'wilayah'])
@section('content')
    <section class="tab-components">
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Form Tambah Desa</h2>
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
                                    <li class="breadcrumb-item"><a>Desa</a></li>
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
                            <form action="{{ route('desa.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="idKec" class="form-label">Pilih Kecamatan</label>
                                    <select name="idKec" class="form-select" required>
                                        <option value="">-- Pilih Kecamatan --</option>
                                        @foreach ($kecamatan as $index => $item)
                                            <option value="{{ $item->idKec }}">
                                                {{ $index + 1 }}. {{ $item->namaKec }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="namaDesa" class="form-label">Nama Desa</label>
                                    <input type="text" name="namaDesa" class="form-control" id="namaDesa" required>
                                </div>
                                <a href="{{ route('desa.index') }}" class="btn btn-secondary">Kembali</a>
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