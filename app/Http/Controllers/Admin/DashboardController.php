<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PendaftaranService;
use Illuminate\View\View;
use App\Models\Absensi;

class DashboardController extends Controller
{
    public function __construct(
        private readonly PendaftaranService $pendaftaranService
    ) {}

    public function index(): View
    {
        // Statistik pendaftar
        $statistik = $this->pendaftaranService->getStatistik();

        // Ambil absensi terbaru
        $absensis = Absensi::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('statistik', 'absensis'));
    }
    
}