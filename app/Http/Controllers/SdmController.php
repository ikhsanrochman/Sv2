<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\KetersediaanSdm;
use App\Http\Controllers\Controller;
use App\Models\Project;

class SdmController extends Controller
{
    public function index()
{
        $projects = Project::all();
        return view('super_admin.ketersediaan_sdm', compact('projects'));
}

public function store(Request $request)
{
    $request->validate([
            'project_id' => 'required|exists:projects,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'keterangan' => 'nullable|string',
            'users' => 'required|array'
        ]);

        $ketersediaan_sdm = KetersediaanSdm::create([
            'project_id' => $request->project_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan
        ]);

        $ketersediaan_sdm->users()->attach($request->users);

        return redirect()->route('super_admin.ketersediaan_sdm')->with('success', 'Ketersediaan SDM berhasil ditambahkan.');
}

public function detail($id)
{
        $project = Project::with('ketersediaanSdm.users')->findOrFail($id);
        return view('super_admin.ketersediaan_sdm_detail', compact('project'));
}
}
