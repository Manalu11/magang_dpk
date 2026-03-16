@extends('layouts.admin')
@section('title', 'Data Absensi')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-700 mb-1">Data Absensi</h4>
        <p class="text-muted small mb-0">Semua rekap kehadiran peserta magang</p>
    </div>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-600">Peserta</label>
                <select name="pendaftaran_id" class="form-select form-select-sm">
                    <option value="">Semua Peserta</option>
                    @foreach($pendaftarans as $p)
                    <option value="{{ $p->id }}" {{ request('pendaftaran_id')==$p->id ? 'selected' : '' }}>
                        {{ $p->user->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-600">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-600">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="hadir" {{ request('status')=='hadir' ?'selected':'' }}>Hadir</option>
                    <option value="izin" {{ request('status')=='izin'  ?'selected':'' }}>Izin</option>
                    <option value="sakit" {{ request('status')=='sakit' ?'selected':'' }}>Sakit</option>
                    <option value="alpha" {{ request('status')=='alpha' ?'selected':'' }}>Alpha</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-600">Approval</label>
                <select name="approval" class="form-select form-select-sm">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('approval')=='pending'   ?'selected':'' }}>Pending</option>
                    <option value="disetujui" {{ request('approval')=='disetujui' ?'selected':'' }}>Disetujui</option>
                    <option value="ditolak" {{ request('approval')=='ditolak'   ?'selected':'' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('admin.absensi.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="card">
    <div class="card-body p-0">
        {{-- Bulk Approve Form --}}
        <form action="{{ route('admin.absensi.bulkApprove') }}" method="POST">
            @csrf
            <div class="d-flex gap-2 p-3 border-bottom">
                <button type="submit" name="approval" value="disetujui" class="btn btn-success btn-sm">
                    <i class="bi bi-check2-all me-1"></i>Setujui Terpilih
                </button>
                <button type="submit" name="approval" value="ditolak" class="btn btn-danger btn-sm">
                    <i class="bi bi-x-lg me-1"></i>Tolak Terpilih
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width:40px;">
                                <input type="checkbox" class="form-check-input" id="checkAll">
                            </th>
                            <th>Peserta</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Keterangan</th>
                            <th>Approval</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensis as $a)
                        <tr>
                            <td class="ps-4">
                                <input type="checkbox" name="ids[]" value="{{ $a->id }}" class="form-check-input">
                            </td>
                            <td>
                                <div class="fw-600 small">{{ $a->pendaftaran->user->name }}</div>
                                <div class="text-muted" style="font-size:.75rem;">
                                    {{ $a->pendaftaran->bidang->nama }}
                                </div>
                            </td>
                            <td class="small">{{ $a->tanggal->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ match($a->status){
                                    'hadir'=>'success','izin'=>'info','sakit'=>'warning','alpha'=>'danger',default=>'secondary'
                                } }}">{{ $a->status_label }}</span>
                            </td>
                            <td class="small">{{ $a->jam_masuk ?? '-' }}</td>
                            <td class="small">{{ $a->jam_keluar ?? '-' }}</td>
                            <td class="small text-muted">{{ Str::limit($a->keterangan, 40) ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ match($a->approval){
                                    'disetujui'=>'success','ditolak'=>'danger',default=>'secondary'
                                } }}">{{ $a->approval_label }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <button type="submit" form="approve-form-{{ $a->id }}" name="approval" value="disetujui"
                                    class="btn btn-sm btn-outline-success" title="Setujui">
                                    <i class="bi bi-check2"></i>
                                </button>
                                <button type="submit" form="approve-form-{{ $a->id }}" name="approval" value="ditolak"
                                    class="btn btn-sm btn-outline-danger" title="Tolak">
                                    <i class="bi bi-x"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">Tidak ada data absensi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        <div class="px-4 py-3">{{ $absensis->links() }}</div>
    </div>
</div>

{{-- Form approve per-baris, di luar bulk form --}}
@foreach($absensis as $a)
<form id="approve-form-{{ $a->id }}" action="{{ route('admin.absensi.approve', $a->id) }}" method="POST"
    style="display:none;">
    @csrf
    @method('PATCH')
</form>
@endforeach

<script>
document.getElementById('checkAll').addEventListener('change', function() {
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = this.checked);
});
</script>
@endsection