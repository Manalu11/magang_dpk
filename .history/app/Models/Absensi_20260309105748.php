<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = [
        'pendaftaran_id',
        'tanggal',
        'status',
        'jam_masuk',
        'jam_keluar',
        'keterangan',
        'approval',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // ==================== Relasi ====================

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    // Shortcut ke user lewat pendaftaran
    public function user()
    {
        return $this->hasOneThrough(
            \App\Models\User::class,
            \App\Models\Pendaftaran::class,
            'id',           // FK di pendaftaran
            'id',           // FK di users
            'pendaftaran_id',
            'user_id'
        );
    }

    // ==================== Accessors ====================

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'hadir' => 'Hadir',
            'izin'  => 'Izin',
            'sakit' => 'Sakit',
            'alpha' => 'Alpha',
            default => '-',
        };
    }

    public function getApprovalLabelAttribute(): string
    {
        return match ($this->approval) {
            'pending'   => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
            default     => '-',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'hadir' => 'success',
            'izin'  => 'info',
            'sakit' => 'warning',
            'alpha' => 'danger',
            default => 'secondary',
        };
    }

    public function getApprovalBadgeAttribute(): string
    {
        return match ($this->approval) {
            'disetujui' => 'success',
            'ditolak'   => 'danger',
            default     => 'secondary',
        };
    }

    // ==================== Helpers ====================

    public function isPending(): bool
    {
        return $this->approval === 'pending';
    }

    public function isDisetujui(): bool
    {
        return $this->approval === 'disetujui';
    }
}