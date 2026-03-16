<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';

    protected $fillable = [
        'user_id',
        'bidang_id',
        'status',
        'nim_nis',
        'asal_institusi',
        'jurusan',
        'jenis_program',
        'tanggal_mulai',
        'tanggal_selesai',
        'cv',
        'surat_pengantar',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Status constants
    const STATUS_PENDING  = 'pending';
    const STATUS_DITERIMA = 'diterima';
    const STATUS_DITOLAK  = 'ditolak';
    const STATUS_SELESAI  = 'selesai';

    // ==================== Relasi ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function laporanAkhir()
    {
        return $this->hasOne(LaporanAkhir::class);
    }

    public function sertifikat()
    {
        return $this->hasOne(Sertifikat::class);
    }

    // ==================== Accessors ====================

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'diterima' => 'success',
            'ditolak'  => 'danger',
            'selesai'  => 'primary',
            default    => 'warning',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'diterima' => 'Diterima',
            'ditolak'  => 'Ditolak',
            'selesai'  => 'Selesai',
            default    => 'Menunggu',
        };
    }

    public function getJenisProgramLabelAttribute(): string
    {
        return match ($this->jenis_program) {
            'kp'  => 'Kerja Praktek (KP)',
            'pkl' => 'Praktik Kerja Lapangan (PKL)',
            default => 'Magang',
        };
    }

    // ==================== Helpers ====================

    public function isLocked(): bool
    {
        return in_array($this->status, ['diterima', 'selesai']);
    }

    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }

    public function isDiterima(): bool
    {
        return $this->status === 'diterima';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isDitolak(): bool
    {
        return $this->status === 'ditolak';
    }
}