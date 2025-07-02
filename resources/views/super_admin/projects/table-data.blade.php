<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">No</th>
                <th scope="col">Nama Proyek</th>
                <th scope="col">Keterangan</th>
                <th scope="col">Tanggal Mulai</th>
                <th scope="col">Tanggal Selesai</th>
                <th scope="col" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody id="projectTableBody">
            @forelse($projects as $project)
            <tr>
                <td class="text-center">{{ $loop->iteration + ($projects->firstItem() - 1) }}</td>
                <td>{{ $project->nama_proyek }}</td>
                <td>{{ $project->keterangan }}</td>
                <td>{{ $project->tanggal_mulai->format('d/m/Y') }}</td>
                <td>{{ $project->tanggal_selesai->format('d/m/Y') }}</td>
                <td class="text-center">
                    <button class="btn btn-danger btn-sm me-1 delete-project" data-id="{{ $project->id }}">Hapus</button>
                    <a href="{{ route('super_admin.projects.edit', $project->id) }}" class="btn btn-primary btn-sm">Edit</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data proyek</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-between align-items-center mt-4">
    <div>
        <span class="text-muted">Menampilkan {{ $projects->firstItem() ?? 0 }} sampai {{ $projects->lastItem() ?? 0 }} dari {{ $projects->total() }} data</span>
    </div>
    <nav aria-label="Page navigation">
        {{ $projects->appends(request()->except('page'))->links() }}
    </nav>
</div>
