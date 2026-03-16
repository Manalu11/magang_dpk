<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('approval', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->timestamps();

            $table->unique(['pendaftaran_id', 'tanggal']); // 1 absen per hari
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};