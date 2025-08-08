<?php

namespace App\Http\Controllers;

use App\Models\AjuanCapil;
use App\Models\Layanan;
use App\Models\OperatorDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Respon;
use App\Models\FinalDokumen;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\PengajuanMasukMail;
use App\Models\Kecamatan;
use App\Models\Desa;
use Yajra\DataTables\DataTables;

class AjuanCapilController extends Controller
{
    public function index()
    {
        $listLayanan = Layanan::where('jenis', 'capil')->get();
        $listKecamatan = null;
        $namaKecamatan = null;
        $idKec = null;
        $namaDesa = null;
        $idDesa = null;

        if (Auth::user()->roleUser === 'operatorDesa') {
            $opdes = OperatorDesa::where('idUser', Auth::user()->idUser)->first();
            $listKecamatan = Kecamatan::where('idKec', $opdes->desa->kecamatan->idKec)->get();
            $namaKecamatan = $listKecamatan->first()?->namaKec;
            $idKec = $listKecamatan->first()?->idKec;
            $namaDesa = $opdes->desa->namaDesa ?? null;
            $idDesa = $opdes->desa->idDesa ?? null;
            $ajuan = AjuanCapil::with('layanan', 'operatorDesa.desa.kecamatan', 'respon')
                ->whereHas('operatorDesa', function ($query) use ($opdes) {
                    $query->where('idDesa', $opdes->idDesa);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $listKecamatan = Kecamatan::whereIn('idKec', Desa::select('idKec'))->get();
            $ajuan = AjuanCapil::with('layanan', 'operatorDesa.desa.kecamatan', 'respon')->orderBy('created_at', 'desc')->get();
        }

        return view('ajuanCapil.index', compact('ajuan', 'listLayanan', 'listKecamatan', 'namaKecamatan', 'namaDesa','idKec','idDesa'));
    }

    public function create()
    {
        $layanan = Layanan::where('jenis', 'capil')->get();

        $user = Auth::user();
        $operatorDesa = OperatorDesa::with('desa.kecamatan', 'user')
            ->where('idUser', Auth::id())
            ->firstOrFail();

        return view('ajuanCapil.create', compact('layanan', 'operatorDesa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idOpdes' => 'required|exists:operatordesa,idOpdes',
            'idLayanan' => 'required',
            'noAkta' => 'required|string|size:21|regex:/^[0-9]{4}-[A-Z]{2}-[0-9]{8}-[0-9]{4}$/',
            'noKK' => 'required|digits:16',
            'nik'  => 'required|digits:16',
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:255',
            'linkBerkas' => 'nullable|url',
            'rt' => 'required|digits_between:1,3',
            'rw' => 'required|digits_between:1,3',
            'email' => 'required|email',
        ]);

        $data = $request->all();
        $data['token'] = Str::random(6);

        $capil = AjuanCapil::create($data);
        $ajuan = AjuanCapil::with(['layanan', 'operatorDesa.desa.kecamatan'])
            ->where('idCapil', $capil->idCapil)
            ->first();

        // Untuk Kirim email
        $data = [
            'nama' => $ajuan->nama,
            'layanan' => $ajuan->layanan->namaLayanan,
            'jenis' => $ajuan->layanan->jenis,
            'token' => $ajuan->token,
            'created_at' => $ajuan->created_at
        ];
        if (!empty($request->email)) {
            Mail::to($request->email)->send(new PengajuanMasukMail($data));;
        }

        return redirect()->route('ajuanCapil.index')->with('success', 'Ajuan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ajuan = AjuanCapil::findOrFail($id);
        $layanan = Layanan::where('jenis', 'capil')->get();
        $operatorDesa = OperatorDesa::with('desa.kecamatan', 'user')
            ->where('idUser', Auth::id())
            ->firstOrFail();

        return view('ajuanCapil.edit', compact('ajuan', 'layanan', 'operatorDesa'));
    }

    public function update(Request $request, $id)
    {
        $ajuan = AjuanCapil::findOrFail($id);
        $request->validate([
            'idLayanan' => 'required|exists:layanan,idLayanan',
            'noAkta' => 'required|string|size:21|regex:/^[0-9]{4}-[A-Z]{2}-[0-9]{8}-[0-9]{4}$/',
            'noKK' => 'required|digits:16',
            'nik'  => 'required|digits:16',
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:255',
            'linkBerkas' => 'nullable|url'
        ]);

        $ajuan->update($request->all());

        return redirect()->route('ajuanCapil.index')->with('success', 'Ajuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Cek apakah user login adalah operatorDesa
        if (Auth::user()->roleUser !== 'operatorDesa') {
            return redirect()->route('ajuanCapil.index')->with('error', 'Anda tidak memiliki izin untuk menghapus ajuan ini.');
        }
        $data = AjuanCapil::findOrFail($id);
        if ($data->statAjuan === 'dalam antrian') {
            $data->forceDelete();
            return redirect()->route('ajuanCapil.index')->with('success', 'Ajuan berhasil dihapus.');
        } else {
            $data->delete(); // soft delete
            return redirect()->route('ajuanCapil.index')->with('success', 'Data ajuan sudah ditindaklanjuti, jadi hanya disembunyikan dari daftar.');
        }
    }

    public function filter(Request $request)
    {
        $user = Auth::user();
        $query = AjuanCapil::with([
            'layanan',
            'operatorDesa.desa.kecamatan',
            'finalDokumen',
            'respon'
        ]);

        if ($user->roleUser === 'operatorDesa') {
            $opdes = OperatorDesa::where('idUser', $user->idUser)->first();
            $query->whereHas('operatorDesa', function ($q) use ($opdes) {
                $q->where('idDesa', $opdes->idDesa);
            });
        }

        $start = Carbon::parse($request->startDate)->startOfDay();
        $end = Carbon::parse($request->endDate)->endOfDay();
        if ($request->startDate && $request->endDate) {
            $query->whereBetween('created_at', [$start, $end]);
        }

        if ($request->layanan) {
            $query->where('idLayanan', $request->layanan);
        }

        if ($request->status) {
            $query->where('statAjuan', $request->status);
        }

        if ($request->kecamatan != '') {
            $query->whereHas('operatorDesa.desa', function ($q) use ($request) {
                $q->where('idKec', $request->kecamatan);
            });
        }
        if ($user->roleUser != 'operatorDesa') {
            if ($request->desa != '') {
                $query->whereHas('operatorDesa', function ($q) use ($request) {
                    $q->where('idDesa', $request->desa);
                });
            }
        }

        if ($request->rt) {
            $query->where('rt', $request->rt);
        }
        if ($request->rw) {
            $query->where('rw', $request->rw);
        }
        if ($request->data === 'terhapus') {
            $query->onlyTrashed();
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($ajuan) {
                return $ajuan->created_at
                    ? \Carbon\Carbon::parse($ajuan->created_at)->format('j/n/Y')
                    : '-';
            })
            ->addColumn('layanan', fn($ajuan) => $ajuan->layanan->namaLayanan ?? '-')
            ->addColumn('nama', fn($ajuan) => $ajuan->nama ?? '-')
            ->addColumn('nik', fn($ajuan) => $ajuan->nik ?? '-')
            ->addColumn('noKK', fn($ajuan) => $ajuan->noKK ?? '-')
            ->addColumn('noAkta', fn($ajuan) => $ajuan->noAkta ?? '-')
            ->addColumn('kecamatan', fn($ajuan) => $ajuan->operatorDesa->desa->kecamatan->namaKec ?? '-')
            ->addColumn('desa', fn($ajuan) => $ajuan->operatorDesa->desa->namaDesa ?? '-')
            ->addColumn('rt_rw', function ($ajuan) {
                return ($ajuan->rt ?? '-') . '/' . ($ajuan->rw ?? '-');
            })
            ->addColumn('statAjuan', function ($ajuan) {
                $class = 'bg-secondary'; // default
                if ($ajuan->statAjuan === 'ditolak') {
                    $class = 'bg-danger';
                } elseif ($ajuan->statAjuan === 'sudah diproses') {
                    $class = 'bg-primary';
                } elseif ($ajuan->statAjuan === 'revisi') {
                    $class = 'bg-warning';
                } elseif ($ajuan->statAjuan === 'selesai') {
                    $class = 'bg-success';
                }
                $html = '<span class="badge ' . $class . '">' . $ajuan->statAjuan . '</span>';

                return $html;
            })
            ->addColumn('respon', function ($ajuan) {
                return $ajuan->respon->respon ?? '-';
            })
            ->addColumn('action', function ($ajuan) {
                $user = Auth::user()->roleUser;
                $html = '<div class="action" style="display: flex; gap: 6px; overflow-x: auto;">';
                // Tombol Pulihkan
                if ($ajuan->deleted_at) {
                    $html .= '<a href="' . route('ajuanCapil.restore', $ajuan->idCapil) . '" class="text-warning" title="Pulihkan">
                    <i class="lni lni-reload"></i>
                  </a>';
                } else {
                    // Tombol Detail
                    $html .= '<a href="' . route('ajuanCapil.show', $ajuan->idCapil) . '" class="text-success" title="Detail">
                <i class="lni lni-eye"></i>
              </a>';
                    // Tombol Lihat Final Dokumen
                    if (isset($ajuan->finalDokumen?->filePath)) {
                        $html .= '<a href="' . asset($ajuan->finalDokumen->filePath) . '" target="_blank" class="text-primary" title="Lihat Final Dokumen">
                    <i class="lni lni-archive"></i>
                  </a>';
                    }

                    // Tombol Lihat Berkas GDrive
                    if (!empty($ajuan->linkBerkas)) {
                        $html .= '<a href="' . $ajuan->linkBerkas . '" target="_blank" class="text-muted" title="Lihat Berkas di GDrive">
                    <i class="lni lni-telegram-original"></i>
                  </a>';
                    }

                    // Role: operatorDesa
                    if ($user === 'operatorDesa') {
                        if ($ajuan->statAjuan === 'dalam antrian') {
                            $html .= '<a href="' . route('ajuanCapil.edit', $ajuan->idCapil) . '" class="text-warning" title="Edit Ajuan">
                        <i class="lni lni-pencil"></i>
                      </a>';
                            $html .= '<form action="' . route('ajuanCapil.destroy', $ajuan->idCapil) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button onclick="return confirm(\'Yakin hapus?\')" class="text-danger" title="Hapus Ajuan">
                            <i class="lni lni-trash-can"></i>
                        </button>
                      </form>';
                        }

                        if ($ajuan->statAjuan === 'ditolak') {
                            $html .= '<a href="' . route('respon.edit', ['jenis' => 'capil', 'id' => $ajuan->idCapil]) . '" class="text-warning" title="Ajukan Ulang">
                        <i class="lni lni-reload"></i>
                      </a>';
                        }

                        if (in_array($ajuan->statAjuan, ['sudah diproses', 'selesai'])) {
                            if (isset($ajuan->finalDokumen)) {
                                $html .= '<a href="' . route('finalDokumen.edit', ['jenis' => 'capil', 'id' => $ajuan->idCapil]) . '" class="text-warning" title="Ubah Dokumen">
                            <i class="lni lni-pencil-alt"></i>
                          </a>';
                            } else {
                                $html .= '<a href="' . route('finalDokumen.create', ['jenis' => 'capil', 'id' => $ajuan->idCapil]) . '" class="text-primary" title="Upload Dokumen">
                            <i class="lni lni-cloud-upload"></i>
                          </a>';
                            }
                        }

                        $html .= '<a href="' . route('ajuan.cetak', ['jenis' => 'capil', 'id' => $ajuan->idCapil]) . '" class="text-secondary" title="Bukti Pengajuan" target="_blank">
                    <i class="lni lni-cog"></i>
                  </a>';
                    }

                    // Role: opDinCapil
                    elseif ($user === 'opDinCapil') {
                        if ($ajuan->statAjuan === 'dalam antrian') {
                            $html .= '<a href="' . route('respon.create', ['jenis' => 'capil', 'id' => $ajuan->idCapil]) . '" class="text-primary" title="Beri Respon">
                        <i class="lni lni-reply"></i>
                      </a>';
                        } else {
                            $html .= '<a href="' . route('respon.edit', ['jenis' => 'capil', 'id' => $ajuan->idCapil]) . '" class="text-warning" title="Ubah Respon">
                        <i class="lni lni-pencil-alt"></i>
                      </a>';
                        }
                    }
                }

                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['statAjuan', 'action'])
            ->make(true);
    }

    public function show($id)
    {
        $respon = Respon::where('idAjuan', $id)
            ->where('jenis', 'capil')
            ->first();

        $finalDokumen = FinalDokumen::where('idAjuan', $id)
            ->where('jenis', 'capil')
            ->first();
        $ajuan = AjuanCapil::with('operatorDesa.desa.kecamatan', 'layanan', 'respon', 'finalDOkumen')->findOrFail($id);
        return view('ajuanCapil.show', compact('ajuan', 'respon', 'finalDokumen'));
    }
    public function restore($id)
    {
        $ajuan = AjuanCapil::withTrashed()->where('idCapil', $id)->first();
        if ($ajuan) {
            $ajuan->restore();
        }
        return redirect()->route('ajuanCapil.index')->with('success', 'Ajuan berhasil dipulihkan.');
    }
}
