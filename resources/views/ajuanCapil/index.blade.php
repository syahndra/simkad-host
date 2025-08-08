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
                                    <option value="" disabled selected>-- Pilih Layanan --</option>
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
                            <input type="hidden" name="role" value="verif">
                            <input type="hidden" name="rw" value="">
                            <input type="hidden" name="rt" value="">
                            <div class="col-md-3">
                                <select class="form-control" name="kecamatan" id="filterKecamatan">
                                    <option value="" selected disabled>-- Pilih Kecamatan --</option>
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
                            <select name="kecamatan" hidden id="filterKecamatan">
                                <option value="{{ $idKec }}" selected>{{ $namaKecamatan }}</option>
                            </select>
                            <select name="desa" hidden id="filterDesa">
                                <option value="{{ $idDesa }}" selected>{{ $namaDesa }}</option>
                            </select>
                            <input type="hidden" name="role" value="operatorDesa">
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
                            <table id="table" class="table  w-100">
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
                                <tbody id="tableBody"></tbody>
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- <script>
    let table = $('#table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('ajuanCapil.filter') }}",
            data: function(d) {
                d.data = $('select[name="data"]').val();
                d.startDate = $('input[name="startDate"]').val();
                d.endDate = $('input[name="endDate"]').val();
                d.layanan = $('select[name="layanan"]').val();
                d.status = $('select[name="status"]').val();
                d.role = $('input[name="role"]').val();
                d.rw = $('input[name="rw"]').val();
                d.rt = $('input[name="rt"]').val();

            }
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'tanggal',
                name: 'ajuan.tanggal'
            },
            {
                data: 'layanan',
                name: 'layanan.namaLayanan'
            },
            {
                data: 'nama',
                name: 'ajuan.nama'
            },
            {
                data: 'nik',
                name: 'ajuan.nik'
            },
            {
                data: 'noKK',
                name: 'ajuan.noKK'
            },
            {
                data: 'noAkta',
                name: 'ajuan.noAkta'
            },
            {
                data: 'kecamatan',
                name: 'operatorDesa.desa.kecamatan.namaKec',
                orderable: false,
                searchable: false
            }, {
                data: 'desa',
                name: 'desa.namaDesa',
                orderable: false,
                searchable: false
            },
            {
                data: null, // karena kita ambil lebih dari 1 field
                name: 'rt_rw', // name ini bebas, karena tidak dipakai untuk sorting/pencarian
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    let rt = row.rt ?? '-';
                    let rw = row.rw ?? '-';
                    return `${rt}/${rw}`;
                }
            },
            {
                data: 'statAjuan',
                name: 'statAjuan',
            },
            {
                data: 'respon',
                name: 'respon',
                orderable: false,
                searchable: true // atau false jika tidak ingin bisa dicari
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }

        ]
    });
    // if (role !== 'operatorDesa') {
    //     columns.push({
    //         data: 'kecamatan',
    //         name: 'operatorDesa.desa.kecamatan.namaKec',
    //         orderable: false,
    //         searchable: false
    //     }, {
    //         data: 'desa',
    //         name: 'desa.namaDesa',
    //         orderable: false,
    //         searchable: false
    //     });
    // }
    // columns = columns.concat([{
    //         data: null, // karena kita ambil lebih dari 1 field
    //         name: 'rt_rw', // name ini bebas, karena tidak dipakai untuk sorting/pencarian
    //         orderable: false,
    //         searchable: false,
    //         render: function(data, type, row) {
    //             let rt = row.rt ?? '-';
    //             let rw = row.rw ?? '-';
    //             return `${rt}/${rw}`;
    //         }
    //     },
    //     {
    //         data: 'statAjuan',
    //         name: 'statAjuan',
    //     },
    //     {
    //         data: 'respon',
    //         name: 'respon',
    //         orderable: false,
    //         searchable: true // atau false jika tidak ingin bisa dicari
    //     },
    //     {
    //         data: 'action',
    //         name: 'action',
    //         orderable: false,
    //         searchable: false
    //     }
    // ]);

    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
</script> -->
<script>
    let role = $('input[name="role"]').val(); // ambil nilai role lebih awal

    let columns = [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },
        {
            data: 'tanggal',
            name: 'ajuan.tanggal',
            orderable: false,
            searchable: false
        },
        {
            data: 'layanan',
            name: 'layanan.namaLayanan',
            orderable: true,
            searchable: true
        },
        {
            data: 'nama',
            name: 'ajuancapil.nama',
            orderable: true,
            searchable: true
        },
        {
            data: 'nik',
            name: 'ajuancapil.nik',
            orderable: true,
            searchable: true

        },
        {
            data: 'noKK',
            name: 'ajuancapil.noKK',
            orderable: true,
            searchable: true
        },
        {
            data: 'noAkta',
            name: 'ajuancapil.noAkta',
            orderable: true,
            searchable: true
        }
    ];

    if (role !== 'operatorDesa') {
        columns.push({
            data: 'kecamatan',
            name: 'operatorDesa.desa.kecamatan.namaKec',
            orderable: true,
            searchable: true
        }, {
            data: 'desa',
            name: 'operatorDesa.desa.namaDesa',
            orderable: true,
            searchable: true
        });
    }

    columns = columns.concat([{
            data: null,
            name: 'rt_rw',
            orderable: false,
            searchable: false,
            render: function(data, type, row) {
                let rt = row.rt ?? '-';
                let rw = row.rw ?? '-';
                return `${rt}/${rw}`;
            }
        },
        {
            data: 'statAjuan',
            name: 'ajuancapil.statAjuan',
            orderable: true,
            searchable: true
        },
        {
            data: 'respon',
            name: 'respon',
            orderable: true,
            searchable: true
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        }
    ]);

    let table = $('#table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('ajuanCapil.filter') }}",
            data: function(d) {
                d.data = $('select[name="data"]').val();
                d.startDate = $('input[name="startDate"]').val();
                d.endDate = $('input[name="endDate"]').val();
                d.layanan = $('select[name="layanan"]').val();
                d.status = $('select[name="status"]').val();
                d.role = $('input[name="role"]').val();
                d.rw = $('input[name="rw"]').val();
                d.rt = $('input[name="rt"]').val();
                d.kecamatan = $('select[name="kecamatan"]').val();
                d.desa = $('select[name="desa"]').val();
                
            }
        },
        columns: columns
    });

    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        table.ajax.reload();
    });
</script>
<script>
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