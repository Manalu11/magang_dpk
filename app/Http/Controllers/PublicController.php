<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Services\BidangService;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function __construct(
        private readonly BidangService $bidangService
    ) {}

    public function index(): View
{
    try {
        $bidang = $this->bidangService->getAll() ?? collect([]);
    } catch (\Throwable $e) {
        dd($e->getMessage(), $e->getFile(), $e->getLine());
    }
    return view('public.landing', compact('bidang'));
}

    public function bidang(): View
    {
        $bidang = $this->bidangService->getAll() ?? collect([]);
        return view('public.bidang', compact('bidang'));
    }

    public function bidangDetail(Bidang $bidang): View
    {
        return view('public.bidang-detail', compact('bidang'));
    }
}