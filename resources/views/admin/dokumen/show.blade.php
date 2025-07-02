@extends('layouts.admin')

@section('content')
<!-- Breadcrumb Section -->
<div class="breadcrumb-section mb-4">
    <div class="d-flex justify-content-between bg-dark-blue py-2 px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-white">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.dokumen.index') }}" class="text-decoration-none text-white">Dokumen Instansi</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Detail Dokumen</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark-blue text-white">
            <h5 class="mb-0">Detail Dokumen</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-bold">ID Dokumen:</label>
                <p>{{ $document->id }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Judul Dokumen:</label>
                <p>{{ $document->judul_dokumen }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Topik:</label>
                <p>{{ $document->topik ?? '-' }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Kategori:</label>
                <p>{{ $document->documentCategory->name ?? '-' }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">File Dokumen:</label>
                @if ($document->file_path)
                    <p><a href="{{ route('admin.dokumen.download', $document->id) }}" target="_blank" class="text-decoration-none">Lihat File</a></p>
                @else
                    <p>Tidak ada file</p>
                @endif
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Deskripsi:</label>
                <p>{{ $document->deskripsi ?? '-' }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Dibuat Pada:</label>
                <p>{{ $document->created_at->format('d M Y H:i') }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Terakhir Diperbarui:</label>
                <p>{{ $document->updated_at->format('d M Y H:i') }}</p>
            </div>
            <a href="{{ route('admin.dokumen.index') }}" class="btn btn-secondary">Kembali</a>
            <a href="{{ route('admin.dokumen.edit', $document->id) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>
</div>
@endsection 