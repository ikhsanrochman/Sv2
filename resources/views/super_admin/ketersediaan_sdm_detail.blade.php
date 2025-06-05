@extends('layouts.super_admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Detail Ketersediaan SDM - {{ $project->nama_proyek }}</h3>
                </div>
                <div class="card-body">
                    <!-- Informasi Project -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4>Informasi Project</h4>
                            <hr>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Nama Project:</strong>
                                <p>{{ $project->nama_proyek }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Tanggal Mulai:</strong>
                                <p>{{ \Carbon\Carbon::parse($project->tanggal_mulai)->format('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Tanggal Selesai:</strong>
                                <p>{{ \Carbon\Carbon::parse($project->tanggal_selesai)->format('d F Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Keterangan:</strong>
                                <p>{{ $project->keterangan ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar SDM -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4>Daftar SDM yang Terlibat</h4>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSdmModal">
                                    <i class="fas fa-plus"></i> Tambah SDM
                                </button>
                            </div>
                            <hr>
                            @if($project->ketersediaanSdm->isEmpty())
                                <div class="alert alert-info">
                                    Belum ada data SDM yang terlibat dalam project ini.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Jenis Pekerja</th>
                                                <th>No. SIB</th>
                                                <th>Berlaku Sampai</th>
                                                <th>Tanggal Dibuat</th>
                                                <th>Tanggal Diperbarui</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($project->ketersediaanSdm->flatMap->users as $index => $user)
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
                                                    <td>{{ $user->berlaku ? \Carbon\Carbon::parse($user->berlaku)->format('d F Y') : '-' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d F Y H:i') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($user->updated_at)->format('d F Y H:i') }}</td>
                                                    <td>
                                                        <form action="{{ route('super_admin.ketersediaan_sdm.remove_user', ['project_id' => $project->id, 'user_id' => $user->id]) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus SDM ini?')">
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
                </div>
                <div class="card-footer">
                    <a href="{{ route('super_admin.ketersediaan_sdm') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah SDM -->
<div class="modal fade" id="addSdmModal" tabindex="-1" aria-labelledby="addSdmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSdmModalLabel">Tambah SDM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('super_admin.ketersediaan_sdm.add_user', $project->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="userSelects">
                        <div class="user-select-group mb-3">
                            <select class="form-select select2-user" name="users[]" required>
                                <option value="">Pilih SDM</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->nama }} - {{ $user->jenisPekerja->pluck('nama')->join(', ') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="addUserSelect">
                        <i class="fas fa-plus"></i> Tambah SDM Lain
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container {
        z-index: 99999;
    }
    .select2-container .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2-search__field {
        padding: 6px !important;
    }
    .modal-dialog {
        max-width: 500px;
    }
    .modal-content {
        border-radius: 8px;
    }
    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        padding: 1rem;
    }
    .modal-body {
        padding: 1.5rem;
    }
    .modal-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
        padding: 1rem;
    }
    .user-select-group {
        margin-bottom: 1rem;
        padding: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        background-color: #fff;
    }
    .user-select-group:last-child {
        margin-bottom: 0;
    }
    .btn-outline-primary {
        margin-top: 1rem;
    }
    .remove-select {
        margin-top: 0.5rem;
    }
    #userSelects {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 5px;
    }
    #userSelects::-webkit-scrollbar {
        width: 6px;
    }
    #userSelects::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    #userSelects::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
    #userSelects::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Function to initialize select2 with consistent styling
        function initializeSelect2(element) {
            element.select2({
                dropdownParent: $('#addSdmModal'),
                width: '100%',
                placeholder: 'Pilih SDM',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "Tidak ada hasil yang ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    }
                }
            });
        }

        // Function to update available options
        function updateAvailableOptions() {
            const selectedValues = [];
            
            // Collect all selected values
            $('.select2-user').each(function() {
                const value = $(this).val();
                if (value) {
                    selectedValues.push(value);
                }
            });
            
            // Update each select
            $('.select2-user').each(function() {
                const currentSelect = $(this);
                const currentValue = currentSelect.val();
                
                // Hide options that are selected in other dropdowns
                currentSelect.find('option').each(function() {
                    const optionValue = $(this).val();
                    if (optionValue && optionValue !== currentValue && selectedValues.includes(optionValue)) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
                
                // Refresh select2
                currentSelect.trigger('change.select2');
            });
        }

        // Initialize first select
        initializeSelect2($('.select2-user'));

        // Add new select
        $('#addUserSelect').click(function() {
            const newSelect = $('.user-select-group:first').clone();
            const selectElement = newSelect.find('select');
            
            // Clear value and remove any existing select2
            selectElement.val('');
            selectElement.next('.select2-container').remove();
            
            if ($('.user-select-group').length > 0) {
                newSelect.append(`
                    <button type="button" class="btn btn-outline-danger btn-sm remove-select">
                        <i class="fas fa-times"></i> Hapus
                    </button>
                `);
            }
            
            $('#userSelects').append(newSelect);
            
            // Initialize new select2
            initializeSelect2(selectElement);
            
            // Update available options
            updateAvailableOptions();
        });

        // Remove select
        $(document).on('click', '.remove-select', function() {
            const selectGroup = $(this).closest('.user-select-group');
            const select = selectGroup.find('select');
            
            // Destroy select2 before removing
            select.select2('destroy');
            selectGroup.remove();
            
            // Update available options
            updateAvailableOptions();
        });

        // Handle select changes
        $(document).on('change', '.select2-user', function() {
            updateAvailableOptions();
        });

        // Reset modal when closed
        $('#addSdmModal').on('hidden.bs.modal', function() {
            // Destroy all select2 instances
            $('.select2-user').each(function() {
                $(this).select2('destroy');
            });
            
            // Remove additional selects
            $('.user-select-group').not(':first').remove();
            
            // Reset and reinitialize first select
            const firstSelect = $('.select2-user:first');
            firstSelect.val('');
            initializeSelect2(firstSelect);
        });
    });
</script>
@endpush
@endsection 