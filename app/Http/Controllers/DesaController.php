<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class DesaController extends Controller
{
    public function index()
    {
        $desa = Desa::with('kecamatan')->get();
        return view('desa.index', compact('desa'));
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
        $desa->delete();
        return redirect()->route('desa.index')->with('success', 'Desa berhasil dihapus.');
    }
}

