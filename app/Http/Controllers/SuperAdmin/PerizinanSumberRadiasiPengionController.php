<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\PerizinanSumberRadiasiPengion;

class PerizinanSumberRadiasiPengionController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('super_admin.perizinan_sumber_radiasi_pengion', compact('projects'));
    }

    public function show($projectId)
    {
        $project = Project::findOrFail($projectId);
        $perizinanSumberRadiasiPengion = PerizinanSumberRadiasiPengion::where('project_id', $projectId)->get();
        
        return view('super_admin.perizinan_sumber_radiasi_pengion_detail', compact('project', 'perizinanSumberRadiasiPengion'));
    }
}
