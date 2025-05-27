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
        $users = User::with(['role', 'jenisPekerja'])->paginate(10);
        $roles = Role::all();
        $jenisPekerja = JenisPekerja::all();
        return view('super_admin.kelola_akun.index', compact('users', 'roles', 'jenisPekerja'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:6',
                'role_id' => 'required|exists:roles,id',
                'keahlian' => 'required|exists:jenis_pekerja,id',
                'no_sib' => 'required|string|max:255',
                'berlaku' => 'required|date',
            ]);

            $user = User::create([
                'nama' => $validated['nama'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['role_id'],
                'keahlian' => $validated['keahlian'],
                'no_sib' => $validated['no_sib'],
                'berlaku' => $validated['berlaku']
            ]);

            DB::commit();
            return redirect()->route('super_admin.kelola_akun')->with('success', 'Akun berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}