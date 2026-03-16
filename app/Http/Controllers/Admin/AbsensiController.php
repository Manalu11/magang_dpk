<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $pendaftarans = Pendaftaran::with('user', 'bidang')->get();

        $absensis = Absensi::with('pendaftaran.user', 'pendaftaran.bidang')
            ->when($request->pendaftaran_id, fn($q) => $q->where('pendaftaran_id', $request->pendaftaran_id))
            ->when($request->tanggal, fn($q) => $q->where('tanggal', $request->tanggal))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->approval, fn($q) => $q->where('approval', $request->approval))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.absensi.index', compact('absensis', 'pendaftarans'));
    }

    // Approve satu absensi
    public function approve(Request $request, Absensi $absensi)
    {
        $request->validate([
            'approval' => 'required|in:disetujui,ditolak',
        ]);

        $absensi->update(['approval' => $request->approval]);

        return back()->with('success', 'Status absensi berhasil diperbarui.');
    }

    // Bulk approve banyak absensi sekaligus
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'ids'      => 'required|array',
            'ids.*'    => 'exists:absensis,id',
            'approval' => 'required|in:disetujui,ditolak',
        ]);

        Absensi::whereIn('id', $request->ids)
            ->update(['approval' => $request->approval]);

        return back()->with('success', 'Absensi terpilih berhasil diperbarui.');
    }
}