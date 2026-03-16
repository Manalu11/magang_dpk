@extends('layouts.admin')

@section('content')
<h4>Edit Bidang</h4>

<form action="{{ route('admin.bidang.update', $bidang) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" value="{{ $bidang->nama }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" required>{{ $bidang->deskripsi }}</textarea>
    </div>

    <div class="mb-3">
        <label>Kriteria</label>
        <textarea name="kriteria" class="form-control" required>{{ $bidang->kriteria }}</textarea>
    </div>

    {{-- Thumbnail --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">Thumbnail</label>

        {{-- Thumbnail saat ini --}}
        @if($bidang->thumbnail)
        <div class="mb-2">
            <p class="text-muted small mb-1">Thumbnail saat ini:</p>
            @if(Str::startsWith($bidang->thumbnail, 'storage/'))
            {{-- Gambar yang diupload --}}
            <img src="{{ asset($bidang->thumbnail) }}" alt="Thumbnail" class="rounded border"
                style="height:80px; width:80px; object-fit:cover;">
            @else
            {{-- Ikon SVG --}}
            @php
            $ikonList = [
            'archive' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="36"
                height="36">
                <path
                    d="M20.54 5.23l-1.39-1.68C18.88 3.21 18.47 3 18 3H6c-.47 0-.88.21-1.16.55L3.46 5.23C3.17 5.57 3 6.02 3 6.5V19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6.5c0-.48-.17-.93-.46-1.27zM12 17.5L6.5 12H10v-2h4v2h3.5L12 17.5zM5.12 5l.81-1h12l.94 1H5.12z" />
            </svg>',
            'code' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="36"
                height="36">
                <path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z" />
            </svg>',
            'library' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="36"
                height="36">
                <path
                    d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm4-8H9V5h10v2z" />
            </svg>',
            'finance' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="36"
                height="36">
                <path
                    d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z" />
            </svg>',
            'chart' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="36"
                height="36">
                <path d="M5 9.2h3V19H5V9.2zM10.6 5h2.8v14h-2.8V5zm5.6 8H19v6h-2.8v-6z" />
            </svg>',
            'people' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="36"
                height="36">
                <path
                    d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
            </svg>',
            'settings' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="36"
                height="36">
                <path
                    d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.07.63-.07.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z" />
            </svg>',
            'globe' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="36"
                height="36">
                <path
                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
            </svg>',
            ];
            @endphp
            <div class="d-flex align-items-center justify-content-center border rounded"
                style="width:64px; height:64px; background:#1e3a5f; color:#fff; border-radius:12px!important;">
                {!! $ikonList[$bidang->thumbnail] ?? '' !!}
            </div>
            @endif
        </div>
        @endif

        {{-- Tab pilihan --}}
        <ul class="nav nav-tabs mb-3" id="thumbnailTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-ikon" type="button">
                    🎨 Pilih Ikon
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-upload" type="button">
                    📁 Upload Gambar
                </button>
            </li>
        </ul>

        <div class="tab-content border rounded p-3 bg-light">

            {{-- Tab Ikon --}}
            <div class="tab-pane fade show active" id="tab-ikon">
                <p class="text-muted small mb-2">Pilih ikon untuk bidang ini:</p>
                <div class="d-flex flex-wrap gap-2">
                    @php
                    $ikonList = [
                    'archive' => ['label' => 'Kearsipan', 'svg' => '<svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="currentColor" width="28" height="28">
                        <path
                            d="M20.54 5.23l-1.39-1.68C18.88 3.21 18.47 3 18 3H6c-.47 0-.88.21-1.16.55L3.46 5.23C3.17 5.57 3 6.02 3 6.5V19c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6.5c0-.48-.17-.93-.46-1.27zM12 17.5L6.5 12H10v-2h4v2h3.5L12 17.5zM5.12 5l.81-1h12l.94 1H5.12z" />
                    </svg>'],
                    'code' => ['label' => 'Kominfo', 'svg' => '<svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="currentColor" width="28" height="28">
                        <path
                            d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z" />
                    </svg>'],
                    'library' => ['label' => 'Perpustakaan', 'svg' => '<svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="currentColor" width="28" height="28">
                        <path
                            d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm4-8H9V5h10v2z" />
                    </svg>'],
                    'finance' => ['label' => 'Keuangan', 'svg' => '<svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="currentColor" width="28" height="28">
                        <path
                            d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z" />
                    </svg>'],
                    'chart' => ['label' => 'Statistik', 'svg' => '<svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="currentColor" width="28" height="28">
                        <path d="M5 9.2h3V19H5V9.2zM10.6 5h2.8v14h-2.8V5zm5.6 8H19v6h-2.8v-6z" />
                    </svg>'],
                    'people' => ['label' => 'SDM', 'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="currentColor" width="28" height="28">
                        <path
                            d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>'],
                    'settings' => ['label' => 'IT', 'svg' => '<svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="currentColor" width="28" height="28">
                        <path
                            d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.07.63-.07.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z" />
                    </svg>'],
                    'globe' => ['label' => 'Humas', 'svg' => '<svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24" fill="currentColor" width="28" height="28">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
                    </svg>'],
                    ];
                    @endphp

                    @foreach($ikonList as $key => $ikon)
                    <div class="ikon-option text-center" style="cursor:pointer; width:70px;"
                        onclick="pilihIkon('{{ $key }}', this)">
                        <div id="ikon-box-{{ $key }}"
                            class="ikon-box border rounded p-2 d-flex flex-column align-items-center"
                            style="transition: all .15s; background: {{ $bidang->thumbnail === $key ? '#e8f0fb' : '#f8f9fa' }}; border: {{ $bidang->thumbnail === $key ? '2px solid #1e3a5f' : '1px solid #dee2e6' }};">
                            <div style="color:#1e3a5f;">{!! $ikon['svg'] !!}</div>
                            <small class="text-muted mt-1" style="font-size:10px;">{{ $ikon['label'] }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="ikon" id="selectedIkon"
                    value="{{ !Str::startsWith($bidang->thumbnail ?? '', 'storage/') ? $bidang->thumbnail : '' }}">
            </div>

            {{-- Tab Upload --}}
            <div class="tab-pane fade" id="tab-upload">
                <p class="text-muted small mb-2">Upload gambar thumbnail baru (jpg, png, webp — maks 2MB):</p>
                <input type="file" name="thumbnail" id="inputThumbnail" class="form-control mb-2"
                    accept="image/jpeg,image/png,image/webp" onchange="previewThumbnail(event)">
                <div id="previewWrapper" class="d-none">
                    <img id="previewImg" src="" alt="Preview" class="rounded border"
                        style="max-height:140px; max-width:100%; object-fit:cover;">
                </div>
            </div>

        </div>
    </div>
    {{-- End Thumbnail --}}

    <button class="btn btn-primary">Update</button>
</form>

@push('scripts')
<script>
function pilihIkon(key, el) {
    document.querySelectorAll('.ikon-box').forEach(b => {
        b.style.background = '#f8f9fa';
        b.style.borderColor = '';
        b.style.borderWidth = '1px';
    });
    const box = document.getElementById('ikon-box-' + key);
    box.style.background = '#e8f0fb';
    box.style.borderColor = '#1e3a5f';
    box.style.borderWidth = '2px';
    box.style.borderStyle = 'solid';
    document.getElementById('selectedIkon').value = key;
    document.getElementById('inputThumbnail').value = '';
    document.getElementById('previewWrapper').classList.add('d-none');
}

function previewThumbnail(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewWrapper').classList.remove('d-none');
    };
    reader.readAsDataURL(file);
    document.querySelectorAll('.ikon-box').forEach(b => {
        b.style.background = '#f8f9fa';
        b.style.borderColor = '';
        b.style.borderWidth = '1px';
    });
    document.getElementById('selectedIkon').value = '';
}
</script>
@endpush
@endsection