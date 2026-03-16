@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- Hero --}}
<section class="hero-section">
    <div class="container position-relative" style="z-index:1;">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="mb-3">
                    <span class="badge rounded-pill text-bg-light text-primary fw-600 px-3 py-2">
                        <i class="bi bi-mortarboard me-1"></i> Program Magang Resmi
                    </span>
                </div>
                <h1 class="display-5 fw-700 mb-3 lh-base">
                    Pendaftaran Magang Online<br>
                    <span style="color: #7EC8F0;">Dinas Perpustakaan dan Kearsipan Kota Bontang</span>
                </h1>
                <p class="lead mb-4 text-white-75" style="font-size:1.05rem; opacity:0.9;">
                    Dinas perpustakaan dan kearsipan membuka kesempatan magang bagi mahasiswa dan siswa.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg fw-600 text-primary px-4">
                        <i class="bi bi-pencil-square me-2"></i>Daftar Sekarang
                    </a>
                    <a href="{{ route('bidang.index') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-grid me-2"></i>Lihat Bidang
                    </a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
            </div>
        </div>
    </div>
</section>

{{-- Stats Bar --}}
<section style="background: var(--primary); padding: 1.5rem 0;">
    <div class="container">
        <div class="row text-center text-white g-3">
            <div class="col-6 col-md-3">
                <div class="fw-700 fs-4">{{ $bidang?->count() ?? 0 }}+</div>
                <div class="text-white-50 small">Bidang Tersedia</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-700 fs-4">100%</div>
                <div class="text-white-50 small">Gratis</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-700 fs-4">Online</div>
                <div class="text-white-50 small">Proses Pendaftaran</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-700 fs-4">Resmi</div>
                <div class="text-white-50 small">Pemerintah Kota</div>
            </div>
        </div>
    </div>
</section>


{{-- Bidang Section --}}
<section class="section" style="background: var(--bg-soft);">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Bidang Magang Tersedia</h2>
            <p class="section-subtitle">Pilih bidang yang sesuai dengan minat dan program studimu</p>
        </div>
        <div class="row g-4">
            @php
            $ikonSvg = [
            'archive' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="32"
                height="32">
                <path
                    d="M20.54 5.23l-1.39-1.68C18.88 3.21 18.47 3 18 3H6c-.47 0-.88.21-1.16.55L3.46 5.23C3.17 5.57 3 6.02 3 6.5V19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6.5c0-.48-.17-.93-.46-1.27zM12 17.5L6.5 12H10v-2h4v2h3.5L12 17.5zM5.12 5l.81-1h12l.94 1H5.12z" />
            </svg>',
            'code' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="32"
                height="32">
                <path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z" />
            </svg>',
            'library' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="32"
                height="32">
                <path
                    d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm4-8H9V5h10v2z" />
            </svg>',
            'finance' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="32"
                height="32">
                <path
                    d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z" />
            </svg>',
            'chart' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="32"
                height="32">
                <path d="M5 9.2h3V19H5V9.2zM10.6 5h2.8v14h-2.8V5zm5.6 8H19v6h-2.8v-6z" />
            </svg>',
            'people' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="32"
                height="32">
                <path
                    d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
            </svg>',
            'settings' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="32"
                height="32">
                <path
                    d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.07.63-.07.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z" />
            </svg>',
            'globe' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="32"
                height="32">
                <path
                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
            </svg>',
            ];
            @endphp

            @foreach($bidang ?? collect([]) as $item)
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('bidang.show', $item) }}" class="text-decoration-none">
                    <div class="card bidang-card p-4">
                        @if($item->thumbnail && Str::startsWith($item->thumbnail, 'storage/'))
                        {{-- Gambar full width seperti marketplace --}}
                        <div
                            style="margin: -1.5rem -1.5rem 1rem -1.5rem; height: 160px; overflow: hidden; border-radius: 12px 12px 0 0;">
                            <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->nama }}"
                                style="width:100%; height:100%; object-fit:cover;">
                        </div>
                        @else
                        <div class="bidang-icon">
                            @if($item->thumbnail && isset($ikonSvg[$item->thumbnail]))
                            {!! $ikonSvg[$item->thumbnail] !!}
                            @else
                            <i class="bi bi-briefcase"></i>
                            @endif
                        </div>
                        @endif
                        <h6 class="fw-700 mb-2 text-dark">{{ $item->nama }}</h6>
                        <p class="text-muted small mb-3" style="line-height:1.6;">{{ Str::limit($item->deskripsi, 90) }}
                        </p>
                        <div class="mt-auto">
                            <span class="badge bg-light text-primary">
                                <i class="bi bi-people me-1"></i>{{ $item->pendaftaran_count }} Pendaftar
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach

        </div>
        <div class="text-center mt-4">
            <a href="{{ route('bidang.index') }}" class="btn btn-primary px-4">
                Lihat Semua Bidang <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

{{-- How to Register Section --}}
<section class="section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-5">
                <h2 class="section-title mb-3">Alur Magang</h2>
                <p class="text-muted mb-4">Berikut tahapan proses pelaksanaan magang di DPK Kota Bontang.</p>

                <div class="step-item">
                    <div class="step-number">1</div>
                    <div>
                        <h6 class="fw-700 mb-1">Pengajuan Surat</h6>
                        <p class="text-muted small mb-0">
                            Peserta mengajukan surat permohonan magang kepada DPK Kota Bontang.
                        </p>
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-number">2</div>
                    <div>
                        <h6 class="fw-700 mb-1">Verifikasi</h6>
                        <p class="text-muted small mb-0">
                            Surat permohonan diverifikasi oleh unit terkait sesuai kebutuhan dan ketersediaan bidang.
                        </p>
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-number">3</div>
                    <div>
                        <h6 class="fw-700 mb-1">Persetujuan & Penjadwalan</h6>
                        <p class="text-muted small mb-0">
                            Peserta yang disetujui akan dijadwalkan dan ditempatkan sesuai bidang yang tersedia.
                        </p>
                    </div>
                </div>

                <div class="step-item">
                    <div class="step-number">4</div>
                    <div>
                        <h6 class="fw-700 mb-1">Pelaksanaan Magang</h6>
                        <p class="text-muted small mb-0">
                            Peserta melaksanakan kegiatan magang sesuai bidang penempatan.
                        </p>
                    </div>
                </div>

                <div class="step-item mb-0">
                    <div class="step-number">5</div>
                    <div>
                        <h6 class="fw-700 mb-1">Laporan Akhir</h6>
                        <p class="text-muted small mb-0">
                            Peserta menyusun laporan magang sebagai dasar penilaian akhir.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card p-4" style="background: linear-gradient(135deg, #f8faff, #fff);">
                    <h6 class="fw-700 mb-3 text-primary">Persyaratan Umum</h6>
                    <ul class="list-unstyled mb-0">
                        @foreach([
                        'Mahasiswa/siswa aktif dengan surat pengantar dari institusi',
                        'CV terbaru dalam format PDF (maksimal 2 MB)',
                        'Surat pengantar/permohonan dari institusi (PDF, maks 2 MB)',
                        'Bersedia mengikuti aturan dan tata tertib instansi',
                        'Satu peserta hanya dapat mengajukan satu pendaftaran'
                        ] as $syarat)
                        <li class="d-flex align-items-start gap-2 mb-3">
                            <i class="bi bi-check-circle-fill text-success mt-1"
                                style="font-size:0.9rem; flex-shrink:0;"></i>
                            <span class="text-muted small">{{ $syarat }}</span>
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-primary w-100 mt-3">
                        <i class="bi bi-pencil-square me-2"></i>Mulai Pendaftaran
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection