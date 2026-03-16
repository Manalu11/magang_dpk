<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePendaftaranRequest;
use App\Http\Requests\UpdatePendaftaranRequest;
use App\Models\Pendaftaran;
use App\Services\BidangService;
use App\Services\PendaftaranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PendaftaranController extends Controller
{
    public function __construct(
        private readonly PendaftaranService $pendaftaranService,
        private readonly BidangService $bidangService
    ) {}

    public function dashboard(): View
    {
        $user        = auth()->user();
        $pendaftaran = Pendaftaran::with('bidang')
            ->where('user_id', $user->id)
            ->first();

        $rekap             = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0];
        $absensis          = collect();
        $sudahAbsenHariIni = false;
        $laporan           = null;

        if ($pendaftaran) {
            $rekap = [
                'hadir' => $pendaftaran->absensis()->where('status', 'hadir')->count(),
                'izin'  => $pendaftaran->absensis()->where('status', 'izin')->count(),
                'sakit' => $pendaftaran->absensis()->where('status', 'sakit')->count(),
                'alpha' => $pendaftaran->absensis()->where('status', 'alpha')->count(),
            ];

            $absensis = $pendaftaran->absensis()
                ->latest('tanggal')
                ->take(5)
                ->get();

            $sudahAbsenHariIni = $pendaftaran->absensis()
                ->whereDate('tanggal', today())
                ->exists();

            // Ambil laporan langsung dari tabel laporan_akhirs
            $laporan = \App\Models\LaporanAkhir::where('pendaftaran_id', $pendaftaran->id)
                ->first();
        }

        return view('peserta.dashboard', compact(
            'pendaftaran',
            'rekap',
            'absensis',
            'sudahAbsenHariIni',
            'laporan',
        ));
    }

    public function create(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($this->pendaftaranService->sudahMendaftar($user)) {
            return redirect()->route('peserta.dashboard')
                ->with('warning', 'Anda sudah memiliki pendaftaran aktif. Tidak dapat mendaftar lebih dari satu kali.');
        }

        $bidang = $this->bidangService->getAll();
        return view('peserta.pendaftaran.create', compact('bidang'));
    }

    public function store(StorePendaftaranRequest $request): RedirectResponse
    {
       
        Log::info('Store dipanggil', $request->validated());

        $user = auth()->user();

        if ($this->pendaftaranService->sudahMendaftar($user)) {
            return redirect()->route('peserta.dashboard')
                ->with('warning', 'Anda sudah memiliki pendaftaran aktif.');
        }

        try {
            $this->pendaftaranService->store(
                $user,
                $request->validated(),
                $request->file('cv'),
                $request->file('surat_pengantar')
            );
        } catch (\Exception $e) {
            Log::error('Error store pendaftaran: ' . $e->getMessage());
            dd($e->getMessage()); // error akan muncul di browser
        }

        return redirect()->route('peserta.dashboard')
            with('success', 'Pendaftaran magang berhasil dikirim! Silakan tunggu konfirmasi dari admin.');
    }

    public function show(): View|RedirectResponse
    {
        $pendaftaran = Pendaftaran::with(['bidang', 'user'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$pendaftaran) {
            return redirect()->route('peserta.pendaftaran.create')
                ->with('info', 'Anda belum memiliki pendaftaran. Silakan mendaftar terlebih dahulu.');
        }

        return view('peserta.pendaftaran.show', compact('pendaftaran'));
    }

    public function edit(Pendaftaran $pendaftaran): View|RedirectResponse
    {
        if ($pendaftaran->user_id !== auth()->id()) {
            abort(403);
        }

        if ($pendaftaran->status === 'diterima') {
            return redirect()
                ->route('peserta.pendaftaran.show')
                ->with('error', 'Pendaftaran sudah di ACC dan tidak bisa diubah.');
        }

        $bidang = $this->bidangService->getAll();
        return view('peserta.pendaftaran.edit', compact('pendaftaran', 'bidang'));
    }

    public function update(UpdatePendaftaranRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        if ($pendaftaran->user_id !== auth()->id()) {
            abort(403);
        }

        if ($pendaftaran->status === 'diterima') {
            return redirect()
                ->route('peserta.pendaftaran.show')
                ->with('error', 'Pendaftaran sudah di ACC dan tidak bisa diubah.');
        }

        $pendaftaran->update($request->validated());

        return redirect()
            ->route('peserta.pendaftaran.show')
            ->with('success', 'Pendaftaran berhasil diperbarui.');
    }

    public function destroy(Pendaftaran $pendaftaran): RedirectResponse
    {
        if ($pendaftaran->user_id !== auth()->id()) {
            abort(403);
        }

        if ($pendaftaran->status === 'diterima') {
            return back()->with('error', 'Pendaftaran sudah di ACC dan tidak bisa dihapus.');
        }

        $pendaftaran->delete();

        return redirect()
            ->route('peserta.dashboard')
            ->with('success', 'Pendaftaran berhasil dihapus.');
    }
}