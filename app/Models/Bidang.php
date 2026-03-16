<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    protected $table = 'bidang';

    protected $fillable = [
        'nama',
        'deskripsi',
        'kriteria',
        'thumbnail',
    ];

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function getTotalPendaftarAttribute(): int
    {
        return $this->pendaftaran()->count();
    }
}
