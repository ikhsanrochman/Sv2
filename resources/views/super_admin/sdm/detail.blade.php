@extends('layouts.super_admin')

@section('content')
<!-- Breadcrumb Section -->
<div id="breadcrumb-container" class="breadcrumb-section mb-4 position-fixed" style="right: 30px; left: 280px; top: 85px; z-index: 999; transition: left 0.3s ease;">
    <div class="d-flex justify-content-between bg-dark-blue py-2 px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('super_admin.perizinan.index') }}" class="text-decoration-none text-white">Ketersediaan Sumber Daya Manusia</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Detail Ketersediaan Sumber Daya Manusia</li>
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
        
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    const isSidebarCollapsed = sidebar.classList.contains('collapsed');
                    breadcrumbContainer.style.left = isSidebarCollapsed ? '30px' : '280px';
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Detail Ketersediaan SDM :  {{ $project->nama_proyek }}</h2>
        <a href="{{ route('super_admin.perizinan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>    <!-- Detail Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-secondary text-white py-3">
            <h5 class="fw-bold mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Project</h5>
        </div>
        <div class="card-body bg-light">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-2"><span class="fw-bold">Nama Project</span> : {{ $project->nama_proyek }}</p>
                    <p class="mb-0"><span class="fw-bold">Keterangan</span> : {{ $project->keterangan }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><span class="fw-bold">Tanggal Mulai</span> : {{ $project->tanggal_mulai->format('d-M-Y') }}</p>
                    <p class="mb-0"><span class="fw-bold">Tanggal Selesai</span> : {{ $project->tanggal_selesai->format('d-M-Y') }}</p>
                </div>
            </div>
        </div>
    </div>    <!-- Data Perizinan Sumber Radiasi Pengion Section -->    
    </div>

    <!-- Data Pekerja Radiasi Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-secondary text-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-users me-2"></i>Data Pekerja Radiasi
            </h5>
            <a href="{{ route('super_admin.sdm.create', $project->id) }}" class="btn btn-light">
                <i class="fas fa-plus me-2"></i>Tambah data
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Jenis Pekerja</th>
                            <th>No. SIB</th>
                            <th>Berlaku</th>
                            <th>Keahlian</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($project->ketersediaanSdm->flatMap->users ?? [] as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->nama }}</td>
                            <td>
                                @if($user->jenisPekerja->count() > 0)
                                    <ul class="list-unstyled mb-0">
                                        @foreach($user->jenisPekerja as $jenisPekerja)
                                            <li>{{ $jenisPekerja->nama }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $user->no_sib ?? '-' }}</td>
                            <td>{{ $user->berlaku ? \Carbon\Carbon::parse($user->berlaku)->format('d-M-Y') : '-' }}</td>
                            <td>{{ $user->keahlian ?? '-' }}</td>
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm delete-worker" data-id="{{ $user->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data pekerja radiasi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
    }      .table thead tr th {
        background-color: #1e3a5f;
        color: white;
        font-weight: 600;
        padding: 12px 8px;
        border-color: #1e3a5f;
    }
    
    .card {
        border-radius: 8px;
    }

    .bg-custom-dark-gray {
        background-color: #1e3a5f; /* Adjusted to dark blue to match image headers */
        color: #ffffff; /* Changed to white text color to match image headers */
    }

    .bg-custom-light-gray {
        background-color: white; /* Remains white to match image card bodies */
    }

    .btn-custom-dark {
        background-color: #1e3a5f;
        color: #ffffff;
        border-color: #1e3a5f;
    }

    .btn-custom-dark:hover {
        background-color: #162f4d;
        border-color: #162f4d;
    }
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // Handle worker deletion
    $('.delete-worker').click(function() {
        const userId = $(this).data('id');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data pekerja ini akan dihapus dari project",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/super-admin/sdm/${project.id}/user/${userId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Terhapus!',
                                'Data pekerja berhasil dihapus dari project',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Gagal!',
                            'Terjadi kesalahan saat menghapus data',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>
@endpush
@endsection 