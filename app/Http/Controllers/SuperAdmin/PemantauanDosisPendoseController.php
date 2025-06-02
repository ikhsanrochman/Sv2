<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\PemantauanDosisPendose;

class PemantauanDosisPendoseController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('super_admin.pemantauan_dosis_pendose', compact('projects'));
    }

    public function detail($id)
    {
        $project = Project::findOrFail($id);
        $pemantauan = PemantauanDosisPendose::with('user')
            ->where('project_id', $id)
            ->orderBy('tanggal_pengukuran', 'desc')
            ->get();
        
        return view('super_admin.pemantauan_dosis_pendose_detail', compact('project', 'pemantauan'));
    }
}
