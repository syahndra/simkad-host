@extends('layouts.app', ['title' => 'Daftar Desa', 'menu' => 'wilayah'])
@section('content')
<!-- ========== table components start ========== -->
<section class="table-components">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title">
                        <h2>Daftar Desa</h2>
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
                                <li class="breadcrumb-item"><a>Kategori Wilayah</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Daftar Desa
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
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <div class="d-flex gap-2 align-items-center mb-3">
                            <a href="{{ route('desa.create') }}" class="btn btn-success mb-3">Tambah +</a>
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
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table id="table" class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Desa</th>
                                        <th>Kecamatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @foreach ($desa as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->namaDesa }}</td>
                                        <td>{{ $item->kecamatan->namaKec }}</td>
                                        <td>
                                            <div class="action">
                                                <a href="{{ route('desa.edit', $item->idDesa) }}"
                                                    class="text-warning">
                                                    <i class="lni lni lni-pencil"></i>
                                                </a>
                                                <form action="{{ route('desa.destroy', $item->idDesa) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button onclick="return confirm('Yakin hapus?')"
                                                        class="text-danger"><i class="lni lni-trash-can"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

    function renderActions(a) {
        let html = '';

        html += `<div class="action">`;
        if (a.deleted_at) {
            html +=
                `<button><a href="/desa/restore/${a.idDesa}" class ="text-success" title="Pulihkan"><i class="lni lni-reload"></i></a></button>`;
        } else {
            html += `<a href="/desa/${a.idDesa}/edit" class="text-warning" title="Edit Ajuan">
                <i class="lni lni-pencil"></i>
            </a>`;

            html += `<form action="/desa/${a.idDesa}" method="POST" style="display:inline;">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
                <button onclick="return confirm('Yakin hapus?')" class="text-danger" title="Hapus Ajuan">
                    <i class="lni lni-trash-can"></i>
                </button>
            </form>`;
        }

        html += `</div>`;

        return html;
    }

    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        console.log('Form data:', formData);
        $.ajax({
            url: "{{ route('desa.filter') }}",
            type: 'GET',
            data: formData,
            success: function(res) {
                let html = '';
                res.data.forEach((a, i) => {
                    html += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${a.namaDesa}</td>
                        <td>${a.kecamatan.namaKec}</td>
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
</script>

@endsection