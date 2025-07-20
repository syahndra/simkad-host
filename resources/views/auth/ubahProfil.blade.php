@extends('layouts.app', ['title' => 'Ubah Profil', 'menu' => 'profil'])

@section('content')
    <section class="tab-components">
        <div class="container-fluid">
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Ubah Profil</h2>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="breadcrumb-wrapper">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Ubah Profil</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-elements-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-style mb-30" style="min-height: 550px; overflow: auto;">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

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

                            <form method="POST" action="{{ route('profil.update') }}">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="idUser" value="{{ $user->idUser }}">
                                <div class="mb-3">
                                    <label>nama</label>
                                    <input type="text" name="nama" class="form-control"
                                        value="{{ old('nama', $user->nama) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $user->email) }}" required>
                                </div>

                                <div class="mb-3">
                                    <button type="button" class="btn btn-warning" onclick="togglePasswordForm()">Ganti
                                        Password</button>
                                </div>

                                <div id="passwordFields" style="display: none;">
                                    <hr>
                                    <div class="mb-3">
                                        <label>Password Lama <small class="text-danger">*</small></label>
                                        <input type="password" name="current_password" class="form-control"
                                            placeholder="Masukkan password saat ini">
                                    </div>

                                    <div class="mb-3">
                                        <label>Password Baru</label>
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Masukkan password baru">
                                    </div>

                                    <div class="mb-3">
                                        <label>Konfirmasi Password Baru</label>
                                        <input type="password" name="password_confirmation" class="form-control"
                                            placeholder="Ulangi password baru">
                                    </div>
                                </div>
                                <a href="{{ route('ajuanDafduk.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
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
        function togglePasswordForm() {
            const form = document.getElementById('passwordFields');
            const isVisible = form.style.display === 'block';

            form.style.display = isVisible ? 'none' : 'block';
        }
    </script>
@endsection
