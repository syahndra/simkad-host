<?php

namespace App\Http\Controllers;

use App\Models\FinalDokumen;
use App\Models\AjuanCapil;
use App\Models\AjuanDafduk;
use Illuminate\Http\Request;

class FinalDokumenController extends Controller
{
    public function index()
    {
        $dokumen = FinalDokumen::all();
        return view('finalDokumen.index', compact('dokumen'));
    }

    public function create($jenis, $id)
    {
        $ajuan = null;

        if ($jenis === 'capil') {
            $ajuan = AjuanCapil::with('operatorDesa.desa.kecamatan', 'layanan')->findOrFail($id);
        } elseif ($jenis === 'dafduk') {
            $ajuan = AjuanDafduk::with('operatorDesa.desa.kecamatan', 'layanan')->findOrFail($id);
        } else {
            abort(404, 'Jenis ajuan tidak dikenali');
        }

        return view('finalDokumen.create', compact('ajuan', 'jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:capil,dafduk',
            'idAjuan' => 'required',
            'filename' => 'required',
            'file' => 'required|file|mimes:pdf|max:2048',
        ]);

        if ($request->jenis === 'capil') {
            $ajuan = AjuanCapil::findOrFail($request->idAjuan);
        } elseif ($request->jenis === 'dafduk') {
            $ajuan = AjuanDafduk::findOrFail($request->idAjuan);
        } else {
            abort(404, 'Permintaan tidak dikenali');
        }

        $ajuan->statAjuan = $request->statAjuan;
        $ajuan->save();

        // Upload file ke public_html/dokumen_final
        $file = $request->file('file');
        $namaFile = time() . '_' . str_replace(' ', '_', $request->filename) . '.' . $file->getClientOriginalExtension();
        $tujuanFolder = $_SERVER['DOCUMENT_ROOT'] . '/dokumen_final';

        if (!file_exists($tujuanFolder)) {
            mkdir($tujuanFolder, 0755, true);
        }

        $file->move($tujuanFolder, $namaFile);

        FinalDokumen::create([
            'jenis' => $request->jenis,
            'idAjuan' => $request->idAjuan,
            'filename' => $request->filename,
            'filePath' => 'dokumen_final/' . $namaFile,
        ]);

        return redirect()->route('ajuan' . ucfirst($request->jenis) . '.index')
            ->with('success', 'Final Dokumen berhasil dikirim.');
    }

    public function edit($jenis, $id)
    {
        //$finalDokumen = FinalDokumen::where('idAjuan', $id)->firstOrFail();
        $finalDokumen = FinalDokumen::where('idAjuan', $id)
    ->where('jenis', $jenis) // <--- Tambahkan pengecekan jenis di sini
    ->firstOrFail();

        if ($jenis === 'capil') {
            $ajuan = AjuanCapil::with('operatorDesa.desa.kecamatan', 'layanan')->findOrFail($finalDokumen->idAjuan);
        } elseif ($jenis === 'dafduk') {
            $ajuan = AjuanDafduk::with('operatorDesa.desa.kecamatan')->findOrFail($finalDokumen->idAjuan);
        } else {
            abort(404, 'Jenis dokumen tidak dikenali');
        }

        $jenis = $finalDokumen->jenis;

        return view('finalDokumen.edit', compact('finalDokumen', 'ajuan', 'jenis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'filename' => 'required',
            'file' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $finalDokumen = FinalDokumen::findOrFail($id);
        $finalDokumen->filename = $request->filename;

        if ($request->hasFile('file')) {
            $tujuanFolder = $_SERVER['DOCUMENT_ROOT'] . '/dokumen_final';

            if (!file_exists($tujuanFolder)) {
                mkdir($tujuanFolder, 0755, true);
            }

            if ($finalDokumen->filePath && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $finalDokumen->filePath)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $finalDokumen->filePath);
            }

            $file = $request->file('file');
            $namaFile = time() . '_' . str_replace(' ', '_', $request->filename) . '.' . $file->getClientOriginalExtension();
            $file->move($tujuanFolder, $namaFile);

            $finalDokumen->filePath = 'dokumen_final/' . $namaFile;
        }

        $finalDokumen->save();

        return redirect()->route('ajuan' . ucfirst($finalDokumen->jenis) . '.index')
            ->with('success', 'Final Dokumen berhasil diperbarui.');
    }
}
