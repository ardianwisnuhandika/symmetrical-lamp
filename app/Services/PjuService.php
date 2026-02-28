<?php

namespace App\Services;

use App\Models\PjuPoint;
use Illuminate\Support\Facades\Auth;

class PjuService
{
    public function getAll(array $filters = [])
    {
        $query = PjuPoint::with(['creator', 'verifier'])
            ->latest();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['kategori'])) {
            $query->where('kategori', $filters['kategori']);
        }
        if (!empty($filters['jenis'])) {
            $query->where('jenis', $filters['jenis']);
        }
        if (!empty($filters['search'])) {
            $query->where('nama', 'like', '%' . $filters['search'] . '%');
        }

        return $query->paginate(15)->withQueryString();
    }

    public function getAllForMap(): \Illuminate\Database\Eloquent\Collection
    {
        return PjuPoint::select([
            'id',
            'nama',
            'kategori',
            'jenis',
            'daya',
            'letak',
            'type',
            'lat',
            'long',
            'status',
            'is_verified',
        ])->get();
    }

    public function create(array $data): PjuPoint
    {
        $data['created_by'] = Auth::id();
        return PjuPoint::create($data);
    }

    public function update(PjuPoint $pju, array $data): PjuPoint
    {
        $pju->update($data);
        return $pju->fresh();
    }

    public function delete(PjuPoint $pju): void
    {
        $pju->delete();
    }

    public function verify(PjuPoint $pju, string $action): PjuPoint
    {
        if ($action === 'approve') {
            $pju->update([
                'is_verified' => true,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);
        } else {
            $pju->update([
                'is_verified' => false,
                'verified_by' => null,
                'verified_at' => null,
            ]);
        }
        return $pju->fresh();
    }

    public function getStats(): array
    {
        $total = PjuPoint::count();
        $normal = PjuPoint::where('status', 'normal')->count();
        $mati = PjuPoint::where('status', 'mati')->count();
        $verified = PjuPoint::where('is_verified', true)->count();

        return compact('total', 'normal', 'mati', 'verified');
    }
}
