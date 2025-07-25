@extends('layouts.app', ['title' => 'Form Edit Operator Kecamatan', 'menu' => 'user'])
@section('content')
    <section class="tab-components">
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Form Edit Operator Kecamatan</h2>
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
                                    <li class="breadcrumb-item"><a>Manajemen Operator Kecamatan</a></li>
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
                            <form action="{{ route('operatorKec.update', $operatorKec->idOpkec) }}" method="POST">
                                @csrf @method('PUT')

                                <div class="mb-3">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control"
                                        value="{{ old('nama', $operatorKec->user->nama) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $operatorKec->user->email) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label>Password (Kosongkan jika tidak ingin ganti)</label>
                                    <input type="password" name="password" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label>Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label>Pilih Kecamatan</label>
                                    <select name="idKec" class="form-control" required>
                                        <option value="">-- Pilih Kecamatan --</option>
                                        @foreach ($kecamatan as $kec)
                                            <option value="{{ $kec->idKec }}"
                                                {{ $operatorKec->idKec == $kec->idKec ? 'selected' : '' }}>
                                                {{ $kec->idKec }} - {{ $kec->namaKec }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <a href="{{ route('operatorKec.index') }}" class="btn btn-secondary">Kembali</a>
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
