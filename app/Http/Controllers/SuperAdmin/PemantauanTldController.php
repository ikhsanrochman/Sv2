<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class PemantauanTldController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('super_admin.pemantauan_tld', compact('projects'));
    }

    public function detail($id)
    {
        $project = Project::findOrFail($id);
        $users = $project->users()->with('pemantauanDosisTld')->get();
        return view('super_admin.pemantauan_tld_detail', compact('project', 'users'));
    }
}
