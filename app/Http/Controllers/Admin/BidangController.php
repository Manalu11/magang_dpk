<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BidangController extends Controller
{
    public function index()
    {
        $bidang = Bidang::latest()->get();
        return view('admin.bidang.index', compact('bidang'));
    }

    public function create()
    {
        return view('admin.bidang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'required',
            'kriteria'  => 'required',
            'thumbnail' => 'nullable|image|max:2048',
            'ikon'      => 'nullable|string',
        ]);

        $data = $request->only('nama', 'deskripsi', 'kriteria');

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $data['thumbnail'] = 'storage/' . $path;
        } elseif ($request->filled('ikon')) {
            $data['thumbnail'] = $request->ikon;
        }

        Bidang::create($data);

        return redirect()->route('admin.bidang.index')
            ->with('success', 'Bidang berhasil ditambahkan');
    }

    public function edit(Bidang $bidang)
    {
        return view('admin.bidang.edit', compact('bidang'));
    }

    public function update(Request $request, Bidang $bidang)
    {
        $request->validate([
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'required',
            'kriteria'  => 'required',
            'thumbnail' => 'nullable|image|max:2048',
            'ikon'      => 'nullable|string',
        ]);

        $data = $request->only('nama', 'deskripsi', 'kriteria');

        if ($request->hasFile('thumbnail')) {
            // Hapus gambar lama jika ada
            if ($bidang->thumbnail && Str::startsWith($bidang->thumbnail, 'storage/')) {
                Storage::disk('public')->delete(Str::after($bidang->thumbnail, 'storage/'));
            }
            $path = $request->file('thumbnail')->store('thumbnails', 'public');
            $data['thumbnail'] = 'storage/' . $path;
        } elseif ($request->filled('ikon')) {
            $data['thumbnail'] = $request->ikon;
        }
        // Jika tidak ada input thumbnail/ikon baru → thumbnail tidak berubah

        $bidang->update($data);

        return redirect()->route('admin.bidang.index')
            ->with('success', 'Bidang berhasil diperbarui');
    }

    public function destroy(Bidang $bidang)
    {
        // Cek apakah bidang masih digunakan di pendaftaran
        if ($bidang->pendaftaran()->exists()) {
            return redirect()->route('admin.bidang.index')
                ->with('error', 'Bidang tidak dapat dihapus karena masih memiliki data pendaftaran.');
        }

        // Hapus gambar dari storage jika ada
        if ($bidang->thumbnail && Str::startsWith($bidang->thumbnail, 'storage/')) {
            Storage::disk('public')->delete(Str::after($bidang->thumbnail, 'storage/'));
        }

        $bidang->delete();

        return redirect()->route('admin.bidang.index')
            ->with('success', 'Bidang berhasil dihapus');
    }
}