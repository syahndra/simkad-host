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
        $kecamatan->delete();
        return redirect()->route('kecamatan.index')->with('success', 'Kecamatan berhasil dihapus.');
    }
}
