<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\JenisPekerja;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class KelolaAkunController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'jenisPekerja'])
                    ->where('role_id', 3) // Only show users with role_id = 3 (user role)
                    ->get();
        $roles = Role::all();
        $jenisPekerja = JenisPekerja::all();
        
        return view('admin.kelola_akun.index', compact('users', 'roles', 'jenisPekerja'));
    }

    public function create()
    {
        $roles = Role::where('id', 3)->get(); // Only show user role (role_id = 3)
        $jenisPekerja = JenisPekerja::all();
        
        return view('admin.kelola_akun.create', compact('roles', 'jenisPekerja'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'keahlian' => 'required|array|min:1',
            'keahlian.*' => 'exists:jenis_pekerja,id',
            'no_sib' => 'required|string|max:255',
            'npr' => 'required|string|max:255',
            'berlaku' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'npr' => $request->npr,
            'no_sib' => $request->no_sib,
            'berlaku' => $request->berlaku,
            'is_active' => true,
        ]);

        // Attach jenis pekerja (many-to-many relationship)
        if ($request->has('keahlian')) {
            $user->jenisPekerja()->attach($request->keahlian);
        }

        return redirect()->route('admin.kelola_akun')
            ->with('success', 'Akun berhasil dibuat!');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('admin.kelola_akun')
            ->with('success', "Akun berhasil {$status}!");
    }

    public function edit($id)
    {
        $user = User::with(['role', 'jenisPekerja'])->findOrFail($id);
        $roles = Role::where('id', 3)->get(); // Only show user role (role_id = 3)
        $jenisPekerja = JenisPekerja::all();
        
        return view('admin.kelola_akun.edit', compact('user', 'roles', 'jenisPekerja'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
            'keahlian' => 'required|array|min:1',
            'keahlian.*' => 'exists:jenis_pekerja,id',
            'no_sib' => 'required|string|max:255',
            'npr' => 'required|string|max:255',
            'berlaku' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::findOrFail($id);
        $user->update([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'npr' => $request->npr,
            'no_sib' => $request->no_sib,
            'berlaku' => $request->berlaku,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $passwordValidator = Validator::make($request->only('password', 'password_confirmation'), [
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($passwordValidator->fails()) {
                return redirect()->back()
                    ->withErrors($passwordValidator)
                    ->withInput();
            }

            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Sync jenis pekerja
        $user->jenisPekerja()->sync($request->keahlian);

        return redirect()->route('admin.kelola_akun')
            ->with('success', 'Akun berhasil diperbarui!');
    }
} 