<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengangkutanSumberRadioaktifController extends Controller
{
    public function index()
    {
        return view('super_admin.pengangkutan_sumber_radioaktif');
    }
}
