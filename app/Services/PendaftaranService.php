<?php

namespace App\Services;

use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PendaftaranService
{
    /**
     * Membuat pendaftaran baru untuk peserta.
     */
    public function store(User $user, array $data, UploadedFile $cv, UploadedFile $suratPengantar): Pendaftaran
    {
        return DB::transaction(function () use ($user, $data, $cv, $suratPengantar) {
            $cvPath              = $this->uploadFile($cv, 'cv');
            $suratPengantarPath  = $this->uploadFile($suratPengantar, 'surat_pengantar');

            return Pendaftaran::create([
                'user_id'         => $user->id,
                'bidang_id'       => $data['bidang_id'],
                'nim_nis'         => $data['nim_nis'],
                'asal_institusi'  => $data['asal_institusi'],
                'jurusan'         => $data['jurusan'],
                'jenis_program'   => $data['jenis_program'],
                'tanggal_mulai'   => $data['tanggal_mulai'],
                'tanggal_selesai' => $data['tanggal_selesai'],
                'cv'              => $cvPath,
                'surat_pengantar' => $suratPengantarPath,
                'status'          => Pendaftaran::STATUS_PENDING,
            ]);
        });
    }

    /**
     * Update status dan catatan admin.
     */
    public function updateStatus(Pendaftaran $pendaftaran, string $status, ?string $catatan = null): Pendaftaran
    {
        $pendaftaran->update([
            'status'        => $status,
            'catatan_admin' => $catatan,
        ]);

        return $pendaftaran->fresh(['user', 'bidang']);
    }

    /**
     * Mengecek apakah peserta sudah memiliki pendaftaran aktif.
     */
    public function sudahMendaftar(User $user): bool
    {
        return Pendaftaran::where('user_id', $user->id)->exists();
    }

    /**
     * Mendapatkan statistik untuk dashboard admin.
     */
    public function getStatistik(): array
    {
        return [
            'total'    => Pendaftaran::count(),
            'pending'  => Pendaftaran::where('status', 'pending')->count(),
            'diterima' => Pendaftaran::where('status', 'diterima')->count(),
            'ditolak'  => Pendaftaran::where('status', 'ditolak')->count(),
        ];
    }

    /**
     * Upload file ke storage.
     */
    private function uploadFile(UploadedFile $file, string $folder): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs("pendaftaran/{$folder}", $filename, 'public');
    }

    /**
     * Hapus file dari storage.
     */
    public function deleteFiles(Pendaftaran $pendaftaran): void
    {
        Storage::disk('public')->delete([
            $pendaftaran->cv,
            $pendaftaran->surat_pengantar,
        ]);
    }
}