@extends('layouts.admin')

@section('content')
<!-- Breadcrumb Section -->
<div id="breadcrumb-container" class="breadcrumb-section mb-4 position-fixed" style="right: 30px; left: 280px; top: 85px; z-index: 999; transition: left 0.3s ease;">
    <div class="d-flex justify-content-between bg-dark-blue py-2 px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-white">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pemantauan.index') }}" class="text-decoration-none text-white">Pemantauan</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pemantauan.pendos', $project->id) }}" class="text-decoration-none text-white">Pendos</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Detail</li>
            </ol>
        </nav>
    </div>
</div>

<div style="margin-top: 50px;"></div>

<div class="container-fluid">
    <!-- Informasi Project -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-light">
            <h6 class="fw-bold mb-3">Informasi Project</h6>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="ps-0" style="width: 140px;">Nama Proyek</td>
                            <td class="px-3">:</td>
                            <td>{{ $project->nama_proyek }}</td>
                        </tr>
                        <tr>
                            <td class="ps-0">Keterangan</td>
                            <td class="px-3">:</td>
                            <td>{{ $project->keterangan }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td class="ps-0" style="width: 140px;">Tanggal Mulai</td>
                            <td class="px-3">:</td>
                            <td>{{ $project->tanggal_mulai }}</td>
                        </tr>
                        <tr>
                            <td class="ps-0">Tanggal Selesai</td>
                            <td class="px-3">:</td>
                            <td>{{ $project->tanggal_selesai }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Detail Pendos Section -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-bold mb-0">Data Detail Pemantauan Pendos</h6>
                <a href="{{ route('admin.pemantauan.pendos', $project->id) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <!-- User Information -->
            <div class="card bg-light mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Informasi SDM</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td class="ps-0" style="width: 140px;">Nama</td>
                                    <td class="px-3">:</td>
                                    <td>{{ $user->nama }}</td>
                                </tr>
                                <tr>
                                    <td class="ps-0">NPR</td>
                                    <td class="px-3">:</td>
                                    <td>{{ $user->npr ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dosis Data Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Tanggal Pengukuran</th>
                            <th class="text-center">Hasil Pengukuran</th>
                            <th class="text-center" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dosisData as $index => $dosis)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($dosis->tanggal_pengukuran)->format('d F Y') }}</td>
                                <td class="text-center">{{ $dosis->hasil_pengukuran }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-warning btn-sm" onclick="editDosis({{ $dosis->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteDosis({{ $dosis->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data dosis</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .breadcrumb-section {
        background-color: #1e3a5f !important;
    }
    
    .table-dark th {
        background-color: #1e3a5f;
        border-color: #1e3a5f;
        font-weight: normal;
        font-size: 0.875rem;
    }
    
    .table td {
        font-size: 0.875rem;
        vertical-align: middle;
    }

    .table-bordered td, .table-bordered th {
        border-color: #dee2e6;
    }

    .bg-light {
        background-color: #f8f9fa !important;
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

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
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

    function editDosis(id) {
        // TODO: Implement edit functionality
        alert('Edit functionality will be implemented');
    }

    function deleteDosis(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            // TODO: Implement delete functionality
            alert('Delete functionality will be implemented');
        }
    }
</script>
@endpush
@endsection 