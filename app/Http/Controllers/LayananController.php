<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LayananController extends Controller
{
    public function index()
    {
        return view('layanan.index');
    }

    public function create()
    {
        return view('layanan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'namaLayanan' => 'required|string|max:100',
            'jenis' => 'required|in:dafduk,capil',
            'aksesVer' => 'required|in:dinasDafduk,dinasCapil,kecamatan',
        ]);

        Layanan::create($request->all());
        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = Layanan::findOrFail($id);
        return view('layanan.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'namaLayanan' => 'required|string|max:100',
            'jenis' => 'required|in:dafduk,capil',
            'aksesVer' => 'required|in:dinasDafduk,dinasCapil,kecamatan',
        ]);

        $data = Layanan::findOrFail($id);
        $data->update($request->all());
        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $data = Layanan::findOrFail($id);

        // Cek apakah ada data relasi
        $digunakan = $data->ajuanDafduk()->exists() || $data->ajuanCapil()->exists();

        if ($digunakan) {
            // Soft delete
            $data->delete();
            return redirect()->route('layanan.index')->with('success', 'Data layanan masih digunakan, jadi hanya disembunyikan dari daftar.');
        } else {
            // Hard delete permanen
            $data->forceDelete();
            return redirect()->route('layanan.index')->with('success', 'Data layanan berhasil dihapus.');
        }
    }

    public function filter(Request $request)
    {
        $query = Layanan::query();

        if ($request->data === 'terhapus') {
            $query->onlyTrashed();
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($item) {
                $html = '<div class="action" style="display: flex; gap: 6px; overflow-x: auto;">';
                if ($item->deleted_at) {
                    $html .=  '<a href="/layanan/restore/' . $item->idLayanan . '" class="text-success"><i class="lni lni-reload"></i></a>';
                } else {
                    $html .=  '
                        <a href="/layanan/' . $item->idLayanan . '/edit" class="text-warning"><i class="lni lni-pencil"></i></a>
                        <form action="/layanan/' . $item->idLayanan . '" method="POST" style="display:inline;">
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
        $layanan = Layanan::withTrashed()->where('idLayanan', $id)->first();

        if ($layanan) {
            $layanan->restore();
        }

        return redirect()->route('layanan.index')->with('success', 'Data layanan berhasil dipulihkan.');
    }
}
