<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\AjuanCapil;
use App\Models\AjuanDafduk;
use App\Models\OperatorDesa;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TokenController extends Controller
{
    public function cetakToken($jenis, $id)
    {
        $ajuan = null;

        if ($jenis === 'capil') {
            $ajuan = AjuanCapil::findOrFail($id);
        } elseif ($jenis === 'dafduk') {
            $ajuan = AjuanDafduk::findOrFail($id);
        } else {
            abort(404, 'Permintaan tidak dikenali');
        }

        $operatorDesa = OperatorDesa::with('desa.kecamatan', 'user')
            ->where('idUser', Auth::id())
            ->firstOrFail();

        // $barcode = base64_encode(QrCode::format('svg')->size(200)->generate($ajuan->token));
        $qrUrl = route('cek.pengajuan', ['jenis' => $jenis, 'token' => $ajuan->token]);

        $barcode = base64_encode(
            QrCode::format('svg')->size(200)->generate($qrUrl)
        );

        // Data yang akan dikirim ke view cetak
        $data = [
            'nama' => $ajuan->nama ?? '-',
            'layanan' => $ajuan->layanan->namaLayanan,
            'token' => $ajuan->token,
            'created_at' => $ajuan->created_at->translatedFormat('d F Y'),
            'barcode' => $barcode,
            'jenis' => $jenis,
            'nokk' => $ajuan->noKK,
            'nik' => $ajuan->nik,
            'desa' => $operatorDesa
        ];

        // Buat PDF langsung dari view
        $pdf = Pdf::loadView('token.cetak', $data);

        return $pdf->stream("Token-Ajuan-{$ajuan->token}.pdf");
        // gunakan ->download() jika ingin diunduh
    }

    public function form()
    {
        // Halaman awal tanpa data
        return view('token.cek', ['data' => null, 'jenis' => null, 'token' => null]);
    }

    public function cek($jenis, $token)
    {
        if ($jenis === 'capil') {
            $ajuan = AjuanCapil::where('token', $token)->firstOrFail();
            $aksesVer = $ajuan->layanan->aksesVer ?? null;
            $createdAt = $ajuan->created_at;

            $query = AjuanCapil::where('created_at', '<', $createdAt)
                ->where('statAjuan', 'dalam antrian') // Tambahan di sini
                ->whereHas('layanan', function ($q) use ($aksesVer) {
                    $q->where('aksesVer', $aksesVer);
                });

            if ($aksesVer === 'kecamatan') {
                $idKec = $ajuan->operatorDesa->desa->idKec ?? null;
                if ($idKec) {
                    $query->whereHas('operatorDesa.desa', function ($q) use ($idKec) {
                        $q->where('idKec', $idKec);
                    });
                }
            }

            $antrian = $query->count() + 1;
        } elseif ($jenis === 'dafduk') {
            $ajuan = AjuanDafduk::where('token', $token)->firstOrFail();
            $aksesVer = $ajuan->layanan->aksesVer ?? null;
            $createdAt = $ajuan->created_at;

            $query = AjuanDafduk::where('created_at', '<', $createdAt)
                ->where('statAjuan', 'dalam antrian') // Tambahan di sini
                ->whereHas('layanan', function ($q) use ($aksesVer) {
                    $q->where('aksesVer', $aksesVer);
                });

            if ($aksesVer === 'kecamatan') {
                $idKec = $ajuan->operatorDesa->desa->idKec ?? null;
                if ($idKec) {
                    $query->whereHas('operatorDesa.desa', function ($q) use ($idKec) {
                        $q->where('idKec', $idKec);
                    });
                }
            }

            $antrian = $query->count() + 1;
        } else {
            abort(404, 'Permintaan tidak dikenali');
        }

        // Data yang dikirim ke view
        $data = [
            'tgl' => $ajuan->created_at->format('Y-m-d'),
            'layanan' => $ajuan->layanan->namaLayanan ?? '-',
            'nama' => $ajuan->nama ?? '-',
            'kk' => $ajuan->noKK ?? '-',
            'nik' => $ajuan->nik ?? '-',
            'opdes' => $ajuan->operatorDesa->user->nama ?? '-',
            'status' => $ajuan->statAjuan ?? '-',
            'antrian' => $antrian
        ];

        return view('token.cek', ['data' => $data, 'jenis' => $jenis, 'token' => $token, 'ajuan' => $ajuan]);
    }
}
