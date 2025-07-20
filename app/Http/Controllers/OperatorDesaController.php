<?php

namespace App\Http\Controllers;

use App\Models\OperatorDesa;
use App\Models\User;
use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OperatorDesaController extends Controller
{
    public function index()
    {
        $data = OperatorDesa::with(['user', 'desa.kecamatan'])->get();
        return view('operatorDesa.index', compact('data'));
    }

    public function create()
    {
        $kecamatan = Kecamatan::whereIn('idKec', Desa::select('idKec'))->get();
        return view('operatorDesa.create', compact('kecamatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'idDesa' => 'required|exists:desa,idDesa'
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roleUser' => 'operatorDesa',
        ]);

        OperatorDesa::create([
            'idUser' => $user->idUser,
            'idDesa' => $request->idDesa
        ]);

        return redirect()->route('operatorDesa.index')->with('success', 'Operator Desa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $op = OperatorDesa::with('user', 'desa')->findOrFail($id);
        $idKec = $op->desa->idKec;
        $daftarDesa = Desa::where('idKec', $idKec)->get();
        $kecamatan = Kecamatan::whereIn('idKec', Desa::select('idKec'))->get();
        return view('operatorDesa.edit', compact('op', 'daftarDesa', 'kecamatan'));
    }

    public function update(Request $request, $id)
    {
        $op = OperatorDesa::with('user')->findOrFail($id);

        $request->validate([
            'nama' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $op->user->idUser . ',idUser',
            'idDesa' => 'required|exists:desa,idDesa'
        ]);

        $op->user->update([
            'nama' => $request->nama,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $op->user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        $op->update([
            'idDesa' => $request->idDesa
        ]);

        return redirect()->route('operatorDesa.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $op = OperatorDesa::with('user')->findOrFail($id);
        $op->user->delete();
        $op->delete();

        return redirect()->route('operatorDesa.index')->with('success', 'Data berhasil dihapus.');
    }

    public function getDesaByKecamatan($idKec)
    {
        $desa = Desa::where('idKec', $idKec)->get(['idDesa', 'namaDesa']);
        return response()->json($desa);
    }
}
