@extends('layouts.app')

@section('title', 'Bidang Magang')

@section('content')
<div class="py-4" style="background: var(--bg-soft); border-bottom: 1px solid var(--border);">
    <div class="container">
        <h1 class="fw-700 mb-1" style="color: var(--primary);">Bidang Magang</h1>
        <p class="text-muted mb-0">Pilih bidang yang sesuai dengan minat dan latar belakang pendidikanmu</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="row g-4">
            @foreach($bidang as $item)
            @php $icons = ['bi-book', 'bi-archive', 'bi-code-slash', 'bi-briefcase']; @endphp
            <div class="col-md-6">
                <div class="card h-100 p-4">
                    <div class="d-flex gap-3 align-items-start mb-3">

                        {{-- Thumbnail kecil --}}
                        @if($item->thumbnail)
                        <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->nama }}"
                            style="width:48px;height:48px;border-radius:12px;object-fit:cover;flex-shrink:0;">
                        @else
                        <div class="bidang-icon" style="width:48px;height:48px;border-radius:12px;flex-shrink:0;">
                            <i class="bi {{ $icons[($item->id - 1) % 4] }} fs-5"></i>
                        </div>
                        @endif

                        <div>
                            <h5 class="fw-700 mb-1">{{ $item->nama }}</h5>
                            <span class="badge bg-light text-primary">
                                <i class="bi bi-people me-1"></i>{{ $item->pendaftaran_count }} Pendaftar
                            </span>
                        </div>
                    </div>
                    <p class="text-muted small mb-3">{{ Str::limit($item->deskripsi, 150) }}</p>
                    <a href="{{ route('bidang.show', $item) }}"
                        class="btn btn-outline-primary btn-sm mt-auto align-self-start">
                        Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection