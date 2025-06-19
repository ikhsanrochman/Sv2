@extends('layouts.super_admin')

@section('content')
<!-- Breadcrumb Section -->
<div id="breadcrumb-container" class="breadcrumb-section mb-4 position-fixed" style="right: 30px; left: 280px; top: 85px; z-index: 999; transition: left 0.3s ease;">
    <div class="d-flex justify-content-between bg-dark-blue py-2 px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('super_admin.kelola_akun') }}" class="text-decoration-none text-white">Home / Kelola Akun</a></li>
                <li class="breadcrumb-item active text-white">Edit Akun</li>
            </ol>
        </nav>
    </div>
</div>

<div style="margin-top: 50px;"></div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.sidebar');
        const breadcrumbContainer = document.getElementById('breadcrumb-container');
        const mainContentWrapper = document.getElementById('main-content-wrapper');
        
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    const isSidebarCollapsed = sidebar.classList.contains('collapsed');
                    breadcrumbContainer.style.left = isSidebarCollapsed ? '25px' : '280px';
                    if (mainContentWrapper) {
                        mainContentWrapper.style.marginLeft = isSidebarCollapsed ? '25px' : '280px';
                    }
                }
            });
        });

        observer.observe(sidebar, {
            attributes: true
        });
    });
</script>
@endpush

<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4 ps-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold">Edit Akun</h2>
                <p class="text-muted mb-0">Edit informasi akun pengguna</p>
            </div>
            <a href="{{ route('super_admin.kelola_akun') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i>Form Edit Akun</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('super_admin.kelola_akun.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Personal Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Informasi Pribadi</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                       id="nama" name="nama" value="{{ old('nama', $user->nama) }}" 
                                       placeholder="Masukkan nama lengkap" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label fw-bold">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username', $user->username) }}" 
                                       placeholder="Masukkan username" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" 
                                       placeholder="Masukkan email" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Security Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-lock me-2"></i>Informasi Keamanan</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-bold">Password Baru <span class="text-muted">(Opsional)</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password Baru <span class="text-muted">(Opsional)</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Konfirmasi password baru">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Role and Expertise -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-user-tag me-2"></i>Role dan Keahlian</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="role_id" class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                                <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                                    <option value="">Pilih Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="keahlian" class="form-label fw-bold">Bidang Keahlian <span class="text-danger">*</span></label>
                                <select class="form-select @error('keahlian') is-invalid @enderror" id="keahlian" name="keahlian[]" multiple required>
                                    @foreach($jenisPekerja as $jp)
                                        <option value="{{ $jp->id }}" {{ in_array($jp->id, old('keahlian', $user->jenisPekerja->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $jp->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Tekan Ctrl (atau Cmd di Mac) untuk memilih multiple bidang</small>
                                @error('keahlian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-id-card me-2"></i>Informasi Profesional</h6>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="no_sib" class="form-label fw-bold">No. SIB <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('no_sib') is-invalid @enderror" 
                                       id="no_sib" name="no_sib" value="{{ old('no_sib', $user->no_sib) }}" 
                                       placeholder="Masukkan nomor SIB" required>
                                @error('no_sib')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="npr" class="form-label fw-bold">NPR <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('npr') is-invalid @enderror" 
                                       id="npr" name="npr" value="{{ old('npr', $user->npr) }}" 
                                       placeholder="Masukkan nomor NPR" required>
                                @error('npr')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="berlaku" class="form-label fw-bold">Tanggal Berlaku <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('berlaku') is-invalid @enderror" 
                                       id="berlaku" name="berlaku" value="{{ old('berlaku', $user->berlaku) }}" required>
                                @error('berlaku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <hr class="my-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('super_admin.kelola_akun') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Akun
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
    .bg-dark-blue {
        background-color: #1e3a5f;
    }
    
    .container-fluid {
        overflow-x: auto;
        max-width: 100vw;
    }

    .breadcrumb-item {
        padding: 0;
        margin: 0;
    }
    
    .breadcrumb-item a {
        color: #ffffff;
        text-decoration: none;
    }
    
    .breadcrumb-item a:hover {
        color: #e0e0e0;
    }

    .breadcrumb {
        margin: 0;
        padding: 0;
    }

    .card-header {
        border-bottom: 1px solid #dee2e6;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1e3a5f;
        box-shadow: 0 0 0 0.2rem rgba(30, 58, 95, 0.25);
    }

    .btn-primary {
        background-color: #1e3a5f;
        border-color: #1e3a5f;
    }

    .btn-primary:hover {
        background-color: #152a47;
        border-color: #152a47;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .text-primary {
        color: #1e3a5f !important;
    }

    .alert {
        border-radius: 8px;
    }

    .card {
        border-radius: 12px;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
    }
</style>

@push('scripts')
<script>
    // Password toggle functionality
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password_confirmation');
        const icon = this.querySelector('i');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;
        
        if (password && confirmPassword && password !== confirmPassword) {
            e.preventDefault();
            alert('Password dan konfirmasi password tidak cocok!');
            return false;
        }
        
        if (password && password.length < 6) {
            e.preventDefault();
            alert('Password minimal 6 karakter!');
            return false;
        }
    });
</script>
@endpush

@endsection
