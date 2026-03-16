@extends('layouts.app')

@section('title', $bidang->nama)

@section('content')
<div class="py-4" style="background: var(--bg-soft); border-bottom: 1px solid var(--border);">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb small mb-2">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('bidang.index') }}" class="text-decoration-none">Bidang
                        Magang</a></li>
                <li class="breadcrumb-item active">{{ $bidang->nama }}</li>
            </ol>
        </nav>
        <h1 class="fw-700 mb-0" style="color: var(--primary);">{{ $bidang->nama }}</h1>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card p-4 mb-4">
                    <h5 class="fw-700 mb-3 text-primary">Deskripsi Bidang</h5>
                    <p class="text-muted" style="line-height:1.8;">{{ $bidang->deskripsi }}</p>
                </div>
                <div class="card p-4">
                    <h5 class="fw-700 mb-3 text-primary">Kriteria Peserta</h5>
                    @foreach(explode("\n", $bidang->kriteria) as $kriteria)
                    @if(trim($kriteria))
                    <div class="d-flex align-items-start gap-2 mb-2">
                        <i class="bi bi-check-circle-fill text-success mt-1" style="flex-shrink:0;"></i>
                        <span class="text-muted small">{{ trim($kriteria) }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card p-4 mb-4">
                    <h6 class="fw-700 mb-3">Info Bidang</h6>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Total Pendaftar</span>
                        <span class="fw-600">{{ $bidang->pendaftaran_count ?? 0 }} orang</span>
                    </div>
                    <div class="d-flex justify-content-between mb-0 small">
                        <span class="text-muted">Status</span>
                        <span class="badge bg-success-subtle text-success fw-600">Dibuka</span>
                    </div>
                </div>
                <div class="card p-4"
                    style="background: linear-gradient(135deg, var(--primary), var(--secondary)); border: none;">
                    <h6 class="fw-700 text-white mb-2">Tertarik Magang di Sini?</h6>
                    <p class="text-white-50 small mb-3">Daftarkan diri sekarang dan mulai perjalanan magangmu.</p>
                    <a href="{{ route('register') }}" class="btn btn-light btn-sm fw-600 text-primary w-100">
                        <i class="bi bi-pencil-square me-1"></i> Daftar Sekarang
                    </a>
                    @auth
                    @if(auth()->user()->isPeserta())
                    <a href="{{ route('peserta.pendaftaran.create') }}" class="btn btn-outline-light btn-sm w-100 mt-2">
                        Ajukan ke Bidang Ini
                    </a>
                    @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>
@endsection