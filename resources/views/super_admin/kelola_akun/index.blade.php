@extends('layouts.super_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Kelola Akun</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahAkunModal">
                        <i class="fas fa-plus"></i> Tambah Akun
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
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Bidang</th>
                                    <th>No Sib</th>
                                    <th>No NPR</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->nama }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->role->name }}</td>
                                    <td>
                                        @if($user->jenisPekerja && $user->jenisPekerja->isNotEmpty())
                                            <ul class="list-unstyled mb-0">
                                                @foreach($user->jenisPekerja as $jp)
                                                    <li>{{ $jp->nama }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $user->no_sib }}</td>
                                    <td>{{ $user->npr }}</td>
                                    <td>
                                        <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }} status-badge" 
                                              style="cursor: pointer;" 
                                              data-user-id="{{ $user->id }}">
                                            {{ $user->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Akun -->
<div class="modal fade" id="tambahAkunModal" tabindex="-1" aria-labelledby="tambahAkunModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahAkunModalLabel">Tambah Akun Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('super_admin.kelola_akun.store') }}" method="POST" id="tambahAkunForm">
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
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                               id="username" name="username" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        <div class="form-text">
                            Password harus memenuhi kriteria berikut:
                            <ul class="mb-0">
                                <li id="length">Minimal 8 karakter</li>
                                <li id="uppercase">Memiliki huruf kapital</li>
                                <li id="lowercase">Memiliki huruf kecil</li>
                                <li id="number">Memiliki angka</li>
                                <li id="special">Memiliki simbol</li>
                            </ul>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role</label>
                        <select class="form-select @error('role_id') is-invalid @enderror" 
                                id="role_id" name="role_id" required>
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bidang Keahlian</label>
                        <div id="keahlian-container">
                            <div class="keahlian-item mb-2">
                                <div class="input-group">
                                    <select class="form-select @error('keahlian') is-invalid @enderror" 
                                            name="keahlian[]" required>
                                        <option value="">Pilih Bidang Keahlian</option>
                                        @foreach($jenisPekerja as $jp)
                                            <option value="{{ $jp->id }}" {{ in_array($jp->id, old('keahlian', [])) ? 'selected' : '' }}>
                                                {{ $jp->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-danger remove-keahlian" style="display: none;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success btn-sm mt-2" id="add-keahlian">
                            <i class="fas fa-plus"></i> Tambah Bidang Keahlian
                        </button>
                        @error('keahlian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="no_sib" class="form-label">No. SIB</label>
                        <input type="text" class="form-control @error('no_sib') is-invalid @enderror" 
                               id="no_sib" name="no_sib" value="{{ old('no_sib') }}" required>
                        @error('no_sib')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="npr" class="form-label">No. NPR</label>
                        <input type="text" class="form-control @error('npr') is-invalid @enderror" 
                               id="npr" name="npr" value="{{ old('npr') }}" required>
                        @error('npr')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="berlaku" class="form-label">Tanggal Berlaku</label>
                        <input type="date" class="form-control @error('berlaku') is-invalid @enderror" 
                               id="berlaku" name="berlaku" value="{{ old('berlaku') }}" required>
                        @error('berlaku')
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
    $(document).ready(function() {
        // Password validation
        const password = document.getElementById('password');
        const form = document.getElementById('tambahAkunForm');
        const length = document.getElementById('length');
        const uppercase = document.getElementById('uppercase');
        const lowercase = document.getElementById('lowercase');
        const number = document.getElementById('number');
        const special = document.getElementById('special');

        // Add initial styling
        [length, uppercase, lowercase, number, special].forEach(item => {
            item.style.color = '#dc3545';
        });

        password.addEventListener('input', function() {
            const value = this.value;
            
            // Check length
            if(value.length >= 8) {
                length.style.color = '#198754';
            } else {
                length.style.color = '#dc3545';
            }
            
            // Check uppercase
            if(/[A-Z]/.test(value)) {
                uppercase.style.color = '#198754';
            } else {
                uppercase.style.color = '#dc3545';
            }
            
            // Check lowercase
            if(/[a-z]/.test(value)) {
                lowercase.style.color = '#198754';
            } else {
                lowercase.style.color = '#dc3545';
            }
            
            // Check number
            if(/[0-9]/.test(value)) {
                number.style.color = '#198754';
            } else {
                number.style.color = '#dc3545';
            }
            
            // Check special character
            if(/[!@#$%^&*(),.?":{}|<>]/.test(value)) {
                special.style.color = '#198754';
            } else {
                special.style.color = '#dc3545';
            }
        });

        // Form validation
        form.addEventListener('submit', function(e) {
            const value = password.value;
            const isValid = 
                value.length >= 8 &&
                /[A-Z]/.test(value) &&
                /[a-z]/.test(value) &&
                /[0-9]/.test(value) &&
                /[!@#$%^&*(),.?":{}|<>]/.test(value);

            if (!isValid) {
                e.preventDefault();
                alert('Password harus memenuhi semua kriteria yang ditentukan!');
            }
        });

        // Handle status badge click
        $('.status-badge').on('click', function() {
            const badge = $(this);
            const userId = badge.data('user-id');
            
            // Optimistically update UI
            const isActive = badge.hasClass('bg-success');
            if (isActive) {
                badge.removeClass('bg-success').addClass('bg-danger').text('Nonaktif');
            } else {
                badge.removeClass('bg-danger').addClass('bg-success').text('Aktif');
            }

            // Send AJAX request to toggle status
            $.ajax({
                url: '/super-admin/kelola-akun/toggle-status/' + userId,
                type: 'POST',
                data: {
                    _token: '{!! csrf_token() !!}',
                    status: isActive ? 'inactive' : 'active'
                },
                success: function(response) {
                    console.log('Status updated successfully', response);
                },
                error: function(xhr, status, error) {
                    // Rollback UI if AJAX fails
                    if (isActive) {
                         badge.removeClass('bg-danger').addClass('bg-success').text('Aktif');
                    } else {
                         badge.removeClass('bg-success').addClass('bg-danger').text('Nonaktif');
                    }
                    console.error('Error updating status:', error);
                    alert('Gagal memperbarui status.');
                }
            });
        });

        // Bidang Keahlian Dynamic Dropdown
        const keahlianContainer = $('#keahlian-container');
        const addKeahlianBtn = $('#add-keahlian');
        
        // Function to create new dropdown
        function createKeahlianDropdown() {
            const dropdownHtml = `
                <div class="keahlian-item mb-2">
                    <div class="input-group">
                        <select class="form-select" name="keahlian[]" required>
                            <option value="">Pilih Bidang Keahlian</option>
                            @foreach($jenisPekerja as $jp)
                                <option value="{{ $jp->id }}">{{ $jp->nama }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-danger remove-keahlian">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            return dropdownHtml;
        }

        // Add new dropdown
        addKeahlianBtn.on('click', function() {
            keahlianContainer.append(createKeahlianDropdown());
            updateRemoveButtons();
        });

        // Remove dropdown
        keahlianContainer.on('click', '.remove-keahlian', function() {
            $(this).closest('.keahlian-item').remove();
            updateRemoveButtons();
        });

        // Update remove buttons visibility
        function updateRemoveButtons() {
            const items = keahlianContainer.find('.keahlian-item');
            items.find('.remove-keahlian').toggle(items.length > 1);
        }

        // Initialize remove buttons
        updateRemoveButtons();

        // Validate unique selections
        keahlianContainer.on('change', 'select', function() {
            const selectedValues = [];
            let hasDuplicate = false;

            keahlianContainer.find('select').each(function() {
                const value = $(this).val();
                if (value) {
                    if (selectedValues.includes(value)) {
                        hasDuplicate = true;
                        return false;
                    }
                    selectedValues.push(value);
                }
            });

            if (hasDuplicate) {
                alert('Bidang keahlian tidak boleh dipilih lebih dari sekali!');
                $(this).val('');
            }
        });
    });
</script>
@endpush
@endsection 