@extends('layouts.app', ['title' => 'Daftar Operator Kecamatan', 'menu' => 'user'])
@section('content')
    <!-- ========== table components start ========== -->
    <section class="table-components">
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Daftar Operator Kecamatan</h2>
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
                                        Daftar Operator Kecamatan
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
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <a href="{{ route('operatorKec.create') }}" class="btn btn-success mb-3">Tambah +</a>
                            <div class="table-responsive">
                                <table id="table" class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Kecamatan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($operatorKec as $i => $op)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $op->user->nama }}</td>
                                                <td>{{ $op->user->email }}</td>
                                                <td>{{ $op->kecamatan->namaKec }}</td>
                                                <td>
                                                    <div class="action">
                                                        <a href="{{ route('operatorKec.edit', $op->idOpkec) }}"
                                                            class="text-warning">
                                                            <i class="lni lni lni-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('operatorKec.destroy', $op->idOpkec) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf @method('DELETE')
                                                            <button onclick="return confirm('Yakin hapus?')"
                                                                class="text-danger"><i
                                                                    class="lni lni-trash-can"></i></button>
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
