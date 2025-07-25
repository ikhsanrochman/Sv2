@extends('layouts.super_admin')

@section('content')
<!-- Breadcrumb Section -->
<div id="breadcrumb-container" class="breadcrumb-section mb-4 position-fixed" style="right: 30px; left: 280px; top: 85px; z-index: 999; transition: left 0.3s ease;">
    <div class="d-flex justify-content-between bg-dark-blue py-2 px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}" class="text-decoration-none text-white">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Profile</li>
            </ol>
        </nav>
    </div>
</div>

<div style="margin-top: 50px;"></div>

@push('scripts')
<!-- Cropper.js CSS & JS -->
<link  href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Sidebar & breadcrumb logic (biarkan jika ada)
        const sidebar = document.querySelector('.sidebar');
        const breadcrumbContainer = document.getElementById('breadcrumb-container');
        const mainContentWrapper = document.getElementById('main-content-wrapper');
    if (sidebar && breadcrumbContainer) {
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
        observer.observe(sidebar, { attributes: true });
    }

    // Password toggle functionality
    const toggleCurrentPassword = document.getElementById('toggleCurrentPassword');
    if (toggleCurrentPassword) {
        toggleCurrentPassword.addEventListener('click', function() {
            const passwordField = document.getElementById('current_password');
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
    }
    const toggleNewPassword = document.getElementById('toggleNewPassword');
    if (toggleNewPassword) {
        toggleNewPassword.addEventListener('click', function() {
            const passwordField = document.getElementById('new_password');
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
    }
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    if (toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener('click', function() {
            const passwordField = document.getElementById('new_password_confirmation');
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
    }

    // Password change form validation
    const passwordForm = document.querySelector('form[action*="update_password"]');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Password baru dan konfirmasi password tidak cocok!');
                return false;
            }
            // Validasi password: minimal 8 karakter, ada huruf kapital, angka, dan simbol
            const passwordRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=\[\]{};':"\\|,.<>\/?]).{8,}$/;
            if (!passwordRegex.test(newPassword)) {
                e.preventDefault();
                alert('Password baru minimal 8 karakter, mengandung huruf kapital, angka, dan simbol!');
                return false;
            }
        });
    }

    // Real-time password requirements check (profile only)
    const newPasswordInput = document.getElementById('new_password');
    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            const value = newPasswordInput.value;
            const pwLength = document.getElementById('profile-pw-length');
            const pwUpper = document.getElementById('profile-pw-uppercase');
            const pwNumber = document.getElementById('profile-pw-number');
            const pwSymbol = document.getElementById('profile-pw-symbol');
            if (pwLength) pwLength.className = value.length >= 8 ? 'text-success' : 'text-danger';
            if (pwUpper) pwUpper.className = /[A-Z]/.test(value) ? 'text-success' : 'text-danger';
            if (pwNumber) pwNumber.className = /\d/.test(value) ? 'text-success' : 'text-danger';
            if (pwSymbol) pwSymbol.className = /[!@#$%^&*()_\-+=\[\]{};':"\\|,.<>\/?]/.test(value) ? 'text-success' : 'text-danger';
        });
    }

    // Bidang selection with tags
    const selector = document.getElementById('jenis_pekerja_selector');
    const hiddenSelect = document.getElementById('jenis_pekerja_hidden');
    const container = document.getElementById('selected_bidang_container');
    function addBidangTag(id, name) {
        if (!id || container.querySelector(`.bidang-tag[data-id="${id}"]`)) {
            return;
        }
        const tag = document.createElement('div');
        tag.className = 'bidang-tag';
        tag.dataset.id = id;
        tag.innerHTML = `<span>${name}</span><button type="button" class="btn-close-tag">&times;</button>`;
        container.appendChild(tag);
        const optionInSelector = selector.querySelector(`option[value="${id}"]`);
        if (optionInSelector) optionInSelector.style.display = 'none';
        if (!hiddenSelect.querySelector(`option[value="${id}"]`)) {
            const option = document.createElement('option');
            option.value = id;
            option.textContent = name;
            option.selected = true;
            hiddenSelect.appendChild(option);
        }
        selector.value = '';
    }
    function removeBidangTag(tagElement) {
        const id = tagElement.dataset.id;
        const optionToRemove = hiddenSelect.querySelector(`option[value="${id}"]`);
        if (optionToRemove) optionToRemove.remove();
        const optionInSelector = selector.querySelector(`option[value="${id}"]`);
        if (optionInSelector) optionInSelector.style.display = 'block';
        tagElement.remove();
    }
    if (selector) {
        selector.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (!selectedOption.value) return;
            addBidangTag(selectedOption.value, selectedOption.dataset.name);
        });
    }
    if (container) {
        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-close-tag')) {
                removeBidangTag(e.target.parentElement);
            }
        });
    }
    if (hiddenSelect) {
        Array.from(hiddenSelect.options).forEach(option => {
            if (option.selected) {
                addBidangTag(option.value, option.textContent);
            }
        });
    }

    // === Cropper.js logic ===
    let cropper;
    const fotoInput = document.getElementById('foto_profil');
    const cropModal = document.getElementById('cropperModal');
    const cropImage = document.getElementById('cropperImage');
    const cropBtn = document.getElementById('cropBtn');
    const closeCropBtn = document.getElementById('closeCropBtn');
    const previewImg = document.querySelector('img[alt="Foto Profil"]');

    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && /^image\/.*/.test(file.type)) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    cropImage.src = evt.target.result;
                    // Show modal
                    cropModal.style.display = 'flex'; // flex agar align center
                    // Init cropper
                    if (cropper) cropper.destroy();
                    cropper = new Cropper(cropImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        movable: true,
                        zoomable: true,
                        rotatable: false,
                        scalable: false,
                        cropBoxResizable: true,
                        minContainerWidth: 200,
                        minContainerHeight: 200,
                        minCropBoxWidth: 100,
                        minCropBoxHeight: 100,
                        autoCropArea: 1
                    });
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (cropBtn) {
        cropBtn.addEventListener('click', function() {
            if (cropper) {
                cropper.getCroppedCanvas({
                    width: 250,
                    height: 250,
                    imageSmoothingQuality: 'high'
                }).toBlob(function(blob) {
                    // Update preview
                    const url = URL.createObjectURL(blob);
                    previewImg.src = url;
                    // Replace file input with cropped blob
                    const dataTransfer = new DataTransfer();
                    const file = new File([blob], 'cropped_profile.png', { type: 'image/png' });
                    dataTransfer.items.add(file);
                    fotoInput.files = dataTransfer.files;
                    // Hide modal
                    cropModal.style.display = 'none';
                    cropper.destroy();
                }, 'image/png');
            }
        });
    }
    if (closeCropBtn) {
        closeCropBtn.addEventListener('click', function() {
            cropModal.style.display = 'none';
            if (cropper) cropper.destroy();
        });
    }
    });
