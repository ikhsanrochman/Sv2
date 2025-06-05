<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\PemantauanDosisTld;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
        $users = $project->users;
        return view('super_admin.pemantauan_tld_detail', compact('project', 'users'));
    }

    public function getDosisData(Project $project)
    {
        $dosisData = PemantauanDosisTld::where('project_id', $project->id)
            ->select('user_id', 'tanggal_pemantauan', 'dosis')
            ->get()
            ->map(function($item) {
                return [
                    'user_id' => $item->user_id,
                    'tanggal_pemantauan' => $item->tanggal_pemantauan->format('Y-m-d'),
                    'dosis' => $item->dosis
                ];
            });

        return response()->json($dosisData);
    }

    public function storeDosis(Request $request, Project $project)
    {
        try {
            // Log the incoming request data
            Log::info('Storing TLD dose data:', $request->all());

            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'tanggal_pemantauan' => 'required|date',
                'dosis' => 'required|numeric|min:0',
            ]);

            // Parse tanggal dan pastikan tahun yang benar
            $tanggal_pemantauan = $validated['tanggal_pemantauan'];

            Log::info('Processed date:', ['original' => $validated['tanggal_pemantauan'], 'processed' => $tanggal_pemantauan]);

            // Check for duplicate entry
            $existingDosis = PemantauanDosisTld::where([
                'project_id' => $project->id,
                'user_id' => $validated['user_id'],
                'tanggal_pemantauan' => $tanggal_pemantauan
            ])->first();

            if ($existingDosis) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data dosis untuk SDM ini pada tanggal tersebut sudah ada. Silakan pilih tanggal lain atau edit data yang sudah ada.'
                    ], 422);
                }
                return redirect()->back()->with('error', 'Data dosis untuk SDM ini pada tanggal tersebut sudah ada. Silakan pilih tanggal lain atau edit data yang sudah ada.');
            }

            DB::beginTransaction();

            $dosis = PemantauanDosisTld::create([
                'user_id' => $validated['user_id'],
                'project_id' => $project->id,
                'tanggal_pemantauan' => $tanggal_pemantauan,
                'dosis' => $validated['dosis'],
            ]);

            DB::commit();

            // Log successful creation
            Log::info('TLD dose data created successfully:', ['dosis' => $dosis->toArray()]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data dosis berhasil ditambahkan',
                    'data' => $dosis
                ]);
            }

            return redirect()->back()->with('success', 'Data dosis berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error
            Log::error('Error storing TLD dose data:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateDosis(Request $request, PemantauanDosisTld $dosis)
    {
        try {
            $validated = $request->validate([
                'tanggal_pemantauan' => 'required|date',
                'dosis' => 'required|numeric|min:0',
            ]);

            // Parse tanggal dan pastikan tahun yang benar
            $tanggal_pemantauan = $validated['tanggal_pemantauan'];

            // Check for duplicate entry, excluding current record
            $existingDosis = PemantauanDosisTld::where([
                'project_id' => $dosis->project_id,
                'user_id' => $dosis->user_id,
                'tanggal_pemantauan' => $tanggal_pemantauan
            ])->where('id', '!=', $dosis->id)->first();

            if ($existingDosis) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data dosis untuk SDM ini pada tanggal tersebut sudah ada. Silakan pilih tanggal lain.'
                ], 422);
            }

            DB::beginTransaction();

            $dosis->update([
                'tanggal_pemantauan' => $tanggal_pemantauan,
                'dosis' => $validated['dosis'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data dosis berhasil diperbarui',
                'data' => $dosis
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteDosis(PemantauanDosisTld $dosis)
    {
        try {
            DB::beginTransaction();

            $projectId = $dosis->project_id;
            $dosis->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data dosis berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
