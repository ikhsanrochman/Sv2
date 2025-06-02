@extends('layouts.super_admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Pemantauan Dosis Pendose - {{ $project->nama_proyek }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('super_admin.pemantauan_dosis_pendose') }}" class="btn btn-secondary btn-sm">
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

                        <h5>Data Pemantauan Dosis Pendose</h5>
                        @if($pemantauan->isEmpty())
                            <div class="alert alert-info">
                                Belum ada data pemantauan dosis pendose untuk project ini.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">Nama</th>
                                            <th rowspan="2">NPR</th>
                                            <th rowspan="2">Alat Pemantauan</th>
                                            <th colspan="5" class="text-center">Hasil Pengukuran Tahunan (mSv)</th>
                                            <th rowspan="2">Kartu Dosis</th>
                                        </tr>
                                        <tr>
                                            @php
                                                $currentYear = date('Y');
                                            @endphp
                                            @for($i = 0; $i < 5; $i++)
                                                <th>{{ $currentYear - $i }}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $groupedData = $pemantauan->groupBy('user_id');
                                        @endphp
                                        @foreach($groupedData as $index => $userData)
                                            @php
                                                $user = $userData->first()->user;
                                                $latestData = $userData->first();
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $user->nama }}</td>
                                                <td>{{ $latestData->npr ?? '-' }}</td>
                                                <td>{{ $latestData->jenis_alat_pemantauan }}</td>
                                                @for($i = 0; $i < 5; $i++)
                                                    @php
                                                        $year = $currentYear - $i;
                                                        $yearlyTotal = $userData->filter(function($item) use ($year) {
                                                            return date('Y', strtotime($item->tanggal_pengukuran)) == $year;
                                                        })->sum('hasil_pengukuran');
                                                    @endphp
                                                    <td>{{ $yearlyTotal > 0 ? number_format($yearlyTotal, 2) : '-' }}</td>
                                                @endfor
                                                <td>
                                                    @if($latestData->kartu_dosis)
                                                        <span class="badge badge-success">Ada</span>
                                                    @else
                                                        <span class="badge badge-danger">Tidak Ada</span>
                                                    @endif
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