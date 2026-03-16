{{-- resources/views/peserta/laporan/edit.blade.php --}}
@extends('layouts.peserta')

@section('title', 'Edit Laporan')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Laporan</h2>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('peserta.laporan.update', $laporan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="judul" class="form-label">Judul Laporan</label>
            <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul', $laporan->judul) }}">
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" id="keterangan"
                class="form-control">{{ old('keterangan', $laporan->keterangan) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">Ganti File (opsional)</label>
            <input type="file" name="file" id="file" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('peserta.laporan.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection