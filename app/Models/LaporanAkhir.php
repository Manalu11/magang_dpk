<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanAkhir extends Model
{
    protected $fillable = [
        'pendaftaran_id',
        'judul',
        'deskripsi',
        'file_path',
        'status',
        'catatan_admin',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
            default     => 'Menunggu Review',
        };
    }
}