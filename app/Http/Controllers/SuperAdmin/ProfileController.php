<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JenisPekerja;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $jenisPekerja = JenisPekerja::all();
        
        return view('super_admin.profile.index', compact('user', 'jenisPekerja'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'npr' => 'nullable|string|max:255',
            'no_sib' => 'nullable|string|max:255',
            'berlaku' => 'nullable|date',
            'keahlian' => 'nullable|array',
            'keahlian.*' => 'exists:jenis_pekerja,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'npr' => $request->npr,
            'no_sib' => $request->no_sib,
            'berlaku' => $request->berlaku,
        ]);

        // Update jenis pekerja relationship
        if ($request->has('keahlian')) {
            $user->jenisPekerja()->sync($request->keahlian);
        } else {
            $user->jenisPekerja()->detach();
        }

        return redirect()->route('super_admin.profile.index')
            ->with('success', 'Profile berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password saat ini tidak sesuai'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('super_admin.profile.index')
            ->with('success', 'Password berhasil diperbarui!');
    }
} 