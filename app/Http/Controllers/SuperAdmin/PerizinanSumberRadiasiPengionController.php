<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\PerizinanSumberRadiasiPengion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerizinanSumberRadiasiPengionController extends Controller
{
    public function index()
    {
        $projects = Project::with('perizinanSumberRadiasiPengion')->get();
        return view('super_admin.perizinan_sumber_radiasi_pengion.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $perizinanSumberRadiasiPengion = $project->perizinanSumberRadiasiPengion;
        return view('super_admin.perizinan_sumber_radiasi_pengion_detail', compact('project', 'perizinanSumberRadiasiPengion'));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'no_seri' => 'required|string|max:255',
            'aktivitas' => 'required|string|max:255',
            'tanggal_aktivitas' => 'required|date',
            'kv_ma' => 'nullable|string|max:255',
            'no_ktun' => 'required|string|max:255',
            'tanggal_berlaku' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $perizinan = new PerizinanSumberRadiasiPengion($request->all());
            $perizinan->project_id = $project->id;
            $perizinan->save();

            DB::commit();

            return redirect()
                ->route('super_admin.perizinan_sumber_radiasi_pengion.show', $project)
                ->with('success', 'Perizinan berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, PerizinanSumberRadiasiPengion $perizinan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'no_seri' => 'required|string|max:255',
            'aktivitas' => 'required|string|max:255',
            'tanggal_aktivitas' => 'required|date',
            'kv_ma' => 'nullable|string|max:255',
            'no_ktun' => 'required|string|max:255',
            'tanggal_berlaku' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $perizinan->update($request->all());

            DB::commit();

            return redirect()
                ->route('super_admin.perizinan_sumber_radiasi_pengion.show', $perizinan->project)
                ->with('success', 'Perizinan berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(PerizinanSumberRadiasiPengion $perizinan)
    {
        try {
            DB::beginTransaction();

            $project = $perizinan->project;
            $perizinan->delete();

            DB::commit();

            return redirect()
                ->route('super_admin.perizinan_sumber_radiasi_pengion.show', $project)
                ->with('success', 'Perizinan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
