<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\AjuanDafduk;
use App\Models\OperatorDesa;
use App\Models\OperatorKec;
use App\Models\Layanan;
use App\Models\Respon;
use App\Models\FinalDokumen;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\PengajuanMasukMail;
use App\Models\Kecamatan;
use App\Models\Desa;
use Yajra\DataTables\DataTables;

class AjuanDafdukController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $listLayanan = Layanan::where('jenis', 'dafduk')->get();
        $listKecamatan = null;
        $idKec = null;
        $namaKecamatan = null;
        $namaDesa = null;

        if ($user->roleUser === 'operatorDesa') {
            $opdes = OperatorDesa::where('idUser', $user->idUser)->first();
            $listKecamatan = Kecamatan::where('idKec', $opdes->desa->kecamatan->idKec)->get();
            $namaKecamatan = $listKecamatan->first()?->namaKec;
            $namaDesa = $opdes->desa->namaDesa ?? null;
            $ajuan = AjuanDafduk::with('operatorDesa.desa.kecamatan', 'layanan')
                ->whereHas('operatorDesa', function ($query) use ($opdes) {
                    $query->where('idDesa', $opdes->idDesa);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($user->roleUser === 'operatorKecamatan') {
            $listLayanan = Layanan::where('aksesVer', 'kecamatan')->get();
            $opkec = OperatorKec::where('idUser', $user->idUser)->first();
            $kecamatan = Kecamatan::where('idKec', $opkec->idKec)->first();
            $idKec = $kecamatan->idKec;
            $ajuan = AjuanDafduk::with('operatorDesa.desa.kecamatan', 'layanan')
                ->whereHas('operatorDesa.desa', function ($query) use ($opkec) {
                    $query->where('idKec', $opkec->idKec);
                })
                ->whereHas('layanan', function ($query) {
                    $query->where('aksesVer', 'kecamatan');
                })
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($user->roleUser === 'opDinDafduk') {
            $listLayanan = Layanan::where('aksesVer', 'dinasDafduk')->get();
            $listKecamatan = Kecamatan::whereIn('idKec', Desa::select('idKec'))->get();
            $ajuan = AjuanDafduk::with('operatorDesa.desa.kecamatan', 'layanan')
                ->whereHas('layanan', function ($query) {
                    $query->where('aksesVer', 'dinasDafduk');
                })
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Role lain, ambil semua
            $listKecamatan = Kecamatan::whereIn('idKec', Desa::select('idKec'))->get();
            $ajuan = AjuanDafduk::with('operatorDesa.desa.kecamatan', 'layanan')->orderBy('created_at', 'desc')->get();
        }

        // dd($namaKecamatan);

        return view('ajuanDafduk.index', compact('idKec','ajuan', 'listLayanan', 'listKecamatan', 'namaKecamatan', 'namaDesa'));
    }

    public function create()
    {
        $layanan = Layanan::where('jenis', 'dafduk')->get();
        $operatorDesa = OperatorDesa::with('desa.kecamatan', 'user')
            ->where('idUser', Auth::id())
            ->firstOrFail();

        return view('ajuanDafduk.create', compact('layanan', 'operatorDesa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idOpdes' => 'required|exists:operatordesa,idOpdes',
            'idLayanan' => 'required|exists:layanan,idLayanan',
            'noKK' => 'required|digits:16',
            'nik'  => 'required|digits:16',
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'statAjuan' => 'required|in:dalam antrian,ditolak,sudah diproses,revisi',
            'linkBerkas' => 'nullable|url',
            'rt' => 'required|digits_between:1,3',
            'rw' => 'required|digits_between:1,3',
            'email' => 'required|email',
        ]);

        $data = $request->all();
        $data['token'] = Str::random(6);

        $dafduk = AjuanDafduk::create($data);
        $ajuan = AjuanDafduk::with(['layanan', 'operatorDesa.desa.kecamatan'])
            ->where('idDafduk', $dafduk->idDafduk)
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

        return redirect()->route('ajuanDafduk.index')->with('success', 'Ajuan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ajuan = AjuanDafduk::findOrFail($id);
        $layanan = Layanan::where('jenis', 'dafduk')->get();
        $operatorDesa = OperatorDesa::with('desa.kecamatan', 'user')
            ->where('idUser', Auth::id())
            ->firstOrFail();

        return view('ajuanDafduk.edit', compact('ajuan', 'layanan', 'operatorDesa'));
    }

    public function update(Request $request, $id)
    {
        $ajuan = AjuanDafduk::findOrFail($id);

        $request->validate([
            'idLayanan' => 'required|exists:layanan,idLayanan',
            'noKK' => 'required|digits:16',
            'nik'  => 'required|digits:16',
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'linkBerkas' => 'nullable|url'
        ]);

        $ajuan->update($request->all());

        return redirect()->route('ajuanDafduk.index')->with('success', 'Ajuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Cek apakah user login adalah operatorDesa
        if (Auth::user()->roleUser !== 'operatorDesa') {
            return redirect()->route('ajuanDafduk.index')->with('error', 'Anda tidak memiliki izin untuk menghapus ajuan ini.');
        }
        $data = AjuanDafduk::findOrFail($id);
        if ($data->statAjuan === 'dalam antrian') {
            $data->forceDelete();
            return redirect()->route('ajuanDafduk.index')->with('success', 'Ajuan berhasil dihapus.');
        } else {
            $data->delete(); // soft delete
            return redirect()->route('ajuanDafduk.index')->with('success', 'Data ajuan sudah ditindaklanjuti, jadi hanya disembunyikan dari daftar.');
        }
    }

    public function filter(Request $request)
    {
        $user = Auth::user();
        $query = AjuanDafduk::with([
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
        } elseif ($user->roleUser === 'operatorKecamatan') {
            $opkec = OperatorKec::where('idUser', $user->idUser)->first();
            $query->whereHas('operatorDesa.desa', function ($q) use ($opkec) {
                $q->where('idKec', $opkec->idKec);
            })->whereHas('layanan', function ($q) {
                $q->where('aksesVer', 'kecamatan');
            });
        } elseif ($user->roleUser === 'opDinDafduk') {
            $query->whereHas('layanan', function ($q) {
                $q->where('aksesVer', 'dinasDafduk');
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
                    $html .= '<a href="' . route('ajuanDafduk.restore', $ajuan->idDafduk) . '" class="text-warning" title="Pulihkan">
                    <i class="lni lni-reload"></i>
                  </a>';
                } else {
                    // Tombol Detail
                    $html .= '<a href="' . route('ajuanDafduk.show', $ajuan->idDafduk) . '" class="text-success" title="Detail">
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
                            $html .= '<a href="' . route('ajuanDafduk.edit', $ajuan->idDafduk) . '" class="text-warning" title="Edit Ajuan">
                        <i class="lni lni-pencil"></i>
                      </a>';
                            $html .= '<form action="' . route('ajuanDafduk.destroy', $ajuan->idDafduk) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button onclick="return confirm(\'Yakin hapus?\')" class="text-danger" title="Hapus Ajuan">
                            <i class="lni lni-trash-can"></i>
                        </button>
                      </form>';
                        }

                        if ($ajuan->statAjuan === 'ditolak') {
                            $html .= '<a href="' . route('respon.edit', ['jenis' => 'Dafduk', 'id' => $ajuan->idDafduk]) . '" class="text-warning" title="Ajukan Ulang">
                        <i class="lni lni-reload"></i>
                      </a>';
                        }

                        if (in_array($ajuan->statAjuan, ['sudah diproses', 'selesai'])) {
                            if (isset($ajuan->finalDokumen)) {
                                $html .= '<a href="' . route('finalDokumen.edit', ['jenis' => 'Dafduk', 'id' => $ajuan->idDafduk]) . '" class="text-warning" title="Ubah Dokumen">
                            <i class="lni lni-pencil-alt"></i>
                          </a>';
                            } else {
                                $html .= '<a href="' . route('finalDokumen.create', ['jenis' => 'Dafduk', 'id' => $ajuan->idDafduk]) . '" class="text-primary" title="Upload Dokumen">
                            <i class="lni lni-cloud-upload"></i>
                          </a>';
                            }
                        }

                        $html .= '<a href="' . route('ajuan.cetak', ['jenis' => 'Dafduk', 'id' => $ajuan->idDafduk]) . '" class="text-secondary" title="Bukti Pengajuan" target="_blank">
                    <i class="lni lni-cog"></i>
                  </a>';
                    }

                    // Role: opDinDafduk
                    elseif ($user === 'opDinDafduk') {
                        if ($ajuan->statAjuan === 'dalam antrian') {
                            $html .= '<a href="' . route('respon.create', ['jenis' => 'Dafduk', 'id' => $ajuan->idDafduk]) . '" class="text-primary" title="Beri Respon">
                        <i class="lni lni-reply"></i>
                      </a>';
                        } else {
                            $html .= '<a href="' . route('respon.edit', ['jenis' => 'Dafduk', 'id' => $ajuan->idDafduk]) . '" class="text-warning" title="Ubah Respon">
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
            ->where('jenis', 'dafduk')
            ->first();

        $finalDokumen = FinalDokumen::where('idAjuan', $id)
            ->where('jenis', 'dafduk')
            ->first();
        $ajuan = AjuanDafduk::with('operatorDesa.desa.kecamatan', 'layanan', 'respon', 'finalDOkumen')->findOrFail($id);
        return view('ajuanDafduk.show', compact('ajuan', 'respon', 'finalDokumen'));
    }

    public function restore($id)
    {
        $ajuan = AjuanDafduk::withTrashed()->where('idDafduk', $id)->first();
        if ($ajuan) {
            $ajuan->restore();
        }

        return redirect()->route('ajuanDafduk.index')->with('success', 'Ajuan berhasil dipulihkan.');
    }
}
