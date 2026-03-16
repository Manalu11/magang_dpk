@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<div class="auth-wrapper">
    <div class="container">
        <div class="mx-auto" style="max-width:460px;">
            <div class="card auth-card shadow-lg border-0 overflow-hidden">
                <div class="auth-brand">
                    <div class="d-flex justify-content-center mb-3">
                        <div style="width:56px;height:56px;background:rgba(255,255,255,0.2);border-radius:14px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-person-plus-fill fs-3 text-white"></i>
                        </div>
                    </div>
                    <h5 class="fw-700 mb-1">Buat Akun Baru</h5>
                    <p class="text-white-50 small mb-0">Daftarkan diri untuk mengajukan permohonan magang</p>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Nama sesuai KTP/KTM"
                                required autofocus>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Minimal 8 karakter"
                                required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation"
                                class="form-control"
                                placeholder="Ulangi password"
                                required>
                        </div>

                        <div class="alert alert-info d-flex gap-2 align-items-start py-2 mb-4" style="font-size:0.82rem;">
                            <i class="bi bi-info-circle-fill mt-1 flex-shrink-0"></i>
                            <span>Akun Anda akan terdaftar sebagai <strong>Peserta</strong>. Admin dibuat secara terpisah oleh sistem.</span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-600">
                            <i class="bi bi-person-check me-2"></i>Buat Akun
                        </button>
                    </form>

                    <hr class="my-4">
                    <p class="text-center text-muted small mb-0">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-primary fw-600 text-decoration-none">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
