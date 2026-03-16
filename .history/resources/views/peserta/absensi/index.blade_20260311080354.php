@extends('layouts.peserta')
@section('title', 'Absensi Saya')
@section('page_title', 'Absensi')

@section('content')

{{-- Rekap bulan --}}
<div class="row g-3 mb-4">
    @foreach([
    ['label'=>'Hadir', 'key'=>'hadir', 'color'=>'success', 'icon'=>'bi-check-circle-fill'],
    ['label'=>'Izin', 'key'=>'izin', 'color'=>'info', 'icon'=>'bi-info-circle-fill'],
    ['label'=>'Sakit', 'key'=>'sakit', 'color'=>'warning', 'icon'=>'bi-thermometer-half'],
    ['label'=>'Alpha', 'key'=>'alpha', 'color'=>'danger', 'icon'=>'bi-x-circle-fill'],
    ] as $r)
    <div class="col-6 col-lg-3">
        <div class="card stat-card" style="background:#fff;">
            <div class="stat-icon" style="background:var(--bs-{{ $r['color'] }}-bg, #f8f9fa);">
                <i class="bi {{ $r['icon'] }} text-{{ $r['color'] }}"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#0F3D73;">{{ $rekap[$r['key']] }}</div>
                <div class="stat-label">{{ $r['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Tabel --}}
<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span><i class="bi bi-calendar-check me-2 text-primary"></i>Riwayat Absensi</span>

        <div class="d-flex flex-wrap align-items-center gap-2">

            {{-- Filter bulan --}}
            <form method="GET" action="{{ route('peserta.absensi.index') }}" class="d-flex gap-2 align-items-center">
                <input type="month" name="bulan" value="{{ $filterBulan }}" class="form-control form-control-sm"
                    style="width:160px;" onchange="this.form.submit()">

                <select name="status" class="form-select form-select-sm" style="width:130px;"
                    onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    @foreach(['hadir'=>'Hadir','izin'=>'Izin','sakit'=>'Sakit','alpha'=>'Alpha'] as $val=>$label)
                    <option value="{{ $val }}" {{ request('status')===$val ? 'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </form>

            {{-- Tombol absen / sudah absen --}}
            @if(!$sudahAbsenHariIni)
            <a href="{{ route('peserta.absensi.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus me-1"></i>Absen Hari Ini
            </a>
            @else
            <span class="badge bg-success py-2 px-3">
                <i class="bi bi-check2 me-1"></i>Sudah absen hari ini
            </span>
            @endif
        </div>
    </div>

    <div class="card-body p-0">
        @if($absensis->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Status</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Keterangan</th>
                        <th>Approval</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensis as $a)
                    <tr>
                        <td class="ps-4 small fw-600">{{ $a->tanggal->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $a->status_badge }}">{{ $a->status_label }}</span>
                        </td>
                        <td class="small">{{ $a->jam_masuk_format }}</td>
                        <td class="small">{{ $a->jam_keluar_format }}</td>
                        <td class="small text-muted" style="max-width:180px;">
                            {{ $a->keterangan ?? '-' }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $a->approval_badge }}">{{ $a->approval_label }}</span>
                        </td>
                        <td class="text-center">
                            @if($a->isPending())
                            <a href="{{ route('peserta.absensi.edit', $a->id) }}"
                                class="btn btn-sm btn-outline-primary py-0 px-2" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 ms-1" title="Hapus"
                                onclick="konfirmasiHapus({{ $a->id }}, '{{ $a->tanggal->format('d M Y') }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                            <form id="form-hapus-{{ $a->id }}" action="{{ route('peserta.absensi.destroy', $a->id) }}"
                                method="POST" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                            @else
                            <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3">{{ $absensis->links() }}</div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
            Belum ada riwayat absensi pada periode ini.
        </div>
        @endif
    </div>
</div>

{{-- Modal konfirmasi hapus --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i>Hapus Absensi?
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <p class="small text-muted mb-0">
                    Absensi tanggal <strong id="tanggalHapus"></strong> akan dihapus permanen.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-danger" id="btnHapusKonfirmasi">Hapus</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let formHapusTarget = null;

function konfirmasiHapus(id, tanggal) {
    formHapusTarget = document.getElementById('form-hapus-' + id);
    document.getElementById('tanggalHapus').textContent = tanggal;
    new bootstrap.Modal(document.getElementById('modalHapus')).show();
}

document.getElementById('btnHapusKonfirmasi').addEventListener('click', function() {
    if (formHapusTarget) formHapusTarget.submit();
});
</script>
@endpush

@endsection