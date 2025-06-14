@extends('layouts.super_admin')

@section('content')
<!-- Breadcrumb Section -->
<div id="breadcrumb-container" class="breadcrumb-section mb-4 position-fixed" style="right: 30px; left: 280px; top: 85px; z-index: 999; transition: left 0.3s ease;">
    <div class="d-flex justify-content-between bg-dark-blue py-2 px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}" class="text-decoration-none text-white">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('super_admin.pemantauan.index') }}" class="text-decoration-none text-white">Pemantauan</a></li>
                <li class="breadcrumb-item"><a href="{{ route('super_admin.pemantauan.tld', $project->id) }}" class="text-decoration-none text-white">TLD</a></li>
                <li class="breadcrumb-item"><a href="{{ route('super_admin.pemantauan.tld.detail', ['projectId' => $project->id, 'userId' => $user->id]) }}" class="text-decoration-none text-white">Detail</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Edit Data</li>
            </ol>
        </nav>
    </div>
</div>

<div style="margin-top: 50px;"></div>

<div class="container-fluid">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold mb-4">Edit Data Dosis Pemantauan TLD</h6>

            <form action="{{ route('super_admin.pemantauan.tld.update', ['projectId' => $project->id, 'userId' => $user->id, 'dosisId' => $dosisTld->id]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Informasi Karyawan -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Karyawan</label>
                            <input type="text" class="form-control" value="{{ $user->nama }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">NPR</label>
                            <input type="text" class="form-control" value="{{ $user->npr ?? '-' }}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Form Data TLD -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_pemantauan" class="form-label">Tanggal Pencatatan <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_pemantauan') is-invalid @enderror" 
                                id="tanggal_pemantauan" name="tanggal_pemantauan" 
                                value="{{ old('tanggal_pemantauan', $dosisTld->tanggal_pemantauan->format('Y-m-d')) }}" required>
                            @error('tanggal_pemantauan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="dosis" class="form-label">Dosis (mSv) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('dosis') is-invalid @enderror" 
                                id="dosis" name="dosis" value="{{ old('dosis', $dosisTld->dosis) }}" required>
                            @error('dosis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('super_admin.pemantauan.tld.detail', ['projectId' => $project->id, 'userId' => $user->id]) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .breadcrumb-section {
        background-color: #1e3a5f !important;
    }
    
    .form-label {
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .form-control, .form-select {
        font-size: 0.875rem;
    }
    
    .form-control:read-only {
        background-color: #f8f9fa;
    }
    
    .btn {
        font-size: 0.875rem;
    }
    
    .btn-primary {
        background-color: #1e3a5f;
        border-color: #1e3a5f;
    }
    
    .btn-primary:hover {
        background-color: #162c46;
        border-color: #162c46;
    }
    
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.sidebar');
        const breadcrumbContainer = document.getElementById('breadcrumb-container');
        
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    const isSidebarCollapsed = sidebar.classList.contains('collapsed');
                    breadcrumbContainer.style.left = isSidebarCollapsed ? '25px' : '280px';
                }
            });
        });

        observer.observe(sidebar, {
            attributes: true
        });
    });
</script>
@endpush
@endsection 