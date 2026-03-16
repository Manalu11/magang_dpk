@extends('layouts.admin')
@section('title', 'Edit User')

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('admin.users.index') }}" class="text-decoration-none">Management User</a>
</li>
<li class="breadcrumb-item active">Edit — {{ $user->name }}</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1">Edit User</h4>
        <p class="text-muted small mb-0">Perbarui data akun pengguna</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Data User</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="email">Email</label>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="role">Role</label>
                        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="peserta" {{ old('role', $user->role) === 'peserta' ? 'selected' : '' }}>
                                Peserta</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin'   ? 'selected' : '' }}>Admin
                            </option>
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <hr>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        Kosongkan kolom password jika tidak ingin mengubah password.
                    </p>


                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="password">Password Baru</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Minimal 8 karakter">
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="togglePass('password', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="password_confirmation">Konfirmasi Password
                            Baru</label>
                        <div class="input-group">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" placeholder="Ulangi password baru">
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="togglePass('password_confirmation', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-2"></i>Perbarui
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    const isPass = input.type === 'password';
    input.type = isPass ? 'text' : 'password';
    btn.innerHTML = isPass ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
}
</script>
@endpush
@endsection