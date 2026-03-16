<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePendaftaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isPeserta();
    }

    public function rules(): array
    {
        return [
            'bidang_id'       => ['required', 'exists:bidang,id'],
            'nim_nis'         => ['required', 'string', 'max:30'],
            'asal_institusi'  => ['required', 'string', 'max:200'],
            'jurusan'         => ['required', 'string', 'max:100'],
            'jenis_program'   => ['required', 'in:magang,kp,pkl'],
            'tanggal_mulai'   => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after:tanggal_mulai'],
            'cv'              => ['required', 'file', 'mimes:pdf', 'max:2048'],
            'surat_pengantar' => ['required', 'file', 'mimes:pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'bidang_id.required'       => 'Bidang magang wajib dipilih.',
            'bidang_id.exists'         => 'Bidang yang dipilih tidak valid.',
            'nim_nis.required'         => 'NIM/NIS wajib diisi.',
            'asal_institusi.required'  => 'Asal institusi wajib diisi.',
            'jurusan.required'         => 'Jurusan wajib diisi.',
            'jenis_program.required'   => 'Jenis program wajib dipilih.',
            'jenis_program.in'         => 'Jenis program tidak valid.',
            'tanggal_mulai.required'   => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after'    => 'Tanggal selesai harus setelah tanggal mulai.',
            'cv.required'              => 'File CV wajib diunggah.',
            'cv.mimes'                 => 'File CV harus berformat PDF.',
            'cv.max'                   => 'Ukuran file CV maksimal 2 MB.',
            'surat_pengantar.required' => 'Surat pengantar wajib diunggah.',
            'surat_pengantar.mimes'    => 'Surat pengantar harus berformat PDF.',
            'surat_pengantar.max'      => 'Ukuran surat pengantar maksimal 2 MB.',
        ];
    }
}