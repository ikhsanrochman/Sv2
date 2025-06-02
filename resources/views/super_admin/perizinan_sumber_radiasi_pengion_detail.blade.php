@extends('layouts.super_admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Detail Perizinan Sumber Radiasi Pengion - {{ $project->nama_proyek }}</h1>
        <a href="{{ route('super_admin.perizinan_sumber_radiasi_pengion') }}" class="btn btn-secondary">Kembali</a>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection 