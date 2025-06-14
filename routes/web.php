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
use App\Http\Controllers\SuperAdmin\ProjectController as SuperAdminProjectController;
use App\Http\Controllers\SuperAdmin\LaporanController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\PerizinanController;
use App\Http\Controllers\Admin\PemantauanController;
use App\Http\Controllers\User\UserProfileController;

// ==========================================
// ROUTE UNTUK HALAMAN UTAMA (LANDING PAGE)
// ==========================================
// Ketika pengunjung membuka website (http://example.com/), 
// mereka akan melihat halaman landing.blade.php
Route::get('/', function () {
    return view('landing');
})->name('landing');

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
    Route::get('/ketersediaan-sdm', [App\Http\Controllers\SuperAdmin\SdmController::class, 'index'])->name('ketersediaan_sdm');
    Route::get('/ketersediaan-sdm/search', [App\Http\Controllers\SuperAdmin\SdmController::class, 'search'])->name('ketersediaan_sdm.search');
    Route::get('/ketersediaan-sdm/{project_id}/create', [App\Http\Controllers\SuperAdmin\SdmController::class, 'create'])->name('ketersediaan_sdm.create');
    Route::post('/ketersediaan-sdm', [App\Http\Controllers\SuperAdmin\SdmController::class, 'store'])->name('ketersediaan_sdm.store');
    Route::get('/ketersediaan-sdm/{id}', [App\Http\Controllers\SuperAdmin\SdmController::class, 'detail'])->name('ketersediaan_sdm.detail');
    Route::get('/ketersediaan-sdm/{id}/edit', [App\Http\Controllers\SuperAdmin\SdmController::class, 'edit'])->name('ketersediaan_sdm.edit');
    Route::put('/ketersediaan-sdm/{id}', [App\Http\Controllers\SuperAdmin\SdmController::class, 'update'])->name('ketersediaan_sdm.update');
    Route::delete('/ketersediaan-sdm/{id}', [App\Http\Controllers\SuperAdmin\SdmController::class, 'destroy'])->name('ketersediaan_sdm.destroy');

    // Kelola Akun
    Route::get('/kelola-akun', [KelolaAkunController::class, 'index'])->name('kelola_akun');
    Route::post('/kelola-akun', [KelolaAkunController::class, 'store'])->name('kelola_akun.store');
    Route::post('/kelola-akun/toggle-status/{id}', [KelolaAkunController::class, 'toggleStatus'])->name('kelola_akun.toggle_status');

    // Perizinan Sumber Radiasi Pengion
    Route::get('/perizinan-sumber-radiasi-pengion', [PerizinanSumberRadiasiPengionController::class, 'index'])
        ->name('perizinan_sumber_radiasi_pengion');
    Route::get('/perizinan-sumber-radiasi-pengion/{project}', [PerizinanSumberRadiasiPengionController::class, 'show'])
        ->name('perizinan_sumber_radiasi_pengion.show');
    Route::post('/perizinan-sumber-radiasi-pengion/{project}', [PerizinanSumberRadiasiPengionController::class, 'store'])
        ->name('perizinan_sumber_radiasi_pengion.store');
    Route::put('/perizinan-sumber-radiasi-pengion/{perizinan}', [PerizinanSumberRadiasiPengionController::class, 'update'])
        ->name('perizinan_sumber_radiasi_pengion.update');
    Route::delete('/perizinan-sumber-radiasi-pengion/{perizinan}', [PerizinanSumberRadiasiPengionController::class, 'destroy'])
        ->name('perizinan_sumber_radiasi_pengion.destroy');

    // Pemantauan routes
    Route::get('/pemantauan', [App\Http\Controllers\SuperAdmin\PemantauanController::class, 'index'])->name('pemantauan.index');
    Route::get('/pemantauan/search', [App\Http\Controllers\SuperAdmin\PemantauanController::class, 'search'])->name('pemantauan.search');
    Route::get('/pemantauan/{project}/tld', [App\Http\Controllers\SuperAdmin\PemantauanController::class, 'tld'])->name('pemantauan.tld');
    Route::get('/pemantauan/{project}/pendos', [App\Http\Controllers\SuperAdmin\PemantauanController::class, 'pendos'])->name('pemantauan.pendos');
    Route::get('/pemantauan/{projectId}/tld/{userId}/detail', [App\Http\Controllers\SuperAdmin\PemantauanController::class, 'tldDetail'])->name('pemantauan.tld.detail');
    Route::get('/pemantauan/{projectId}/tld/{userId}/tambah', [App\Http\Controllers\SuperAdmin\PemantauanController::class, 'tldCreate'])->name('pemantauan.tld.create');
    Route::post('/pemantauan/{projectId}/tld/{userId}/store', [App\Http\Controllers\SuperAdmin\PemantauanController::class, 'tldStore'])->name('pemantauan.tld.store');
    Route::get('/pemantauan/{projectId}/tld/{userId}/edit/{dosisId}', [App\Http\Controllers\SuperAdmin\PemantauanController::class, 'tldEdit'])->name('pemantauan.tld.edit');
    Route::put('/pemantauan/{projectId}/tld/{userId}/update/{dosisId}', [App\Http\Controllers\SuperAdmin\PemantauanController::class, 'tldUpdate'])->name('pemantauan.tld.update');
    Route::delete('/pemantauan/{projectId}/tld/{userId}/destroy/{dosisId}', [App\Http\Controllers\SuperAdmin\PemantauanController::class, 'tldDestroy'])->name('pemantauan.tld.destroy');

    // Pemantauan Dosis Pendose
    Route::get('/pemantauan-dosis-pendose', [PemantauanDosisPendoseController::class, 'index'])->name('pemantauan_dosis_pendose');
    Route::get('/pemantauan-dosis-pendose/{id}', [PemantauanDosisPendoseController::class, 'detail'])->name('pemantauan_dosis_pendose.detail');

    // Pengangkutan Sumber Radioaktif
    Route::get('/pengangkutan-sumber-radioaktif', [PengangkutanSumberRadioaktifController::class, 'index'])
        ->name('pengangkutan_sumber_radioaktif');

    // Projects
    Route::resource('projects', SuperAdminProjectController::class);
    Route::get('/projects/search', [SuperAdminProjectController::class, 'search'])->name('projects.search');

    // Perizinan
    Route::get('/perizinan', [App\Http\Controllers\SuperAdmin\PerizinanController::class, 'index'])->name('perizinan.index');
    Route::get('/perizinan/search', [App\Http\Controllers\SuperAdmin\PerizinanController::class, 'search'])->name('perizinan.search');
    Route::get('/perizinan/{project_id}/create', [App\Http\Controllers\SuperAdmin\PerizinanController::class, 'create'])->name('perizinan.create');
    Route::post('/perizinan', [App\Http\Controllers\SuperAdmin\PerizinanController::class, 'store'])->name('perizinan.store');
    Route::get('/perizinan/{id}', [App\Http\Controllers\SuperAdmin\PerizinanController::class, 'detail'])->name('perizinan.detail');
    Route::get('/perizinan/{id}/edit', [App\Http\Controllers\SuperAdmin\PerizinanController::class, 'edit'])->name('perizinan.edit');
    Route::put('/perizinan/{id}', [App\Http\Controllers\SuperAdmin\PerizinanController::class, 'update'])->name('perizinan.update');
    Route::delete('/perizinan/{id}', [App\Http\Controllers\SuperAdmin\PerizinanController::class, 'destroy'])->name('perizinan.destroy');

    // Ketersediaan SDM
    Route::prefix('sdm')->name('sdm.')->group(function () {
        Route::get('/', [App\Http\Controllers\SuperAdmin\SdmController::class, 'index'])->name('index');
        Route::get('/search', [App\Http\Controllers\SuperAdmin\SdmController::class, 'search'])->name('search');
        Route::get('/{project_id}/create', [App\Http\Controllers\SuperAdmin\SdmController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\SuperAdmin\SdmController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\SuperAdmin\SdmController::class, 'detail'])->name('detail');
        Route::delete('/{project_id}/user/{user_id}', [App\Http\Controllers\SuperAdmin\SdmController::class, 'destroy'])->name('destroy');
    });

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

    // Pemantauan routes
    Route::get('/pemantauan', [PemantauanController::class, 'index'])->name('pemantauan.index');
    Route::get('/pemantauan/search', [PemantauanController::class, 'search'])->name('pemantauan.search');
    Route::get('/pemantauan/{project}/tld', [PemantauanController::class, 'tld'])->name('pemantauan.tld');
    Route::get('/pemantauan/{project}/pendos', [PemantauanController::class, 'pendos'])->name('pemantauan.pendos');
    Route::get('/pemantauan/{projectId}/tld/{userId}/detail', [PemantauanController::class, 'tldDetail'])->name('pemantauan.tld.detail');
    Route::get('/pemantauan/{projectId}/tld/{userId}/tambah', [PemantauanController::class, 'tldCreate'])->name('pemantauan.tld.create');
    Route::post('/pemantauan/{projectId}/tld/{userId}/store', [PemantauanController::class, 'tldStore'])->name('pemantauan.tld.store');
    Route::get('/pemantauan/{projectId}/tld/{userId}/edit/{dosisId}', [PemantauanController::class, 'tldEdit'])->name('pemantauan.tld.edit');
    Route::put('/pemantauan/{projectId}/tld/{userId}/update/{dosisId}', [PemantauanController::class, 'tldUpdate'])->name('pemantauan.tld.update');
    Route::delete('/pemantauan/{projectId}/tld/{userId}/destroy/{dosisId}', [PemantauanController::class, 'tldDestroy'])->name('pemantauan.tld.destroy');

    // SDM Management routes
    Route::get('/sdm', [App\Http\Controllers\Admin\SdmController::class, 'index'])->name('sdm.index');
    Route::get('/sdm/{id}/detail', [App\Http\Controllers\Admin\SdmController::class, 'detail'])->name('sdm.detail');
    Route::get('/sdm/{id}/create', [App\Http\Controllers\Admin\SdmController::class, 'create'])->name('sdm.create');
    Route::post('/sdm/{id}/store', [App\Http\Controllers\Admin\SdmController::class, 'store'])->name('sdm.store');
    Route::delete('/sdm/{project}/user/{id}', [App\Http\Controllers\Admin\SdmController::class, 'removeUser'])->name('sdm.destroy');
    Route::get('/sdm/search-users', [App\Http\Controllers\Admin\SdmController::class, 'searchUsers'])->name('sdm.search-users');

    Route::get('/pengangkutan-sumber', function () {
        return view('admin.pengangkutan');
    })->name('pengangkutan');

    // Update perizinan route to use controller
    Route::get('/perizinan', [PerizinanController::class, 'index'])->name('perizinan.index');
    Route::get('/perizinan/search', [PerizinanController::class, 'search'])->name('perizinan.search');
    Route::get('/perizinan/{project_id}/create', [PerizinanController::class, 'create'])->name('perizinan.create');
    Route::post('/perizinan', [PerizinanController::class, 'store'])->name('perizinan.store');
    Route::get('/perizinan/{id}', [PerizinanController::class, 'detail'])->name('perizinan.detail');
    Route::get('/perizinan/{id}/edit', [PerizinanController::class, 'edit'])->name('perizinan.edit');
    Route::put('/perizinan/{id}', [PerizinanController::class, 'update'])->name('perizinan.update');
    Route::delete('/perizinan/{id}', [PerizinanController::class, 'destroy'])->name('perizinan.destroy');

    // Projects
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/search', [ProjectController::class, 'search'])->name('projects.search');
});

// ==========================================
// ROUTE UNTUK HALAMAN USER
// ==========================================
// Hanya bisa diakses oleh user (role 3)
// Jika admin mencoba akses, akan dapat pesan error
// URL: http://example.com/user/dashboard
Route::middleware(['auth', 'role:3'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
});
