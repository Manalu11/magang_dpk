@extends('layouts.peserta')
@section('title', 'Absen Hari Ini')
@section('page_title', 'Absen Hari Ini')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-calendar-plus me-2 text-primary"></i>
                Absensi — {{ now()->format('d F Y') }}
            </div>
            <div class="card-body p-4">
                <form action="{{ route('peserta.absensi.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-600">Status Kehadiran <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required
                            onchange="toggleJam(this.value)">
                            <option value="">-- Pilih Status --</option>
                            <option value="hadir" {{ old('status')=='hadir'  ? 'selected':'' }}>Hadir</option>
                            <option value="izin" {{ old('status')=='izin'   ? 'selected':'' }}>Izin</option>
                            <option value="sakit" {{ old('status')=='sakit'  ? 'selected':'' }}>Sakit</option>
                            <option value="alpha" {{ old('status')=='alpha'  ? 'selected':'' }}>Alpha</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div id="jam-section" class="row g-3 mb-3" style="display:none!important;">
                        <div class="col-6">
                            <label class="form-label fw-600">Jam Masuk</label>
                            <input type="time" name="jam_masuk" value="{{ old('jam_masuk') }}"
                                class="form-control @error('jam_masuk') is-invalid @enderror">
                            @error('jam_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-600">Jam Keluar</label>
                            <input type="time" name="jam_keluar" value="{{ old('jam_keluar') }}"
                                class="form-control @error('jam_keluar') is-invalid @enderror">
                            @error('jam_keluar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600">Keterangan</label>
                        <textarea name="keterangan" rows="3"
                            class="form-control @error('keterangan') is-invalid @enderror"
                            placeholder="Opsional — tuliskan keterangan tambahan">{{ old('keterangan') }}</textarea>
                        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2 me-1"></i>Simpan Absensi
                        </button>
                        <a href="{{ route('peserta.absensi.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleJam(val) {
        document.getElementById('jam-section').style.setProperty('display', val === 'hadir' ? 'flex' : 'none', 'important');
    }
    document.addEventListener('DOMContentLoaded', () => {
        const sel = document.querySelector('select[name=status]');
        if (sel) toggleJam(sel.value);
    });
</script>
@endsection