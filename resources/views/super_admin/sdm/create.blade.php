@extends('layouts.super_admin')

@section('content')
<!-- Breadcrumb Section -->
<div id="breadcrumb-container" class="breadcrumb-section mb-4 position-fixed" style="right: 30px; left: 280px; top: 85px; z-index: 999; transition: left 0.3s ease;">
    <div class="d-flex justify-content-between bg-dark-blue py-2 px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('super_admin.perizinan.index') }}" class="text-decoration-none text-white">Perizinan</a></li>
                <li class="breadcrumb-item"><a href="{{ route('super_admin.sdm.detail', $project->id) }}" class="text-decoration-none text-white">Detail Perizinan</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Tambah Pekerja Radiasi</li>
            </ol>
        </nav>
    </div>
</div>

<div style="margin-top: 50px;"></div>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Tambah Pekerja Radiasi</h2>
        <a href="{{ route('super_admin.sdm.detail', $project->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Form Section -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-secondary text-white py-3">
            <h5 class="fw-bold mb-0"><i class="fas fa-user-plus me-2"></i>Form Tambah Pekerja Radiasi</h5>
        </div>
        <div class="card-body">
            <form id="addWorkerForm" action="{{ route('super_admin.sdm.store', $project->id) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Nama</label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <option value="">Pilih nama pekerja...</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}" 
                                        data-jenis-pekerja="{{ $user->jenisPekerja->pluck('nama')->join(', ') }}"
                                        data-no-sib="{{ $user->no_sib }}"
                                        data-berlaku="{{ $user->berlaku }}"
                                        data-keahlian="{{ $user->keahlian }}">
                                        {{ $user->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="jenis_pekerja" class="form-label">Jenis Pekerja</label>
                            <input type="text" class="form-control" id="jenis_pekerja" name="jenis_pekerja" disabled>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="no_sib" class="form-label">No. SIB</label>
                            <input type="text" class="form-control" id="no_sib" name="no_sib" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="berlaku" class="form-label">Berlaku Sampai</label>
                            <input type="date" class="form-control" id="berlaku" name="berlaku" disabled>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="keahlian" class="form-label">Keahlian</label>
                    <textarea class="form-control" id="keahlian" name="keahlian" rows="3" disabled></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
    .breadcrumb-section {
        background-color: #1e3a5f;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        content: "/";
        color: #ffffff;
    }
    
    .breadcrumb-item a {
        color: #ffffff;
    }
    
    .breadcrumb-item a:hover {
        color: #e0e0e0;
    }
    
    .breadcrumb-item.active {
        color: #ffffff;
        font-weight: 500;
    }

    .card {
        border-radius: 8px;
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userSelect = document.getElementById('user_id');
    
    userSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            document.getElementById('jenis_pekerja').value = selectedOption.dataset.jenisPekerja || '';
            document.getElementById('no_sib').value = selectedOption.dataset.noSib || '';
            document.getElementById('berlaku').value = selectedOption.dataset.berlaku || '';
            document.getElementById('keahlian').value = selectedOption.dataset.keahlian || '';
        } else {
            document.getElementById('jenis_pekerja').value = '';
            document.getElementById('no_sib').value = '';
            document.getElementById('berlaku').value = '';
            document.getElementById('keahlian').value = '';
        }
    });

    // Handle form submission
    document.getElementById('addWorkerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data pekerja berhasil ditambahkan',
                    icon: 'success'
                }).then(() => {
                    window.location.href = "{{ route('super_admin.sdm.detail', $project->id) }}";
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat menambahkan data',
                icon: 'error'
            });
        });
    });
});
</script>
@endpush
@endsection 