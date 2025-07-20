@extends('layouts.app', ['title' => 'Form Tambah Operator Dinas', 'menu' => 'user'])
@section('content')
    <section class="tab-components">
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Form Tambah Operator Dinas</h2>
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
                                    <li class="breadcrumb-item"><a>Manajemen Operator Dinas</a></li>
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
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Terjadi kesalahan!</strong>
                                    <ul class="mb-0 mt-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <form action="{{ route('operatorDinas.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label>Nama</label>
                                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label>Password</label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="password" class="form-control" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('password')">
                                            👁️
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label>Konfirmasi Password</label>
                                    <div class="input-group">
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control" required>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePassword('password_confirmation')">
                                            👁️
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label>Bidang</label>
                                    <select name="roleUser" class="form-control" required>
                                        <option disabled {{ old('roleUser') ? '' : 'selected' }}>-- Pilih Bidang --</option>
                                        <option value="opDinDafduk"
                                            {{ old('roleUser') == 'opDinDafduk' ? 'selected' : '' }}>Dafduk</option>
                                        <option value="opDinCapil" {{ old('roleUser') == 'opDinCapil' ? 'selected' : '' }}>
                                            Capil</option>
                                    </select>
                                </div>

                                <a href="{{ route('operatorDinas.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
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
@section('scripts')
    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
@endsection
