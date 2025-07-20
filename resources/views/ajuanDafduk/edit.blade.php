@extends('layouts.app', ['title' => 'Edit Ajuan', 'menu' => 'ajuanDafduk'])

@section('content')
    <section class="tab-components">
        <div class="container-fluid">
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Edit Pengajuan Dafduk</h2>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="breadcrumb-wrapper">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item"><a>Pengajuan Dafduk</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

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

                            <form action="{{ route('ajuanDafduk.update', $ajuan->idDafduk) }}" method="POST">
                                @csrf
                                @method('PUT')

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
                                            <option value="{{ $l->idLayanan }}"
                                                {{ $l->idLayanan == $ajuan->idLayanan ? 'selected' : '' }}>
                                                {{ $l->namaLayanan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label>No KK</label>
                                    <input type="text" name="noKK" class="form-control" value="{{ $ajuan->noKK }}"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label>NIK</label>
                                    <input type="text" name="nik" class="form-control" value="{{ $ajuan->nik }}"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" value="{{ $ajuan->nama }}"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control">{{ $ajuan->keterangan }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label>Link GDrive</label>
                                    <input type="url" name="linkBerkas" class="form-control"
                                        value="{{ $ajuan->linkBerkas }}">
                                    <small class="text-muted">Masukkan link dari GDrive yang dapat diakses</small>
                                </div>
                                <a href="{{ route('ajuanDafduk.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
