<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\JenisPekerja;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class KelolaAkunController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'jenisPekerja' => function($query) {
            $query->select('jenis_pekerja.id', 'jenis_pekerja.nama')
                  ->orderBy('jenis_pekerja.nama');
        }])->paginate(10);
        
        // Debug information
        foreach ($users as $user) {
            \Log::info('User ID: ' . $user->id);
            \Log::info('Jenis Pekerja: ' . $user->jenisPekerja);
        }
        
        $roles = Role::all();
        $jenisPekerja = JenisPekerja::orderBy('nama')->get();
        
        return view('super_admin.kelola_akun.index', compact('users', 'roles', 'jenisPekerja'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'keahlian' => 'required|array',
            'keahlian.*' => 'exists:jenis_pekerja,id',
            'no_sib' => 'required|string|max:255',
            'berlaku' => 'required|date'
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'nama' => $request->nama,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'no_sib' => $request->no_sib,
                'berlaku' => $request->berlaku,
                'status' => 'active'
            ]);

            // Attach multiple jenis_pekerja
            $user->jenisPekerja()->attach($request->keahlian);

            DB::commit();
            return redirect()->route('super_admin.kelola_akun')
                ->with('success', 'Akun berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan akun')
                ->withInput();
        }
    }

    /**
     * Toggle the active status of a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = request('status');
        $user->save();

        return response()->json([
            'message' => 'Status updated successfully',
            'status' => $user->status
        ]);
    }
}