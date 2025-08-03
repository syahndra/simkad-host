<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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

        // Simpan user baru
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roleUser' => $request->roleUser,
        ]);

        // Kirim email verifikasi
        $token = base64_encode($user->email . '|' . now());
        $verificationUrl = route('verification.custom', ['token' => $token]);

        Mail::send('emails.custom_verification', [
            'nama' => $user->nama,
            'verificationUrl' => $verificationUrl
        ], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Verifikasi Email Anda');
        });

        try {
            return redirect()->route('operatorDinas.index')
                ->with('success', 'Operator Dinas berhasil ditambahkan dan email verifikasi telah dikirim.');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
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

        $digunakan = $opdinas->respon()->exists();

        if ($digunakan) {
            $opdinas->delete(); // soft delete
            return redirect()->route('operatorDinas.index')->with('success', 'Data operator Dinas masih digunakan, jadi hanya disembunyikan dari daftar.');
        } else {
            $opdinas->forceDelete(); // hard delete
            return redirect()->route('operatorDinas.index')->with('success', 'Data operator Dinas berhasil dihapus.');
        }
    }
}
