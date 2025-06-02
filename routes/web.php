<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\SdmController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SuperAdmin\KelolaAkunController;
use App\Http\Controllers\SuperAdmin\PerizinanSumberRadiasiPengionController;
use App\Http\Controllers\SuperAdmin\PemantauanTldController;
use App\Http\Controllers\SuperAdmin\PemantauanDosisPendoseController;
use App\Http\Controllers\SuperAdmin\PengangkutanSumberRadioaktifController;
use App\Http\Controllers\SuperAdmin\ProjectController;
use App\Http\Controllers\SuperAdmin\LaporanController;

// ==========================================
// ROUTE UNTUK HALAMAN UTAMA (LANDING PAGE)
// ==========================================
// Ketika pengunjung membuka website (http://example.com/), 
// mereka akan melihat halaman landing.blade.php
Route::get('/', function () {
    return view('landing');
});

// ==========================================
// ROUTE UNTUK SISTEM LOGIN/REGISTER
// ==========================================
// Ini akan membuat route otomatis untuk:
// - Login    : http://example.com/login
// - Register : http://example.com/register
// - Logout   : http://example.com/logout
// - Reset Password : http://example.com/password/reset
Auth::routes();

// ==========================================
// ROUTE UNTUK HALAMAN SUPER ADMIN
// ==========================================
// Hanya bisa diakses oleh super admin (role 1)
Route::middleware(['auth', 'role:1'])->prefix('super-admin')->name('super_admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('super_admin.dashboard');
    })->name('dashboard');

    // Ketersediaan SDM
    Route::get('/ketersediaan-sdm', [SdmController::class, 'index'])->name('ketersediaan_sdm');
    Route::post('/ketersediaan-sdm', [SdmController::class, 'store'])->name('ketersediaan_sdm.store');
    Route::get('/ketersediaan-sdm/{id}/detail', [SdmController::class, 'detail'])->name('ketersediaan_sdm.detail');

    // Kelola Akun
    Route::get('/kelola-akun', [KelolaAkunController::class, 'index'])->name('kelola_akun');
    Route::post('/kelola-akun', [KelolaAkunController::class, 'store'])->name('kelola_akun.store');

    // Perizinan Sumber Radiasi Pengion
    Route::get('/perizinan-sumber-radiasi-pengion', [PerizinanSumberRadiasiPengionController::class, 'index'])
        ->name('perizinan_sumber_radiasi_pengion');
    Route::get('/perizinan-sumber-radiasi-pengion/{projectId}', [PerizinanSumberRadiasiPengionController::class, 'show'])
        ->name('perizinan_sumber_radiasi_pengion.show');

    // Pemantauan TLD
    Route::get('/pemantauan-tld', [PemantauanTldController::class, 'index'])->name('pemantauan_tld');
    Route::get('/pemantauan-tld/{id}', [PemantauanTldController::class, 'detail'])->name('pemantauan_tld.detail');

    // Pemantauan Dosis Pendose
    Route::get('/pemantauan-dosis-pendose', [PemantauanDosisPendoseController::class, 'index'])->name('pemantauan_dosis_pendose');
    Route::get('/pemantauan-dosis-pendose/{id}', [PemantauanDosisPendoseController::class, 'detail'])->name('pemantauan_dosis_pendose.detail');

    // Pengangkutan Sumber Radioaktif
    Route::get('/pengangkutan-sumber-radioaktif', [PengangkutanSumberRadioaktifController::class, 'index'])
        ->name('pengangkutan_sumber_radioaktif');

    // Projects
    Route::resource('projects', ProjectController::class);

    // Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/{id}', [LaporanController::class, 'projectDetail'])->name('laporan.project_detail');
});

// ==========================================
// ROUTE UNTUK HALAMAN ADMIN
// ==========================================
// Hanya bisa diakses oleh admin (role 2)
// Jika user biasa mencoba akses, akan dapat pesan error
// URL: http://example.com/admin/dashboard
Route::middleware(['auth', 'role:2'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/pengangkutan-sumber', function () {
        return view('admin.pengangkutan');
    })->name('pengangkutan');
});

// ==========================================
// ROUTE UNTUK HALAMAN USER
// ==========================================
// Hanya bisa diakses oleh user (role 3)
// Jika admin mencoba akses, akan dapat pesan error
// URL: http://example.com/user/dashboard
Route::middleware(['auth', 'role:3'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');
});
