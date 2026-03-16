@extends('layouts.peserta')
@section('title', 'Absen Hari Ini')
@section('page_title', 'Absen Hari Ini')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-calendar-plus me-2 text-primary"></i>
                Absensi — {{ now()->translatedFormat('d F Y') }}
            </div>
            <div class="card-body p-4">
                <form action="{{ route('peserta.absensi.store') }}" method="POST" id="formAbsensi">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Status Kehadiran <span class="text-danger">*</span>
                        </label>
                        <select name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            id="selectStatus"
                            required>
                            <option value="">-- Pilih Status --</option>
                            <option value="hadir" {{ old('status') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="izin"  {{ old('status') === 'izin'  ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ old('status') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="alpha" {{ old('status') === 'alpha' ? 'selected' : '' }}>Alpha</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jam masuk & keluar — hanya tampil saat status = hadir --}}
                    <div id="jam-section" class="row g-3 mb-3" style="display:none;">
                        <div class="col-6">
                            <label class="form-label fw-semibold">
                                Jam Masuk <span class="text-danger">*</span>
                            </label>
                            <input type="time" name="jam_masuk"
                                value="{{ old('jam_masuk') }}"
                                id="inputJamMasuk"
                                class="form-control @error('jam_masuk') is-invalid @enderror">
                            @error('jam_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Jam Keluar</label>
                            <input type="time" name="jam_keluar"
                                value="{{ old('jam_keluar') }}"
                                id="inputJamKeluar"
                                class="form-control @error('jam_keluar') is-invalid @enderror">
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
                            maxlength="500">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-end">
                            <span id="charCount">0</span>/500
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary" onclick="konfirmasiSubmit()">
                            <i class="bi bi-check2 me-1"></i>Simpan Absensi
                        </button>
                        <a href="{{ route('peserta.absensi.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal konfirmasi submit --}}
<div class="modal fade" id="modalKonfirmasi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-shield-check me-1 text-primary"></i>Konfirmasi Absensi
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <p class="small text-muted mb-2">Pastikan data sudah benar sebelum menyimpan.</p>
                <table class="table table-sm table-borderless mb-0 small">
                    <tr>
                        <td class="text-muted ps-0">Tanggal</td>
                        <td class="fw-semibold">{{ now()->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Status</td>
                        <td class="fw-semibold" id="previewStatus">—</td>
                    </tr>
                    <tr id="rowJamMasuk">
                        <td class="text-muted ps-0">Jam Masuk</td>
                        <td class="fw-semibold" id="previewJamMasuk">—</td>
                    </tr>
                    <tr id="rowJamKeluar">
                        <td class="text-muted ps-0">Jam Keluar</td>
                        <td class="fw-semibold" id="previewJamKeluar">—</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Koreksi</button>
                <button type="button" class="btn btn-sm btn-primary" id="btnSubmitFinal">
                    <i class="bi bi-check2 me-1"></i>Ya, Simpan
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const selectStatus  = document.getElementById('selectStatus');
    const jamSection    = document.getElementById('jam-section');
    const inputJamMasuk = document.getElementById('inputJamMasuk');
    const inputJamKeluar= document.getElementById('inputJamKeluar');
    const textarea      = document.querySelector('textarea[name=keterangan]');
    const charCount     = document.getElementById('charCount');

    // Toggle jam section
    function toggleJam(val) {
        const show = val === 'hadir';
        jamSection.style.display = show ? 'flex' : 'none';
        inputJamMasuk.required   = show;
    }

    selectStatus.addEventListener('change', e => toggleJam(e.target.value));

    // Inisialisasi saat load (penting untuk old() setelah validasi gagal)
    toggleJam(selectStatus.value);

    // Hitung karakter keterangan
    function updateChar() { charCount.textContent = textarea.value.length; }
    textarea.addEventListener('input', updateChar);
    updateChar();

    // Modal konfirmasi
    function konfirmasiSubmit() {
        const status = selectStatus.options[selectStatus.selectedIndex];

        if (!selectStatus.value) {
            selectStatus.classList.add('is-invalid');
            selectStatus.focus();
            return;
        }
        selectStatus.classList.remove('is-invalid');

        const statusLabels = { hadir:'Hadir', izin:'Izin', sakit:'Sakit', alpha:'Alpha' };
        document.getElementById('previewStatus').textContent   = statusLabels[selectStatus.value] ?? '—';

        const isHadir = selectStatus.value === 'hadir';
        document.getElementById('rowJamMasuk').style.display  = isHadir ? '' : 'none';
        document.getElementById('rowJamKeluar').style.display = isHadir ? '' : 'none';
        document.getElementById('previewJamMasuk').textContent  = inputJamMasuk.value  || '—';
        document.getElementById('previewJamKeluar').textContent = inputJamKeluar.value || '(belum diisi)';

        new bootstrap.Modal(document.getElementById('modalKonfirmasi')).show();
    }

    document.getElementById('btnSubmitFinal').addEventListener('click', function () {
        document.getElementById('formAbsensi').submit();
    });
</script>
@endpush

@endsection