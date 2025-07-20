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

                            <a href="{{ route('desa.create') }}" class="btn btn-success mb-3">Tambah +</a>
                            <div class="table-responsive">
                                <table id="table" class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama Desa</th>
                                            <th>Kecamatan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($desa as $item)
                                            <tr>
                                                <td>{{ $item->idDesa }}</td>
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
@endsection