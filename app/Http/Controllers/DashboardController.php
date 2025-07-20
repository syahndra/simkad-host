<?php

namespace App\Http\Controllers;

use App\Models\AjuanCapil;
use App\Models\AjuanDafduk;
use App\Models\OperatorDesa;
use App\Models\OperatorKec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->roleUser;

        $capil = AjuanCapil::query();
        $dafduk = AjuanDafduk::query();

        // Filter by role
        if ($role === 'operatorDesa') {
            $desaId = OperatorDesa::where('idUser', $user->idUser)->value('idDesa');
            $capil->whereHas('operatorDesa', fn($q) => $q->where('idDesa', $desaId));
            $dafduk->whereHas('operatorDesa', fn($q) => $q->where('idDesa', $desaId));
        } elseif ($role === 'operatorKecamatan') {
            $kecId = OperatorKec::where('idUser', $user->idUser)->value('idKec');
            $capil->whereRaw('0 = 1'); // kosongkan
            $dafduk->whereHas('operatorDesa.desa.kecamatan', fn($q) => $q->where('idKec', $kecId))
                ->whereHas('layanan', fn($q) => $q->where('aksesVer', 'kecamatan'));
        } elseif ($role === 'opDinDafduk') {
            $capil->whereRaw('0 = 1'); // kosongkan
            $dafduk->whereHas('layanan', fn($q) => $q->where('aksesVer', 'dinasDafduk'));
        } elseif ($role === 'opDinCapil') {
            $dafduk->whereRaw('0 = 1'); // kosongkan

        } // admin dan superadmin: tanpa filter

        // Ambil jumlah per status
        $capilStats = $capil->select('statAjuan', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('statAjuan')->pluck('jumlah', 'statAjuan');

        $dafdukStats = $dafduk->select('statAjuan', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('statAjuan')->pluck('jumlah', 'statAjuan');

        // Hitung total per status (gabung revisi ke dalam antrian)
        $statusCounts = collect([
            'dalam antrian' => ($capilStats['dalam antrian'] ?? 0) + ($capilStats['revisi'] ?? 0)
                + ($dafdukStats['dalam antrian'] ?? 0) + ($dafdukStats['revisi'] ?? 0),
            'sudah diproses' => ($capilStats['sudah diproses'] ?? 0) + ($dafdukStats['sudah diproses'] ?? 0),
            'ditolak' => ($capilStats['ditolak'] ?? 0) + ($dafdukStats['ditolak'] ?? 0),
            'selesai' => ($capilStats['selesai'] ?? 0) + ($dafdukStats['selesai'] ?? 0),
        ]);

        return view('dashboard', compact('statusCounts'));
    }
    
    public function getSelesaiChartData(Request $request)
    {
        $filter = $request->filter ?? 'yearly';
        $now = now();
        $user = Auth::user();
        $role = $user->roleUser;
        $queryCapil = AjuanCapil::query()->where('statAjuan', 'selesai');
        $queryDafduk = AjuanDafduk::query()->where('statAjuan', 'selesai');

        // Filter berdasarkan role user
        if ($role === 'operatorDesa') {
            $desaId = OperatorDesa::where('idUser', $user->idUser)->value('idDesa');
            $queryCapil->whereHas('operatorDesa', fn($q) => $q->where('idDesa', $desaId));
            $queryDafduk->whereHas('operatorDesa', fn($q) => $q->where('idDesa', $desaId));
        } elseif ($role === 'operatorKecamatan') {
            $kecId = OperatorKec::where('idUser', $user->idUser)->value('idKec');
            $queryCapil->whereRaw('0 = 1'); // kosongkan
            $queryDafduk->whereHas('operatorDesa.desa.kecamatan', fn($q) => $q->where('idKec', $kecId));
        } elseif ($role === 'opDinDafduk') {
            $queryCapil->whereRaw('0 = 1'); // kosongkan
            $queryDafduk->whereHas('layanan', fn($q) => $q->where('aksesVer', 'dinasDafduk'));
        } elseif ($role === 'opDinCapil') {
            $queryDafduk->whereRaw('0 = 1'); // kosongkan
        } // admin dan superadmin: tanpa filter

        // Siapkan hasil akhir
        $labels = [];
        $data = [];

        if ($filter === 'monthly') {
            $capil = clone $queryCapil;
            $dafduk = clone $queryDafduk;

            $capil = $capil->selectRaw('DAY(created_at) as hari, COUNT(*) as jumlah')
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->groupByRaw('DAY(created_at)')
                ->pluck('jumlah', 'hari');

            $dafduk = $dafduk->selectRaw('DAY(created_at) as hari, COUNT(*) as jumlah')
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->groupByRaw('DAY(created_at)')
                ->pluck('jumlah', 'hari');

            $labels = range(1, $now->daysInMonth);
            $data = collect($labels)->map(fn($hari) => ($capil[$hari] ?? 0) + ($dafduk[$hari] ?? 0));
        } elseif ($filter === 'weekly') {
            $startDate = $now->copy()->subDays(6)->startOfDay();
            $endDate = $now->endOfDay();

            $capil = clone $queryCapil;
            $dafduk = clone $queryDafduk;

            $capil = $capil->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupByRaw('DATE(created_at)')
                ->pluck('jumlah', 'tanggal');

            $dafduk = $dafduk->selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupByRaw('DATE(created_at)')
                ->pluck('jumlah', 'tanggal');

            $labels = collect();
            $data = collect();

            for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
                $tgl = $date->format('Y-m-d');
                $labels->push($date->format('D'));
                $data->push(($capil[$tgl] ?? 0) + ($dafduk[$tgl] ?? 0));
            }
        } else {
            // Default: yearly
            $tahun = $request->tahun ?? $now->year;

            $capil = clone $queryCapil;
            $dafduk = clone $queryDafduk;

            $capil = $capil->selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
                ->whereYear('created_at', $tahun)
                ->groupByRaw('MONTH(created_at)')
                ->pluck('jumlah', 'bulan');

            $dafduk = $dafduk->selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
                ->whereYear('created_at', $tahun)
                ->groupByRaw('MONTH(created_at)')
                ->pluck('jumlah', 'bulan');

            $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $data = collect(range(1, 12))->map(fn($bulan) => ($capil[$bulan] ?? 0) + ($dafduk[$bulan] ?? 0));
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
