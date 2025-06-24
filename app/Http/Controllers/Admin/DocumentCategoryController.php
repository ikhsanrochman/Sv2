<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentCategoryController extends Controller
{
    public function store(Request $request)
    {
        // Untuk sementara, hanya return response sukses
        return response()->json(['success' => true, 'message' => 'Kategori dokumen berhasil ditambahkan (dummy).']);
    }
} 