@extends('layouts.app', ['title' => 'Edit Ajuan', 'menu' => 'ajuanCapil'])

@section('content')
<section class="tab-components">
    <div class="container-fluid">
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title">
                        <h2>Edit Pengajuan Capil</h2>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="breadcrumb-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a>Pengajuan Capil</a></li>
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

                        <form action="{{ route('ajuanCapil.update', $ajuan->idCapil) }}" method="POST">
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
                                <input type="text" name="noKK" class="form-control" value="{{ old('noKK', $ajuan->noKK) }}" maxlength="16" minlength="16" pattern="\d{16}"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'');" required>
                            </div>

                            <div class="mb-3">
                                <label>NIK</label>
                                <input type="text" name="nik" class="form-control" value="{{ old('nik', $ajuan->nik) }}" maxlength="16" minlength="16" pattern="\d{16}"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'');" required>
                            </div>

                            <div class="mb-3">
                                <label>No Akta</label>
                                <input type="text" name="noAkta" class="form-control" oninput="formatNoAkta(this)" value="{{ old('noAkta', $ajuan->noAkta) }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama', $ajuan->nama) }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label>RT / RW</label>
                                <div class="row">
                                    <div class="col-md-2">
                                        <input type="text" name="rt" class="form-control"
                                            maxlength="3" value="{{ old('rt', $ajuan->rt) }}" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                    </div>
                                    /
                                    <div class="col-md-2">
                                        <input type="text" name="rw" class="form-control"
                                            maxlength="3" value="{{ old('rw', $ajuan->rw) }}" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control">{{ old('keterangan', $ajuan->keterangan) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label>Link GDrive</label>
                                <input type="url" name="linkBerkas" class="form-control"
                                    value="{{ old('linkBerkas', $ajuan->linkBerkas) }}">
                                <small class="text-muted">Masukkan link dari GDrive yang dapat diakses</small>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required value="{{ old('email', $ajuan->email) }}">
                            </div>
                            <a href="{{ route('ajuanCapil.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script>
    function formatNoAkta(el) {
        let raw = el.value.toUpperCase().replace(/[^A-Z0-9]/g, '');

        let part1 = raw.substring(0, 4).replace(/[^0-9]/g, ''); // 4 digit angka
        let part2 = raw.substring(4, 6).replace(/[^A-Z]/g, ''); // 2 huruf
        let part3 = raw.substring(6, 14).replace(/[^0-9]/g, ''); // 8 digit angka
        let part4 = raw.substring(14, 18).replace(/[^0-9]/g, ''); // 4 digit angka

        let formatted = part1;
        if (part2) formatted += '-' + part2;
        if (part3) formatted += '-' + part3;
        if (part4) formatted += '-' + part4;

        el.value = formatted;
    }
</script>
@endsection