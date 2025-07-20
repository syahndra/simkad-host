@extends('layouts.app', ['title' => 'Form Edit Kecamatan', 'menu' => 'kecamatan'])

@section('content')
    <section class="tab-components">
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Form Edit Kecamatan</h2>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="breadcrumb-wrapper">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#0">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('kecamatan.index') }}">Kecamatan</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ========== title-wrapper end ========== -->

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

                            <form action="{{ route('kecamatan.update', $kecamatan->idKec) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="namaKec" class="form-label">Nama Kecamatan</label>
                                    <input type="text" name="namaKec" class="form-control" id="namaKec"
                                        value="{{ old('namaKec', $kecamatan->namaKec) }}" required>
                                </div>

                                <a href="{{ route('kecamatan.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Perbarui</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ========== form-elements-wrapper end ========== -->
        </div>
    </section>
@endsection