<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PendaftaranController as AdminPendaftaranController;
use App\Http\Controllers\Admin\AbsensiController as AdminAbsensiController;
use App\Http\Controllers\Admin\LaporanAkhirController as AdminLaporanAkhirController;
use App\Http\Controllers\Peserta\PendaftaranController as PesertaPendaftaranController;
use App\Http\Controllers\Peserta\AbsensiController as PesertaAbsensiController;
use App\Http\Controllers\Peserta\LaporanController as PesertaLaporanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/bidang', [PublicController::class, 'bidang'])->name('bidang.index');
Route::get('/bidang/{bidang}', [PublicController::class, 'bidangDetail'])->name('bidang.show');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Peserta Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'isPeserta'])
    ->prefix('peserta')
    ->name('peserta.')
    ->group(function () {

        Route::get('/dashboard', [PesertaPendaftaranController::class, 'dashboard'])
            ->name('dashboard');

        // Pendaftaran
        Route::get('/pendaftaran',                      [PesertaPendaftaranController::class, 'show'])->name('pendaftaran.show');
        Route::get('/pendaftaran/daftar',               [PesertaPendaftaranController::class, 'create'])->name('pendaftaran.create');
        Route::post('/pendaftaran',                     [PesertaPendaftaranController::class, 'store'])->name('pendaftaran.store');
        Route::get('/pendaftaran/{pendaftaran}/edit',   [PesertaPendaftaranController::class, 'edit'])->name('pendaftaran.edit');
        Route::put('/pendaftaran/{pendaftaran}',        [PesertaPendaftaranController::class, 'update'])->name('pendaftaran.update');
        Route::delete('/pendaftaran/{pendaftaran}',     [PesertaPendaftaranController::class, 'destroy'])->name('pendaftaran.destroy');

        // Absensi
        Route::get('/absensi',         [PesertaAbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/absensi/create',  [PesertaAbsensiController::class, 'create'])->name('absensi.create');
        Route::post('/absensi',        [PesertaAbsensiController::class, 'store'])->name('absensi.store');
        Route::put('/absensi/{id}',    [PesertaAbsensiController::class, 'update'])->name('absensi.update');
        Route::delete('/absensi/{id}', [PesertaAbsensiController::class, 'destroy'])->name('absensi.destroy');

        // Laporan Akhir
        Route::get('/laporan',           [PesertaLaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/create',    [PesertaLaporanController::class, 'create'])->name('laporan.create');
        Route::post('/laporan',          [PesertaLaporanController::class, 'store'])->name('laporan.store');
        Route::get('/laporan/{id}/edit', [PesertaLaporanController::class, 'edit'])->name('laporan.edit');
        Route::put('/laporan/{id}',      [PesertaLaporanController::class, 'update'])->name('laporan.update');
        Route::delete('/laporan/{id}',   [PesertaLaporanController::class, 'destroy'])->name('laporan.destroy');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'isAdmin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Pendaftaran
        Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
            Route::get('/',                              [AdminPendaftaranController::class, 'index'])->name('index');
            Route::get('/{pendaftaran}',                 [AdminPendaftaranController::class, 'show'])->name('show');
            Route::patch('/{pendaftaran}/status',        [AdminPendaftaranController::class, 'updateStatus'])->name('update-status');
            Route::get('/{pendaftaran}/download/{type}', [AdminPendaftaranController::class, 'downloadFile'])->name('download');
        });

        // Bidang
        Route::resource('bidang', \App\Http\Controllers\Admin\BidangController::class);

        // Absensi
        Route::prefix('absensi')->name('absensi.')->group(function () {
            Route::get('/',                    [AdminAbsensiController::class, 'index'])->name('index');
            Route::patch('/{absensi}/approve', [AdminAbsensiController::class, 'approve'])->name('approve');
            Route::post('/bulk-approve',       [AdminAbsensiController::class, 'bulkApprove'])->name('bulkApprove');
        });

        // Laporan Akhir
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/',              [AdminLaporanAkhirController::class, 'index'])->name('index');
            Route::get('/{laporanAkhir}', [AdminLaporanAkhirController::class, 'show'])->name('show');
            Route::patch('/{laporanAkhir}', [AdminLaporanAkhirController::class, 'update'])->name('update');
        });

        // Management User
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    });