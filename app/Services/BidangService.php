<?php

namespace App\Services;

use App\Models\Bidang;
use Illuminate\Database\Eloquent\Collection;

class BidangService
{
    public function getAll(): Collection
    {
        return Bidang::withCount('pendaftaran')->orderBy('nama')->get();
    }

    public function findById(int $id): Bidang
    {
        return Bidang::withCount('pendaftaran')->findOrFail($id);
    }
}
