@extends('layouts.super_admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Laporan Lengkap Proyek - {{ $project->nama_proyek }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('super_admin.laporan') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Proyek
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- Informasi Project --}}
                        <div class="mb-4">
                            <h5>Informasi Project</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px">Nama Project</th>
                                    <td>{{ $project->nama_proyek }}</td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>{{ $project->keterangan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Mulai</th>
                                    <td>{{ $project->tanggal_mulai ? date('d/m/Y', strtotime($project->tanggal_mulai)) : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Selesai</th>
                                    <td>{{ $project->tanggal_selesai ? date('d/m/Y', strtotime($project->tanggal_selesai)) : '-' }}</td>
                                </tr>
                            </table>
                        </div>

                        <hr>

                        {{-- Perizinan Sumber Radiasi Pengion --}}
                        <div class="mb-4">
                            <h5>Perizinan Sumber Radiasi Pengion</h5>
                            @if($project->perizinanSumberRadiasiPengion->isEmpty())
                                <div class="alert alert-info">Tidak ada data perizinan sumber radiasi pengion untuk project ini.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nomor Perizinan</th>
                                                <th>Jenis Sumber</th>
                                                <th>Kategori</th>
                                                <th>Tanggal Mulai</th>
                                                <th>Tanggal Selesai</th>
                                                <th>Status</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($project->perizinanSumberRadiasiPengion as $index => $izin)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $izin->nomor_perizinan }}</td>
                                                    <td>{{ $izin->jenis_sumber }}</td>
                                                    <td>{{ $izin->kategori }}</td>
                                                    <td>{{ $izin->tanggal_mulai ? date('d/m/Y', strtotime($izin->tanggal_mulai)) : '-' }}</td>
                                                    <td>{{ $izin->tanggal_selesai ? date('d/m/Y', strtotime($izin->tanggal_selesai)) : '-' }}</td>
                                                    <td>{{ $izin->status_perizinan }}</td>
                                                    <td>{{ $izin->keterangan ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <hr>

                        {{-- Ketersediaan SDM --}}
                        <div class="mb-4">
                            <h5>Ketersediaan SDM</h5>
                            @php
                                $allUsers = $project->ketersediaanSdm->flatMap->users->unique('id');
                            @endphp
                            @if($allUsers->isEmpty())
                                <div class="alert alert-info">Tidak ada data SDM yang terkait dengan project ini.</div>
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allUsers as $index => $user)
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
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <hr>

                        {{-- Pemantauan Dosis TLD --}}
                        <div class="mb-4">
                            <h5>Pemantauan Dosis TLD</h5>
                            @if($tldUsers->isEmpty())
                                <div class="alert alert-info">Belum ada data pemantauan TLD untuk project ini.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NPR</th>
                                                <th>Tanggal Pemantauan</th>
                                                <th>Dosis</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $tldIndex = 0;
                                            @endphp
                                            @foreach($tldUsers as $user)
                                                @foreach($user->pemantauanDosisTld->sortByDesc('tanggal_pemantauan') as $tldData)
                                                    <tr>
                                                        <td>{{ ++$tldIndex }}</td>
                                                        <td>{{ $user->nama }}</td>
                                                        <td>{{ $user->npr ?? '-' }}</td>
                                                        <td>{{ $tldData->tanggal_pemantauan ? date('d/m/Y', strtotime($tldData->tanggal_pemantauan)) : '-' }}</td>
                                                        <td>{{ number_format($tldData->dosis, 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                         <hr>

                        {{-- Pemantauan Dosis Pendose --}}
                        <div class="mb-4">
                            <h5>Pemantauan Dosis Pendose</h5>
                             @if($project->pemantauanDosisPendose->isEmpty())
                                <div class="alert alert-info">Belum ada data pemantauan dosis pendose untuk project ini.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NPR</th>
                                                <th>Jenis Alat Pemantauan</th>
                                                <th>Hasil Pengukuran</th>
                                                <th>Tanggal Pengukuran</th>
                                                <th>Kartu Dosis</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($project->pemantauanDosisPendose->sortByDesc('tanggal_pengukuran') as $index => $pendoseData)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $pendoseData->user->nama }}</td>
                                                    <td>{{ $pendoseData->npr ?? '-' }}</td>
                                                    <td>{{ $pendoseData->jenis_alat_pemantauan }}</td>
                                                    <td>{{ number_format($pendoseData->hasil_pengukuran, 2) }}</td>
                                                    <td>{{ $pendoseData->tanggal_pengukuran ? date('d/m/Y', strtotime($pendoseData->tanggal_pengukuran)) : '-' }}</td>
                                                     <td>
                                                        @if($pendoseData->kartu_dosis)
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

                        {{-- Tambahkan bagian untuk Pengangkutan Sumber Radioaktif di sini --}}
                        {{-- Contoh:
                        <hr>

                        <div class="mb-4">
                            <h5>Pengangkutan Sumber Radioaktif</h5>
                            @if($project->pengangkutanSumberRadioaktif->isEmpty())
                                <div class="alert alert-info">Tidak ada data pengangkutan sumber radioaktif untuk project ini.</div>
                            @else
                                -- Tabel Pengangkutan --
                            @endif
                        </div>
                        --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 