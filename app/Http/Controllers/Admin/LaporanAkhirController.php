<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanAkhir;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanAkhirController extends Controller
{
    public function index(Request $request)
    {
        $query = LaporanAkhir::with(['pendaftaran.user', 'pendaftaran.bidang'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhereHas(
                        'pendaftaran.user',
                        fn($u) => $u->where('name', 'like', "%{$search}%")
                    );
            });
        }

        $laporans = $query->paginate(15);

        $stats = [
            'total'     => LaporanAkhir::count(),
            'pending'   => LaporanAkhir::where('status', 'pending')->count(),
            'disetujui' => LaporanAkhir::where('status', 'disetujui')->count(),
            'ditolak'   => LaporanAkhir::where('status', 'ditolak')->count(),
        ];

        return view('admin.laporan-akhir.index', compact('laporans', 'stats'));
    }

    public function show(LaporanAkhir $laporanAkhir)
    {
        $laporanAkhir->load(['pendaftaran.user', 'pendaftaran.bidang', 'pendaftaran.sertifikat']);
        return view('admin.laporan-akhir.show', ['laporan' => $laporanAkhir]);
    }

    public function update(Request $request, LaporanAkhir $laporanAkhir)
    {
        $request->validate([
            'status'        => ['required', 'in:pending,disetujui,ditolak'],
            'catatan_admin' => ['nullable', 'string', 'max:1000'],
        ]);

        $laporanAkhir->update([
            'status'        => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        // Upload sertifikat jika disetujui dan ada file
        if ($request->status === 'disetujui' && $request->hasFile('file_sertifikat')) {
            $request->validate([
                'file_sertifikat' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
                'nilai'           => ['nullable', 'string', 'max:10'],
            ]);

            // Hapus file lama jika ada
            $sertifikatLama = $laporanAkhir->pendaftaran->sertifikat;
            if ($sertifikatLama) {
                Storage::disk('public')->delete($sertifikatLama->file_path);
            }

            $path = $request->file('file_sertifikat')->store('sertifikat', 'public');

            $laporanAkhir->pendaftaran->sertifikat()->updateOrCreate(
                ['pendaftaran_id' => $laporanAkhir->pendaftaran_id],
                [
                    'file_path' => $path,
                    'nilai'     => $request->nilai,
                    'catatan'   => $request->catatan_admin,
                ]
            );
        }

        $statusLabel = match ($request->status) {
            'disetujui' => 'disetujui',
            'ditolak'   => 'ditolak',
            default     => 'dikembalikan ke pending',
        };

        return redirect()
            ->route('admin.laporan.index')
            ->with('success', "Laporan akhir berhasil {$statusLabel}.");
    }
}