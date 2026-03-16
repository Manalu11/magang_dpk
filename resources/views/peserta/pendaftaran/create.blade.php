@extends('layouts.peserta')

@section('title', 'Form Pendaftaran Magang')

@section('content')

{{-- ===== HEADER ===== --}}
<div class="py-4" style="background: var(--bg-soft); border-bottom: 1px solid var(--border);">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('peserta.dashboard') }}" class="text-decoration-none">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Form Pendaftaran</li>
            </ol>
        </nav>
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="fw-700 mb-1" style="color: var(--primary);">Form Pendaftaran Magang</h1>
                <p class="text-muted mb-0 small">Isi data dengan benar dan lengkap. Semua field wajib diisi.</p>
            </div>
            <a href="{{ route('peserta.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

{{-- ===== MAIN CONTENT ===== --}}
<div class="container py-4">
    <div class="row g-4">

        {{-- FORM --}}
        <div class="col-lg-8">
            <form action="{{ route('peserta.pendaftaran.store') }}" method="POST" enctype="multipart/form-data"
                id="formPendaftaran">
                @csrf

                {{-- Data Akademik --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="bi bi-mortarboard text-primary"></i>
                        <span class="fw-semibold">Data Akademik</span>
                    </div>
                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Bidang Magang <span
                                    class="text-danger">*</span></label>
                            <select name="bidang_id" class="form-select @error('bidang_id') is-invalid @enderror"
                                required>
                                <option value="">— Pilih Bidang —</option>
                                @foreach($bidang as $item)
                                <option value="{{ $item->id }}" {{ old('bidang_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                                @endforeach
                            </select>
                            @error('bidang_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">NIM / NIS <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="nim_nis"
                                    class="form-control @error('nim_nis') is-invalid @enderror"
                                    value="{{ old('nim_nis') }}" placeholder="Nomor Induk Mahasiswa/Siswa">
                                @error('nim_nis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Jenis Program <span
                                        class="text-danger">*</span></label>
                                <select name="jenis_program"
                                    class="form-select @error('jenis_program') is-invalid @enderror" required>
                                    <option value="">— Pilih Program —</option>
                                    <option value="magang" {{ old('jenis_program') === 'magang' ? 'selected' : '' }}>
                                        Magang</option>
                                    <option value="kp" {{ old('jenis_program') === 'kp'     ? 'selected' : '' }}>Kerja
                                        Praktek (KP)</option>
                                    <option value="pkl" {{ old('jenis_program') === 'pkl'    ? 'selected' : '' }}>
                                        Praktik Kerja Lapangan (PKL)</option>
                                </select>
                                @error('jenis_program') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Asal Institusi <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="asal_institusi"
                                    class="form-control @error('asal_institusi') is-invalid @enderror"
                                    value="{{ old('asal_institusi') }}" placeholder="Nama universitas / sekolah">
                                @error('asal_institusi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Jurusan / Program Studi <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="jurusan"
                                    class="form-control @error('jurusan') is-invalid @enderror"
                                    value="{{ old('jurusan') }}" placeholder="Nama jurusan Anda">
                                @error('jurusan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Periode Magang --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="bi bi-calendar3 text-primary"></i>
                        <span class="fw-semibold">Periode Magang</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Mulai <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="tanggal_mulai"
                                    class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                    value="{{ old('tanggal_mulai') }}" min="{{ date('Y-m-d') }}">
                                @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tanggal Selesai <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="tanggal_selesai"
                                    class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                    value="{{ old('tanggal_selesai') }}">
                                @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dokumen --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="bi bi-paperclip text-primary"></i>
                        <span class="fw-semibold">Unggah Dokumen</span>
                    </div>
                    <div class="card-body p-4">

                        <div class="alert alert-info d-flex gap-2 align-items-start mb-4" style="font-size:0.82rem;">
                            <i class="bi bi-info-circle-fill mt-1 flex-shrink-0"></i>
                            <span>Dokumen harus dalam format <strong>PDF</strong>, ukuran maksimal <strong>2 MB</strong>
                                per file.</span>
                        </div>

                        {{-- CV --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Curriculum Vitae (CV) <span
                                    class="text-danger">*</span></label>
                            <div class="border rounded-3 p-4 text-center bg-light" id="cvArea"
                                style="cursor:pointer; border-style: dashed !important;"
                                onclick="document.getElementById('cvInput').click()">
                                <i class="bi bi-cloud-upload fs-3 text-muted mb-2 d-block"></i>
                                <p class="text-muted small mb-2">Klik atau seret file CV ke sini</p>
                                <p class="text-muted small mb-3">Format: PDF — Maks. 2 MB</p>
                                <input type="file" name="cv" id="cvInput"
                                    class="d-none @error('cv') is-invalid @enderror" accept=".pdf" required
                                    onchange="showFileName(this, 'cvName')">
                                <span class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-folder2-open me-1"></i>Pilih File
                                </span>
                                <div id="cvName" class="mt-2 small text-success fw-semibold"></div>
                            </div>
                            @error('cv') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Surat Pengantar --}}
                        <div>
                            <label class="form-label fw-semibold">Surat Pengantar Institusi <span
                                    class="text-danger">*</span></label>
                            <div class="border rounded-3 p-4 text-center bg-light" id="suratArea"
                                style="cursor:pointer; border-style: dashed !important;"
                                onclick="document.getElementById('suratInput').click()">
                                <i class="bi bi-cloud-upload fs-3 text-muted mb-2 d-block"></i>
                                <p class="text-muted small mb-2">Klik atau seret surat pengantar ke sini</p>
                                <p class="text-muted small mb-3">Format: PDF — Maks. 2 MB</p>
                                <input type="file" name="surat_pengantar" id="suratInput"
                                    class="d-none @error('surat_pengantar') is-invalid @enderror" accept=".pdf" required
                                    onchange="showFileName(this, 'suratName')">
                                <span class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-folder2-open me-1"></i>Pilih File
                                </span>
                                <div id="suratName" class="mt-2 small text-success fw-semibold"></div>
                            </div>
                            @error('surat_pengantar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                    </div>
                </div>

                {{-- Tombol Submit --}}
                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold">
                        <i class="bi bi-send me-2"></i>Kirim Pendaftaran
                    </button>
                    <a href="{{ route('peserta.dashboard') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-x me-1"></i>Batal
                    </a>
                </div>

            </form>
        </div>

        {{-- Sidebar Checklist --}}
        <div class="col-lg-4">
            <div class="card shadow-sm p-4 sticky-top" style="top: 1rem;">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-clipboard-check text-primary me-2"></i>Checklist Dokumen
                </h6>
                <ul class="list-unstyled mb-0">
                    @foreach([
                    'CV terbaru dalam format PDF',
                    'Surat pengantar resmi dari institusi',
                    'NIM/NIS yang valid',
                    'Periode magang yang realistis',
                    'Ukuran file maksimal 2 MB',
                    ] as $check)
                    <li class="d-flex align-items-start gap-2 mb-3">
                        <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
                        <span class="text-muted small">{{ $check }}</span>
                    </li>
                    @endforeach
                </ul>

                <hr>

                <div class="alert alert-warning small mb-0 d-flex gap-2">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                    <span>Anda hanya dapat mengajukan <strong>satu pendaftaran</strong>. Pastikan data sudah benar
                        sebelum mengirim.</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function showFileName(input, targetId) {
    const target = document.getElementById(targetId);
    if (input.files && input.files[0]) {
        target.innerHTML = '<i class="bi bi-file-earmark-pdf-fill me-1"></i>' + input.files[0].name;
    }
}
</script>
@endpush