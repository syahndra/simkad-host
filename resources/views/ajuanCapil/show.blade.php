@extends('layouts.app', ['title' => 'Detail Ajuan Capil', 'menu' => 'ajuanCapil'])
@section('content')
    <section class="tab-components">
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Detail Ajuan Capil</h2>
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
                                    <li class="breadcrumb-item"><a>Pengajuan Capil</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Detail
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

                            <div class="mb-3">
                                <label>No Akta</label>
                                <input type="text" name="noAkta" class="form-control" value="{{ $ajuan->noAkta }}"
                                    disabled>
                            </div>

                            <div class="mb-3">
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control" value="{{ $ajuan->nama }}"
                                    disabled>
                            </div>

                            @if ($ajuan->keterangan != null || $ajuan->keterangan != '')
                                <div class="mb-3">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control" disabled>{{ $ajuan->keterangan }}</textarea>
                                </div>
                            @endif
                            @if ($ajuan->linkBerkas)
                                <div class="mb-3">
                                    <label>Link GDrive</label>
                                    <div class="form-control">
                                            <a href="{{ $ajuan->linkBerkas }}"
                                                target="_blank">Lihat Berkas</a>
                                        </div>
                                </div>
                            @endif
                            @if (!empty($respon) && !empty($respon->respon))
                                <div class="mb-3">
                                    <label>Respon</label>
                                    <textarea name="respon" class="form-control" disabled>{{ $respon->respon }}</textarea>
                                </div>
                            @endif

                            @if (!empty($finalDokumen) && !empty($finalDokumen->filePath))
                                <div class="mb-3">
                                    <label>Dokumen</label>
                                    @if ($finalDokumen->filePath)
                                        <div class="form-control">
                                            <a href="{{ asset('storage/' . $finalDokumen->filePath) }}"
                                                target="_blank">Lihat Dokumen</a>
                                        </div>
                                    @endif
                                </div>
                            @endif



                            <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
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
