@extends('layouts.admin')

@section('title', 'Daftar Pendaftar')

@section('breadcrumb')
<li class="breadcrumb-item active">Pendaftar</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="fw-700 mb-0">Daftar Pendaftar Magang</h4>
    <span class="badge bg-primary-subtle text-primary px-3 py-2 fw-600">
        {{ $pendaftaran->total() }} Total
    </span>
</div>

{{-- Filter & Search --}}
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.pendaftaran.index') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-600">Cari</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                        value="{{ request('search') }}" placeholder="Nama, NIM, atau institusi...">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-600">Filter Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending'  ? 'selected' : '' }}>Menunggu</option>
                    <option value="diterima" {{ request('status') === 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="ditolak" {{ request('status') === 'ditolak'  ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="{{ route('admin.pendaftaran.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x me-1"></i>Reset
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Pendaftar</th>
                        <th>Institusi</th>
                        <th>Bidang</th>
                        <th>Program</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendaftaran as $index => $item)
                    <tr>
                        <td class="text-muted">{{ $pendaftaran->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-600">{{ $item->user->name }}</div>
                            <div class="text-muted small">{{ $item->user->email }}</div>
                        </td>
                        <td>
                            <div>{{ $item->asal_institusi }}</div>
                            <div class="text-muted small">{{ $item->jurusan }}</div>
                        </td>
                        <td>{{ $item->bidang->nama }}</td>
                        <td>
                            <span class="badge bg-light text-dark">
                                {{ strtoupper($item->jenis_program) }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="badge badge-{{ $item->status }}">
                                {{ $item->status_label }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.pendaftaran.show', $item) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada data pendaftaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($pendaftaran->hasPages())
    <div class="card-footer bg-white py-3">
        <div class="d-flex align-items-center justify-content-between">
            <div class="text-muted small">
                Menampilkan {{ $pendaftaran->firstItem() }}–{{ $pendaftaran->lastItem() }}
                dari {{ $pendaftaran->total() }} data
            </div>
            {{ $pendaftaran->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif
</div>
@endsection