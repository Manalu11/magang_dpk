@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-700 mb-1">Dashboard</h4>
        <p class="text-muted small mb-0">Selamat datang, {{ auth()->user()->name }} — {{ now()->format('d F Y') }}</p>
    </div>
    <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-eye me-1"></i>Lihat Semua Pendaftar
    </a>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card" style="background: linear-gradient(135deg, #0F3D73, #1E5AA8); color:#fff;">
            <div class="stat-icon" style="background:rgba(255,255,255,0.15);">
                <i class="bi bi-people-fill text-white"></i>
            </div>
            <div>
                <div class="stat-value">{{ $statistik['total'] }}</div>
                <div class="stat-label text-white-50">Total Pendaftar</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card" style="background:#fff;">
            <div class="stat-icon" style="background:#FFF3CD;">
                <i class="bi bi-hourglass-split" style="color:#856404;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#0F3D73;">{{ $statistik['pending'] }}</div>
                <div class="stat-label">Menunggu Review</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card" style="background:#fff;">
            <div class="stat-icon" style="background:#D1FAE5;">
                <i class="bi bi-check-circle-fill" style="color:#065F46;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#0F3D73;">{{ $statistik['diterima'] }}</div>
                <div class="stat-label">Diterima</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card" style="background:#fff;">
            <div class="stat-icon" style="background:#FEE2E2;">
                <i class="bi bi-x-circle-fill" style="color:#991B1B;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#0F3D73;">{{ $statistik['ditolak'] }}</div>
                <div class="stat-label">Ditolak</div>
            </div>
        </div>
    </div>
</div>

{{-- Progress Overview --}}
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bar-chart me-2 text-primary"></i>Statistik Pendaftaran</span>
            </div>
            <div class="card-body p-4">
                @php $total = max($statistik['total'], 1); @endphp
                @foreach([
                ['label' => 'Menunggu Review', 'key' => 'pending', 'color' => 'warning'],
                ['label' => 'Diterima', 'key' => 'diterima', 'color' => 'success'],
                ['label' => 'Ditolak', 'key' => 'ditolak', 'color' => 'danger'],
                ] as $item)
                <div class="mb-4">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="fw-600">{{ $item['label'] }}</span>
                        <span class="text-muted">{{ $statistik[$item['key']] }} dari {{ $statistik['total'] }}</span>
                    </div>
                    <div class="progress" style="height:10px;border-radius:10px;">
                        <div class="progress-bar bg-{{ $item['color'] }} rounded"
                            style="width: {{ $statistik['total'] > 0 ? round(($statistik[$item['key']] / $total) * 100) : 0 }}%">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-lightning-charge me-2 text-primary"></i>Aksi Cepat
            </div>
            <div class="card-body p-3">
                <div class="list-group list-group-flush">

                    {{-- Review Pendaftar --}}
                    <a href="{{ route('admin.pendaftaran.index', ['status' => 'pending']) }}"
                        class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 border-0 rounded mb-1"
                        style="background:var(--bg-soft);">
                        <i class="bi bi-hourglass-split text-warning fs-5"></i>
                        <div>
                            <div class="fw-600 small">Review Pendaftar</div>
                            <div class="text-muted" style="font-size:0.75rem;">{{ $statistik['pending'] }} menunggu
                            </div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                    </a>

                    {{-- Semua Pendaftar --}}
                    <a href="{{ route('admin.pendaftaran.index') }}"
                        class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 border-0 rounded mb-1"
                        style="background:var(--bg-soft);">
                        <i class="bi bi-people text-primary fs-5"></i>
                        <div>
                            <div class="fw-600 small">Semua Pendaftar</div>
                            <div class="text-muted" style="font-size:0.75rem;">Lihat semua data</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                    </a>

                    {{-- Kelola User --}}
                    <a href="{{ route('admin.users.index') }}"
                        class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 border-0 rounded mb-1"
                        style="background:var(--bg-soft);">
                        <i class="bi bi-person-gear text-info fs-5"></i>
                        <div>
                            <div class="fw-600 small">Kelola User</div>
                            <div class="text-muted" style="font-size:0.75rem;">Tambah, edit, hapus akun</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                    </a>

                    {{-- Tambah User Baru --}}
                    <a href="{{ route('admin.users.create') }}"
                        class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 border-0 rounded"
                        style="background:var(--bg-soft);">
                        <i class="bi bi-person-plus text-success fs-5"></i>
                        <div>
                            <div class="fw-600 small">Tambah User Baru</div>
                            <div class="text-muted" style="font-size:0.75rem;">Buat akun pengguna</div>
                        </div>
                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                    </a>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection