<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\LaporanAkhir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    /**
     * Ambil pendaftaran aktif milik user yang login.
     * Return null jika belum ada (tidak throw 404).
     */
    private function getPendaftaran()
    {
        return Auth::user()
            ->pendaftaran()
            ->whereIn('status', ['diterima', 'selesai'])
            ->with(['sertifikat'])
            ->first(); // ← was firstOrFail(), sekarang first() agar tidak 404
    }

    /**
     * Sama seperti getPendaftaran() tapi throw 404 jika tidak ada.
     * Dipakai di action yang memang butuh pendaftaran aktif.
     */
    private function getPendaftaranOrFail()
    {
        return Auth::user()
            ->pendaftaran()
            ->whereIn('status', ['diterima', 'selesai'])
            ->with(['sertifikat'])
            ->firstOrFail();
    }

    public function index()
    {
        $pendaftaran = $this->getPendaftaran(); // null-safe, tidak 404

        $laporan = null;
        if ($pendaftaran) {
            $laporan = LaporanAkhir::where('pendaftaran_id', $pendaftaran->id)
                ->latest()
                ->first();
        }

        return view('peserta.laporan-akhir.index', compact('laporan', 'pendaftaran'));
    }

    public function create()
    {
        $pendaftaran = $this->getPendaftaran(); // null-safe

        // Belum daftar atau belum diterima
        if (!$pendaftaran) {
            return redirect()->route('peserta.laporan.index')
                ->with('info', 'Kamu belum memiliki pendaftaran magang yang aktif. Daftar terlebih dahulu.');
        }

        // Hanya boleh upload 1 laporan
        if (LaporanAkhir::where('pendaftaran_id', $pendaftaran->id)->exists()) {
            return redirect()->route('peserta.laporan.index')
                ->with('info', 'Laporan akhir sudah pernah diunggah.');
        }

        return view('peserta.laporan-akhir.create', compact('pendaftaran'));
    }

    public function store(Request $request)
    {
        $pendaftaran = $this->getPendaftaran();

        if (!$pendaftaran) {
            return redirect()->route('peserta.laporan.index')
                ->with('info', 'Kamu belum memiliki pendaftaran magang yang aktif.');
        }

        if (LaporanAkhir::where('pendaftaran_id', $pendaftaran->id)->exists()) {
            return redirect()->route('peserta.laporan.index')
                ->with('info', 'Laporan akhir sudah pernah diunggah.');
        }

        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'file'      => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $path = $request->file('file')->store('laporan_akhir', 'public');

        LaporanAkhir::create([
            'pendaftaran_id' => $pendaftaran->id,
            'judul'          => $request->judul,
            'deskripsi'      => $request->deskripsi,
            'file_path'      => $path,
            'status'         => 'pending',
        ]);

        return redirect()->route('peserta.laporan.index')
            ->with('success', 'Laporan akhir berhasil diunggah.');
    }

    public function edit($id)
    {
        $pendaftaran = $this->getPendaftaranOrFail();

        $laporan = LaporanAkhir::where('id', $id)
            ->where('pendaftaran_id', $pendaftaran->id)
            ->firstOrFail();

        // Kunci jika sudah direview admin
        if ($laporan->status !== 'pending') {
            return redirect()->route('peserta.laporan.index')
                ->with('error', 'Laporan yang sudah direview tidak dapat diubah.');
        }

        return view('peserta.laporan-akhir.edit', compact('laporan', 'pendaftaran'));
    }

    public function update(Request $request, $id)
    {
        $pendaftaran = $this->getPendaftaranOrFail();

        $laporan = LaporanAkhir::where('id', $id)
            ->where('pendaftaran_id', $pendaftaran->id)
            ->firstOrFail();

        if ($laporan->status !== 'pending') {
            return redirect()->route('peserta.laporan.index')
                ->with('error', 'Laporan yang sudah direview tidak dapat diubah.');
        }

        $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'file'      => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($laporan->file_path);
            $laporan->file_path = $request->file('file')->store('laporan_akhir', 'public');
        }

        $laporan->judul     = $request->judul;
        $laporan->deskripsi = $request->deskripsi;
        $laporan->save();

        return redirect()->route('peserta.laporan.index')
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pendaftaran = $this->getPendaftaranOrFail();

        $laporan = LaporanAkhir::where('id', $id)
            ->where('pendaftaran_id', $pendaftaran->id)
            ->firstOrFail();

        if ($laporan->status !== 'pending') {
            return redirect()->route('peserta.laporan.index')
                ->with('error', 'Laporan yang sudah direview tidak dapat dihapus.');
        }

        Storage::disk('public')->delete($laporan->file_path);
        $laporan->delete();

        return redirect()->route('peserta.laporan.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}