@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="auth-wrapper">
    <div class="container">
        <div class="mx-auto" style="max-width:440px;">
            <div class="card auth-card shadow-lg border-0 overflow-hidden">
                {{-- Brand Header --}}
                <div class="auth-brand">
                    <div class="d-flex justify-content-center mb-3">
                        <div style="width:56px;height:56px;background:rgba(255,255,255,0.2);border-radius:14px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-archive-fill fs-3 text-white"></i>
                        </div>
                    </div>
                    <h5 class="fw-700 mb-1">Masuk ke Sistem</h5>
                    <p class="text-white-50 small mb-0">Dinas perpustakaan dan kearsipan Kota Bontang — Pendaftaran Magang</p>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input type="email" name="email"
                                    class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="nama@email.com"
                                    required autofocus>
                            </div>
                            @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-lock text-muted"></i>
                                </span>
                                <input type="password" name="password" id="passwordInput"
                                    class="form-control border-start-0 border-end-0 ps-0 @error('password') is-invalid @enderror"
                                    placeholder="Masukkan password" required>
                                <button class="btn btn-light border" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small" for="remember">Ingat saya</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-600">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                        </button>
                    </form>

                    <hr class="my-4">
                    <p class="text-center text-muted small mb-0">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-primary fw-600 text-decoration-none">Daftar sekarang</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
</script>
@endpush