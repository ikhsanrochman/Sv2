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

    public function create()
    {
        $roles = Role::all();
        $jenisPekerja = JenisPekerja::all();
        
        return view('super_admin.kelola_akun.create', compact('roles', 'jenisPekerja'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'keahlian' => 'required|array',
            'keahlian.*' => 'exists:jenis_pekerja,id',
            'no_sib' => 'required|string|max:255',
            'npr' => 'required|string|max:255',
            'berlaku' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'nama' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'no_sib' => $request->no_sib,
                'npr' => $request->npr,
                'berlaku' => $request->berlaku,
                'status' => 'active'
            ]);

            $user->jenisPekerja()->attach($request->keahlian);

            DB::commit();

            return redirect()->route('super_admin.kelola_akun')
                ->with('success', 'Akun berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menambahkan akun.'])
                ->withInput();
        }
    }

    public function toggleStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->status = $user->status === 'active' ? 'inactive' : 'active';
            $user->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    public function edit($id)
    {
        $user = User::with(['role', 'jenisPekerja'])->findOrFail($id);
        $roles = Role::all();
        $jenisPekerja = JenisPekerja::all();
        
        return view('super_admin.kelola_akun.edit', compact('user', 'roles', 'jenisPekerja'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
            'keahlian' => 'required|array',
            'keahlian.*' => 'exists:jenis_pekerja,id',
            'no_sib' => 'required|string|max:255',
            'npr' => 'required|string|max:255',
            'berlaku' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->update([
                'nama' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'no_sib' => $request->no_sib,
                'npr' => $request->npr,
                'berlaku' => $request->berlaku,
            ]);

            // Update password if provided
            if ($request->filled('password')) {
                $request->validate([
                    'password' => 'string|min:8|confirmed',
                ]);
                $user->password = Hash::make($request->password);
                $user->save();
            }

            // Sync jenis pekerja
            $user->jenisPekerja()->sync($request->keahlian);

            DB::commit();

            return redirect()->route('super_admin.kelola_akun')
                ->with('success', 'Akun berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui akun.'])
                ->withInput();
        }
    }
} 