@extends('layouts.admin')
@section('title', 'Laporan Akhir')

@section('breadcrumb')
<li class="breadcrumb-item active">Laporan Akhir</li>
@endsection

@section('content')
<div class="mb-4 d-flex align-items-center justify-content-between">
    <div>
        <h4 class="fw-bold mb-1">Laporan Akhir</h4>
        <p class="text-muted small mb-0">Kelola laporan akhir peserta magang</p>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fs-3 fw-bold">{{ $stats['total'] }}</div>
            <div class="text-muted small">Total</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fs-3 fw-bold text-warning">{{ $stats['pending'] }}</div>
            <div class="text-muted small">Pending</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fs-3 fw-bold text-success">{{ $stats['disetujui'] }}</div>
            <div class="text-muted small">Disetujui</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="fs-3 fw-bold text-danger">{{ $stats['ditolak'] }}</div>
            <div class="text-muted small">Ditolak</div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama / judul..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" @selected(request('status')==='pending' )>Pending</option>
                    <option value="disetujui" @selected(request('status')==='disetujui' )>Disetujui</option>
                    <option value="ditolak" @selected(request('status')==='ditolak' )>Ditolak</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Peserta</th>
                        <th>Judul</th>
                        <th>Bidang</th>
                        <th>Status</th>
                        <th>Diunggah</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporans as $laporan)
                    @php
                    $badge = match($laporan->status) {
                    'disetujui' => 'success',
                    'ditolak' => 'danger',
                    default => 'warning',
                    };
                    @endphp
                    <tr>
                        <td>{{ $laporans->firstItem() + $loop->index }}</td>
                        <td>{{ $laporan->pendaftaran->user->name ?? '-' }}</td>
                        <td>{{ $laporan->judul }}</td>
                        <td>{{ $laporan->pendaftaran->bidang->nama ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $badge }}-subtle text-{{ $badge }} rounded-pill px-2">
                                {{ ucfirst($laporan->status) }}
                            </span>
                        </td>
                        <td>{{ $laporan->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.laporan.show', $laporan->id) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Tidak ada laporan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
<div class="mt-3 d-flex justify-content-end">
    {{ $laporans->withQueryString()->links() }}
</div>

@endsection
```