</script>
@endpush

<!-- Header -->
<div class="mb-2">
    <h2 class="fw-bold">Profile Saya</h2>
    <div class="text-muted" style="font-size: 15px;">Kelola informasi profile dan keamanan akun Anda</div>
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

<div class="row">
    <!-- Profile Information -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Profile</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('super_admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Foto Profil -->
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ $user->foto_profil ? asset('storage/' . $user->foto_profil) : asset('img/user.png') }}"
                             alt="Foto Profil"
                             class="rounded-circle"
                             style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #1e3a5f;">
                        <div class="ms-3">
                            <label for="foto_profil" class="form-label fw-bold mb-1">Ubah Foto Profil</label>
                            <input type="file" class="form-control" id="foto_profil" name="foto_profil" accept="image/*">
                            @error('foto_profil')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
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
                        <div class="col-md-12 mb-3">
                            <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" 
                                   placeholder="Masukkan email" required>
                            @error('email')
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
                            <label for="no_sib" class="form-label fw-bold">No. SIB</label>
                            <input type="text" class="form-control @error('no_sib') is-invalid @enderror" 
                                   id="no_sib" name="no_sib" value="{{ old('no_sib', $user->no_sib) }}" 
                                   placeholder="Masukkan nomor SIB">
                            @error('no_sib')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="npr" class="form-label fw-bold">NPR</label>
                            <input type="text" class="form-control @error('npr') is-invalid @enderror" 
                                   id="npr" name="npr" value="{{ old('npr', $user->npr) }}" 
                                   placeholder="Masukkan nomor NPR">
                            @error('npr')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="berlaku" class="form-label fw-bold">Tanggal Berlaku</label>
                            <input type="date" class="form-control @error('berlaku') is-invalid @enderror" 
                                   id="berlaku" name="berlaku" value="{{ old('berlaku', $user->berlaku) }}">
                            @error('berlaku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Expertise -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="fas fa-user-tag me-2"></i>Keahlian</h6>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="jenis_pekerja_selector" class="form-label fw-bold">Bidang <span class="text-danger">*</span></label>
                            <select class="form-select" id="jenis_pekerja_selector">
                                <option value="">Pilih Bidang...</option>
                                @foreach($jenisPekerja as $jp)
                                    <option value="{{ $jp->id }}" data-name="{{ $jp->nama }}">{{ $jp->nama }}</option>
                                @endforeach
                            </select>
                            <div id="selected_bidang_container" class="mt-2">
                                {{-- Selected items will be populated here by JavaScript --}}
                            </div>
                            <select name="jenis_pekerja[]" id="jenis_pekerja_hidden" multiple class="d-none">
                                @foreach($jenisPekerja as $jp)
                                    @if(in_array((int)$jp->id, array_map('intval', old('jenis_pekerja', $user->jenisPekerja->pluck('id')->toArray()))))
                                        <option value="{{ $jp->id }}" selected>{{ $jp->nama }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('jenis_pekerja')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="keahlian" class="form-label fw-bold">Keahlian</label>
                            <input type="text" class="form-control @error('keahlian') is-invalid @enderror" id="keahlian" name="keahlian" value="{{ old('keahlian', $user->keahlian) }}" placeholder="Masukkan keahlian">
                            @error('keahlian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-12">
                            <hr class="my-4">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary px-4 py-2">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Change -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Ubah Password</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('super_admin.profile.update_password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-bold">Password Saat Ini <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" placeholder="Masukkan password saat ini" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label fw-bold">Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" name="new_password" placeholder="Masukkan password baru" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <ul id="profile-password-requirements" class="mt-2 mb-0 ps-3" style="list-style: disc; font-size: 0.95em;">
                            <li id="profile-pw-length" class="text-danger">Minimal 8 karakter</li>
                            <li id="profile-pw-uppercase" class="text-danger">Mengandung huruf kapital</li>
                            <li id="profile-pw-number" class="text-danger">Mengandung angka</li>
                            <li id="profile-pw-symbol" class="text-danger">Mengandung simbol</li>
                        </ul>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label fw-bold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" 
                                   id="new_password_confirmation" name="new_password_confirmation" 
                                   placeholder="Konfirmasi password baru" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning px-4 py-2">
                            <i class="fas fa-key me-2"></i>Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cropper -->
<div id="cropperModal" style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.7); align-items:center; justify-content:center;">
    <div style="background:#fff; padding:24px 18px 18px 18px; border-radius:14px; max-width:340px; width:95vw; max-height:95vh; box-shadow:0 8px 32px rgba(30,58,95,0.18); display:flex; flex-direction:column; align-items:center;">
        <div style="font-weight:600; font-size:1.1rem; margin-bottom:10px; color:#1e3a5f;">Atur & Crop Foto Profil</div>
        <img id="cropperImage" src="" style="max-width:250px; max-height:250px; width:100%; height:auto; border-radius:8px; border:1px solid #eee; background:#f8f9fa; display:block; margin:auto;">
        <div class="mt-3 d-flex justify-content-center gap-2" style="width:100%;">
            <button type="button" class="btn btn-primary flex-fill" id="cropBtn">Crop & Simpan</button>
            <button type="button" class="btn btn-secondary flex-fill" id="closeCropBtn">Batal</button>
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

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
        color: #000;
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

    .bidang-tag {
        display: inline-flex;
        align-items: center;
        padding: 0.5em 1em;
        margin: 0.25em 0.5em 0.25em 0;
        font-size: 1rem;
        color: #fff;
        background-color: #1e3a5f;
        border-radius: 8px;
        font-weight: 600;
    }
    .btn-close-tag {
        background: none;
        border: none;
        color: #fff;
        margin-left: 0.5em;
        font-size: 1.1em;
        font-weight: bold;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }
    .btn-close-tag:hover {
        color: #e0e0e0;
    }
</style>

@endsection
