@extends('layouts.admin')
@section('title', 'Management User')

@section('breadcrumb')
<li class="breadcrumb-item active">Management User</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">Management User</h4>
        <p class="text-muted small mb-0">Kelola semua akun pengguna sistem</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-2"></i>Tambah User
    </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:#eff6ff;">
                    <i class="bi bi-people fs-4 text-primary"></i>
                </div>
                <div>
                    <div class="text-muted small">Total User</div>
                    <div class="fw-bold fs-4">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:#f0fdf4;">
                    <i class="bi bi-shield-check fs-4 text-success"></i>
                </div>
                <div>
                    <div class="text-muted small">Admin</div>
                    <div class="fw-bold fs-4">{{ $stats['admin'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 p-3" style="background:#faf5ff;">
                    <i class="bi bi-mortarboard fs-4 text-purple" style="color:#7c3aed;"></i>
                </div>
                <div>
                    <div class="text-muted small">Peserta</div>
                    <div class="fw-bold fs-4">{{ $stats['peserta'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label small fw-semibold text-muted">Cari Nama / Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                        placeholder="Nama atau email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Role</label>
                <select name="role" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
                    <option value="peserta" {{ request('role') === 'peserta' ? 'selected' : '' }}>Peserta</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                @if(request()->hasAny(['search','role']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($users->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-people fs-1 d-block mb-2"></i>
            <p class="mb-0">Tidak ada user yang ditemukan.</p>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width:40px;">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Terdaftar</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $i => $user)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $users->firstItem() + $i }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                    style="width:34px;height:34px;font-size:0.8rem;flex-shrink:0;
                                    background:{{ $user->role === 'admin' ? '#16a34a' : '#2563eb' }};">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="fw-semibold small">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td class="small text-muted">{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                            <span
                                class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2">
                                <i class="bi bi-shield-check me-1"></i>Admin
                            </span>
                            @else
                            <span
                                class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2">
                                <i class="bi bi-mortarboard me-1"></i>Peserta
                            </span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="text-center pe-4">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                    class="btn btn-sm btn-outline-primary px-2" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                    class="btn btn-sm btn-outline-secondary px-2" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus user {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-4 py-3 border-top d-flex align-items-center justify-content-between">
            <div class="text-muted small">
                Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} user
            </div>
            {{ $users->withQueryString()->links() }}
        </div>
        @endif
        @endif
    </div>
</div>
@endsection