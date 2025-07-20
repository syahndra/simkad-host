@extends('layouts.app', ['title' => 'Edit Respon', 'menu' => 'respon'])

@section('content')
    <section class="tab-components">
        <div class="container-fluid">
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2>
                            Edit Respon Ajuan {{ ucfirst($respon->jenis) }}
                            @if (Auth::user()->roleUser === 'operatorDesa')
                                - Ajukan Ulang
                            @endif
                        </h2>
                    </div>
                    <div class="col-md-6">
                        <ol class="breadcrumb float-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a>Respon Ajuan</a></li>
                            <li class="breadcrumb-item active">Edit</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="form-elements-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-style mb-30" style="min-height: 650px; overflow: auto;">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

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

                            <div class="mb-3">
                                <label>Operator Desa</label>
                                <input type="text" class="form-control"
                                    value="{{ $ajuan->operatorDesa->user->nama }} - {{ $ajuan->operatorDesa->desa->namaDesa }} ({{ $ajuan->operatorDesa->desa->kecamatan->namaKec }})"
                                    disabled>
                            </div>

                            <div class="mb-3">
                                <label>Layanan</label>
                                <input type="text" name="layanan" class="form-control"
                                    value="{{ $ajuan->layanan->namaLayanan }}" disabled>
                            </div>

                            <div class="mb-3"><label>No KK</label><input class="form-control" value="{{ $ajuan->noKK }}"
                                    disabled></div>
                            <div class="mb-3"><label>NIK</label><input class="form-control" value="{{ $ajuan->nik }}"
                                    disabled></div>

                            @if ($respon->jenis === 'capil')
                                <div class="mb-3"><label>No Akta</label><input class="form-control"
                                        value="{{ $ajuan->noAkta }}" disabled></div>
                            @endif

                            <div class="mb-3"><label>Nama</label><input class="form-control" value="{{ $ajuan->nama }}"
                                    disabled></div>
                            <div class="mb-3"><label>Keterangan</label>
                                <textarea class="form-control" disabled>{{ $ajuan->keterangan }}</textarea>
                            </div>

                            <form action="{{ route('respon.update', $respon->idRespon) }}" method="POST">
                                @csrf
                                @method('PUT')

                                @if ($ajuan->statAjuan === 'ditolak')
                                    <input type="hidden" name="statAjuan" value="revisi">

                                    <div class="form-group mb-3">
                                        <label for="respon">Note</label>
                                        <textarea name="respon" id="respon" rows="4" class="form-control" required></textarea>
                                    </div>
                                @else
                                    <div class="mb-3">
                                        <label>Tindak Lanjut</label>
                                        <select name="statAjuan" class="form-control" required>
                                            <option disabled selected>-- Pilih Tindak Lanjut --</option>
                                            <option value="sudah diproses"
                                                {{ $ajuan->statAjuan === 'sudah diproses' ? 'selected' : '' }}>Sudah
                                                Diproses
                                            </option>
                                            <option value="ditolak"
                                                {{ $ajuan->statAjuan === 'ditolak' ? 'selected' : '' }}>
                                                Ditolak</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="respon">Ubah Respon</label>
                                        <textarea name="respon" id="respon" rows="4" class="form-control">{{ old('respon', $respon->respon) }}</textarea>
                                    </div>
                                @endif


                                @if (Auth::user()->roleUser === 'operatorDesa')
                                    <button type="submit" class="btn btn-success">Ajukan Ulang</button>
                                @else
                                    <button type="submit" class="btn btn-primary">Update Respon</button>
                                @endif

                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
