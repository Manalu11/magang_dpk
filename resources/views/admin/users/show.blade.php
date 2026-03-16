@extends('layouts.admin')
@section('title', 'Detail User')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.users.index') }}" class="text-decoration-none">Management User</a>
</li>
<li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">Detail User</h4>
        <p class="text-muted small mb-0">Informasi lengkap akun pengguna</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 text-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold mx-auto mb-3"
                    style="width:72px;height:72px;font-size:1.8rem;
                    background:{{ $user->role === 'admin' ? '#16a34a' : '#2563eb' }};">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <div class="text-muted small mb-3">{{ $user->email }}</div>
                @if($user->role === 'admin')
                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-2">
                    <i class="bi bi-shield-check me-1"></i>Admin
                </span>
                @else
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
                    <i class="bi bi-mortarboard me-1"></i>Peserta
                </span>
                @endif
                <hr>
                <div class="d-flex flex-column gap-2 small text-start">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">ID User</span>
                        <span class="fw-semibold">#{{ $user->id }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Terdaftar</span>
                        <span class="fw-semibold">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Diperbarui</span>
                        <span class="fw-semibold">{{ $user->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        {{-- Riwayat Pendaftaran --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-file-earmark-person me-2 text-primary"></i>Riwayat Pendaftaran
                </h6>
            </div>
            <div class="card-body p-0">
                @if($user->pendaftaran->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Bidang</th>
                                <th>Program</th>
                                <th>Periode</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->pendaftaran as $p)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $p->bidang->nama ?? '-' }}</td>
                                <td>{{ $p->jenis_program_label }}</td>
                                <td class="text-muted">
                                    {{ optional($p->tanggal_mulai)->format('d M Y') }} —
                                    {{ optional($p->tanggal_selesai)->format('d M Y') }}
                                </td>
                                <td>
                                    <span
                                        class="badge bg-{{ $p->status_badge }}-subtle text-{{ $p->status_badge }} border border-{{ $p->status_badge }}-subtle rounded-pill px-2">
                                        {{ $p->status_label }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4 text-muted small">
                    <i class="bi bi-file-earmark-x fs-2 d-block mb-1"></i>
                    Belum ada riwayat pendaftaran.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection