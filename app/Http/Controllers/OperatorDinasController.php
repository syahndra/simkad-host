<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

class OperatorDinasController extends Controller
{
    public function index()
    {
        return view('operatorDinas.index');
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

    public function filter(Request $request)
    {
        $query = User::whereIn('roleUser', ['opDinCapil', 'opDinDafduk']);

        if ($request->data === 'terhapus') {
            $query->onlyTrashed();
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('email_verified', function ($item) {
                return $item->email_verified_at
                    ? '<span class="badge bg-success">Terverifikasi</span>'
                    : '<span class="badge bg-danger">Belum</span>';
            })
            ->addColumn('bidang', function ($item) {
                return match ($item->roleUser) {
                    'opDinCapil' => 'Capil',
                    'opDinDafduk' => 'Dafduk',
                    default => ucfirst($item->roleUser),
                };
            })
            ->addColumn('aksi', function ($item) {
                $html = '<div class="action">';
                if ($item->deleted_at) {
                    $html .= '<a href="/operatorDinas/restore/' . $item->idUser . '" class="text-success" title="Pulihkan"><i class="lni lni-reload"></i></a>';
                } else {
                    $html .= '
                    <a href="/operatorDinas/' . $item->idUser . '/edit" class="text-warning" title="Edit"><i class="lni lni-pencil"></i></a>
                    <form action="/operatorDinas/' . $item->idUser . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" onclick="return confirm(\'Yakin hapus?\')" class="border-0 bg-transparent text-danger p-0" title="Hapus">
                            <i class="lni lni-trash-can"></i>
                        </button>
                    </form>';
                    if (!$item->email_verified_at) {
                        $html .= '
                    <form action="/resend-verification/' . $item->idUser . '" method="POST" style="display:inline;">
                        ' . csrf_field() . '
                        <button type="submit" class="border-0 bg-transparent text-info p-0" title="Kirim Ulang Verifikasi">
                            <i class="lni lni-envelope"></i>
                        </button>
                    </form>';
                    }
                }
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['email_verified', 'aksi'])
            ->make(true);
    }

    public function restore($id)
    {
        $user = User::withTrashed()->where('idUser', $id)->first();

        if ($user) {
            $user->restore();
        }

        return redirect()->route('operatorDinas.index')->with('success', 'Data operator kecamatan berhasil dipulihkan.');
    }
}
