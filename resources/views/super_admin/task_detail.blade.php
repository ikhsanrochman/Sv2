@extends('layouts.super_admin')

@section('content')
    <h1>Detail Tugas: {{ $task->name }}</h1>

    <a href="{{ route('super_admin.ketersediaan_sdm') }}" class="btn btn-secondary mb-3">← Kembali</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jenis Pekerja</th>
                <th>No SIB</th>
                <th>Berlaku</th>
                <th>Keahlian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($workers as $index => $worker)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $worker->nama }}</td>
                    <td>{{ $worker->keahlian }}</td>
                    <td>{{ $worker->no_sib }}</td>
                    <td>{{ $worker->berlaku }}</td>
                    <td>-</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
