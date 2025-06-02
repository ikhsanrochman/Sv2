@extends('layouts.super_admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pemantauan TLD (Thermoluminescent Dosimeter)</h3>
                    </div>
                    <div class="card-body">
                        @if($projects->isEmpty())
                            <div class="alert alert-info">
                                Belum ada data project.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Project</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Selesai</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($projects as $index => $project)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $project->nama_proyek }}</td>
                                                <td>{{ $project->tanggal_mulai ? date('d/m/Y', strtotime($project->tanggal_mulai)) : '-' }}</td>
                                                <td>{{ $project->tanggal_selesai ? date('d/m/Y', strtotime($project->tanggal_selesai)) : '-' }}</td>
                                                <td>{{ $project->keterangan ?? '-' }}</td>
                                                <td>
                                                    <a href="{{ route('super_admin.pemantauan_tld.detail', $project->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
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
@endsection 