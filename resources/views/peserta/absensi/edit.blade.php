@extends('layouts.peserta')
@section('title', 'Edit Absensi')
@section('page_title', 'Edit Absensi')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil-square me-2 text-primary"></i>
                Edit Absensi — {{ $absen->tanggal->translatedFormat('d F Y') }}
            </div>
            <div class="card-body p-4">
                <form action="{{ route('peserta.absensi.update', $absen->id) }}" method="POST" id="formEditAbsensi">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Status Kehadiran <span class="text-danger">*</span>
                        </label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror"
                            id="selectStatus" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="hadir" {{ old('status', $absen->status) === 'hadir' ? 'selected' : '' }}>
                                Hadir</option>
                            <option value="izin" {{ old('status', $absen->status) === 'izin'  ? 'selected' : '' }}>Izin
                            </option>
                            <option value="sakit" {{ old('status', $absen->status) === 'sakit' ? 'selected' : '' }}>
                                Sakit</option>
                            <option value="alpha" {{ old('status', $absen->status) === 'alpha' ? 'selected' : '' }}>
                                Alpha</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="jam-section" class="row g-3 mb-3" style="display:none;">
                        <div class="col-6">
                            <label class="form-label fw-semibold">
                                Jam Masuk <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="jam_masuk"
                                value="{{ old('jam_masuk', $absen->jam_masuk ? \Carbon\Carbon::createFromFormat('H:i:s', $absen->jam_masuk)->format('H:i') : '') }}"
                                id="inputJamMasuk" class="form-control @error('jam_masuk') is-invalid @enderror">
                            @error('jam_masuk')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Jam Keluar</label>
                            <input type="time" name="jam_keluar"
                                value="{{ old('jam_keluar', $absen->jam_keluar ? \Carbon\Carbon::createFromFormat('H:i:s', $absen->jam_keluar)->format('H:i') : '') }}"
                                id="inputJamKeluar" class="form-control @error('jam_keluar') is-invalid @enderror">
                            @error('jam_keluar')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" rows="3"
                            class="form-control @error('keterangan') is-invalid @enderror"
                            placeholder="Opsional — tuliskan keterangan tambahan"
                            maxlength="500">{{ old('keterangan', $absen->keterangan) }}</textarea>
                        @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-end">
                            <span id="charCount">0</span>/500
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2 me-1"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('peserta.absensi.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const selectStatus = document.getElementById('selectStatus');
const jamSection = document.getElementById('jam-section');
const inputJamMasuk = document.getElementById('inputJamMasuk');
const textarea = document.querySelector('textarea[name=keterangan]');
const charCount = document.getElementById('charCount');

function toggleJam(val) {
    const show = val === 'hadir';
    jamSection.style.display = show ? 'flex' : 'none';
    inputJamMasuk.required = show;
}

selectStatus.addEventListener('change', e => toggleJam(e.target.value));
toggleJam(selectStatus.value);

function updateChar() {
    charCount.textContent = textarea.value.length;
}
textarea.addEventListener('input', updateChar);
updateChar();
</script>
@endpush

@endsection