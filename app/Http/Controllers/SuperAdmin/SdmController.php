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

        try {
            // Check if user is already assigned to this project
            $existingAssignment = KetersediaanSdm::where('project_id', $project_id)
                ->whereHas('users', function($query) use ($request) {
                    $query->where('user_id', $request->user_id);
                })
                ->first();

            if ($existingAssignment) {
                return redirect()->back()
                    ->with('error', 'Pekerja sudah terdaftar dalam proyek ini.')
                    ->withInput();
            }

            // Get or create ketersediaan SDM record for this project
            $ketersediaanSdm = KetersediaanSdm::firstOrCreate([
                'project_id' => $project_id
            ]);

            // Attach the user to the ketersediaan SDM
            $ketersediaanSdm->users()->attach($request->user_id);

            return redirect()->route('super_admin.sdm.detail', $project_id)
                ->with('success', 'Pekerja berhasil ditambahkan ke proyek.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan pekerja.')
                ->withInput();
        }
    }

    public function destroy($project_id, $user_id)
    {
        KetersediaanSdm::where('project_id', $project_id)
            ->where('user_id', $user_id)
            ->delete();

        return response()->json(['success' => true]);
    }
} 