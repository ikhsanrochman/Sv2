<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\PemantauanDosisTld;
use App\Models\PemantauanDosisPendose;
use Illuminate\Support\Facades\DB;

class PemantauanController extends Controller
{
    public function index()
    {
        $projects = Project::paginate(10);
        return view('super_admin.pemantauan.index', compact('projects'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $projects = Project::where('nama_proyek', 'LIKE', "%{$search}%")
            ->orWhere('keterangan', 'LIKE', "%{$search}%")
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('super_admin.pemantauan.search', compact('projects'))->render()
            ]);
        }

        return view('super_admin.pemantauan.index', compact('projects'));
    }

    public function tld($id)
    {
        $project = Project::with('ketersediaanSdm.users')->findOrFail($id);
        $dosisTlds = PemantauanDosisTld::where('project_id', $id)->get();
        
        $users = $project->ketersediaanSdm->flatMap->users->unique('id');

        // Prepare user data as JSON string in the controller
        $projectUsersJson = $users->map(function($user) {
            return [
                'id' => $user->id,
                'nama' => $user->nama,
                'npr' => $user->npr,
                'pemantauanDosisTld' => $user->pemantauanDosisTld->map(function($dosis) {
                    return [
                        'tanggal_pemantauan' => $dosis->tanggal_pemantauan,
                        'dosis' => $dosis->dosis,
                        'year' => substr($dosis->tanggal_pemantauan, 0, 4)
                    ];
                })->toArray()
            ];
        })->toJson();

        return view('super_admin.pemantauan.tld.tld', compact('project', 'dosisTlds', 'projectUsersJson'));
    }

    public function pendos($id)
    {
        $project = Project::with(['ketersediaanSdm.users' => function($query) {
            $query->with('pemantauanDosisPendose');
        }])->findOrFail($id);
        
        $users = $project->ketersediaanSdm->flatMap->users->unique('id');

        // Prepare user data as JSON string in the controller
        $projectUsersJson = $users->map(function($user) {
            return [
                'id' => $user->id,
                'nama' => $user->nama,
                'npr' => $user->npr,
                'pemantauanDosisPendos' => $user->pemantauanDosisPendose->map(function($dosis) {
                    return [
                        'tanggal_pemantauan' => $dosis->tanggal_pengukuran,
                        'dosis' => $dosis->hasil_pengukuran,
                        'year' => substr($dosis->tanggal_pengukuran, 0, 4)
                    ];
                })->toArray()
            ];
        })->toJson();

        return view('super_admin.pemantauan.pendos.pendos', compact('project', 'projectUsersJson'));
    }

    public function tldDetail($projectId, $userId)
    {
        $project = Project::with('ketersediaanSdm.users')->findOrFail($projectId);
        $user = $project->ketersediaanSdm->flatMap->users->where('id', $userId)->firstOrFail();
        $dosisTlds = $user->pemantauanDosisTld; // Get all TLD doses for this user

        return view('super_admin.pemantauan.tld.detail', compact('project', 'user', 'dosisTlds'));
    }

    public function tldCreate($projectId, $userId)
    {
        $project = Project::with('ketersediaanSdm.users')->findOrFail($projectId);
        $user = $project->ketersediaanSdm->flatMap->users->where('id', $userId)->firstOrFail();

        return view('super_admin.pemantauan.tld.create', compact('project', 'user'));
    }

    public function tldStore(Request $request, $projectId, $userId)
    {
        try {
            $validated = $request->validate([
                'tanggal_pemantauan' => 'required|date',
                'dosis' => 'required|numeric|min:0',
            ]);

            // Check for duplicate entry
            $existingDosis = PemantauanDosisTld::where([
                'project_id' => $projectId,
                'user_id' => $userId,
                'tanggal_pemantauan' => $validated['tanggal_pemantauan']
            ])->first();

            if ($existingDosis) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Data dosis untuk tanggal ini sudah ada. Silakan pilih tanggal lain atau edit data yang sudah ada.');
            }

            DB::beginTransaction();

            $dosis = PemantauanDosisTld::create([
                'user_id' => $userId,
                'project_id' => $projectId,
                'tanggal_pemantauan' => $validated['tanggal_pemantauan'],
                'dosis' => $validated['dosis'],
            ]);

            DB::commit();

            return redirect()
                ->route('super_admin.pemantauan.tld.detail', ['projectId' => $projectId, 'userId' => $userId])
                ->with('success', 'Data dosis berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function tldEdit($projectId, $userId, $dosisId)
    {
        $project = Project::with('ketersediaanSdm.users')->findOrFail($projectId);
        $user = $project->ketersediaanSdm->flatMap->users->where('id', $userId)->firstOrFail();
        $dosisTld = PemantauanDosisTld::findOrFail($dosisId);

        return view('super_admin.pemantauan.tld.edit', compact('project', 'user', 'dosisTld'));
    }

    public function tldUpdate(Request $request, $projectId, $userId, $dosisId)
    {
        try {
            $validated = $request->validate([
                'tanggal_pemantauan' => 'required|date',
                'dosis' => 'required|numeric|min:0',
            ]);

            $dosisTld = PemantauanDosisTld::findOrFail($dosisId);

            // Check for duplicate entry, excluding current record
            $existingDosis = PemantauanDosisTld::where([
                'project_id' => $projectId,
                'user_id' => $userId,
                'tanggal_pemantauan' => $validated['tanggal_pemantauan']
            ])->where('id', '!=', $dosisId)->first();

            if ($existingDosis) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Data dosis untuk tanggal ini sudah ada. Silakan pilih tanggal lain.');
            }

            DB::beginTransaction();

            $dosisTld->update([
                'tanggal_pemantauan' => $validated['tanggal_pemantauan'],
                'dosis' => $validated['dosis'],
            ]);

            DB::commit();

            return redirect()
                ->route('super_admin.pemantauan.tld.detail', ['projectId' => $projectId, 'userId' => $userId])
                ->with('success', 'Data dosis berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function tldDestroy($projectId, $userId, $dosisId)
    {
        try {
            $dosisTld = PemantauanDosisTld::findOrFail($dosisId);

            DB::beginTransaction();
            $dosisTld->delete();
            DB::commit();

            return redirect()
                ->route('super_admin.pemantauan.tld.detail', ['projectId' => $projectId, 'userId' => $userId])
                ->with('success', 'Data dosis berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
} 