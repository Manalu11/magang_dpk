@extends('layouts.peserta')
@section('title', 'Unggah Laporan Akhir')
@section('page_title', 'Unggah Laporan Akhir')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-upload me-2 text-primary"></i>Form Laporan Akhir
            </div>
            <div class="card-body p-4">
                <form action="{{ route('peserta.laporan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-600">Judul Laporan <span class="text-danger">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}"
                            class="form-control @error('judul') is-invalid @enderror"
                            placeholder="Contoh: Laporan Akhir Magang di Divisi IT" required>
                        @error('judul')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600">Deskripsi Singkat</label>
                        <textarea name="deskripsi" rows="3"
                            class="form-control @error('deskripsi') is-invalid @enderror"
                            placeholder="Ringkasan isi laporan (opsional)">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-600">File Laporan <span class="text-danger">*</span></label>
                        <input type="file" name="file" accept=".pdf,.doc,.docx"
                            class="form-control @error('file') is-invalid @enderror" required>
                        <div class="form-text">Format: PDF / DOC / DOCX. Maks 10 MB.</div>
                        @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-1"></i>Unggah Laporan
                        </button>
                        <a href="{{ route('peserta.laporan.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection