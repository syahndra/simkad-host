@extends('layouts.app', ['title' => 'Daftar Operator Desa', 'menu' => 'user'])
@section('content')
<!-- ========== table components start ========== -->
<section class="table-components">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title">
                        <h2>Daftar Operator Desa</h2>
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
                                <li class="breadcrumb-item"><a>Manajemen User</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Daftar Operator Desa
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
                            <a href="{{ route('operatorDesa.create') }}" class="btn btn-success mb-3">Tambah +</a>
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
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Email Terverifikasi</th>
                                        <th>Desa</th>
                                        <th>Kecamatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @foreach ($data as $i => $op)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $op->user->nama }}</td>
                                        <td>{{ $op->user->email }}</td>
                                        <td>
                                            @if ($op->user->email_verified_at)
                                            <span class="badge bg-success">Terverifikasi</span>
                                            @else
                                            <span class="badge bg-danger">Belum</span>
                                            @endif
                                        </td>
                                        <td>{{ $op->desa->namaDesa }}</td>
                                        <td>{{ $op->desa->kecamatan->namaKec }}</td>
                                        <td>
                                            <div class="action">
                                                <a href="{{ route('operatorDesa.edit', $op->idOpdes) }}"
                                                    class="text-warning">
                                                    <i class="lni lni lni-pencil"></i>
                                                </a>
                                                <form action="{{ route('operatorDesa.destroy', $op->idOpdes) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button onclick="return confirm('Yakin hapus?')"
                                                        class="text-danger"><i
                                                            class="lni lni-trash-can"></i></button>
                                                </form>
                                                @if (!$op->user->email_verified_at)
                                                <form action="{{ route('verification.resend', $op->user->idUser) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="text-info" title="Kirim Ulang Verifikasi"><i class="lni lni-envelope"></i></button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <!-- end table row -->
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

    function renderActions(a) {
        let html = '';

        html += `<div class="action">`;
        if (a.deleted_at) {
            html +=
                `<button><a href="/operatorDesa/restore/${a.idOpdes}" class ="text-success" title="Pulihkan"><i class="lni lni-reload"></i></a></button>`;
        } else {
            html += `<a href="/operatorDesa/${a.idOpdes}/edit" class="text-warning" title="Edit Ajuan">
                <i class="lni lni-pencil"></i>
            </a>`;

            html += `<form action="/operatorDesa/${a.idOpdes}" method="POST" style="display:inline;">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
                <button onclick="return confirm('Yakin hapus?')" class="text-danger" title="Hapus Ajuan">
                    <i class="lni lni-trash-can"></i>
                </button>
            </form>`;

            if (!a.user?.email_verified_at) {
                html += `<form action="/resend-verification/${a.user.idUser}" method="POST" style="display:inline;">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <button type="submit" class="text-info" title="Kirim Ulang Verifikasi">
                        <i class="lni lni-envelope"></i>
                    </button>
                </form>`;
            }
        }

        html += `</div>`;

        return html;
    }

    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        // console.log('Form data:', formData);
        $.ajax({
            url: "{{ route('operatorDesa.filter') }}",
            type: 'GET',
            data: formData,
            success: function(res) {
                let html = '';
                res.data.forEach((a, i) => {
                    html += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${a.user.nama}</td>
                        <td>${a.user.email}</td>
                        <td>
                            ${a.user.email_verified_at 
                                ? '<span class="badge bg-success">Terverifikasi</span>' 
                                : '<span class="badge bg-danger">Belum</span>'}
                        </td>
                        <td>${a.desa.namaDesa}</td>
                        <td>${a.desa.kecamatan.namaKec}</td>
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