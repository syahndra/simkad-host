<?php

namespace App\Http\Controllers;

use App\Models\OperatorDesa;
use App\Models\User;
use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\OperatorKec;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

class OperatorDesaController extends Controller
{
    public function index()
    {
        $opkec = OperatorKec::where('idUser', Auth::user()->idUser)->first();
        $data = OperatorDesa::with(['user', 'desa.kecamatan'])
            ->whereHas('desa', function ($query) use ($opkec) {
                $query->where('idKec', $opkec->idKec);
            })
            ->get();
        return view('operatorDesa.index', compact('data'));
    }

    public function create()
    {
        $opkec = OperatorKec::where('idUser', Auth::user()->idUser)->first();
        $desa = Desa::where('idKec', $opkec->idKec)->get();
        return view('operatorDesa.create', compact('desa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'idDesa' => 'required|exists:desa,idDesa'
        ]);

        // Simpan user
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roleUser' => 'operatorDesa',
        ]);

        // Simpan detail operator desa
        OperatorDesa::create([
            'idUser' => $user->idUser,
            'idDesa' => $request->idDesa
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

        return redirect()->route('operatorDesa.index')->with('success', 'Operator Desa berhasil ditambahkan dan link verifikasi telah dikirim.');
    }

    public function edit($id)
    {
        $opkec = OperatorKec::where('idUser', Auth::user()->idUser)->first();
        $desa = Desa::where('idKec', $opkec->idKec)->get();
        $op = OperatorDesa::with('user', 'desa')->findOrFail($id);
        $daftarDesa = Desa::where('idKec', $opkec->idKec)->get();
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
        $user = $op->user;

        $digunakan = $op->ajuanDafduk()->exists() || $op->ajuanCapil()->exists() || $user->respon()->exists();

        // Soft delete user jika masih punya respon
        if ($digunakan) {
            $user->delete(); // soft delete
            $op->delete(); // soft delete
            return redirect()->route('operatorDesa.index')->with('success', 'Operator masih memiliki pengajuan, jadi hanya disembunyikan dari daftar.');
        } else {
            $user->forceDelete(); // hard delete
            $op->forceDelete(); // hard delete
            return redirect()->route('operatorDesa.index')->with('success', 'Data operator berhasil dihapus.');
        }
    }

    public function getDesaByKecamatan($idKec)
    {
        $desa = Desa::where('idKec', $idKec)->get(['idDesa', 'namaDesa']);
        return response()->json($desa);
    }

    public function filter(Request $request)
    {
        $opkec = OperatorKec::where('idUser', Auth::user()->idUser)->first();

        $query = OperatorDesa::with(['user', 'desa.kecamatan'])
            ->whereHas('desa', function ($q) use ($opkec) {
                $q->where('idKec', $opkec->idKec);
            });

        if ($request->data === 'terhapus') {
            $query->onlyTrashed();
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama', fn($op) => $op->user->nama ?? '-')
            ->addColumn('email', fn($op) => $op->user->email ?? '-')
            ->addColumn('verifikasi', fn($op) => $op->user?->email_verified_at
                ? '<span class="badge bg-success">Terverifikasi</span>'
                : '<span class="badge bg-danger">Belum</span>')
            ->addColumn('desa', fn($op) => $op->desa->namaDesa ?? '-')
            ->addColumn('kecamatan', fn($op) => $op->desa->kecamatan->namaKec ?? '-')
            ->addColumn('aksi', function ($op) {
                $html = '<div class="action" style="display: flex; gap: 6px; overflow-x: auto;">';
                if ($op->deleted_at) {
                    $html .= '<a href="/operatorDesa/restore/' . $op->idOpdes . '" class="text-success" title="Pulihkan"><i class="lni lni-reload"></i></a>';
                } else {
                    $html .= '
                    <a href="/operatorDesa/' . $op->idOpdes . '/edit" class="text-warning" title="Edit"><i class="lni lni-pencil"></i></a>
                    <form action="/operatorDesa/' . $op->idOpdes . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button onclick="return confirm(\'Yakin hapus?\')" class="border-0 bg-transparent text-danger p-0" title="Hapus">
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
        $op = OperatorDesa::withTrashed()->where('idOpdes', $id)->firstOrFail();
        $user = User::withTrashed()->where('idUser', $op->idUser)->first();

        $op->restore();

        if ($user) {
            $user->restore();
        }

        return redirect()->route('operatorDesa.index')->with('success', 'Data operator berhasil dipulihkan.');
    }
}
