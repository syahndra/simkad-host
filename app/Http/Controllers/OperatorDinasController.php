<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OperatorDinasController extends Controller
{
    public function index()
    {
        $data = User::whereIn('roleUser', ['opDinCapil', 'opDinDafduk'])->get();
        return view('operatorDinas.index', compact('data'));
    }

    public function create()
    {
        return view('operatorDinas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'roleUser' => 'required|in:opDinDafduk,opDinCapil',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roleUser' => $request->roleUser,
        ]);

        return redirect()->route('operatorDinas.index')->with('success', 'Operator Dinas berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $opdinas = User::findOrFail($id);
        return view('operatorDinas.edit', compact('opdinas'));
    }

    public function update(Request $request, $id)
    {
        $opdinas = User::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email,' . $id . ',idUser',
            'password' => 'nullable|confirmed|min:6',
            'roleUser' => 'required|in:opDinDafduk,opDinCapil',
        ]);

        $opdinas->nama = $request->nama;
        $opdinas->email = $request->email;
        $opdinas->roleUser = $request->roleUser;
        if ($request->filled('password')) {
            $opdinas->password = Hash::make($request->password);
        }
        $opdinas->save();

        return redirect()->route('operatorDinas.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $opdinas = User::findOrFail($id);
        $opdinas->delete();

        return redirect()->route('operatorDinas.index')->with('success', 'Operator Dinas dihapus.');
    }
}
