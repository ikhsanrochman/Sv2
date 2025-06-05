@extends('layouts.super_admin')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css" rel="stylesheet">
<style>
    .table-responsive {
        overflow-x: auto;
    }
    .table th, .table td {
        white-space: nowrap;
    }
    .year-column {
        min-width: 120px;
    }
    .year-navigation {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    .year-navigation button {
        margin: 0 0.5rem;
    }
    .year-navigation .current-years {
        font-weight: bold;
    }
</style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Pemantauan TLD - {{ $project->nama_proyek }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addDosisModal">
                                <i class="fas fa-plus"></i> Tambah Data Dosis
                            </button>
                            <a href="{{ route('super_admin.pemantauan_tld') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Informasi Project</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 200px">Nama Project</th>
                                        <td>{{ $project->nama_proyek }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Mulai</th>
                                        <td>{{ $project->tanggal_mulai ? date('d/m/Y', strtotime($project->tanggal_mulai)) : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Selesai</th>
                                        <td>{{ $project->tanggal_selesai ? date('d/m/Y', strtotime($project->tanggal_selesai)) : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Keterangan</th>
                                        <td>{{ $project->keterangan ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <h5>Data Pemantauan TLD</h5>
                        @if($users->isEmpty())
                            <div class="alert alert-info">
                                Belum ada data pemantauan TLD untuk project ini.
                            </div>
                        @else
                            <div class="year-navigation">
                                <button type="button" class="btn btn-secondary" id="prevYears">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span class="current-years" id="yearRange"></span>
                                <button type="button" class="btn btn-secondary" id="nextYears">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px">No</th>
                                            <th style="width: 200px">Nama</th>
                                            <th style="width: 100px">NPR</th>
                                            <th class="year-column" data-year=""></th>
                                            <th class="year-column" data-year=""></th>
                                            <th class="year-column" data-year=""></th>
                                            <th style="width: 100px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $user->nama }}</td>
                                                <td>{{ $user->npr ?? '-' }}</td>
                                                <td class="text-right year-data" data-user-id="{{ $user->id }}"></td>
                                                <td class="text-right year-data" data-user-id="{{ $user->id }}"></td>
                                                <td class="text-right year-data" data-user-id="{{ $user->id }}"></td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewDosisModal{{ $user->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Data Dosis -->
    <div class="modal fade" id="addDosisModal" tabindex="-1" role="dialog" aria-labelledby="addDosisModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDosisModalLabel">Tambah Data Dosis</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addDosisForm" action="{{ route('super_admin.pemantauan_tld.store_dosis', $project->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="user_id">SDM</label>
                            <select class="form-control select2" id="user_id" name="user_id" required>
                                <option value="">Pilih SDM</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->nama }} ({{ $user->npr ?? 'No NPR' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_pemantauan">Tanggal Pemantauan</label>
                            <input type="date" class="form-control" id="tanggal_pemantauan" name="tanggal_pemantauan" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="dosis">Dosis (mSv)</label>
                            <input type="number" step="0.01" class="form-control" id="dosis" name="dosis" required>
                            <small class="form-text text-muted">
                                Batas aman: 20 mSv/tahun, Batas berbahaya: 50 mSv/tahun
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="submitDosisBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Lihat Data Dosis untuk setiap SDM -->
    @foreach($users as $user)
    <div class="modal fade" id="viewDosisModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="viewDosisModalLabel{{ $user->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDosisModalLabel{{ $user->id }}">Data Dosis TLD - {{ $user->nama }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tanggal Pemantauan</th>
                                    <th>Dosis (mSv)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->pemantauanDosisTld->where('project_id', $project->id)->sortByDesc('tanggal_pemantauan') as $dosis)
                                <tr>
                                    <td>{{ $dosis->tanggal_pemantauan->format('d/m/Y') }}</td>
                                    <td>{{ number_format($dosis->dosis, 2) }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editDosisModal{{ $dosis->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('super_admin.pemantauan_tld.delete_dosis', $dosis->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal Edit Data Dosis -->
    @foreach($users as $user)
        @foreach($user->pemantauanDosisTld->where('project_id', $project->id) as $dosis)
        <div class="modal fade" id="editDosisModal{{ $dosis->id }}" tabindex="-1" role="dialog" aria-labelledby="editDosisModalLabel{{ $dosis->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDosisModalLabel{{ $dosis->id }}">Edit Data Dosis</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('super_admin.pemantauan_tld.update_dosis', $dosis->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="tanggal_pemantauan{{ $dosis->id }}">Tanggal Pemantauan</label>
                                <input type="date" class="form-control" id="tanggal_pemantauan{{ $dosis->id }}" name="tanggal_pemantauan" value="{{ $dosis->tanggal_pemantauan }}" required>
                            </div>
                            <div class="form-group">
                                <label for="dosis{{ $dosis->id }}">Dosis (mSv)</label>
                                <input type="number" step="0.01" class="form-control" id="dosis{{ $dosis->id }}" name="dosis" value="{{ $dosis->dosis }}" required>
                                <small class="form-text text-muted">
                                    Batas aman: 20 mSv/tahun, Batas berbahaya: 50 mSv/tahun
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    @endforeach
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // Initialize year navigation
        let startYear = new Date().getFullYear() - 1;
        let endYear = startYear + 2;

        function updateYearDisplay() {
            $('#yearRange').text(startYear + ' - ' + endYear);
            
            // Update table headers
            $('.year-column').each(function(index) {
                $(this).attr('data-year', startYear + index);
                $(this).text(startYear + index);
            });

            // Update data for each user
            $('.year-data').each(function() {
                const userId = $(this).data('user-id');
                const year = $(this).parent().find('.year-column').attr('data-year');
                
                // Get all dosis data for this user
                const dosisData = window.dosisData[userId] || [];
                
                // Calculate total for the year
                const yearlyTotal = dosisData
                    .filter(d => new Date(d.tanggal_pemantauan).getFullYear() == year)
                    .reduce((sum, d) => sum + parseFloat(d.dosis), 0);
                
                // Update the cell
                $(this).text(yearlyTotal > 0 ? yearlyTotal.toFixed(2) : '-');
            });
        }

        // Load user data
        function loadUserData() {
            const projectId = {{ $project->id }};
            const userIds = @json($users->pluck('id'));
            
            // Get all dosis data for the project
            $.get(`/api/pemantauan-tld/${projectId}/dosis`, function(data) {
                // Group data by user
                window.dosisData = {};
                data.forEach(d => {
                    if (!window.dosisData[d.user_id]) {
                        window.dosisData[d.user_id] = [];
                    }
                    window.dosisData[d.user_id].push(d);
                });
                
                // Update display
                updateYearDisplay();
            });
        }

        // Load initial data
        loadUserData();

        // Handle year navigation
        $('#prevYears').click(function() {
            if (startYear > 2020) { // Set minimum year
                startYear--;
                endYear--;
                updateYearDisplay();
            }
        });

        $('#nextYears').click(function() {
            if (endYear < 2030) { // Set maximum year
                startYear++;
                endYear++;
                updateYearDisplay();
            }
        });

        // Initialize year display
        updateYearDisplay();

        // Handle modal triggers
        $('[data-toggle="modal"]').on('click', function() {
            var targetModal = $($(this).data('target'));
            targetModal.modal('show');
        });

        // Clean up modal backdrop when modal is hidden
        $('.modal').on('hidden.bs.modal', function () {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });

        // Handle form submissions
        $(document).on('click', '#submitDosisBtn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var form = $('#addDosisForm');
            var formData = new FormData(form[0]);
            
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#addDosisModal').modal('hide');
                    window.location.reload();
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data');
                }
            });
        });

        // Set today's date for new dosis form
        $('#addDosisModal').on('show.bs.modal', function () {
            var today = new Date().toISOString().split('T')[0];
            $('#tanggal_pemantauan').val(today);
        });

        // Handle edit form submissions
        $('.edit-dosis-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var data = form.serialize();

            $.ajax({
                url: url,
                method: 'PUT',
                data: data,
                success: function(response) {
                    if (response.success) {
                        form.closest('.modal').modal('hide');
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Terjadi kesalahan saat mengupdate data');
                }
            });
        });

        // Handle delete submissions
        $('.delete-dosis-form').on('submit', function(e) {
            e.preventDefault();
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                return;
            }

            var form = $(this);
            var url = form.attr('action');

            $.ajax({
                url: url,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus data');
                }
            });
        });
    });
</script>
@endpush 