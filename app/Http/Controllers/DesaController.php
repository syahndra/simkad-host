<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DesaController extends Controller
{
    public function index()
    {
        return view('desa.index');
    }

    public function create()
    {
        $kecamatan = Kecamatan::orderBy('idKec')->get();
        return view('desa.create', compact('kecamatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'namaDesa' => 'required|string|max:100',
            'idKec' => 'required|exists:kecamatan,idKec',
        ]);

        Desa::create($request->all());

        return redirect()->route('desa.index')->with('success', 'Desa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $desa = Desa::findOrFail($id);
        $kecamatan = Kecamatan::all();
        return view('desa.edit', compact('desa', 'kecamatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'namaDesa' => 'required|string|max:100',
            'idKec' => 'required|exists:kecamatan,idKec',
        ]);

        $desa = Desa::findOrFail($id);
        $desa->update($request->all());

        return redirect()->route('desa.index')->with('success', 'Desa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $desa = Desa::findOrFail($id);

        $digunakan = $desa->operatorDesa()->exists();

        if ($digunakan) {
            $desa->delete();
            return redirect()->route('desa.index')->with('success', 'Data desa masih digunakan, jadi hanya disembunyikan dari daftar.');
        } else {
            $desa->forceDelete();
            return redirect()->route('desa.index')->with('success', 'Data desa berhasil dihapus.');
        }
    }

    public function filter(Request $request)
    {
        $query = Desa::with('kecamatan');

        if ($request->data === 'terhapus') {
            $query->onlyTrashed();
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_kecamatan', function ($item) {
                return $item->kecamatan ? $item->kecamatan->namaKec : '-';
            })
            ->addColumn('aksi', function ($item) {
                if ($item->deleted_at) {
                    return '<a href="/desa/restore/' . $item->idDesa . '" class="text-success"><i class="lni lni-reload"></i></a>';
                } else {
                    return '
                    <a href="/desa/' . $item->idDesa . '/edit" class="text-warning"><i class="lni lni-pencil"></i></a>
                    <form action="/desa/' . $item->idDesa . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" onclick="return confirm(\'Yakin hapus?\')" 
                            class="border-0 bg-transparent text-danger p-0" title="Hapus">
                            <i class="lni lni-trash-can"></i>
                        </button>
                    </form>';
                }
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function restore($id)
    {
        $desa = Desa::withTrashed()->where('idDesa', $id)->first();

        if ($desa) {
            $desa->restore();
        }

        return redirect()->route('desa.index')->with('success', 'Data desa berhasil dipulihkan.');
    }
}
