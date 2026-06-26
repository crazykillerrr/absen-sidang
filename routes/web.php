<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RuangSidangController;
use App\Http\Controllers\Admin\PerkaraController;
use App\Http\Controllers\Admin\JadwalSidangController;
use App\Http\Controllers\Admin\PihakSidangController;
use App\Http\Controllers\Admin\NotifikasiController;
use App\Http\Controllers\Admin\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Gateway Portal PTUN
Route::get('/', function () {
    return view('portal');
})->middleware('guest')->name('portal');

// Rute Absensi Publik (Tanpa Login)
Route::get('/absensi', [AbsensiController::class, 'index'])->name('public.absensi');
Route::post('/absensi', [AbsensiController::class, 'store'])->name('public.absensi.store');
Route::get('/absensi/hearing-details', [AbsensiController::class, 'getHearingDetails'])->name('public.absensi.hearing-details');
Route::get('/absensi/success', [AbsensiController::class, 'success'])->name('public.absensi.success');

// Redirect bawaan Breeze /dashboard ke Admin Dashboard
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified']);

// Rute Admin Panel (Wajib Login & Verifikasi)
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');


    // CRUD Ruang Sidang
    Route::resource('ruang-sidang', RuangSidangController::class);

    // CRUD Perkara
    Route::resource('perkara', PerkaraController::class);

    // CRUD Jadwal Sidang
    Route::resource('jadwal-sidang', JadwalSidangController::class);
    Route::post('jadwal-sidang/{jadwal_sidang}/panggil', [JadwalSidangController::class, 'panggil'])->name('jadwal-sidang.panggil');

    // CRUD Pihak Berperkara per Jadwal Sidang
    Route::get('jadwal-sidang/{jadwal_sidang}/pihak', [PihakSidangController::class, 'index'])->name('pihak-sidang.index');
    Route::get('jadwal-sidang/{jadwal_sidang}/pihak-data', [PihakSidangController::class, 'getAttendanceData'])->name('pihak-sidang.data');
    Route::get('jadwal-sidang/{jadwal_sidang}/pihak/create', [PihakSidangController::class, 'create'])->name('pihak-sidang.create');
    Route::post('jadwal-sidang/{jadwal_sidang}/pihak', [PihakSidangController::class, 'store'])->name('pihak-sidang.store');
    Route::get('pihak-sidang/{pihak_sidang}/edit', [PihakSidangController::class, 'edit'])->name('pihak-sidang.edit');
    Route::put('pihak-sidang/{pihak_sidang}', [PihakSidangController::class, 'update'])->name('pihak-sidang.update');
    Route::delete('pihak-sidang/{pihak_sidang}', [PihakSidangController::class, 'destroy'])->name('pihak-sidang.destroy');

    // Monitoring Notifikasi WhatsApp
    Route::get('notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');

    // Laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan-data', [LaporanController::class, 'getLaporanData'])->name('laporan.data');
    Route::get('laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export-pdf');
    Route::get('laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel');

    // Integrasi SIPP
    Route::get('integrasi-sipp', [\App\Http\Controllers\Admin\SippController::class, 'index'])->name('integrasi-sipp.index');
    Route::post('integrasi-sipp/sync', [\App\Http\Controllers\Admin\SippController::class, 'syncNow'])->name('integrasi-sipp.sync');
});

// Profile Admin
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
