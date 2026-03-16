@extends('layouts.admin')

@section('title', 'Detail Pendaftaran')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.pendaftaran.index') }}" class="text-decoration-none">Pendaftar</a></li>
    <li class="breadcrumb-item active">Detail</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-700 mb-1">Detail Pendaftaran</h4>
        <p class="text-muted small mb-0">ID Pendaftaran: #{{ $pendaftaran->id }}</p>
    </div>
    <div class="d-flex align-items-center gap-3">
        <span class="badge badge-{{ $pendaftaran->status }} px-3 py-2 fs-6">
            {{ $pendaftaran->status_label }}
        </span>
        <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Data Peserta --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-person me-2 text-primary"></i>Data Peserta
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    @foreach([
                        ['label' => 'Nama Lengkap',     'value' => $pendaftaran->user->name],
                        ['label' => 'Email',             'value' => $pendaftaran->user->email],
                        ['label' => 'NIM / NIS',         'value' => $pendaftaran->nim_nis],
                        ['label' => 'Asal Institusi',    'value' => $pendaftaran->asal_institusi],
                        ['label' => 'Jurusan',           'value' => $pendaftaran->jurusan],
                        ['label' => 'Jenis Program',     'value' => $pendaftaran->jenis_program_label],
                        ['label' => 'Bidang Magang',     'value' => $pendaftaran->bidang->nama],
                    ] as $field)
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">{{ $field['label'] }}</div>
                        <div class="fw-600">{{ $field['value'] }}</div>
                    </div>
                    @endforeach
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Periode Magang</div>
                        <div class="fw-600">
                            {{ $pendaftaran->tanggal_mulai->format('d M Y') }} —
                            {{ $pendaftaran->tanggal_selesai->format('d M Y') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">Tanggal Mendaftar</div>
                        <div class="fw-600">{{ $pendaftaran->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Update Status Form --}}
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil-square me-2 text-primary"></i>Update Status Pendaftaran
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.pendaftaran.update-status', $pendaftaran) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="pending"  {{ $pendaftaran->status === 'pending'  ? 'selected' : '' }}>Menunggu</option>
                            <option value="diterima" {{ $pendaftaran->status === 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak"  {{ $pendaftaran->status === 'ditolak'  ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Catatan Admin</label>
                        <textarea name="catatan_admin" class="form-control @error('catatan_admin') is-invalid @enderror"
                            rows="4"
                            placeholder="Isi catatan untuk peserta (opsional). Catatan ini akan ditampilkan kepada peserta.">{{ old('catatan_admin', $pendaftaran->catatan_admin) }}</textarea>
                        @error('catatan_admin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary fw-600">
                        <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">
        {{-- Download Dokumen --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-download me-2 text-primary"></i>Download Dokumen
            </div>
            <div class="card-body p-3">
                <a href="{{ route('admin.pendaftaran.download', [$pendaftaran, 'cv']) }}"
                   class="btn btn-outline-danger w-100 mb-2 d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-pdf fs-5"></i>
                    <span>Download CV</span>
                    <i class="bi bi-download ms-auto"></i>
                </a>
                <a href="{{ route('admin.pendaftaran.download', [$pendaftaran, 'surat_pengantar']) }}"
                   class="btn btn-outline-danger w-100 d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-pdf fs-5"></i>
                    <span>Download Surat Pengantar</span>
                    <i class="bi bi-download ms-auto"></i>
                </a>
            </div>
        </div>

        {{-- Status Log --}}
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2 text-primary"></i>Info Pendaftaran
            </div>
            <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-3 small">
                    <span class="text-muted">Status Saat Ini</span>
                    <span class="badge badge-{{ $pendaftaran->status }}">{{ $pendaftaran->status_label }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 small">
                    <span class="text-muted">Dibuat</span>
                    <span class="fw-600">{{ $pendaftaran->created_at->format('d M Y') }}</span>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="text-muted">Diperbarui</span>
                    <span class="fw-600">{{ $pendaftaran->updated_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
