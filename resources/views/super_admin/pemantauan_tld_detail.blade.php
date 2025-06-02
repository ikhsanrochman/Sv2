@extends('layouts.super_admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Pemantauan TLD - {{ $project->nama_proyek }}</h3>
                        <div class="card-tools">
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
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>NPR</th>
                                            @php
                                                $startYear = 2022;
                                                $endYear = 2024;
                                            @endphp
                                            @for ($year = $startYear; $year <= $endYear; $year++)
                                                <th>Total Dosis {{ $year }}</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $user->nama }}</td>
                                                <td>{{ $user->npr ?? '-' }}</td>
                                                @for ($year = $startYear; $year <= $endYear; $year++)
                                                    @php
                                                        $totalDosis = $user->pemantauanDosisTld
                                                            ->where('project_id', $project->id)
                                                            ->filter(function($item) use ($year) {
                                                                return date('Y', strtotime($item->tanggal_pemantauan)) == $year;
                                                            })
                                                            ->sum('dosis');
                                                    @endphp
                                                    <td>{{ $totalDosis > 0 ? number_format($totalDosis, 2) : '-' }}</td>
                                                @endfor
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