<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusPendaftaranRequest;
use App\Models\Pendaftaran;
use App\Services\PendaftaranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PendaftaranController extends Controller
{
    public function __construct(
        private readonly PendaftaranService $pendaftaranService
    ) {}

    public function index(Request $request): View
    {
        $query = Pendaftaran::with(['user', 'bidang'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"))
                    ->orWhere('asal_institusi', 'like', "%{$search}%")
                    ->orWhere('nim_nis', 'like', "%{$search}%");
            });
        }

        $pendaftaran = $query->paginate(15)->withQueryString();

        return view('admin.pendaftaran.index', compact('pendaftaran'));
    }

    public function show(Pendaftaran $pendaftaran): View
    {
        $pendaftaran->load(['user', 'bidang']);
        return view('admin.pendaftaran.show', compact('pendaftaran'));
    }

    public function updateStatus(UpdateStatusPendaftaranRequest $request, Pendaftaran $pendaftaran): RedirectResponse
    {
        $this->pendaftaranService->updateStatus(
            $pendaftaran,
            $request->validated('status'),
            $request->validated('catatan_admin')
        );

        $label = match ($request->status) {
            'diterima' => 'diterima',
            'ditolak'  => 'ditolak',
            default    => 'diperbarui',
        };

        return redirect()->route('admin.pendaftaran.show', $pendaftaran)
            ->with('success', "Status pendaftaran berhasil {$label}.");
    }

    public function downloadFile(Pendaftaran $pendaftaran, string $type): Response|RedirectResponse
    {
        $allowedTypes = ['cv', 'surat_pengantar'];

        if (!in_array($type, $allowedTypes)) {
            abort(404);
        }

        $filePath = $pendaftaran->$type;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        $filename = $pendaftaran->user->name . '_' . $type . '.pdf';
        return Storage::disk('public')->download($filePath, $filename);
    }
}