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
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|exists:roles,id',
            'keahlian' => 'required|exists:jenis_pekerja,id',
            'no_sib' => 'required|string|max:255',
            'berlaku' => 'required|date'
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'jenis_pekerja_id' => $request->keahlian,
            'no_sib' => $request->no_sib,
            'berlaku' => $request->berlaku,
            'is_active' => true
        ]);

        return redirect()->route('super_admin.kelola_akun')
            ->with('success', 'Akun berhasil ditambahkan');
    }
}