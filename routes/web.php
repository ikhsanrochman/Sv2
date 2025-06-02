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
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/super_admin/dashboard', function () {
        return view('super_admin.dashboard');
    })->name('super_admin.dashboard');
    Route::get('/super_admin/ketersediaan-sdm', [SdmController::class, 'index'])->name('super_admin.ketersediaan_sdm');
    Route::post('/super_admin/ketersediaan_sdm', [SdmController::class, 'store'])->name('super_admin.ketersediaan_sdm.store');
    Route::get('super_admin/ketersediaan_sdm/{id}/detail', [SdmController::class, 'detail'])->name('super_admin.ketersediaan_sdm.detail');
    Route::get('/super-admin/kelola-akun', [KelolaAkunController::class, 'index'])->name('super_admin.kelola_akun');
    Route::post('/super-admin/kelola-akun', [KelolaAkunController::class, 'store'])->name('super_admin.kelola_akun.store');
    Route::get('/super-admin/perizinan-sumber-radiasi-pengion', [PerizinanSumberRadiasiPengionController::class, 'index'])->name('super_admin.perizinan_sumber_radiasi_pengion');

    // New routes for Super Admin
    Route::get('/super-admin/pemantauan-tld', [PemantauanTldController::class, 'index'])->name('super_admin.pemantauan_tld');
    Route::get('/super-admin/pemantauan-dosis-pendose', [PemantauanDosisPendoseController::class, 'index'])->name('super_admin.pemantauan_dosis_pendose');
    Route::get('/super-admin/pengangkutan-sumber-radioaktif', [PengangkutanSumberRadioaktifController::class, 'index'])->name('super_admin.pengangkutan_sumber_radioaktif');
    Route::get('/pemantauan-tld', [PemantauanTldController::class, 'index'])->name('super_admin.pemantauan_tld');
    Route::get('/pemantauan-tld/{id}', [PemantauanTldController::class, 'detail'])->name('super_admin.pemantauan_tld.detail');
    Route::get('/pemantauan-dosis-pendose', [PemantauanDosisPendoseController::class, 'index'])->name('super_admin.pemantauan_dosis_pendose');
    Route::get('/pemantauan-dosis-pendose/{id}', [PemantauanDosisPendoseController::class, 'detail'])->name('super_admin.pemantauan_dosis_pendose.detail');

    // Perizinan Sumber Radiasi Pengion Routes
    Route::get('/perizinan-sumber-radiasi-pengion', [PerizinanSumberRadiasiPengionController::class, 'index'])
        ->name('perizinan-sumber-radiasi-pengion.index');
    Route::get('/perizinan-sumber-radiasi-pengion/{projectId}', [PerizinanSumberRadiasiPengionController::class, 'show'])
        ->name('perizinan-sumber-radiasi-pengion.show');

    // Project Routes
    Route::resource('projects', ProjectController::class);

    Route::get('/laporan', [LaporanController::class, 'index'])->name('super_admin.laporan');
    Route::get('/laporan/{id}', [LaporanController::class, 'show'])->name('super_admin.laporan.project_detail');
});

// ==========================================
// ROUTE UNTUK HALAMAN ADMIN
// ==========================================
// Hanya bisa diakses oleh admin (role 2)
// Jika user biasa mencoba akses, akan dapat pesan error
// URL: http://example.com/admin/dashboard
Route::middleware(['auth', 'role:2'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/admin/pengangkutan-sumber', function () {
        return view('admin.pengangkutan');
    })->name('admin.pengangkutan');

    
});

// ==========================================
// ROUTE UNTUK HALAMAN USER
// ==========================================
// Hanya bisa diakses oleh user (role 3)
// Jika admin mencoba akses, akan dapat pesan error
// URL: http://example.com/user/dashboard
Route::middleware(['auth', 'role:3'])->get('/user/dashboard', function () {
    return view('user.dashboard');
})->name('user.dashboard');
