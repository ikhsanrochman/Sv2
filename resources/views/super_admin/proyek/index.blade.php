@extends('layouts.super_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Kelola Proyek</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahProyekModal">
                        <i class="fas fa-plus"></i> Tambah Proyek
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Proyek</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($proyeks as $proyek)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $proyek->nama_proyek }}</td>
                                    <td>{{ $proyek->keterangan ?? '-' }}</td>
                                    <td>{{ $proyek->tanggal_mulai->format('d/m/Y') }}</td>
                                    <td>{{ $proyek->tanggal_selesai->format('d/m/Y') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editProyekModal{{ $proyek->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form id="delete-form-{{ $proyek->id }}" action="{{ route('super_admin.proyek.destroy', $proyek->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger" title="Hapus" onclick="confirmDelete({{ $proyek->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Modal Edit Proyek -->
                                <div class="modal fade" id="editProyekModal{{ $proyek->id }}" tabindex="-1" aria-labelledby="editProyekModalLabel{{ $proyek->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editProyekModalLabel{{ $proyek->id }}">Edit Proyek</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('super_admin.proyek.update', $proyek->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="edit_nama_proyek{{ $proyek->id }}" class="form-label">Nama Proyek</label>
                                                        <input type="text" class="form-control @error('nama_proyek') is-invalid @enderror" 
                                                               id="edit_nama_proyek{{ $proyek->id }}" name="nama_proyek" 
                                                               value="{{ old('nama_proyek', $proyek->nama_proyek) }}" required>
                                                        @error('nama_proyek')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="edit_keterangan{{ $proyek->id }}" class="form-label">Keterangan</label>
                                                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                                                  id="edit_keterangan{{ $proyek->id }}" name="keterangan" 
                                                                  rows="3">{{ old('keterangan', $proyek->keterangan) }}</textarea>
                                                        @error('keterangan')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="edit_tanggal_mulai{{ $proyek->id }}" class="form-label">Tanggal Mulai</label>
                                                        <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                                               id="edit_tanggal_mulai{{ $proyek->id }}" name="tanggal_mulai" 
                                                               value="{{ old('tanggal_mulai', $proyek->tanggal_mulai->format('Y-m-d')) }}" required>
                                                        @error('tanggal_mulai')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="edit_tanggal_selesai{{ $proyek->id }}" class="form-label">Tanggal Selesai</label>
                                                        <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                                               id="edit_tanggal_selesai{{ $proyek->id }}" name="tanggal_selesai" 
                                                               value="{{ old('tanggal_selesai', $proyek->tanggal_selesai->format('Y-m-d')) }}" required>
                                                        @error('tanggal_selesai')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $proyeks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Proyek -->
<div class="modal fade" id="tambahProyekModal" tabindex="-1" aria-labelledby="tambahProyekModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahProyekModalLabel">Tambah Proyek Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('super_admin.proyek.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_proyek" class="form-label">Nama Proyek</label>
                        <input type="text" class="form-control @error('nama_proyek') is-invalid @enderror" 
                               id="nama_proyek" name="nama_proyek" value="{{ old('nama_proyek') }}" required>
                        @error('nama_proyek')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                               id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                        @error('tanggal_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                               id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                        @error('tanggal_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endpush
@endsection 