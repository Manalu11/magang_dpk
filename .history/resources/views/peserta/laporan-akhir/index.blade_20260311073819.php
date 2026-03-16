@extends('layouts.peserta')
@section('title', 'Laporan Akhir')
@section('page_title', 'Laporan Akhir Magang')

@section('content')

@if($laporan)
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-file-earmark-text me-2 text-primary"></i>Laporan Akhir</span>
        <span class="badge bg-{{ match($laporan->status){
            'disetujui'=>'success','ditolak'=>'danger',default=>'warning'
        } }}">{{ $laporan->status_label }}</span>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-8">
                <div class="p-3 bg-light rounded">
                    <small class="text-muted d-block mb-1">Judul Laporan</small>
                    <div class="fw-600">{{ $laporan->judul }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded">
                    <small class="text-muted d-block mb-1">Diunggah</small>
                    <div class="fw-600">{{ $laporan->created_at->format('d M Y') }}</div>
                </div>
            </div>
            @if($laporan->deskripsi)
            <div class="col-12">
                <div class="p-3 bg-light rounded">
                    <small class="text-muted d-block mb-1">Deskripsi</small>
                    <div class="small">{{ $laporan->deskripsi }}</div>
                </div>
            </div>
            @endif
            @if($laporan->catatan_admin)
            <div class="col-12">
                <div class="p-3 rounded border border-{{ $laporan->status === 'ditolak' ? 'danger' : 'success' }}">
                    <small class="text-muted d-block mb-1">Catatan Admin</small>
                    <div class="small">{{ $laporan->catatan_admin }}</div>
                </div>
            </div>
            @endif
        </div>

        <a href="{{ Storage::url($laporan->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-download me-1"></i>Unduh Laporan
        </a>
        @if($laporan->status === 'pending')
        <a href="{{ route('peserta.laporan.edit', $laporan->id) }}" class="btn btn-outline-warning btn-sm">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>

        <form action="{{ route('peserta.laporan.destroy', $laporan->id) }}" method="POST" class="d-inline"
            onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-trash me-1"></i>Hapus
                button
        </form>
        @endif
    </div>
</div>

{{-- Sertifikat jika sudah ada --}}
@if($laporan->pendaftaran->sertifikat)
<div class="card mt-4">
    <div class="card-header">
        <i class="bi bi-award me-2 text-warning"></i>Sertifikat Magang
    </div>
    <div class="card-body">
        @php $sert = $laporan->pendaftaran->sertifikat; @endphp
        <div class="row g-3 mb-3">
            @if($sert->nilai)
            <div class="col-md-4">
                <div class="p-3 bg-light rounded text-center">
                    <small class="text-muted d-block mb-1">Nilai Akhir</small>
                    <div class="fw-700 fs-4" style="color:#0F3D73;">{{ $sert->nilai }}</div>
                </div>
            </div>
            @endif
            @if($sert->catatan)
            <div class="col-md-8">
                <div class="p-3 bg-light rounded">
                    <small class="text-muted d-block mb-1">Catatan</small>
                    <div class="small">{{ $sert->catatan }}</div>
                </div>
            </div>
            @endif
        </div>
        <a href="{{ Storage::url($sert->file_path) }}" target="_blank" class="btn btn-success btn-sm">
            <i class="bi bi-download me-1"></i>Unduh Sertifikat
        </a>
    </div>
</div>
@endif

@else
<div class="card text-center p-5">
    <i class="bi bi-file-earmark-arrow-up text-primary" style="font-size:3rem;"></i>
    <h5 class="fw-700 mt-3">Belum Ada Laporan Akhir</h5>
    <p class="text-muted mb-4">Unggah laporan akhir magang Anda sebelum periode berakhir.</p>
    <div>
        <a href="{{ route('peserta.laporan.create') }}" class="btn btn-primary px-4">
            <i class="bi bi-upload me-2"></i>Unggah Laporan
        </a>
    </div>
</div>
@endif

@endsection