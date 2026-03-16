<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom status 'selesai' ke tabel pendaftarans
        Schema::table('pendaftarans', function (Blueprint $table) {
            // Ubah enum status agar mendukung 'selesai'
            // Jalankan raw jika pakai MySQL:
            // DB::statement("ALTER TABLE pendaftarans MODIFY COLUMN status ENUM('pending','diterima','ditolak','selesai') DEFAULT 'pending'");
        });

        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');           // path file sertifikat (PDF/JPG)
            $table->string('nilai')->nullable();   // nilai akhir (opsional, mis. "A", "85")
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};