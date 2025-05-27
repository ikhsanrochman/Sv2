@extends('layouts.super_admin')

@section('content')
    <h1>Ketersediaan SDM</h1>

    <!-- Button Tambah Tugas Baru -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahTugasModal">
        Tambah Tugas Baru
    </button>

    @if($tasks->isEmpty())
        <p>Tidak ada data tugas.</p>
    @else
        <table class="table table-bordered mt-2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Tugas</th>
                    <th>Dibuat Pada</th>
                    <th>Detail</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $index => $task)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $task->name }}</td>
                        <td>{{ $task->created_at->format('d M Y H:i') }}</td>
                        <td>
    <a href="{{ route('super_admin.tasks.detail', $task->id) }}" class="btn btn-info btn-sm">
        Detail
    </a>
</td>

                        <td>
                            <button class="btn btn-warning btn-sm me-1" onclick="alert('Edit belum dibuat');">
                                Edit
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="alert('Delete belum dibuat');">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Modal Tambah Tugas Baru -->
    <div class="modal fade" id="tambahTugasModal" tabindex="-1" aria-labelledby="tambahTugasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('super_admin.tasks.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahTugasModalLabel">Tambah Tugas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="taskName" class="form-label">Nama Tugas</label>
                        <input type="text" class="form-control" id="taskName" placeholder="Masukkan nama tugas" name="task_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Pekerja</label>
                        <div id="workerList">
                            <div class="d-flex align-items-center mb-2 worker-row" data-index="1">
                                <div style="width: 30px;">1.</div>
                                <select class="form-select" name="user_ids[]" style="flex: 1;" required>
                                    <option value="" disabled selected>Pilih pekerja</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary mt-2" id="addWorkerBtn">Tambah Pekerja</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let workerList = document.getElementById('workerList');
        let addWorkerBtn = document.getElementById('addWorkerBtn');

        addWorkerBtn.addEventListener('click', function() {
            let currentCount = workerList.querySelectorAll('.worker-row').length;
            let nextIndex = currentCount + 1;

            // Buat div worker-row baru
            let newWorkerRow = document.createElement('div');
            newWorkerRow.classList.add('d-flex', 'align-items-center', 'mb-2', 'worker-row');
            newWorkerRow.setAttribute('data-index', nextIndex);

            // Nomor urut
            let numberDiv = document.createElement('div');
            numberDiv.style.width = '30px';
            numberDiv.textContent = nextIndex + '.';

            // Clone select pertama dan reset valuenya
            let firstSelect = workerList.querySelector('select.form-select');
            let selectClone = firstSelect.cloneNode(true);
            selectClone.value = "";
            selectClone.required = true;

            // Tambahkan ke worker-row
            newWorkerRow.appendChild(numberDiv);
            newWorkerRow.appendChild(selectClone);

            // Tambahkan ke workerList
            workerList.appendChild(newWorkerRow);
        });
    });
</script>
@endpush
