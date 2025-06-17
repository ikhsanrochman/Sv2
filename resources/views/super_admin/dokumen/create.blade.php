@extends('layouts.super_admin')

@section('content')
<!-- Breadcrumb Section -->
<div class="breadcrumb-section mb-4">
    <div class="d-flex justify-content-between bg-dark-blue py-2 px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}" class="text-decoration-none text-white">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('super_admin.documents.index') }}" class="text-decoration-none text-white">Dokumen Instansi</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Tambah Dokumen</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark-blue text-white">
            <h5 class="mb-0">Tambah Dokumen Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('super_admin.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="judul_dokumen" class="form-label">Judul Dokumen</label>
                    <input type="text" class="form-control @error('judul_dokumen') is-invalid @enderror" id="judul_dokumen" name="judul_dokumen" value="{{ old('judul_dokumen') }}" required>
                    @error('judul_dokumen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="topik" class="form-label">Topik</label>
                    <input type="text" class="form-control @error('topik') is-invalid @enderror" id="topik" name="topik" value="{{ old('topik') }}">
                    @error('topik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="document_category_id" class="form-label">Kategori</label>
                    <select class="form-select @error('document_category_id') is-invalid @enderror" id="document_category_id" name="document_category_id" required>
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('document_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('document_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="file" class="form-label">File Dokumen</label>
                    <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" required>
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="5">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-dark-blue">Simpan Dokumen</button>
                <a href="{{ route('super_admin.documents.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
