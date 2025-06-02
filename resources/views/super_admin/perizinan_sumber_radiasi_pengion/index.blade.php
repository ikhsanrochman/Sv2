@extends('layouts.super_admin')

@section('content')
    <h1>Daftar Perizinan Sumber Radiasi Pengion</h1>

    @if($perizinanSumberRadiasiPengion->isEmpty())
        <div class="alert alert-info">
            Belum ada data perizinan sumber radiasi pengion.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
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
                        <th>Aksi</th>
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
                            <td>
                                {{-- Add action buttons here (e.g., View, Edit, Delete) --}}
                                <button class="btn btn-info btn-sm">Detail</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection 