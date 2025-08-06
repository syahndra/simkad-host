<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OperatorKec;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

class OperatorKecController extends Controller
{
    public function index()
    {
        return view('operatorKec.index');
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

        return redirect()->route('operatorKec.index')->with('success', 'Operator Kecamatan berhasil ditambahkan dan link verifikasi telah dikirim.');
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
        $user = $operatorKec->user;

        if ($user->respon()->exists()) {
            $user->delete(); // soft delete
            $operatorKec->delete();

            return redirect()->route('operatorKec.index')->with('success', 'Data operator kecamatan masih digunakan, jadi hanya disembunyikan dari daftar.');
        } else {
            $user->forceDelete(); // hard delete
            $operatorKec->forceDelete();

            return redirect()->route('operatorKec.index')->with('success', 'Data operator kecamatan berhasil dihapus.');
        }
    }

    public function filter(Request $request)
    {
        $query = OperatorKec::with(['user', 'kecamatan']);

        if ($request->data === 'terhapus') {
            $query->onlyTrashed();
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama', fn($op) => $op->user->nama ?? '-')
            ->addColumn('email', fn($op) => $op->user->email ?? '-')
            ->addColumn('verifikasi', function ($op) {
                return $op->user && $op->user->email_verified_at
                    ? '<span class="badge bg-success">Terverifikasi</span>'
                    : '<span class="badge bg-danger">Belum</span>';
            })
            ->addColumn('kecamatan', fn($op) => $op->kecamatan->namaKec ?? '-')
            ->addColumn('aksi', function ($op) {
                $html = '<div class="action">';
                if ($op->deleted_at) {
                    $html .= '<a href="/operatorKec/restore/' . $op->idOpkec . '" class="text-success" title="Pulihkan"><i class="lni lni-reload"></i></a>';
                } else {
                    $html .= '
                    <a href="/operatorKec/' . $op->idOpkec . '/edit" class="text-warning" title="Edit"><i class="lni lni-pencil"></i></a>
                    <form action="/operatorKec/' . $op->idOpkec . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" onclick="return confirm(\'Yakin hapus?\')" class="border-0 bg-transparent text-danger p-0" title="Hapus">
                            <i class="lni lni-trash-can"></i>
                        </button>
                    </form>';

                    if (!$op->user || !$op->user->email_verified_at) {
                        $html .= '
                        <form action="/resend-verification/' . $op->user->idUser . '" method="POST" style="display:inline;">
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
            ->rawColumns(['verifikasi', 'aksi'])
            ->make(true);
    }

    public function restore($id)
    {
        $op = OperatorKec::withTrashed()->where('idOpkec', $id)->firstOrFail();
        $user = User::withTrashed()->where('idUser', $op->idUser)->first();

        $op->restore();

        if ($user) {
            $user->restore();
        }

        return redirect()->route('operatorKec.index')->with('success', 'Data operator kecamatan berhasil dipulihkan.');
    }
}
