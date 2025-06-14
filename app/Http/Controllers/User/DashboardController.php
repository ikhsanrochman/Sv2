<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PemantauanDosisTld;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalDosis = PemantauanDosisTld::where('user_id', $user->id)->sum('dosis');
        return view('user.dashboard', compact('totalDosis'));
    }
} 