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
                            <h4>Daftar SDM yang Terlibat</h4>
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
@endsection 