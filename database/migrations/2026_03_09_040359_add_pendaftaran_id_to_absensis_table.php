<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            // pendaftaran_id & jam_masuk sudah ada, tinggal tambah yang kurang
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir')->after('tanggal');
            $table->time('jam_keluar')->nullable()->after('jam_masuk');
            $table->enum('approval', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('keterangan');

            // Cegah double absen per hari
            $table->unique(['pendaftaran_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropUnique(['pendaftaran_id', 'tanggal']);
            $table->dropColumn(['status', 'jam_keluar', 'approval']);
        });
    }
};