<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePendaftaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pastikan yang update adalah pemilik pendaftaran
        return $this->pendaftaran->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'bidang_id'       => ['required', 'exists:bidang,id'],
            'nim_nis'         => ['required', 'string', 'max:50'],
            'jenis_program'   => ['required', 'in:magang,kp,pkl'],
            'asal_institusi'  => ['required', 'string', 'max:255'],
            'jurusan'         => ['required', 'string', 'max:255'],
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],

            // File opsional saat edit — hanya validasi jika diunggah
            'cv'              => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
            'surat_pengantar' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'bidang_id.required'          => 'Bidang magang wajib dipilih.',
            'bidang_id.exists'            => 'Bidang magang tidak valid.',
            'nim_nis.required'            => 'NIM/NIS wajib diisi.',
            'jenis_program.required'      => 'Jenis program wajib dipilih.',
            'jenis_program.in'            => 'Jenis program tidak valid.',
            'asal_institusi.required'     => 'Asal institusi wajib diisi.',
            'jurusan.required'            => 'Jurusan wajib diisi.',
            'tanggal_mulai.required'      => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date'          => 'Format tanggal mulai tidak valid.',
            'tanggal_selesai.required'    => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date'        => 'Format tanggal selesai tidak valid.',
            'tanggal_selesai.after'       => 'Tanggal selesai harus setelah tanggal mulai.',
            'cv.mimes'                    => 'CV harus berformat PDF.',
            'cv.max'                      => 'Ukuran CV maksimal 2 MB.',
            'surat_pengantar.mimes'       => 'Surat pengantar harus berformat PDF.',
            'surat_pengantar.max'         => 'Ukuran surat pengantar maksimal 2 MB.',
        ];
    }
}