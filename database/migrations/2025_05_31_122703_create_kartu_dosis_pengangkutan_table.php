<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kartu_dosis_pengangkutan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->date('kegiatan_pengangkutan_tanggal')->nullable();
            $table->time('kegiatan_pengangkutan_waktu')->nullable();
            $table->string('kegiatan_pengangkutan_tempat')->nullable();
            $table->decimal('pengukuran_surveymeter_depan', 8, 2)->nullable();
            $table->decimal('pengukuran_surveymeter_belakang', 8, 2)->nullable();
            $table->decimal('pengukuran_surveymeter_kanan', 8, 2)->nullable();
            $table->decimal('pengukuran_surveymeter_kiri', 8, 2)->nullable();
            $table->decimal('pengukuran_surveymeter_pengemudi', 8, 2)->nullable();
            $table->string('sdm_nama')->nullable();
            $table->decimal('dosis_pendose_pengangkutan', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_dosis_pengangkutan');
    }
};
