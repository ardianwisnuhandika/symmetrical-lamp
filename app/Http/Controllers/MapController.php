<?php

namespace App\Http\Controllers;

use App\Services\PjuService;

class MapController extends Controller
{
    public function __construct(protected PjuService $pjuService)
    {
    }

    public function index()
    {
        return view('map.index');
    }

    public function apiMarkers()
    {
        $points = $this->pjuService->getAllForMap();

        return response()->json($points->map(fn($p) => [
            'id' => $p->id,
            'nama' => $p->nama,
            'kategori' => $p->kategori,
            'jenis' => $p->jenis,
            'daya' => $p->daya,
            'letak' => $p->letak,
            'type' => $p->type,
            'lat' => (float) $p->lat,
            'lng' => (float) $p->long,
            'status' => $p->status,
            'is_verified' => $p->is_verified,
        ]));
    }
}
