<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\SdmController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SuperAdmin\KelolaAkunController;

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
    Route::post('/super_admin/tasks', [SdmController::class, 'store'])->name('super_admin.tasks.store');
    Route::get('super_admin/tasks/{task}/detail', [SdmController::class, 'detail'])->name('super_admin.tasks.detail');
    Route::get('/super-admin/kelola-akun', [KelolaAkunController::class, 'index'])->name('super_admin.kelola_akun');
    Route::post('/super-admin/kelola-akun', [KelolaAkunController::class, 'store'])->name('super_admin.kelola_akun.store');
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
