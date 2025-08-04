<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\OperatorDinasController;
use App\Http\Controllers\OperatorKecController;
use App\Http\Controllers\OperatorDesaController;
use App\Http\Controllers\AjuanDafdukController;
use App\Http\Controllers\AjuanCapilController;
use App\Http\Controllers\ResponController;
use App\Http\Controllers\FinalDokumenController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\DashboardController;
use App\Models\Kecamatan;
use App\Models\OperatorDesa;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/cek-pengajuan', [TokenController::class, 'form'])->name('cek.form');
Route::get('/cek-pengajuan/{jenis}/{token}', [TokenController::class, 'cek'])->name('cek.pengajuan');
// Lupa Password
Route::post('/send-reset-code', [AuthController::class, 'sendResetCode']);
Route::post('/submit-reset-password', [AuthController::class, 'submitResetPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('home');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getSelesaiChartData'])->name('dashboard.chart-data');
    Route::get('/ajuan-dafduk/filter', [AjuanDafdukController::class, 'filter'])->name('ajuanDafduk.filter');
    Route::get('/ajuan-capil/filter', [AjuanCapilController::class, 'filter'])->name('ajuanCapil.filter');
    Route::get('/ubahProfil', [AuthController::class, 'ubahProfil'])->name('ubahProfil');
    Route::put('/ubahProfil', [AuthController::class, 'updateProfil'])->name('profil.update');
    Route::get('/operatorDesa/filter', [OperatorDesaController::class, 'filter'])->name('operatorDesa.filter');
    Route::get('/operatorKec/filter', [OperatorKecController::class, 'filter'])->name('operatorKec.filter');
    Route::get('/operatorDinas/filter', [OperatorDinasController::class, 'filter'])->name('operatorDinas.filter');
    Route::get('/desa/filter', [DesaController::class, 'filter'])->name('desa.filter');
    Route::get('/kecamatan/filter', [KecamatanController::class, 'filter'])->name('kecamatan.filter');
    Route::get('/layanan/filter', [LayananController::class, 'filter'])->name('layanan.filter');

    Route::middleware(['checkRole:admin'])->group(function () {
        Route::resource('kecamatan', KecamatanController::class);
        Route::resource('desa', DesaController::class);
        Route::resource('layanan', LayananController::class);
        Route::resource('operatorDinas', OperatorDinasController::class);
        Route::resource('operatorKec', OperatorKecController::class);
        Route::get('/operatorKec/restore/{id}', [OperatorKecController::class, 'restore'])->name('operatorKec.restore');
        Route::get('/operatorDinas/restore/{id}', [OperatorDinasController::class, 'restore'])->name('operatorDinas.restore');
        Route::get('/desa/restore/{id}', [DesaController::class, 'restore'])->name('desa.restore');
        Route::get('/kecamatan/restore/{id}', [KecamatanController::class, 'restore'])->name('kecamatan.restore');
        Route::get('/layanan/restore/{id}', [LayananController::class, 'restore'])->name('layanan.restore');

    });
    Route::middleware(['checkRole:operatorKecamatan'])->group(function () {
        Route::resource('operatorDesa', OperatorDesaController::class);
        Route::get('/getDesa-by-kecamatan/{idKec}', [OperatorDesaController::class, 'getDesaByKecamatan'])->name('getDesaByKecamatan');
        Route::get('/operatorDesa/restore/{id}', [OperatorDesaController::class, 'restore'])->name('operatorDesa.restore');
    });
    Route::middleware(['checkRole:operatorDesa,operatorKecamatan,opDinDafduk,admin'])->group(function () {
        Route::resource('ajuanDafduk', AjuanDafdukController::class);
        Route::get('/ajuanDafduk/{id}', [AjuanDafdukController::class, 'show'])->name('ajuanDafduk.show');
        Route::get('/ajuanDafduk/restore/{id}', [AjuanDafdukController::class, 'restore'])->name('ajuanDafduk.restore');
    });

    Route::middleware(['checkRole:operatorDesa,opDinCapil,admin'])->group(function () {
        Route::resource('ajuanCapil', AjuanCapilController::class);
        // Route::get('/ajuanCapil/delete/{id}', [AjuanCapilController::class, 'destroy'])->name('ajuanCapil.destroy');
        Route::get('/ajuanCapil/{id}', [AjuanCapilController::class, 'show'])->name('ajuanCapil.show');
        Route::get('/ajuanCapil/restore/{id}', [AjuanCapilController::class, 'restore'])->name('ajuanCapil.restore');
    });

    Route::middleware(['checkRole:operatorDesa,opDinCapil,opDinDafduk,operatorKecamatan'])->group(function () {
        Route::get('/respon/{jenis}/{id}/create', [ResponController::class, 'create'])->name('respon.create');
        Route::post('/respon', [ResponController::class, 'store'])->name('respon.store');
        Route::get('/respon/{jenis}/{id}/edit', [ResponController::class, 'edit'])->name('respon.edit');
        Route::put('/respon/{id}', [ResponController::class, 'update'])->name('respon.update');
    });

    Route::middleware(['checkRole:operatorDesa,opDinCapil'])->group(function () {
        Route::get('/respon/{id}/revisi', [ResponController::class, 'revisi'])->name('ajuan.revisi');
        Route::put('/respon/{id}/revisi', [ResponController::class, 'revisiProses'])->name('ajuan.revisi');
    });

    Route::middleware(['checkRole:operatorDesa'])->group(function () {
        Route::get('/finalDok/{jenis}/{id}/create', [FinalDokumenController::class, 'create'])->name('finalDokumen.create');
        Route::post('/finalDok', [FinalDokumenController::class, 'store'])->name('finalDokumen.store');
        Route::get('/finalDok/{jenis}/{id}/edit', [FinalDokumenController::class, 'edit'])->name('finalDokumen.edit');
        Route::put('/finalDok/{id}', [FinalDokumenController::class, 'update'])->name('finalDokumen.update');
        Route::get('/cetak-token/{jenis}/{id}', [TokenController::class, 'cetakToken'])->name('ajuan.cetak');
    });
});

Route::get('/dokumen_final/{filename}', function ($filename) {
    $path = base_path('dokumen_final/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    return Response::file($path);
});

Route::get('/get-desa-by-kecamatan/{idKec}', function ($idKec) {
    return \App\Models\Desa::where('idKec', $idKec)->get();
});

Route::get('/unverified', function () {
    return view('auth.unverified');
})->name('auth.unverified');

// Verifikasi Email
// Kirim ulang email verifikasi
Route::post('/resend-verification/{id}', [AuthController::class, 'resendVerification'])->name('verification.resend');

// Link verifikasi email
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verification.custom');
// Route::get('/ajuan-dafduk/export', [AjuanDafdukController::class, 'exportPDF'])->name('ajuanDafduk.export');
