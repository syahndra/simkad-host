<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KecamatanController extends Controller
{
    public function index()
    {
        return view('kecamatan.index');
    }

    public function create()
    {
        return view('kecamatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'namaKec' => 'required|string|max:100'
        ]);

        Kecamatan::create([
            'namaKec' => $request->namaKec
        ]);

        return redirect()->route('kecamatan.index')->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    public function edit(Kecamatan $kecamatan)
    {
        return view('kecamatan.edit', compact('kecamatan'));
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $request->validate([
            'namaKec' => 'required|string|max:100'
        ]);

        $kecamatan->update([
            'namaKec' => $request->namaKec
        ]);

        return redirect()->route('kecamatan.index')->with('success', 'Kecamatan berhasil diupdate.');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        $digunakan = $kecamatan->desa()->exists() || $kecamatan->operatorKec()->exists();

        if ($digunakan) {
            $kecamatan->delete();
            return redirect()->route('kecamatan.index')->with('success', 'Data kecamatan masih digunakan, jadi hanya disembunyikan dari daftar.');
        } else {
            $kecamatan->forceDelete();
            return redirect()->route('kecamatan.index')->with('success', 'Data kecamatan berhasil dihapus.');
        }
    }

    public function filter(Request $request)
    {
        $query = Kecamatan::query();

        if ($request->data === 'terhapus') {
            $query->onlyTrashed();
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($item) {
                $html = '<div class="action" style="display: flex; gap: 6px; overflow-x: auto;">';
                if ($item->deleted_at) {
                    $html .= '<a href="/kecamatan/restore/' . $item->idKec . '" class="text-success"><i class="lni lni-reload"></i></a>';
                } else {
                    $html .= '
                    <a href="/kecamatan/' . $item->idKec . '/edit" class="text-warning"><i class="lni lni-pencil"></i></a>
                    <form action="/kecamatan/' . $item->idKec . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" onclick="return confirm(\'Yakin hapus?\')" 
                            class="border-0 bg-transparent text-danger p-0" title="Hapus">
                            <i class="lni lni-trash-can"></i>
                        </button>
                    </form>';
                }
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function restore($id)
    {
        $kecamatan = Kecamatan::withTrashed()->where('idKec', $id)->first();

        if ($kecamatan) {
            $kecamatan->restore();
        }

        return redirect()->route('kecamatan.index')->with('success', 'Data kecamatan berhasil dipulihkan.');
    }
}
