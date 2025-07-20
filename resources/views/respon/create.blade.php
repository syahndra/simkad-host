@extends('layouts.app', ['title' => 'Tambah Respon', 'menu' => 'respon'])
@section('content')
    <section class="tab-components">
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Tambah Respon Ajuan {{ ucfirst($jenis) }}</h2>
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
                                    <li class="breadcrumb-item"><a>Respon Ajuan {{ ucfirst($jenis) }}</a></li>
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
                            <div class="mb-3">
                                <label>Operator Desa</label>
                                <input type="hidden" name="idOpdes" value="{{ $ajuan->operatorDesa->idOpdes }}">
                                <input type="text" class="form-control"
                                    value="{{ $ajuan->operatorDesa->user->nama }} - {{ $ajuan->operatorDesa->desa->namaDesa }} ({{ $ajuan->operatorDesa->desa->kecamatan->namaKec }})"
                                    disabled>
                            </div>

                            <div class="mb-3">
                                <label>Layanan</label>
                                <input type="text" name="layanan" class="form-control"
                                    value="{{ $ajuan->layanan->namaLayanan }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label>No KK</label>
                                <input type="text" name="noKK" class="form-control" value="{{ $ajuan->noKK }}"
                                    disabled>
                            </div>

                            <div class="mb-3">
                                <label>NIK</label>
                                <input type="text" name="nik" class="form-control" value="{{ $ajuan->nik }}"
                                    disabled>
                            </div>

                            @if ($jenis === 'capil')
                                <div class="mb-3">
                                    <label>No Akta</label>
                                    <input type="text" name="noAkta" class="form-control" value="{{ $ajuan->noAkta }}"
                                        disabled>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control" value="{{ $ajuan->nama }}"
                                    disabled>
                            </div>

                            <div class="mb-3">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control" disabled>{{ $ajuan->keterangan }}</textarea>
                            </div>

                            {{-- Form Respon --}}
                            <form action="{{ route('respon.store') }}" method="POST">
                                @csrf

                                <input type="hidden" name="idAjuan" value="{{ $ajuan->getKey() }}">
                                <input type="hidden" name="jenis" value="{{ $jenis }}">

                                <div class="mb-3">
                                    <label>Tindak Lanjut</label>
                                    <select name="statAjuan" class="form-control" required>
                                        <option disabled selected>-- Pilih Tindak Lanjut --</option>
                                        <option value="sudah diproses"
                                            {{ $ajuan->keterangan == 'sudah diproses' ? 'selected' : '' }}>
                                            Sudah Diproses
                                        </option>
                                        <option value="ditolak" {{ $ajuan->keterangan == 'ditolak' ? 'selected' : '' }}>
                                            Ditolak
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="respon">Tulis Respon</label>
                                    <textarea name="respon" id="respon" rows="4" class="form-control">{{ old('respon') }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Kirim Respon</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
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
