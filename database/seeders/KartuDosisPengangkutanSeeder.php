<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KartuDosisPengangkutanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first project ID for seeding
        $projectId = DB::table('projects')->first()->id;

        $data = [
            [
                'project_id' => $projectId,
                'kegiatan_pengangkutan_tanggal' => Carbon::now()->subDays(5),
                'kegiatan_pengangkutan_waktu' => '09:00:00',
                'kegiatan_pengangkutan_tempat' => 'PT. XYZ - Rumah Sakit ABC',
                'pengukuran_surveymeter_depan' => 0.15,
                'pengukuran_surveymeter_belakang' => 0.12,
                'pengukuran_surveymeter_kanan' => 0.10,
                'pengukuran_surveymeter_kiri' => 0.11,
                'pengukuran_surveymeter_pengemudi' => 0.08,
                'sdm_nama' => 'John Doe',
                'dosis_pendose_pengangkutan' => 0.25,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'project_id' => $projectId,
                'kegiatan_pengangkutan_tanggal' => Carbon::now()->subDays(3),
                'kegiatan_pengangkutan_waktu' => '14:30:00',
                'kegiatan_pengangkutan_tempat' => 'Rumah Sakit ABC - PT. XYZ',
                'pengukuran_surveymeter_depan' => 0.18,
                'pengukuran_surveymeter_belakang' => 0.14,
                'pengukuran_surveymeter_kanan' => 0.12,
                'pengukuran_surveymeter_kiri' => 0.13,
                'pengukuran_surveymeter_pengemudi' => 0.09,
                'sdm_nama' => 'Jane Smith',
                'dosis_pendose_pengangkutan' => 0.28,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'project_id' => $projectId,
                'kegiatan_pengangkutan_tanggal' => Carbon::now()->subDay(),
                'kegiatan_pengangkutan_waktu' => '11:15:00',
                'kegiatan_pengangkutan_tempat' => 'PT. XYZ - Klinik DEF',
                'pengukuran_surveymeter_depan' => 0.16,
                'pengukuran_surveymeter_belakang' => 0.13,
                'pengukuran_surveymeter_kanan' => 0.11,
                'pengukuran_surveymeter_kiri' => 0.12,
                'pengukuran_surveymeter_pengemudi' => 0.07,
                'sdm_nama' => 'Robert Johnson',
                'dosis_pendose_pengangkutan' => 0.22,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('kartu_dosis_pengangkutan')->insert($data);
    }
}
