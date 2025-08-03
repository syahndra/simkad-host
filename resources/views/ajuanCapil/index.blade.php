@extends('layouts.app', ['title' => 'Ajuan Capil', 'menu' => 'ajuanCapil'])
@section('content')
<!-- ========== table components start ========== -->
<section class="table-components">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title">
                        <h2>Daftar Pengajuan Pencatatan Sipil</h2>
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
                                    Daftar Ajuan Capil
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

        <!-- ========== tables-wrapper start ========== -->
        <div class="tables-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-style mb-30">
                        @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                        @endif
                        <div class="d-flex gap-2 align-items-center mb-3">
                            <!-- Tombol Tambah -->
                            @if (Auth::user()->roleUser === 'operatorDesa')
                            <a href="{{ route('ajuanCapil.create') }}" class="btn btn-success">Tambah +</a>
                            @endif
                            <!-- Dropdown Export -->
                            <!-- <div class="dropdown">
                                <button class="btn btn-primary" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><button class="dropdown-item" onclick="exportToExcel('capil')">Export ke Excel</button></li>
                                    <li><button class="dropdown-item" onclick="exportToPDF('capil')">Export ke PDF</button></li>
                                </ul>
                            </div> -->
                            <button class="btn btn-primary" onclick="exportToPDF('capil')">
                                Export
                            </button>
                        </div>
                        <hr>
                        <!-- Filter -->
                        <form id="filterForm" class="row g-2 align-items-end mb-3">
                            <div class="col-md-3">
                                <select class="form-control" name="data">
                                    <option value="aktif" selected>Data Aktif</option>
                                    <option value="terhapus">Data Terhapus</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="startDate">
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="endDate">
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="layanan">
                                    <option disabled selected>-- Pilih Layanan --</option>
                                    <option value="">Semua</option>
                                    @foreach ($listLayanan as $l)
                                    <option value="{{ $l->idLayanan }}">{{ $l->namaLayanan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="status">
                                    <option disabled selected>-- Pilih Status --</option>
                                    <option value="">Semua</option>
                                    <option value="dalam antrian">dalam antrian</option>
                                    <option value="sudah diproses">Sudah Diproses</option>
                                    <option value="revisi">Revisi</option>
                                    <option value="ditolak">Ditolak</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                            @if (auth()->user()->roleUser !== 'operatorDesa')
                            <div class="col-md-3">
                                <select class="form-control" name="kecamatan" id="filterKecamatan">
                                    <option value="" selected>-- Pilih Kecamatan --</option>
                                    <option value="">Semua</option>
                                    @foreach ($listKecamatan as $kec)
                                    <option value="{{ $kec->idKec }}">{{ $kec->namaKec }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="desa" id="filterDesa">
                                    <option value="" disabled selected>-- Pilih Desa --</option>
                                    <option value="">Semua</option>
                                    {{-- Nantinya desa akan diubah via JavaScript --}}
                                </select>
                            </div>
                            @else
                            <input type="hidden" name="kecamatanInput" value="{{ $namaKecamatan }}">
                            <input type="hidden" name="desaInput" value="{{ $namaDesa }}">
                            <div class="col-md-2">
                                <input type="text" name="rw" class="form-control" placeholder="RW"
                                    maxlength="3" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="rt" class="form-control" placeholder="RT"
                                    maxlength="3" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            </div>
                            @endif
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table id="table" class="table" style="font-size: 14px;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tgl Ajuan</th>
                                        <th>Layanan</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>No KK</th>
                                        <th>No Akta</th>
                                        @if (auth()->user()->roleUser !== 'operatorDesa')
                                        <th>Kecamatan</th>
                                        <th>Desa</th>
                                        @endif
                                        <th>RT/RW</th>
                                        <th>Status</th>
                                        <th>Respon</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @foreach ($ajuan as $a)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ \Carbon\Carbon::parse($a->created_at)->format('j/n/Y') }}</td>
                                        </td>
                                        <td>{{ $a->layanan->namaLayanan }}</td>
                                        <td>{{ $a->nama }}</td>
                                        <td>{{ $a->nik }}</td>
                                        <td>{{ $a->noKK }}</td>
                                        <td>{{ $a->noAkta }}</td>
                                        @if (auth()->user()->roleUser !== 'operatorDesa')
                                        <td>{{ $a->operatorDesa->desa->kecamatan->namaKec ?? '-' }}</td>
                                        <td>{{ $a->operatorDesa->desa->namaDesa ?? '-' }}</td>
                                        @endif
                                        <td>{{ $a->rt ?? '-' }}/
                                            {{ $a->rw ?? '-' }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge 
                                            {{ $a->statAjuan === 'ditolak'
                                                ? 'bg-danger'
                                                : ($a->statAjuan === 'sudah diproses'
                                                    ? 'bg-primary'
                                                    : ($a->statAjuan === 'revisi'
                                                        ? 'bg-warning'
                                                        : ($a->statAjuan === 'selesai'
                                                            ? 'bg-success'
                                                            : 'bg-secondary'))) }}">
                                                {{ $a->statAjuan }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($a->respon)
                                            {{ $a->respon->respon }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action">
                                                <button>
                                                    <a href="{{ route('ajuanCapil.show', $a->idCapil) }}"
                                                        class="text-success" title="Detail">
                                                        <i class="lni lni-eye"></i>
                                                    </a>
                                                </button>
                                                @if($a->deleted_at)
                                                <button>
                                                    <a href="{{ route('ajuanCapil.restore', $a->idCapil) }}" class="text-warning" title="Pulihkan">
                                                        <i class="lni lni-reload"></i>
                                                    </a>
                                                </button>
                                                @endif
                                                @isset($a->finalDokumen->filePath)
                                                <button>
                                                    <a href="{{ asset( $a->finalDokumen->filePath) }}"
                                                        target="_blank" class="text-primary"
                                                        title="Lihat Final Dokumen">
                                                        <i class="lni lni-archive"></i>
                                                    </a>
                                                </button>
                                                @endisset
                                                @if (!empty($a->linkBerkas))
                                                <button>
                                                    <a href="{{ $a->linkBerkas }}" target="_blank"
                                                        class="text-muted" title="Lihat Berkas di GDrive">
                                                        <i class="lni lni-telegram-original"></i>
                                                    </a>
                                                </button>
                                                @endif
                                                @if (Auth::user()->roleUser === 'operatorDesa')
                                                @if ($a->statAjuan === 'dalam antrian')
                                                <a href="{{ route('ajuanCapil.edit', $a->idCapil) }}"
                                                    class="text-warning" title="Edit Ajuan">
                                                    <i class="lni lni lni-pencil"></i>
                                                </a>
                                                <form
                                                    action="{{ route('ajuanCapil.destroy', $a->idCapil) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button onclick="return confirm('Yakin hapus?')"
                                                        class="text-danger" title="Hapus Ajuan"><i
                                                            class="lni lni-trash-can"></i></button>
                                                </form>
                                                @endif
                                                @if ($a->statAjuan === 'ditolak')
                                                <button>
                                                    <a href="{{ route('respon.edit', ['jenis' => 'capil', 'id' => $a->idCapil]) }}"
                                                        class="text-warning" title="Ajukan Ulang">
                                                        <i class="lni lni-reload"></i>
                                                    </a>
                                                </button>
                                                @endif
                                                @if (in_array($a->statAjuan, ['sudah diproses', 'selesai']))
                                                @isset($a->finalDokumen)
                                                <button>
                                                    <a href="{{ route('finalDokumen.edit', ['jenis' => 'capil', 'id' => $a->idCapil]) }}"
                                                        class="text-warning" title="Ubah Dokumen">
                                                        <i class="lni lni-pencil-alt"></i>
                                                    </a>
                                                </button>
                                                @else
                                                <button>
                                                    <a href="{{ route('finalDokumen.create', ['jenis' => 'capil', 'id' => $a->idCapil]) }}"
                                                        class="text-primary" title="Upload Dokumen">
                                                        <i class="lni lni lni-cloud-upload"></i>
                                                    </a>
                                                </button>
                                                @endisset
                                                @endif
                                                <a href="{{ route('ajuan.cetak', ['jenis' => 'capil', 'id' => $a->idCapil]) }}"
                                                    class="text-secondary" title="Bukti Pengajuan"
                                                    target="_blank">
                                                    <i class="lni lni-cog"></i>
                                                </a>
                                                @elseif (Auth::user()->roleUser === 'opDinCapil')
                                                @if ($a->statAjuan === 'dalam antrian')
                                                <a href="{{ route('respon.create', ['jenis' => 'capil', 'id' => $a->idCapil]) }}"
                                                    class="text-primary" title="Beri Respon">
                                                    <i class="lni lni-reply"></i>
                                                </a>
                                                @else
                                                <a href="{{ route('respon.edit', ['jenis' => 'capil', 'id' => $a->idCapil]) }}"
                                                    class="text-warning" title="Ubah Respon">
                                                    <i class="lni lni-pencil-alt"></i>
                                                </a>
                                                @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- end table -->
                        </div>
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- ========== tables-wrapper end ========== -->
    </div>
    <!-- end container -->
</section>
<!-- ========== table components end ========== -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const csrfToken = '{{ csrf_token() }}';
    const roleUser = '{{ Auth::user()->roleUser }}';

    function getBadgeClass(status) {
        switch (status) {
            case 'ditolak':
                return 'bg-danger';
            case 'sudah diproses':
                return 'bg-primary';
            case 'revisi':
                return 'bg-warning';
            case 'selesai':
                return 'bg-success';
            default:
                return 'bg-secondary';
        }
    }

    function renderNote(a) {
        if (a.respon) {
            return `${a.respon.respon ?? ''}`;
        }
        return ''; // opsional, jika tidak ada respon
    }

    function renderDokumenLink(a) {
        if (a.final_dokumen && a.final_dokumen.filePath) {
            return `<a href="${a.final_dokumen.filePath}" target="_blank" class="badge text-primary" title="Lihat Dokumen">Dokumen</a>`;
        }
        return '';
    }

    function renderActions(a) {
        const status = a.statAjuan;
        let html = '';

        html += `<div class="action">`;
        if (a.deleted_at) {
            if (roleUser === 'operatorDesa') {
                html +=
                    `<button><a href="/ajuanCapil/restore/${a.idCapil}" class ="text-success" title="Pulihkan"><i class="lni lni-reload"></i></a></button>`;
            }
        } else {
            html +=
                `<button><a href="/ajuanCapil/${a.idCapil}" class="text-success" title="Detail"><i class="lni lni-eye"></i></a></button>`;
            if (a.final_dokumen && a.final_dokumen.filePath) {
                html +=
                    `<button><a href="${a.final_dokumen.filePath}" target="_blank" class="text-primary" title="Lihat Final Dokumen"><i class="lni lni-archive"></i></a></button>`;
            }
            if (a.linkBerkas && typeof a.linkBerkas === 'string' && a.linkBerkas.trim() !== '') {
                html +=
                    `<button><a href="${a.linkBerkas}" target="_blank" class="text-muted" title="Lihat Berkas di GDrive"><i class="lni lni-telegram-original"></i></a></button>`;
            }
            if (roleUser === 'operatorDesa') {
                if (status === 'dalam antrian') {
                    html +=
                        `<a href="/ajuanCapil/${a.idCapil}/edit" class="text-warning" title="Edit Ajuan"><i class="lni lni-pencil"></i></a>`;
                    html += `<form action="/ajuanCapil/${a.idCapil}" method="POST" style="display:inline;">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button onclick="return confirm('Yakin hapus?')" class="text-danger" title="Hapus Ajuan">
                                <i class="lni lni-trash-can"></i>
                            </button>
                         </form>`;

                }
                if (status === 'ditolak') {
                    html +=
                        `<button><a href="/respon/capil/${a.idCapil}/edit" class="text-success" title="Ajukan Ulang"><i class="lni lni-reload"></i></a><button>`;
                }
                if (['sudah diproses', 'selesai'].includes(status)) {
                    if (a.final_dokumen && a.final_dokumen.filePath) {
                        html +=
                            `<button><a href="/finalDok/capil/${a.idCapil}/edit" class="text-warning" title="Ubah Dokumen"><i class="lni lni-pencil-alt"></i></a></button>`;
                    } else {
                        html +=
                            `<button><a href="/finalDok/capil/${a.idCapil}/create" class="text-primary" title="Upload Dokumen"><i class="lni lni-cloud-upload"></i></a><button>`;
                    }
                }
                html +=
                    `<a href="/cetak-token/capil/${a.idCapil}" class="text-secondary" title="Bukti Pengajuan" target="_blank"><i class="lni lni-cog"></i></a>`;
            } else if (['opDinCapil'].includes(roleUser)) {
                if (status === 'dalam antrian') {
                    html +=
                        `<a href="/respon/capil/${a.idCapil}/create" class="text-primary" title="Beri Respon"><i class="lni lni-reply"></i></a>`;
                } else {
                    html +=
                        `<a href="/respon/capil/${a.idCapil}/edit" class="text-warning" title="Ubah Respon"><i class="lni lni-pencil-alt"></i></a>`;
                }
            }
        }
        html += `</div>`;

        return html;
    }

    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.ajax({
            url: "{{ route('ajuanCapil.filter') }}",
            type: 'GET',
            data: formData,
            success: function(res) {
                let html = '';
                res.data.forEach((a, i) => {
                    const kec = a.operator_desa?.desa?.kecamatan?.namaKec || '-';
                    const desa = a.operator_desa?.desa?.namaDesa || '-';
                    const rt = a.rt || '-';
                    const rw = a.rw || '-';
                    const layanan = a.layanan?.namaLayanan || '-';
                    const status = a.statAjuan || '-';

                    html += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${new Date(a.created_at).toLocaleDateString('id-ID')}</td>
                        <td>${layanan}</td>
                        <td>${a.nama}</td>
                        <td>${a.nik}</td>
                        <td>${a.noKK}</td>
                        <td>${a.noAkta}</td>
                        @if (auth()->user()->roleUser !== 'operatorDesa')
                        <td>${kec}</td>
                        <td>${desa}</td>
                        @endif
                        <td>${rt}/ ${rw}</td>
                        <td><span class="badge ${getBadgeClass(status)}">${status}</span></td>
                        <td>${renderNote(a)}</td>
                        <td>${renderActions(a)}</td>
                    </tr>`;
                });
                $('#tableBody').html(html);
            },
            error: function(err) {
                alert('Gagal memfilter data!');
                console.error(err);
            }
        });
    });

    $('#filterKecamatan').on('change', function() {
        const kecamatanId = $(this).val();
        if (!kecamatanId) {
            $('#filterDesa').html('<option disabled selected>-- Pilih Desa --</option><option value="">Semua</option>');
            return;
        }

        $.ajax({
            url: '/get-desa-by-kecamatan/' + kecamatanId,
            type: 'GET',
            success: function(res) {
                let desaOptions = '<option disabled selected>-- Pilih Desa --</option><option value="">Semua</option>';
                res.forEach(d => {
                    desaOptions += `<option value="${d.idDesa}">${d.namaDesa}</option>`;
                });
                $('#filterDesa').html(desaOptions);
            }
        });
    });
</script>
@endsection