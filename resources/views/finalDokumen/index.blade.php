@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('finalDokumen.create') }}" class="btn btn-primary mb-3">Upload Dokumen</a>
    <table class="table">
        <thead>
            <tr>
                <th>Jenis</th>
                <th>ID Ajuan</th>
                <th>Nama File</th>
                <th>Dokumen</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dokumen as $d)
                <tr>
                    <td>{{ ucfirst($d->jenis) }}</td>
                    <td>{{ $d->idAjuan }}</td>
                    <td>{{ $d->filename }}</td>
                    <td><a href="{{ asset('storage/' . $d->filePath) }}" target="_blank">Lihat</a></td>
                    <td>
                        <form action="{{ route('finalDokumen.destroy', $d->idFinDok) }}" method="POST">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Yakin hapus dokumen ini?')" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
