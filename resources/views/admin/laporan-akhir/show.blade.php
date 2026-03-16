@extends('layouts.admin')
@section('title', 'Review Laporan Akhir')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.laporan.index') }}" class="text-decoration-none">Laporan Akhir</a>
</li>
<li class="breadcrumb-item active">Review #{{ $laporan->id }}</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">Review Laporan Akhir</h4>
        <p class="text-muted small mb-0">Detail laporan akhir yang dikirimkan peserta magang</p>
    </div>
    <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row g-4">
    {{-- LEFT: Detail Laporan --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom py-3 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-journal-richtext me-2 text-primary"></i>Isi Laporan Akhir</h6>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <label class="text-muted small fw-semibold text-uppercase" style="letter-spacing:0.05em;">Judul
                        Laporan</label>
                    <h5 class="fw-bold mt-1 mb-0">{{ $laporan->judul }}</h5>
                </div>
                <hr>
                <div class="mb-4">
                    <label class="text-muted small fw-semibold text-uppercase"
                        style="letter-spacing:0.05em;">Deskripsi</label>
                    <div class="mt-2 p-3 rounded-3" style="background:#f8fafc; line-height:1.7;">
                        {!! nl2br(e($laporan->deskripsi)) !!}
                    </div>
                </div>

                @if($laporan->file_path)
                <hr>
                <div>
                    <label class="text-muted small fw-semibold text-uppercase" style="letter-spacing:0.05em;">File
                        Laporan</label>
                    <div class="mt-2">
                        @php $ext = pathinfo($laporan->file_path, PATHINFO_EXTENSION); @endphp
                        <a href="{{ asset('storage/' . $laporan->file_path) }}" target="_blank"
                            class="btn btn-outline-primary">
                            <i class="bi bi-file-earmark-arrow-down me-2"></i>
                            Unduh File Laporan (.{{ strtoupper($ext) }})
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Review Form --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Berikan Review</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.laporan.update', $laporan->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Status Laporan</label>
                        <div class="d-flex gap-3 flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusDisetujui"
                                    value="disetujui" {{ $laporan->status === 'disetujui' ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusDisetujui">
                                    <span
                                        class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                        <i class="bi bi-check-circle me-1"></i>Setujui
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusDitolak"
                                    value="ditolak" {{ $laporan->status === 'ditolak' ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusDitolak">
                                    <span
                                        class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1">
                                        <i class="bi bi-x-circle me-1"></i>Tolak
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusPending"
                                    value="pending" {{ $laporan->status === 'pending' ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusPending">
                                    <span
                                        class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1">
                                        <i class="bi bi-hourglass me-1"></i>Pending
                                    </span>
                                </label>
                            </div>
                        </div>
                        @error('status')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="catatan_admin">
                            Catatan / Komentar Admin
                            <span class="text-muted fw-normal">(opsional)</span>
                        </label>
                        <textarea name="catatan_admin" id="catatan_admin" rows="4"
                            class="form-control @error('catatan_admin') is-invalid @enderror"
                            placeholder="Tulis catatan atau umpan balik untuk peserta...">{{ old('catatan_admin', $laporan->catatan_admin) }}</textarea>
                        @error('catatan_admin')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Upload Sertifikat --}}
                    <div id="sertifikatSection" class="mb-4"
                        style="{{ $laporan->status === 'disetujui' ? '' : 'display:none' }}">
                        <hr>
                        <label class="form-label fw-semibold">
                            Upload Sertifikat
                            <span class="text-muted fw-normal">(opsional, hanya jika disetujui)</span>
                        </label>
                        @if($laporan->pendaftaran->sertifikat)
                        <div class="alert alert-success py-2 small mb-2">
                            <i class="bi bi-award me-1"></i>Sertifikat sudah ada.
                            <a href="{{ asset('storage/' . $laporan->pendaftaran->sertifikat->file_path) }}"
                                target="_blank">Lihat</a>
                            — Upload baru untuk mengganti.
                        </div>
                        @endif
                        <input type="file" name="file_sertifikat" class="form-control mb-2"
                            accept=".pdf,.jpg,.jpeg,.png">
                        <input type="text" name="nilai" class="form-control"
                            placeholder="Nilai (opsional, contoh: A / 90)"
                            value="{{ old('nilai', $laporan->pendaftaran->sertifikat->nilai ?? '') }}">
                    </div>

                    <script>
                    document.querySelectorAll('input[name="status"]').forEach(el => {
                        el.addEventListener('change', function() {
                            document.getElementById('sertifikatSection').style.display =
                                this.value === 'disetujui' ? '' : 'none';
                        });
                    });
                    </script>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-2"></i>Simpan Review
                        </button>
                        <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary px-4">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- RIGHT: Info Peserta & Pendaftaran --}}
    <div class="col-lg-4">

        {{-- Info Peserta --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom py-3 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-person me-2 text-primary"></i>Info Peserta</h6>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold mx-auto mb-3"
                        style="width:60px;height:60px;font-size:1.4rem;">
                        {{ strtoupper(substr($laporan->pendaftaran->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <h6 class="fw-bold mb-1">{{ $laporan->pendaftaran->user->name ?? '-' }}</h6>
                    <div class="text-muted small">{{ $laporan->pendaftaran->user->email ?? '-' }}</div>
                </div>
                <hr>
                <div class="d-flex flex-column gap-2 small">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">NIM / NIS</span>
                        <span class="fw-semibold">{{ $laporan->pendaftaran->nim_nis ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Institusi</span>
                        <span class="fw-semibold">{{ $laporan->pendaftaran->asal_institusi ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Jurusan</span>
                        <span class="fw-semibold">{{ $laporan->pendaftaran->jurusan ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Program</span>
                        <span class="fw-semibold">{{ $laporan->pendaftaran->jenis_program_label ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Bidang</span>
                        <span class="fw-semibold">{{ $laporan->pendaftaran->bidang->nama ?? '-' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Periode</span>
                        <span class="fw-semibold">
                            {{ optional($laporan->pendaftaran->tanggal_mulai)->format('d M Y') }} —
                            {{ optional($laporan->pendaftaran->tanggal_selesai)->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Saat Ini --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <label class="text-muted small fw-semibold text-uppercase d-block mb-2"
                    style="letter-spacing:0.05em;">Status Saat Ini</label>
                @php
                $badge = match($laporan->status) {
                'disetujui' => ['success', 'check-circle-fill', 'Disetujui'],
                'ditolak' => ['danger', 'x-circle-fill', 'Ditolak'],
                default => ['warning', 'hourglass-split', 'Menunggu Review'],
                };
                @endphp
                <span
                    class="badge bg-{{ $badge[0] }}-subtle text-{{ $badge[0] }} border border-{{ $badge[0] }}-subtle rounded-pill px-3 py-2 fs-6">
                    <i class="bi bi-{{ $badge[1] }} me-1"></i>{{ $badge[2] }}
                </span>

                @if($laporan->catatan_admin)
                <hr>
                <label class="text-muted small fw-semibold text-uppercase d-block mb-2"
                    style="letter-spacing:0.05em;">Catatan Admin</label>
                <p class="small mb-0 p-2 rounded-2" style="background:#f8fafc;">{{ $laporan->catatan_admin }}</p>
                @endif
            </div>
        </div>

        {{-- Sertifikat --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <label class="text-muted small fw-semibold text-uppercase d-block mb-3"
                    style="letter-spacing:0.05em;">Sertifikat</label>
                @if($laporan->pendaftaran->sertifikat)
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                    <i class="bi bi-award me-1"></i>Sudah Diunggah
                </span>
                @elseif($laporan->status === 'disetujui')
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-2">
                    <i class="bi bi-clock me-1"></i>Belum Diunggah
                </span>
                @else
                <span class="text-muted small">Tersedia setelah laporan disetujui.</span>
                @endif

                <hr>
                <div class="d-flex flex-column gap-2 small">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted"><i class="bi bi-calendar3 me-1"></i>Dikirim</span>
                        <span class="fw-semibold">{{ $laporan->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted"><i class="bi bi-clock-history me-1"></i>Diperbarui</span>
                        <span class="fw-semibold">{{ $laporan->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection