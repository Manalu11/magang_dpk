@extends('layouts.peserta')

@section('title', 'Dashboard Peserta')
@section('page_title', 'Dashboard Saya')

@section('content')

{{-- ============================================================
     BELUM MENDAFTAR
     ============================================================ --}}
@if(!$pendaftaran)
<div class="card text-center p-5 shadow-sm">
    <i class="bi bi-inbox fs-1 text-muted opacity-50 mb-3"></i>
    <h5 class="fw-700">Belum Ada Pendaftaran</h5>
    <p class="text-muted mb-4">Silakan daftar magang terlebih dahulu untuk memulai.</p>
    <div>
        <a href="{{ route('peserta.pendaftaran.create') }}" class="btn btn-primary px-5">
            <i class="bi bi-plus-circle me-2"></i>Daftar Sekarang
        </a>
    </div>
</div>

@else

{{-- ============================================================
     GREETING + STATUS BADGE
     ============================================================ --}}
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <h4 class="fw-700 mb-1">Selamat datang, {{ auth()->user()->name }} 👋</h4>
        <p class="text-muted mb-0 small">Berikut ringkasan aktivitas magang kamu.</p>
    </div>
    <a href="{{ route('peserta.pendaftaran.show', $pendaftaran) }}" class="text-decoration-none">
        <span class="badge fs-6 px-3 py-2 badge-{{ $pendaftaran->status }}">
            <i class="bi bi-circle-fill me-1" style="font-size:8px;vertical-align:middle;"></i>
            {{ $pendaftaran->status_label }}
        </span>
    </a>
</div>

{{-- ============================================================
     STATUS BANNER (pending / ditolak)
     ============================================================ --}}
@if($pendaftaran->status === 'pending')
<div class="alert alert-warning d-flex align-items-center gap-2 shadow-sm mb-4">
    <i class="bi bi-hourglass-split fs-5 flex-shrink-0"></i>
    <div>
        <strong>Pendaftaran sedang diverifikasi.</strong>
        <span class="text-muted ms-1 small">Fitur absensi & laporan akan aktif setelah diterima.</span>
    </div>
</div>
@elseif($pendaftaran->status === 'ditolak')
<div class="alert alert-danger d-flex align-items-center gap-2 shadow-sm mb-4">
    <i class="bi bi-x-circle-fill fs-5 flex-shrink-0"></i>
    <div>
        <strong>Pendaftaran ditolak.</strong>
        @if($pendaftaran->catatan_admin)
        <span class="text-muted ms-1 small">{{ $pendaftaran->catatan_admin }}</span>
        @endif
    </div>
</div>
@endif

