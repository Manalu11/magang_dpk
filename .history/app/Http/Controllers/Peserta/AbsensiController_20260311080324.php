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
    public function index(Request $request)
    {
        $pendaftaran = $this->getPendaftaran();

        if (!$pendaftaran) {
            return view('peserta.absensi.index', [
                'pendaftaran'       => null,
                'absensis'          => collect(),
                'sudahAbsenHariIni' => false,
                'rekap'             => ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0],
                'filterBulan'       => now()->format('Y-m'),
            ]);
        }

        // Filter bulan — default bulan ini
        $filterBulan = $request->input('bulan', now()->format('Y-m'));
        [$tahun, $bulan] = explode('-', $filterBulan);

        $query = Absensi::where('pendaftaran_id', $pendaftaran->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan);

        // Filter status opsional
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $absensis = $query->orderByDesc('tanggal')->paginate(20)->withQueryString();

        $sudahAbsenHariIni = Absensi::where('pendaftaran_id', $pendaftaran->id)
            ->whereDate('tanggal', today())
            ->exists();

        // Rekap untuk bulan yang sedang difilter
        $rekapQuery = Absensi::where('pendaftaran_id', $pendaftaran->id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan);

        $rekap = [
            'hadir' => (clone $rekapQuery)->where('status', 'hadir')->count(),
            'izin'  => (clone $rekapQuery)->where('status', 'izin')->count(),
            'sakit' => (clone $rekapQuery)->where('status', 'sakit')->count(),
            'alpha' => (clone $rekapQuery)->where('status', 'alpha')->count(),
        ];

        return view('peserta.absensi.index', compact(
            'absensis', 'sudahAbsenHariIni', 'rekap', 'pendaftaran', 'filterBulan'
        ));
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
        ], [
            'status.required'         => 'Status kehadiran wajib dipilih.',
            'jam_masuk.required_if'   => 'Jam masuk wajib diisi jika status Hadir.',
            'jam_masuk.date_format'   => 'Format jam masuk tidak valid.',
            'jam_keluar.date_format'  => 'Format jam keluar tidak valid.',
            'jam_keluar.after'        => 'Jam keluar harus setelah jam masuk.',
            'keterangan.max'          => 'Keterangan maksimal 500 karakter.',
        ]);

        Absensi::create([
            'pendaftaran_id' => $pendaftaran->id,
            'tanggal'        => today(),
            'status'         => $request->status,
            'jam_masuk'      => $request->status === 'hadir' ? $request->jam_masuk : null,
            'jam_keluar'     => $request->status === 'hadir' ? $request->jam_keluar : null,
            'keterangan'     => $request->keterangan,
            'approval'       => 'pending',
        ]);

        return redirect()->route('peserta.absensi.index')
            ->with('success', 'Absensi berhasil dicatat.');
    }

    // ─── Form edit absensi ───
    public function edit($id)
    {
        $pendaftaran = $this->getPendaftaranOrFail();

        $absen = Absensi::where('id', $id)
            ->where('pendaftaran_id', $pendaftaran->id)
            ->firstOrFail();

        if (!$absen->isPending()) {
            return redirect()->route('peserta.absensi.index')
                ->with('error', 'Absensi yang sudah direview tidak dapat diubah.');
        }

        return view('peserta.absensi.edit', compact('absen', 'pendaftaran'));
    }

    // ─── Update absensi ───
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
        ], [
            'status.required'         => 'Status kehadiran wajib dipilih.',
            'jam_masuk.required_if'   => 'Jam masuk wajib diisi jika status Hadir.',
            'jam_masuk.date_format'   => 'Format jam masuk tidak valid.',
            'jam_keluar.date_format'  => 'Format jam keluar tidak valid.',
            'jam_keluar.after'        => 'Jam keluar harus setelah jam masuk.',
            'keterangan.max'          => 'Keterangan maksimal 500 karakter.',
        ]);

        $absen->update([
            'status'     => $request->status,
            'jam_masuk'  => $request->status === 'hadir' ? $request->jam_masuk : null,
            'jam_keluar' => $request->status === 'hadir' ? $request->jam_keluar : null,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('peserta.absensi.index')
            ->with('success', 'Absensi berhasil diperbarui.');
    }

    // ─── Hapus absensi ───
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