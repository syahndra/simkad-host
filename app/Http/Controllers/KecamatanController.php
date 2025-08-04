<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function index()
    {
        $kecamatan = Kecamatan::all();
        return view('kecamatan.index', compact('kecamatan'));
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
        if ($request->data === 'terhapus') {
            $query = Kecamatan::onlyTrashed();
        } else {
            $query = Kecamatan::withoutTrashed();
        }

        $result = $query->get();

        return response()->json([
            'data' => $result
        ]);
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
