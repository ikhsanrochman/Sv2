<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proyek;
use Illuminate\Support\Facades\DB;

class ProyekController extends Controller
{
    public function index()
    {
        $proyeks = Proyek::latest()->paginate(10);
        return view('super_admin.proyek.index', compact('proyeks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        try {
            DB::beginTransaction();
            
            Proyek::create($request->all());
            
            DB::commit();
            return redirect()->route('super_admin.proyek')->with('success', 'Proyek berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Proyek $proyek)
    {
        $request->validate([
            'nama_proyek' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        try {
            DB::beginTransaction();
            
            $proyek->update($request->all());
            
            DB::commit();
            return redirect()->route('super_admin.proyek')->with('success', 'Proyek berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Proyek $proyek)
    {
        try {
            DB::beginTransaction();
            
            $proyek->delete();
            
            DB::commit();
            return redirect()->route('super_admin.proyek')->with('success', 'Proyek berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
} 