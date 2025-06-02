@extends('layouts.super_admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Perizinan Sumber Radiasi Pengion</h1>

    @if($projects->isEmpty())
        <div class="alert alert-info">
            Belum ada data proyek.
        </div>
    @else
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
                    @foreach($projects as $index => $project)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $project->nama_proyek }}</td>
                            <td>{{ $project->keterangan }}</td>
                            <td>{{ $project->tanggal_mulai }}</td>
                            <td>{{ $project->tanggal_selesai }}</td>
                            <td>
                                <a href="{{ route('super_admin.perizinan_sumber_radiasi_pengion.show', $project->id) }}" class="btn btn-info btn-sm">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection 