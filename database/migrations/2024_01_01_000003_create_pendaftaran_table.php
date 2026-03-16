<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('bidang_id')->constrained('bidang')->onDelete('restrict');
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->string('nim_nis');
            $table->string('asal_institusi');
            $table->string('jurusan');
            $table->enum('jenis_program', ['magang', 'kp', 'pkl']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('cv');
            $table->string('surat_pengantar');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
