<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $layanan = Layanan::all();
        return view('layanan.index', compact('layanan'));
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
}
