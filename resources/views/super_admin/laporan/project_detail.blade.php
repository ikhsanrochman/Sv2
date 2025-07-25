@extends('layouts.super_admin')

@section('content')
<!-- Breadcrumb Section -->
<div id="breadcrumb-container" class="breadcrumb-section mb-4 position-fixed" style="right: 30px; left: 280px; top: 85px; z-index: 999; transition: left 0.3s ease;">
    <div class="d-flex justify-content-between bg-dark-blue py-2 px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('super_admin.laporan') }}" class="text-decoration-none text-white">Home / Laporan</a></li>
                <li class="breadcrumb-item active text-white">Detail Proyek</li>
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
                <h2 class="fw-bold">{{ $project->nama_proyek }}</h2>
                <p class="text-muted mb-0">Detail lengkap proyek dan semua data terkait</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('super_admin.laporan.project.download', $project->id) }}" class="btn btn-success">
                    <i class="fas fa-download me-2"></i>Download Laporan
                </a>
                <a href="{{ route('super_admin.laporan') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Project Information -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Proyek</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Nama Proyek:</td>
                                    <td>{{ $project->nama_proyek }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tanggal Mulai:</td>
                                    <td>{{ $project->tanggal_mulai ? $project->tanggal_mulai->format('d/m/Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tanggal Selesai:</td>
                                    <td>{{ $project->tanggal_selesai ? $project->tanggal_selesai->format('d/m/Y') : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Durasi:</td>
                                    <td>
                                        @if($project->tanggal_mulai && $project->tanggal_selesai)
                                            {{ $project->tanggal_mulai->diffInDays($project->tanggal_selesai) }} hari
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status:</td>
                                    <td>
                                        @if($project->tanggal_mulai && $project->tanggal_selesai)
                                            @if($project->tanggal_selesai->isPast())
                                                <span class="badge bg-success">Selesai</span>
                                            @elseif($project->tanggal_mulai->isPast() && $project->tanggal_selesai->isFuture())
                                                <span class="badge bg-warning">Berlangsung</span>
                                            @else
                                                <span class="badge bg-info">Belum Dimulai</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Tidak Diketahui</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Keterangan:</td>
                                    <td>{{ $project->keterangan ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-primary mb-1">{{ $project->perizinanSumberRadiasiPengion->count() }}</h4>
                                <small class="text-muted">Perizinan</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                @php
                                    $totalSdmUsers = 0;
                                    foreach($project->ketersediaanSdm as $sdm) {
                                        if($sdm->users) {
                                            $totalSdmUsers += $sdm->users->count();
                                        }
                                    }
                                @endphp
                                <h4 class="text-warning mb-1">{{ $totalSdmUsers }}</h4>
                                <small class="text-muted">SDM</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                @php
                                    $totalTldForProjectSdm = 0;
                                    $totalPendoseForProjectSdm = 0;
                                    
                                    // Get all user IDs from project's SDM
                                    $projectUserIds = [];
                                    foreach($project->ketersediaanSdm as $sdm) {
                                        if($sdm->users) {
                                            foreach($sdm->users as $user) {
                                                $projectUserIds[] = $user->id;
                                            }
                                        }
                                    }
                                    
                                    // Count TLD records for project SDM users
                                    foreach($project->pemantauanDosisTld as $tld) {
                                        if(in_array($tld->user_id, $projectUserIds)) {
                                            $totalTldForProjectSdm++;
                                        }
                                    }
                                    
                                    // Count Pendose records for project SDM users
                                    foreach($project->pemantauanDosisPendose as $pendose) {
                                        if(in_array($pendose->user_id, $projectUserIds)) {
                                            $totalPendoseForProjectSdm++;
                                        }
                                    }
                                @endphp
                                <h4 class="text-danger mb-1">{{ $totalTldForProjectSdm }}</h4>
                                <small class="text-muted">TLD</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-secondary mb-1">{{ $totalPendoseForProjectSdm }}</h4>
                                <small class="text-muted">Pendose</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs for different sections -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <ul class="nav nav-tabs card-header-tabs" id="projectTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="perizinan-tab" data-bs-toggle="tab" data-bs-target="#perizinan" type="button" role="tab">
                        <i class="fas fa-clipboard-list me-2"></i>Perizinan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="sdm-tab" data-bs-toggle="tab" data-bs-target="#sdm" type="button" role="tab">
                        <i class="fas fa-users me-2"></i>SDM
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tld-tab" data-bs-toggle="tab" data-bs-target="#tld" type="button" role="tab">
                        <i class="fas fa-radiation me-2"></i>Pemantauan TLD
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pendose-tab" data-bs-toggle="tab" data-bs-target="#pendose" type="button" role="tab">
                        <i class="fas fa-microchip me-2"></i>Pemantauan Pendose
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="projectTabsContent">
                <!-- Perizinan Tab -->
                <div class="tab-pane fade show active" id="perizinan" role="tabpanel">
                    <h5 class="mb-3">Data Perizinan Sumber Radiasi Pengion</h5>
                    @if($project->perizinanSumberRadiasiPengion->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Tipe</th>
                                        <th>No Seri</th>
                                        <th>Aktivitas</th>
                                        <th>No KTUN</th>
                                        <th>Tanggal Berlaku</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($project->perizinanSumberRadiasiPengion as $perizinan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $perizinan->nama }}</td>
                                        <td>{{ $perizinan->tipe }}</td>
                                        <td>{{ $perizinan->no_seri }}</td>
                                        <td>{{ $perizinan->aktivitas }}</td>
                                        <td>{{ $perizinan->no_ktun }}</td>
                                        <td>{{ $perizinan->tanggal_berlaku ? $perizinan->tanggal_berlaku->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data perizinan untuk proyek ini</p>
                        </div>
                    @endif
                </div>

                <!-- SDM Tab -->
                <div class="tab-pane fade" id="sdm" role="tabpanel">
                    <h5 class="mb-3">Data Ketersediaan SDM</h5>
                    @if($project->ketersediaanSdm->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>NPR</th>
                                        <th>Username</th>
                                        <th>Jenis Pekerja</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $counter = 1; @endphp
                                    @foreach($project->ketersediaanSdm as $sdm)
                                        @if($sdm->users && $sdm->users->count() > 0)
                                            @foreach($sdm->users as $user)
                                            <tr>
                                                <td>{{ $counter++ }}</td>
                                                <td>{{ $user->nama ?? '-' }}</td>
                                                <td>{{ $user->npr ?? '-' }}</td>
                                                <td>{{ $user->username ?? '-' }}</td>
                                                <td>
                                                    @if($user->jenisPekerja && $user->jenisPekerja->count() > 0)
                                                        {{ $user->jenisPekerja->first()->nama ?? '-' }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data SDM untuk proyek ini</p>
                        </div>
                    @endif
                </div>

                <!-- TLD Tab -->
                <div class="tab-pane fade" id="tld" role="tabpanel">
                    <h5 class="mb-3">Data Pemantauan Dosis TLD</h5>
                    @php
                        // Get all user IDs from project's SDM
                        $projectUserIds = [];
                        foreach($project->ketersediaanSdm as $sdm) {
                            if($sdm->users) {
                                foreach($sdm->users as $user) {
                                    $projectUserIds[] = $user->id;
                                }
                            }
                        }
                        
                        // Filter TLD data to only show records for project SDM users
                        $filteredTldData = $project->pemantauanDosisTld->filter(function($tld) use ($projectUserIds) {
                            return in_array($tld->user_id, $projectUserIds);
                        });
                    @endphp
                    
                    @if($filteredTldData->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Tanggal Pemantauan</th>
                                        <th>Dosis</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($filteredTldData as $tld)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tld->user->nama ?? '-' }}</td>
                                        <td>{{ $tld->tanggal_pemantauan ? $tld->tanggal_pemantauan->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $tld->dosis }}</td>
                                        <td>{{ $tld->keterangan ?: '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-radiation fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data pemantauan TLD untuk SDM proyek ini</p>
                        </div>
                    @endif
                </div>

                <!-- Pendose Tab -->
                <div class="tab-pane fade" id="pendose" role="tabpanel">
                    <h5 class="mb-3">Data Pemantauan Dosis Pendose</h5>
                    @php
                        // Filter Pendose data to only show records for project SDM users
                        $filteredPendoseData = $project->pemantauanDosisPendose->filter(function($pendose) use ($projectUserIds) {
                            return in_array($pendose->user_id, $projectUserIds);
                        });
                    @endphp
                    
                    @if($filteredPendoseData->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>NPR</th>
                                        <th>Jenis Alat Pemantauan</th>
                                        <th>Hasil Pengukuran</th>
                                        <th>Tanggal Pengukuran</th>
                                        <th>Kartu Dosis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($filteredPendoseData as $pendose)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pendose->user->nama ?? '-' }}</td>
                                        <td>{{ $pendose->npr ?? '-' }}</td>
                                        <td>{{ $pendose->jenis_alat_pemantauan ?? '-' }}</td>
                                        <td>{{ $pendose->hasil_pengukuran ?? '-' }}</td>
                                        <td>{{ $pendose->tanggal_pengukuran ? $pendose->tanggal_pengukuran->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            @if($pendose->kartu_dosis)
                                                <span class="badge bg-success">Ada</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Ada</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-microchip fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data pemantauan Pendose untuk SDM proyek ini</p>
                        </div>
                    @endif
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
    
    .table-dark th {
        background-color: #1e3a5f;
        border-color: #1e3a5f;
        color: white;
        font-weight: 600;
        padding: 12px 8px;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 2px solid transparent;
    }

    .nav-tabs .nav-link.active {
        color: #1e3a5f;
        background-color: transparent;
        border-bottom: 2px solid #1e3a5f;
    }

    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: #1e3a5f;
    }

    .card-header {
        border-bottom: 1px solid #dee2e6;
    }

    .badge {
        font-size: 0.75rem;
    }
</style>
@endsection 