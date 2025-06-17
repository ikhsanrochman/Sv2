@extends('layouts.super_admin')

@section('content')
<!-- Breadcrumb Section -->
<div id="breadcrumb-container" class="breadcrumb-section mb-4 position-fixed">
    <div class="d-flex justify-content-between bg-dark-blue py-2 px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-white">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Kelola Akun</li>
            </ol>
        </nav>
    </div>
</div>

<div style="margin-top: 50px;"></div>

<!-- Header -->
<div class="mb-2">
    <h2 class="fw-bold">Kelola Akun</h2>
    <div class="text-muted" style="font-size: 15px;">Kelola dan telusuri akun pengguna disini</div>
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

<!-- Filter dan Tambah Akun -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center">
        <form action="{{ route('super_admin.kelola_akun') }}" method="GET" class="d-flex align-items-center">
            <label for="search" class="me-2 mb-0">Apply filter</label>
            <input type="text" name="search" id="search" class="form-control me-2" placeholder="Search by name or username" style="width: 250px;" value="{{ request('search') }}">
            <button type="submit" class="btn btn-dark">Search</button>
        </form>
    </div>
    <a href="{{ route('super_admin.kelola_akun.create') }}" class="btn btn-dark px-4 py-2 d-flex align-items-center" style="border-radius: 18px;">
        <span class="me-2">+</span> Tambah Akun
    </a>
</div>

<!-- Tabel Daftar User -->
<div class="table-responsive">
    <table class="table table-bordered align-middle" style="min-width: 900px;">
        <thead class="bg-dark-blue text-white">
            <tr>
                <th style="width: 40px;">No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Role</th>
                <th>Bidang</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
            <tr style="background: {{ $index % 2 == 0 ? '#e5e7eb' : 'white' }}">
                <td>{{ $users->firstItem() + $index }}</td>
                <td>{{ $user->nama }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->role->name }}</td>
                <td>
                    @if($user->jenisPekerja->isNotEmpty())
                        {{ $user->jenisPekerja->pluck('nama')->join(', ') }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ $user->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td>
                    <div class="d-flex gap-2">
                        <button class="btn btn-warning btn-sm" onclick="toggleUserStatus({{ $user->id }})">
                            <i class="fas fa-power-off"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteUser({{ $user->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data pengguna</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="d-flex justify-content-end mt-3">
    {{ $users->links() }}
</div>

<style>
    .breadcrumb-section {
        background-color: #1e3a5f !important;
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
    .bg-dark-blue { background-color: #1e3a5f !important; }
    .badge.bg-success { background-color: #38b000 !important; }
    .badge.bg-secondary { background-color: #a0a0a0 !important; }
    .btn-danger { background-color: #e63946 !important; border: none; }
    .btn-dark { background-color: #0a2647 !important; border: none; }
    .btn-warning { background-color: #ffc107 !important; border: none; }
    .table th, .table td { vertical-align: middle; }
    .breadcrumb-section {
        left: 280px;
        right: 30px;
        top: 85px;
        z-index: 999;
        transition: left 0.3s;
    }
    body.sidebar-collapsed .breadcrumb-section {
        left: 30px;
    }
</style>

@push('scripts')
<script>
function toggleUserStatus(userId) {
    if (confirm('Apakah Anda yakin ingin mengubah status pengguna ini?')) {
        fetch(`/super-admin/kelola-akun/toggle-status/${userId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Terjadi kesalahan saat mengubah status pengguna.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah status pengguna.');
        });
    }
}

function deleteUser(userId) {
    if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
        // Implement delete functionality
        alert('Fitur hapus pengguna belum diimplementasikan.');
    }
}
</script>
@endpush

@endsection

