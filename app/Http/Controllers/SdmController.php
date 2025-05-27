<?php

namespace App\Http\Controllers;

use App\Models\Task;  // <--- ini yang harus ditambahkan
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class SdmController extends Controller
{
    public function index()
{
    $tasks = Task::with('users')->get(); // Ini penting
    $users = User::all();  // Jika masih diperlukan

    return view('super_admin.ketersediaan_sdm', compact('tasks', 'users'));
}


public function store(Request $request)
{
    $request->validate([
        'task_name' => 'required|string|max:255|unique:tasks,name',
        'user_ids' => 'required|array|min:1',
        'user_ids.*' => 'exists:users,id',
    ]);

    // Cek apakah task dengan nama itu sudah ada
    $task = Task::firstOrCreate(['name' => $request->task_name]);

    // Tambahkan user ke task, tanpa menghapus user lama
    $task->users()->syncWithoutDetaching($request->user_ids);

    return redirect()->route('super_admin.ketersediaan_sdm')->with('success', 'Tugas berhasil dibuat atau diperbarui.');
}
public function detail($id)
{
    $task = Task::findOrFail($id);
    $workers = $task->users; // Pastikan relasi `users()` sudah didefinisikan di model Task

    return view('super_admin.task_detail', compact('task', 'workers'));
}



}
