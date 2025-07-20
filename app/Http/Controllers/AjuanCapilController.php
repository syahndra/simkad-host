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

class AjuanCapilController extends Controller
{
    public function index()
    {
        $listLayanan = Layanan::where('jenis', 'capil')->get();
        if (Auth::user()->roleUser === 'operatorDesa') {
            $opdes = OperatorDesa::where('idUser', Auth::user()->idUser)->first();

            $ajuan = AjuanCapil::with('layanan', 'operatorDesa.desa.kecamatan', 'respon')
                ->whereHas('operatorDesa', function ($query) use ($opdes) {
                    $query->where('idDesa', $opdes->idDesa);
                })
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $ajuan = AjuanCapil::with('layanan', 'operatorDesa.desa.kecamatan', 'respon')->orderBy('created_at', 'desc')->get();
        }

        return view('ajuanCapil.index', compact('ajuan', 'listLayanan'));
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
            'noAkta' => 'nullable|string|max:50',
            'noKK' => 'required|string|max:50',
            'nik' => 'required|string|max:50',
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string|max:255',
            'linkBerkas' => 'nullable|url'
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
            'noKK' => 'required',
            'nik' => 'required',
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'noAkta' => 'nullable|string|max:50',
            'linkBerkas' => 'nullable|url'
        ]);

        $ajuan->update($request->all());

        return redirect()->route('ajuanCapil.index')->with('success', 'Ajuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        AjuanCapil::findOrFail($id)->delete();
        return redirect()->route('ajuanCapil.index')->with('success', 'Ajuan berhasil dihapus.');
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

        $result = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $result
        ]);
    }

    public function show($id)
    {
        $respon = Respon::where('idAjuan', $id)->first();
        $finalDokumen = FinalDokumen::where('idAjuan', $id)->first();
        $ajuan = AjuanCapil::with('operatorDesa.desa.kecamatan', 'layanan', 'respon', 'finalDOkumen')->findOrFail($id);
        return view('ajuanCapil.show', compact('ajuan', 'respon', 'finalDokumen'));
    }
}
