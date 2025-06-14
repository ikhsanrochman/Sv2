<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\KetersediaanSdm;

class SdmController extends Controller
{
    public function index()
    {
        $projects = Project::paginate(10);
        return view('super_admin.sdm.index', compact('projects'));
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $projects = Project::where('nama_proyek', 'like', "%{$search}%")
            ->orWhere('keterangan', 'like', "%{$search}%")
            ->paginate(10);
        return view('super_admin.sdm.search', compact('projects'));
    }

    public function detail($id)
    {
        $project = Project::findOrFail($id);
        return view('super_admin.sdm.detail', compact('project'));
    }

    public function create($project_id)
    {
        $project = Project::findOrFail($project_id);
        $availableUsers = User::whereDoesntHave('ketersediaanSdm', function($query) use ($project_id) {
            $query->where('project_id', $project_id);
        })->get();
        return view('super_admin.sdm.create', compact('project', 'availableUsers'));
    }

    public function store(Request $request, $project_id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $ketersediaanSdm = new KetersediaanSdm();
        $ketersediaanSdm->project_id = $project_id;
        $ketersediaanSdm->user_id = $request->user_id;
        $ketersediaanSdm->save();

        return response()->json(['success' => true]);
    }

    public function destroy($project_id, $user_id)
    {
        KetersediaanSdm::where('project_id', $project_id)
            ->where('user_id', $user_id)
            ->delete();

        return response()->json(['success' => true]);
    }
} 