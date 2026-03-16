@extends('layouts.peserta')

@section('title', 'Detail Pendaftaran')

@section('content')

{{-- ===== HEADER + BREADCRUMB ===== --}}
<div class="py-4" style="background: var(--bg-soft); border-bottom: 1px solid var(--border);">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('peserta.dashboard') }}" class="text-decoration-none">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">Detail Pendaftaran</li>
            </ol>
        </nav>

        {{-- Title row + tombol aksi utama --}}
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h1 class="fw-700 mb-0" style="color: var(--primary);">Detail Pendaftaran</h1>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                {{-- Tombol Create (selalu tampil) --}}
                <a href="{{ route('peserta.pendaftaran.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Daftar Baru
                </a>

                {{-- Edit & Hapus hanya jika belum diterima --}}
                @if($pendaftaran->status !== 'diterima')
                <a href="{{ route('peserta.pendaftaran.edit', $pendaftaran) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square me-1"></i> Edit
                </a>

                <form action="{{ route('peserta.pendaftaran.destroy', $pendaftaran) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Yakin ingin menghapus pendaftaran ini?')">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </form>
                @endif

                <a href="{{ route('peserta.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ===== MAIN CONTENT ===== --}}
<div class="container py-4">

    {{-- STATUS BANNER --}}
    <div class="mb-4">
        @if($pendaftaran->status === 'diterima')
        <div class="alert alert-success d-flex align-items-center gap-2 mb-0 shadow-sm">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>
                <strong>Pendaftaran Diterima</strong>
                <span class="text-muted ms-2 small">— Telah di-ACC oleh Admin</span>
            </div>
            <span class="badge bg-success ms-auto px-3 py-2">ACC ✓</span>
        </div>

        @elseif($pendaftaran->status === 'ditolak')
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-0 shadow-sm">
            <i class="bi bi-x-circle-fill fs-5"></i>
            <div>
                <strong>Pendaftaran Ditolak</strong>
                <span class="text-muted ms-2 small">— Silakan hubungi Admin untuk informasi lebih lanjut</span>
            </div>
            <span class="badge bg-danger ms-auto px-3 py-2">Ditolak</span>
        </div>

        @elseif($pendaftaran->status === 'pending')
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-0 shadow-sm">
            <i class="bi bi-hourglass-split fs-5"></i>
            <div>
                <strong>Menunggu Review Admin</strong>
                <span class="text-muted ms-2 small">— Pendaftaran sedang dalam proses verifikasi</span>
            </div>
            <span class="badge bg-warning text-dark ms-auto px-3 py-2">Pending</span>
        </div>

        @else
        <div class="alert alert-secondary d-flex align-items-center gap-2 mb-0 shadow-sm">
            <i class="bi bi-info-circle-fill fs-5"></i>
            <div>
                <strong>Status:</strong> {{ $pendaftaran->status_label }}
            </div>
            <span class="badge badge-{{ $pendaftaran->status }} ms-auto px-3 py-2">
                {{ $pendaftaran->status_label }}
            </span>
        </div>
        @endif
    </div>

    {{-- CATATAN ADMIN (tampil jika ada isinya) --}}
    @if($pendaftaran->catatan_admin)
    <div class="mb-4">
        @php
        $catatanClass = match($pendaftaran->status) {
        'diterima' => 'border-success',
        'ditolak' => 'border-danger',
        default => 'border-warning',
        };
        $catatanIcon = match($pendaftaran->status) {
        'diterima' => 'bi-check-circle-fill text-success',
        'ditolak' => 'bi-x-circle-fill text-danger',
        default => 'bi-exclamation-circle-fill text-warning',
        };
        @endphp
        <div class="card border-start border-4 {{ $catatanClass }} shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi {{ $catatanIcon }} fs-5"></i>
                    <span class="fw-semibold">Catatan dari Admin</span>
                </div>
                <p class="mb-0 text-muted" style="white-space: pre-line;">{{ $pendaftaran->catatan_admin }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- TABEL DETAIL DATA --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="bi bi-person-lines-fill text-primary"></i>
            <span class="fw-semibold">Data Peserta & Akademik</span>
            <span class="badge badge-{{ $pendaftaran->status }} ms-auto">{{ $pendaftaran->status_label }}</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <tbody>

                        <tr>
                            <th width="30%" class="text-muted fw-semibold ps-4 py-3 bg-light">
                                <i class="bi bi-person me-2"></i>Nama Lengkap
                            </th>
                            <td class="py-3 ps-3">{{ $pendaftaran->user->name }}</td>
                        </tr>

                        <tr>
                            <th class="text-muted fw-semibold ps-4 py-3 bg-light">
                                <i class="bi bi-envelope me-2"></i>Email
                            </th>
                            <td class="py-3 ps-3">{{ $pendaftaran->user->email }}</td>
                        </tr>

                        <tr>
                            <th class="text-muted fw-semibold ps-4 py-3 bg-light">
                                <i class="bi bi-briefcase me-2"></i>Bidang Magang
                            </th>
                            <td class="py-3 ps-3">{{ $pendaftaran->bidang->nama }}</td>
                        </tr>

                        <tr>
                            <th class="text-muted fw-semibold ps-4 py-3 bg-light">
                                <i class="bi bi-journal-bookmark me-2"></i>Jenis Program
                            </th>
                            <td class="py-3 ps-3">{{ $pendaftaran->jenis_program_label }}</td>
                        </tr>

                        <tr>
                            <th class="text-muted fw-semibold ps-4 py-3 bg-light">
                                <i class="bi bi-card-text me-2"></i>NIM / NIS
                            </th>
                            <td class="py-3 ps-3">{{ $pendaftaran->nim_nis }}</td>
                        </tr>

                        <tr>
                            <th class="text-muted fw-semibold ps-4 py-3 bg-light">
                                <i class="bi bi-building me-2"></i>Asal Institusi
                            </th>
                            <td class="py-3 ps-3">{{ $pendaftaran->asal_institusi }}</td>
                        </tr>

                        <tr>
                            <th class="text-muted fw-semibold ps-4 py-3 bg-light">
                                <i class="bi bi-diagram-3 me-2"></i>Jurusan
                            </th>
                            <td class="py-3 ps-3">{{ $pendaftaran->jurusan }}</td>
                        </tr>

                        <tr>
                            <th class="text-muted fw-semibold ps-4 py-3 bg-light">
                                <i class="bi bi-calendar-range me-2"></i>Periode Magang
                            </th>
                            <td class="py-3 ps-3">
                                <span class="badge bg-light text-dark border me-1">
                                    {{ $pendaftaran->tanggal_mulai->format('d M Y') }}
                                </span>
                                <i class="bi bi-arrow-right text-muted mx-1"></i>
                                <span class="badge bg-light text-dark border">
                                    {{ $pendaftaran->tanggal_selesai->format('d M Y') }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th class="text-muted fw-semibold ps-4 py-3 bg-light">
                                <i class="bi bi-clock-history me-2"></i>Tanggal Mendaftar
                            </th>
                            <td class="py-3 ps-3 text-muted small">
                                {{ $pendaftaran->created_at->format('d M Y, H:i') }} WIB
                            </td>
                        </tr>

                        <tr>
                            <th class="text-muted fw-semibold ps-4 py-3 bg-light">
                                <i class="bi bi-shield-check me-2"></i>Status Admin
                            </th>
                            <td class="py-3 ps-3">
                                @if($pendaftaran->status === 'diterima')
                                <span class="badge bg-success px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i> Sudah di-ACC Admin
                                </span>
                                @elseif($pendaftaran->status === 'ditolak')
                                <span class="badge bg-danger px-3 py-2">
                                    <i class="bi bi-x-circle me-1"></i> Ditolak Admin
                                </span>
                                @else
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="bi bi-hourglass-split me-1"></i> Belum di-ACC Admin
                                </span>
                                @endif
                            </td>
                        </tr>

                        {{-- Baris catatan admin di dalam tabel (selalu tampil, kosong jika belum ada) --}}
                        <tr>
                            <th class="text-muted fw-semibold ps-4 py-3 bg-light">
                                <i class="bi bi-chat-left-text me-2"></i>Catatan Admin
                            </th>
                            <td class="py-3 ps-3">
                                @if($pendaftaran->catatan_admin)
                                <span style="white-space: pre-line;">{{ $pendaftaran->catatan_admin }}</span>
                                @else
                                <span class="text-muted fst-italic small">Belum ada catatan dari admin.</span>
                                @endif
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        {{-- Card Footer: tombol aksi ringkas --}}
        <div class="card-footer bg-light d-flex flex-wrap gap-2 justify-content-end">
            @if($pendaftaran->status !== 'diterima')
            <a href="{{ route('peserta.pendaftaran.edit', $pendaftaran) }}" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil-square me-1"></i> Edit Pendaftaran
            </a>

            <form action="{{ route('peserta.pendaftaran.destroy', $pendaftaran) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger"
                    onclick="return confirm('Yakin ingin menghapus pendaftaran ini?')">
                    <i class="bi bi-trash me-1"></i> Hapus
                </button>
            </form>
            @else
            <span class="text-muted small align-self-center">
                <i class="bi bi-lock me-1"></i> Tidak dapat diedit — sudah di-ACC
            </span>
            @endif

            <a href="{{ route('peserta.pendaftaran.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Daftar Baru
            </a>
        </div>
    </div>

</div>
@endsection