<?php

namespace App\Http\Controllers\Peserta;

use App\Models\Absensi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    // Null-safe — dipakai di index() & create()
    private function getPendaftaran()
    {
        return Auth::user()
            ->pendaftaran()
            ->where('status', 'diterima')
            ->first();
    }

    // Throw 404 jika tidak ada — dipakai di store(), update(), destroy()
    private function getPendaftaranOrFail()
    {
        return Auth::user()
            ->pendaftaran()
            ->where('status', 'diterima')
            ->firstOrFail();
    }

    // ─── Daftar riwayat absensi ───
    public function index()
    {
        $pendaftaran = $this->getPendaftaran();

        // Belum daftar atau belum diterima — tampilkan halaman kosong ramah
        if (!$pendaftaran) {
            return view('peserta.absensi.index', [
                'pendaftaran'       => null,
                'absensis'          => collect(),
                'sudahAbsenHariIni' => false,
                'rekap'             => ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0],
            ]);
        }

        $absensis = Absensi::where('pendaftaran_id', $pendaftaran->id)
            ->orderByDesc('tanggal')
            ->paginate(20);

        $sudahAbsenHariIni = Absensi::where('pendaftaran_id', $pendaftaran->id)
            ->whereDate('tanggal', today())
            ->exists();

        $rekap = [
            'hadir' => Absensi::where('pendaftaran_id', $pendaftaran->id)->where('status', 'hadir')->count(),
            'izin'  => Absensi::where('pendaftaran_id', $pendaftaran->id)->where('status', 'izin')->count(),
            'sakit' => Absensi::where('pendaftaran_id', $pendaftaran->id)->where('status', 'sakit')->count(),
            'alpha' => Absensi::where('pendaftaran_id', $pendaftaran->id)->where('status', 'alpha')->count(),
        ];

        return view('peserta.absensi.index', compact('absensis', 'sudahAbsenHariIni', 'rekap', 'pendaftaran'));
    }

    // ─── Form absen hari ini ───
    public function create()
    {
        $pendaftaran = $this->getPendaftaran();

        if (!$pendaftaran) {
            return redirect()->route('peserta.absensi.index')
                ->with('info', 'Kamu belum memiliki pendaftaran magang yang aktif.');
        }

        if (Absensi::where('pendaftaran_id', $pendaftaran->id)->whereDate('tanggal', today())->exists()) {
            return redirect()->route('peserta.absensi.index')
                ->with('info', 'Anda sudah melakukan absen hari ini.');
        }

        return view('peserta.absensi.create', compact('pendaftaran'));
    }

    // ─── Simpan absensi hari ini ───
    public function store(Request $request)
    {
        $pendaftaran = $this->getPendaftaranOrFail();

        if (Absensi::where('pendaftaran_id', $pendaftaran->id)->whereDate('tanggal', today())->exists()) {
            return redirect()->route('peserta.absensi.index')
                ->with('info', 'Anda sudah melakukan absen hari ini.');
        }

        $request->validate([
            'status'     => 'required|in:hadir,izin,sakit,alpha',
            'jam_masuk'  => 'required_if:status,hadir|nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i|after:jam_masuk',
            'keterangan' => 'nullable|string|max:500',
        ]);

        Absensi::create([
            'pendaftaran_id' => $pendaftaran->id,
            'tanggal'        => today(),
            'status'         => $request->status,
            'jam_masuk'      => $request->jam_masuk,
            'jam_keluar'     => $request->jam_keluar,
            'keterangan'     => $request->keterangan,
            'approval'       => 'pending',
        ]);

        return redirect()->route('peserta.absensi.index')
            ->with('success', 'Absensi berhasil dicatat.');
    }

    // ─── Edit absensi (hanya jika approval masih pending) ───
    public function update(Request $request, $id)
    {
        $pendaftaran = $this->getPendaftaranOrFail();

        $absen = Absensi::where('id', $id)
            ->where('pendaftaran_id', $pendaftaran->id)
            ->firstOrFail();

        if (!$absen->isPending()) {
            return back()->with('error', 'Absensi yang sudah direview tidak dapat diubah.');
        }

        $request->validate([
            'status'     => 'required|in:hadir,izin,sakit,alpha',
            'jam_masuk'  => 'required_if:status,hadir|nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i|after:jam_masuk',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $absen->update([
            'status'     => $request->status,
            'jam_masuk'  => $request->status === 'hadir' ? $request->jam_masuk : null,
            'jam_keluar' => $request->status === 'hadir' ? $request->jam_keluar : null,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Absensi berhasil diperbarui.');
    }

    // ─── Hapus absensi (hanya jika approval masih pending) ───
    public function destroy($id)
    {
        $pendaftaran = $this->getPendaftaranOrFail();

        $absen = Absensi::where('id', $id)
            ->where('pendaftaran_id', $pendaftaran->id)
            ->firstOrFail();

        if (!$absen->isPending()) {
            return back()->with('error', 'Absensi yang sudah direview tidak dapat dihapus.');
        }

        $absen->delete();

        return back()->with('success', 'Absensi berhasil dihapus.');
    }
}