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

    public function user()
    {
        return $this->hasOneThrough(
            \App\Models\User::class,
            \App\Models\Pendaftaran::class,
            'id',
            'id',
            'pendaftaran_id',
            'user_id'
        );
    }

    // ==================== Scopes ====================

    public function scopeBulanIni($query)
    {
        return $query->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
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
            default     => 'secondary', // pending
        };
    }

    public function getJamMasukFormatAttribute(): string
    {
        return $this->jam_masuk
            ? \Carbon\Carbon::createFromFormat('H:i:s', $this->jam_masuk)->format('H:i')
            : '-';
    }

    public function getJamKeluarFormatAttribute(): string
    {
        return $this->jam_keluar
            ? \Carbon\Carbon::createFromFormat('H:i:s', $this->jam_keluar)->format('H:i')
            : '-';
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

    public function isDitolak(): bool
    {
        return $this->approval === 'ditolak';
    }
}