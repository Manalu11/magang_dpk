@extends('layouts.peserta')
@section('title', 'Absensi Saya')
@section('page_title', 'Absensi')

@section('content')

{{-- Rekap --}}
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

{{-- Aksi + Tabel --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-calendar-check me-2 text-primary"></i>Riwayat Absensi</span>
        @if(!$sudahAbsenHariIni)
        <a href="{{ route('peserta.absensi.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus me-1"></i>Absen Hari Ini
        </a>
        @else
        <span class="badge bg-success">Sudah absen hari ini</span>
        @endif
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensis as $a)
                    <tr>
                        <td class="ps-4 small fw-600">{{ $a->tanggal->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-{{ match($a->status){
                                'hadir'=>'success','izin'=>'info','sakit'=>'warning','alpha'=>'danger',default=>'secondary'
                            } }}">{{ $a->status_label }}</span>
                        </td>
                        <td class="small">{{ $a->jam_masuk ?? '-' }}</td>
                        <td class="small">{{ $a->jam_keluar ?? '-' }}</td>
                        <td class="small text-muted">{{ $a->keterangan ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ match($a->approval){
                                'disetujui'=>'success','ditolak'=>'danger',default=>'secondary'
                            } }}">{{ $a->approval_label }}</span>
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
            Belum ada riwayat absensi.
        </div>
        @endif
    </div>
</div>

@endsection