<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OperatorKec;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OperatorKecController extends Controller
{
    public function index()
    {
        $operatorKec = OperatorKec::with(['user', 'kecamatan'])->get();
        return view('operatorKec.index', compact('operatorKec'));
    }

    public function create()
    {
        $kecamatan = Kecamatan::orderBy('idKec')->get();
        return view('operatorKec.create', compact('kecamatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'nullable|confirmed|min:6',
            'idKec' => 'required|exists:kecamatan,idKec',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roleUser' => 'operatorKecamatan',
        ]);

        OperatorKec::create([
            'idUser' => $user->idUser,
            'idKec' => $request->idKec,
        ]);

        return redirect()->route('operatorKec.index')->with('success', 'Operator Kecamatan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $operatorKec = OperatorKec::with('user')->findOrFail($id);
        $kecamatan = Kecamatan::orderBy('idKec')->get();
        return view('operatorKec.edit', compact('operatorKec', 'kecamatan'));
    }

    public function update(Request $request, $id)
    {
        $operatorKec = OperatorKec::findOrFail($id);
        $user = $operatorKec->user;

        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->idUser . ',idUser',
            'password' => 'nullable|confirmed|min:6',
            'idKec' => 'required|exists:kecamatan,idKec',
        ]);

        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $operatorKec->update([
            'idKec' => $request->idKec,
        ]);

        return redirect()->route('operatorKec.index')->with('success', 'Data operator diperbarui.');
    }

    public function destroy($id)
    {
        $operatorKec = OperatorKec::findOrFail($id);
        $operatorKec->user()->delete(); // Hapus user otomatis
        $operatorKec->delete();

        return redirect()->route('operatorKec.index')->with('success', 'Data operator dihapus.');
    }
}
