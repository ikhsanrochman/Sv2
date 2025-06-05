@extends('layouts.super_admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Detail Perizinan Sumber Radiasi Pengion - {{ $project->nama_proyek }}</h1>
        <div>
            <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#tambahPerizinanModal">
                <i class="fas fa-plus"></i> Tambah Perizinan
            </button>
            <a href="{{ route('super_admin.perizinan_sumber_radiasi_pengion') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Informasi Proyek</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama Proyek:</strong> {{ $project->nama_proyek }}</p>
                    <p><strong>Keterangan:</strong> {{ $project->keterangan }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tanggal Mulai:</strong> {{ $project->tanggal_mulai }}</p>
                    <p><strong>Tanggal Selesai:</strong> {{ $project->tanggal_selesai }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Perizinan Sumber Radiasi Pengion</h5>
        </div>
        <div class="card-body">
            @if($perizinanSumberRadiasiPengion->isEmpty())
                <div class="alert alert-info">
                    Belum ada data perizinan sumber radiasi pengion untuk proyek ini.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Tipe</th>
                                <th>No. Seri</th>
                                <th>Aktivitas</th>
                                <th>Tanggal Aktivitas</th>
                                <th>kV-mA</th>
                                <th>No. KTUN</th>
                                <th>Tanggal Berlaku</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($perizinanSumberRadiasiPengion as $index => $izin)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $izin->nama }}</td>
                                    <td>{{ $izin->tipe }}</td>
                                    <td>{{ $izin->no_seri }}</td>
                                    <td>{{ $izin->aktivitas }}</td>
                                    <td>{{ $izin->tanggal_aktivitas }}</td>
                                    <td>{{ $izin->kv_ma ?? '-' }}</td>
                                    <td>{{ $izin->no_ktun }}</td>
                                    <td>{{ $izin->tanggal_berlaku }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info edit-perizinan" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editPerizinanModal"
                                                data-id="{{ $izin->id }}"
                                                data-nama="{{ $izin->nama }}"
                                                data-tipe="{{ $izin->tipe }}"
                                                data-no-seri="{{ $izin->no_seri }}"
                                                data-aktivitas="{{ $izin->aktivitas }}"
                                                data-tanggal-aktivitas="{{ $izin->tanggal_aktivitas }}"
                                                data-kv-ma="{{ $izin->kv_ma }}"
                                                data-no-ktun="{{ $izin->no_ktun }}"
                                                data-tanggal-berlaku="{{ $izin->tanggal_berlaku }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('super_admin.perizinan_sumber_radiasi_pengion.destroy', $izin->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus perizinan ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Tambah Perizinan -->
    <div class="modal fade" id="tambahPerizinanModal" tabindex="-1" aria-labelledby="tambahPerizinanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPerizinanModalLabel">Tambah Perizinan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('super_admin.perizinan_sumber_radiasi_pengion.store', $project->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tipe" class="form-label">Tipe</label>
                            <input type="text" class="form-control @error('tipe') is-invalid @enderror" 
                                   id="tipe" name="tipe" value="{{ old('tipe') }}" required>
                            @error('tipe')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="no_seri" class="form-label">No. Seri</label>
                            <input type="text" class="form-control @error('no_seri') is-invalid @enderror" 
                                   id="no_seri" name="no_seri" value="{{ old('no_seri') }}" required>
                            @error('no_seri')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="aktivitas" class="form-label">Aktivitas</label>
                            <input type="text" class="form-control @error('aktivitas') is-invalid @enderror" 
                                   id="aktivitas" name="aktivitas" value="{{ old('aktivitas') }}" required>
                            @error('aktivitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_aktivitas" class="form-label">Tanggal Aktivitas</label>
                            <input type="date" class="form-control @error('tanggal_aktivitas') is-invalid @enderror" 
                                   id="tanggal_aktivitas" name="tanggal_aktivitas" value="{{ old('tanggal_aktivitas') }}" required>
                            @error('tanggal_aktivitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="kv_ma" class="form-label">kV-mA</label>
                            <input type="text" class="form-control @error('kv_ma') is-invalid @enderror" 
                                   id="kv_ma" name="kv_ma" value="{{ old('kv_ma') }}">
                            @error('kv_ma')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="no_ktun" class="form-label">No. KTUN</label>
                            <input type="text" class="form-control @error('no_ktun') is-invalid @enderror" 
                                   id="no_ktun" name="no_ktun" value="{{ old('no_ktun') }}" required>
                            @error('no_ktun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_berlaku" class="form-label">Tanggal Berlaku</label>
                            <input type="date" class="form-control @error('tanggal_berlaku') is-invalid @enderror" 
                                   id="tanggal_berlaku" name="tanggal_berlaku" value="{{ old('tanggal_berlaku') }}" required>
                            @error('tanggal_berlaku')
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

    <!-- Modal Edit Perizinan -->
    <div class="modal fade" id="editPerizinanModal" tabindex="-1" aria-labelledby="editPerizinanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPerizinanModalLabel">Edit Perizinan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPerizinanForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tipe" class="form-label">Tipe</label>
                            <input type="text" class="form-control" id="edit_tipe" name="tipe" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_no_seri" class="form-label">No. Seri</label>
                            <input type="text" class="form-control" id="edit_no_seri" name="no_seri" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_aktivitas" class="form-label">Aktivitas</label>
                            <input type="text" class="form-control" id="edit_aktivitas" name="aktivitas" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tanggal_aktivitas" class="form-label">Tanggal Aktivitas</label>
                            <input type="date" class="form-control" id="edit_tanggal_aktivitas" name="tanggal_aktivitas" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_kv_ma" class="form-label">kV-mA</label>
                            <input type="text" class="form-control" id="edit_kv_ma" name="kv_ma">
                        </div>
                        <div class="mb-3">
                            <label for="edit_no_ktun" class="form-label">No. KTUN</label>
                            <input type="text" class="form-control" id="edit_no_ktun" name="no_ktun" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tanggal_berlaku" class="form-label">Tanggal Berlaku</label>
                            <input type="date" class="form-control" id="edit_tanggal_berlaku" name="tanggal_berlaku" required>
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

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle edit button click
        $('.edit-perizinan').click(function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const tipe = $(this).data('tipe');
            const noSeri = $(this).data('no-seri');
            const aktivitas = $(this).data('aktivitas');
            const tanggalAktivitas = $(this).data('tanggal-aktivitas');
            const kvMa = $(this).data('kv-ma');
            const noKtun = $(this).data('no-ktun');
            const tanggalBerlaku = $(this).data('tanggal-berlaku');

            // Set form action URL
            $('#editPerizinanForm').attr('action', `/super-admin/perizinan-sumber-radiasi-pengion/${id}`);

            // Fill form fields
            $('#edit_nama').val(nama);
            $('#edit_tipe').val(tipe);
            $('#edit_no_seri').val(noSeri);
            $('#edit_aktivitas').val(aktivitas);
            $('#edit_tanggal_aktivitas').val(tanggalAktivitas);
            $('#edit_kv_ma').val(kvMa);
            $('#edit_no_ktun').val(noKtun);
            $('#edit_tanggal_berlaku').val(tanggalBerlaku);
        });
    });
</script>
@endpush
@endsection 