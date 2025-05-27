<?php

namespace App\Http\Middleware;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

// ==========================================
// MIDDLEWARE UNTUK CEK ROLE/PERAN PENGGUNA
// ==========================================
// Middleware ini bertugas mengecek apakah user 
// boleh mengakses halaman tertentu atau tidak
class RoleMiddleware
{
    /**
     * Fungsi untuk mengecek role pengguna
     * 
     * @param $request    : Data request dari user
     * @param $next      : Fungsi yang akan dijalankan selanjutnya
     * @param $role_id   : ID role yang diizinkan (1=user, 2=admin, 3=user)
     */
    public function handle($request, Closure $next, $role_id)
    {
        // Cek apakah user sudah login dan role_id nya sesuai
        if (Auth::check() && Auth::user()->role_id == $role_id) {
            // Kalau sesuai, boleh lanjut ke halaman yang dituju
            return $next($request);
        }

        // Kalau tidak sesuai, tampilkan pesan error
        abort(403, 'Unauthorized access.');
    }
}
