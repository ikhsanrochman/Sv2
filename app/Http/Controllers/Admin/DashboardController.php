<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PemantauanDosisPendose;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPekerja = User::count();
        
        // Get warnings for Pendos for the current month, limited to 3
        $peringatanPendos = PemantauanDosisPendose::with('user')
            ->whereMonth('tanggal_pengukuran', Carbon::now()->month)
            ->whereYear('tanggal_pengukuran', Carbon::now()->year)
            ->orderBy('hasil_pengukuran', 'desc') 
            ->limit(3)
            ->get();
            
        return view('admin.dashboard', compact('totalPekerja', 'peringatanPendos'));
    }
} 