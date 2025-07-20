@extends('layouts.app', ['title' => 'Daftar Admin', 'menu' => 'user'])
@section('content')
<!-- ========== table components start ========== -->
<section class="table-components">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title">
                        <h2>Daftar Admin</h2>
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
                                    Daftar Admin
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
                        {{-- <h6 class="mb-10">Tabel Kecamatan</h6> --}}
                        {{-- <p class="text-sm mb-20">
                                For basic styling—light padding and only horizontal
                                dividers—use the class table.
                            </p> --}}
                        @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <a href="{{ route('admin.create') }}" class="btn btn-success mb-3">Tambah +</a>
                        <div class="table-responsive">
                            <table id="table" class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role User</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($admins as $admin)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $admin->nama }}</td>
                                        <td>{{ $admin->email }}</td>
                                        <td>{{ $admin->roleUser }}</td>
                                        <td>
                                            <div class="action">
                                                <a href="{{ route('admin.edit', $admin->idUser) }}"
                                                    class="text-warning">
                                                    <i class="lni lni lni-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.destroy', $admin->idUser) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button onclick="return confirm('Yakin hapus?')"
                                                        class="text-danger"><i class="lni lni-trash-can"></i></button>
                                                </form>
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
@endsection