<?php

namespace App\Http\Controllers;

use App\Models\PjuPoint;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function index(Request $request)
    {
        $state = $request->query('state', 'pending');
        $search = trim((string) $request->query('search', ''));

        $query = PjuPoint::with(['creator', 'verifier'])->latest();

        if ($state === 'pending') {
            $query->where('is_verified', false);
        } elseif ($state === 'verified') {
            $query->where('is_verified', true);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('keterangan', 'like', '%' . $search . '%');
            });
        }

        $pjuPoints = $query->paginate(15)->withQueryString();
        $stats = [
            'pending' => PjuPoint::where('is_verified', false)->count(),
            'verified' => PjuPoint::where('is_verified', true)->count(),
            'total' => PjuPoint::count(),
        ];

        return view('admin.verification.index', [
            'pjuPoints' => $pjuPoints,
            'stats' => $stats,
            'filters' => [
                'state' => $state,
                'search' => $search,
            ],
        ]);
    }
}