{{-- ============================================================
     INFO PENDAFTARAN (4 tile)
     ============================================================ --}}
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="p-3 bg-light rounded h-100">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-briefcase me-1"></i>Bidang
                    </small>
                    <div class="fw-600">{{ $pendaftaran->bidang->nama }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 bg-light rounded h-100">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-mortarboard me-1"></i>Program
                    </small>
                    <div class="fw-600">{{ $pendaftaran->jenis_program_label }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 bg-light rounded h-100">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-building me-1"></i>Institusi
                    </small>
                    <div class="fw-600">{{ $pendaftaran->asal_institusi }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 bg-light rounded h-100">
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-calendar-range me-1"></i>Periode
                    </small>
                    <div class="fw-600" style="font-size:13px;">
                        {{ $pendaftaran->tanggal_mulai->format('d M Y') }} —
                        {{ $pendaftaran->tanggal_selesai->format('d M Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     PROGRESS BAR DURASI MAGANG
     ============================================================ --}}
@php
$totalHari = max($pendaftaran->tanggal_mulai->diffInDays($pendaftaran->tanggal_selesai), 1);
$hariDijalani = min(max($pendaftaran->tanggal_mulai->diffInDays(now()), 0), $totalHari);
$persenProgress = round(($hariDijalani / $totalHari) * 100);
$hariSisa = max((int) now()->diffInDays($pendaftaran->tanggal_selesai, false), 0);
@endphp
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-600 small">
                <i class="bi bi-hourglass-split me-1 text-primary"></i>Progress Magang
            </span>
            <span class="text-muted small">
                @if(now()->lt($pendaftaran->tanggal_selesai))
                Sisa {{ $hariSisa }} hari
                @else
                <span class="text-success fw-600">
                    <i class="bi bi-check-circle me-1"></i>Magang selesai
                </span>
                @endif
            </span>
        </div>
        <div class="progress" style="height:10px;border-radius:8px;">
            <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated"
                style="width:{{ $persenProgress }}%;border-radius:8px;" role="progressbar"
                aria-valuenow="{{ $persenProgress }}" aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>
        <div class="d-flex justify-content-between mt-1">
            <small class="text-muted">{{ $pendaftaran->tanggal_mulai->format('d M Y') }}</small>
            <small class="fw-600 text-primary">{{ $persenProgress }}%</small>
            <small class="text-muted">{{ $pendaftaran->tanggal_selesai->format('d M Y') }}</small>
        </div>
    </div>
</div>

{{-- ============================================================
     STAT CARDS — pakai $rekap dari AbsensiController@index
     ============================================================ --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 text-center py-4">
            <div class="mb-2"><i class="bi bi-check-circle-fill fs-2 text-success"></i></div>
            <div class="fw-700 fs-2 text-success">{{ $rekap['hadir'] ?? 0 }}</div>
            <small class="text-muted">Hari Hadir</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 text-center py-4">
            <div class="mb-2"><i class="bi bi-info-circle-fill fs-2 text-info"></i></div>
            <div class="fw-700 fs-2 text-info">{{ $rekap['izin'] ?? 0 }}</div>
            <small class="text-muted">Izin</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 text-center py-4">
            <div class="mb-2"><i class="bi bi-thermometer-half fs-2 text-warning"></i></div>
            <div class="fw-700 fs-2 text-warning">{{ $rekap['sakit'] ?? 0 }}</div>
            <small class="text-muted">Sakit</small>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm h-100 text-center py-4">
            <div class="mb-2"><i class="bi bi-x-circle-fill fs-2 text-danger"></i></div>
            <div class="fw-700 fs-2 text-danger">{{ $rekap['alpha'] ?? 0 }}</div>
            <small class="text-muted">Alpha</small>
        </div>
    </div>
</div>

{{-- ============================================================
     DONUT CHART + STATUS LAPORAN AKHIR
     ============================================================ --}}
<div class="row g-3 mb-4">

    {{-- Donut --}}
    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0">
                <span class="fw-600 small">
                    <i class="bi bi-pie-chart-fill me-2 text-primary"></i>Komposisi Kehadiran
                </span>
            </div>
            <div class="card-body d-flex flex-column align-items-center justify-content-center py-3">
                <canvas id="chartDonut" style="max-width:170px;max-height:170px;"></canvas>
                <div class="d-flex flex-wrap gap-2 mt-3 justify-content-center">
                    <span class="badge bg-success px-3 py-2">Hadir: {{ $rekap['hadir'] ?? 0 }}</span>
                    <span class="badge bg-info text-dark px-3 py-2">Izin: {{ $rekap['izin'] ?? 0 }}</span>
                    <span class="badge bg-warning text-dark px-3 py-2">Sakit: {{ $rekap['sakit'] ?? 0 }}</span>
                    <span class="badge bg-danger px-3 py-2">Alpha: {{ $rekap['alpha'] ?? 0 }}</span>
                </div>
                <a href="{{ route('peserta.absensi.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                    Lihat Riwayat Lengkap
                </a>
            </div>
        </div>
    </div>

    {{-- Status Laporan Akhir --}}
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0">
                <span class="fw-600 small">
                    <i class="bi bi-file-earmark-text me-2 text-primary"></i>Laporan Akhir
                </span>
            </div>
            <div class="card-body">
                @if($laporan)
                <div class="row g-3">
                    <div class="col-12">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block mb-1">Judul Laporan</small>
                            <div class="fw-600">{{ $laporan->judul }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded text-center">
                            <small class="text-muted d-block mb-1">Status</small>
                            <span class="badge bg-{{ match($laporan->status){
                                    'disetujui'=>'success','ditolak'=>'danger',default=>'warning'
                                } }} px-3 py-2">{{ $laporan->status_label }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded text-center">
                            <small class="text-muted d-block mb-1">Sertifikat</small>
                            @php
                            $sertifikat = null;
                            try {
                            $sertifikat = $laporan->pendaftaran->sertifikat ?? null;
                            } catch (\Throwable $e) {}
                            @endphp
                            @if($sertifikat)
                            <span class="badge bg-success">
                                <i class="bi bi-award me-1"></i>Tersedia
                            </span>
                            @else
                            <span class="text-muted small fst-italic">Belum tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($laporan->catatan_admin)
                <div class="alert alert-{{ $laporan->status === 'ditolak' ? 'danger' : 'success' }} small mt-3 mb-0">
                    <i class="bi bi-chat-left-text me-1"></i>
                    <strong>Catatan Admin:</strong> {{ $laporan->catatan_admin }}
                </div>
                @endif

                <div class="mt-3 d-flex gap-2 flex-wrap">
                    <a href="{{ Storage::url($laporan->file_path) }}" target="_blank"
                        class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-download me-1"></i>Unduh Laporan
                    </a>
                    @if($sertifikat && $sertifikat->file_path)
                    <a href="{{ Storage::url($sertifikat->file_path) }}" target="_blank"
                        class="btn btn-outline-success btn-sm">
                        <i class="bi bi-award me-1"></i>Unduh Sertifikat
                    </a>
                    @endif
                    <a href="{{ route('peserta.laporan.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-eye me-1"></i>Detail
                    </a>
                </div>

                @else
                <div class="text-center py-4">
                    <i class="bi bi-file-earmark-arrow-up fs-1 text-muted opacity-50 mb-2 d-block"></i>
                    <p class="text-muted mb-3 small">Belum ada laporan akhir diunggah.</p>
                    @if($pendaftaran->status === 'diterima')
                    <a href="{{ route('peserta.laporan.create') }}" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-upload me-1"></i>Unggah Laporan
                    </a>
                    @else
                    <span class="text-muted small fst-italic">
                        Tersedia setelah pendaftaran diterima.
                    </span>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- ============================================================
     ABSENSI TERBARU + QUICK ACTION
     ============================================================ --}}
<div class="row g-3">

    {{-- 5 Absensi terbaru --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-600 small">
                    <i class="bi bi-calendar-check me-2 text-success"></i>Absensi Terbaru
                </span>
                <a href="{{ route('peserta.absensi.index') }}" class="btn btn-sm btn-outline-success">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($absensis ?? [] as $a)
                <div class="d-flex align-items-center px-3 py-2 border-bottom gap-3">
                    <span class="text-muted small fw-600" style="min-width:85px;">
                        {{ $a->tanggal->format('d M Y') }}
                    </span>
                    <span class="badge bg-{{ match($a->status){
                        'hadir'=>'success','izin'=>'info','sakit'=>'warning','alpha'=>'danger',default=>'secondary'
                    } }}">{{ $a->status_label }}</span>
                    <span class="text-muted small ms-auto">
                        {{ $a->jam_masuk ? $a->jam_masuk.' – '.($a->jam_keluar ?? '?') : '-' }}
                    </span>
                    <span class="badge bg-{{ match($a->approval){
                        'disetujui'=>'success','ditolak'=>'danger',default=>'secondary'
                    } }}">{{ $a->approval_label }}</span>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="bi bi-calendar-x fs-3 d-block mb-2 opacity-50"></i>
                    <small>Belum ada riwayat absensi.</small>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick Action --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <span class="fw-600 small">
                    <i class="bi bi-lightning-charge-fill me-2 text-warning"></i>Aksi Cepat
                </span>
            </div>
            <div class="card-body d-flex flex-column gap-2 py-3">

                @if($pendaftaran->status === 'diterima')
                @if($sudahAbsenHariIni ?? false)
                <button class="btn btn-outline-secondary w-100 text-start" disabled>
                    <i class="bi bi-check2-circle me-2 text-success"></i>Sudah Absen Hari Ini
                </button>
                @else
                <a href="{{ route('peserta.absensi.create') }}" class="btn btn-outline-success w-100 text-start">
                    <i class="bi bi-person-check me-2"></i>Absen Hari Ini
                </a>
                @endif
                @endif

                @if(!$laporan)
                <a href="{{ route('peserta.laporan.create') }}"
                    class="btn btn-outline-primary w-100 text-start {{ $pendaftaran->status !== 'diterima' ? 'disabled' : '' }}">
                    <i class="bi bi-upload me-2"></i>Unggah Laporan Akhir
                </a>
                @else
                <a href="{{ route('peserta.laporan.index') }}" class="btn btn-outline-primary w-100 text-start">
                    <i class="bi bi-file-earmark-text me-2"></i>Lihat Laporan Akhir
                </a>
                @if($laporan->status !== 'disetujui')
                <a href="{{ route('peserta.laporan.edit', $laporan) }}"
                    class="btn btn-outline-warning w-100 text-start">
                    <i class="bi bi-pencil me-2"></i>Edit Laporan
                </a>
                @endif
                @endif

                <a href="{{ route('peserta.pendaftaran.show', $pendaftaran) }}"
                    class="btn btn-outline-secondary w-100 text-start">
                    <i class="bi bi-person-lines-fill me-2"></i>Detail Pendaftaran
                </a>

                @if($pendaftaran->status !== 'diterima')
                <a href="{{ route('peserta.pendaftaran.edit', $pendaftaran) }}"
                    class="btn btn-outline-warning w-100 text-start">
                    <i class="bi bi-pencil-square me-2"></i>Edit Pendaftaran
                </a>
                @endif

            </div>
        </div>
    </div>

</div>

@endif {{-- end $pendaftaran --}}

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartDonut');
    if (!ctx) return;

    const hadir = {
        {
            $rekap['hadir'] ?? 0
        }
    };
    const izin = {
        {
            $rekap['izin'] ?? 0
        }
    };
    const sakit = {
        {
            $rekap['sakit'] ?? 0
        }
    };
    const alpha = {
        {
            $rekap['alpha'] ?? 0
        }
    };
    const total = hadir + izin + sakit + alpha;

    // Jika semua 0, tampilkan placeholder abu-abu
    const data = total > 0 ? [hadir, izin, sakit, alpha] : [1];
    const colors = total > 0 ? ['rgba(25,135,84,0.85)', 'rgba(13,202,240,0.85)', 'rgba(255,193,7,0.85)',
        'rgba(220,53,69,0.85)'
    ] : ['rgba(200,200,200,0.4)'];
    const labels = total > 0 ? ['Hadir', 'Izin', 'Sakit', 'Alpha'] : ['Belum ada data'];

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderWidth: total > 0 ? 2 : 0,
                borderColor: '#fff',
            }]
        },
        options: {
            cutout: '68%',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: total > 0,
                }
            }
        }
    });
});
</script>
@endpush