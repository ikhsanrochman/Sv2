<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->paginate(10);
        return view('super_admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('super_admin.projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        Project::create($request->all());

        return redirect()->route('super_admin.projects.index')->with('success', 'Project berhasil ditambahkan!');
    }

    public function edit(Project $project)
    {
        return view('super_admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $project->update($request->all());

        return redirect()->route('super_admin.projects.index')->with('success', 'Project berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('super_admin.projects.index')->with('success', 'Project berhasil dihapus!');
    }

    public function search(Request $request)
    {
        try {
            $search = $request->search;
            $projects = Project::where(function($query) use ($search) {
                $query->where('nama_proyek', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

            $view = view('super_admin.projects.search', compact('projects'))->render();
            return response()->json(['html' => $view]);
        } catch (\Exception $e) {
            \Log::error('Project search error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mencari data'], 500);
        }
    }
} 