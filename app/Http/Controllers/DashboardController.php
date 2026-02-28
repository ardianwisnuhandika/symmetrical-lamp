<?php

namespace App\Http\Controllers;

use App\Services\PjuService;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct(protected PjuService $pjuService)
    {
    }

    public function index()
    {
        $stats = $this->pjuService->getStats();
        $totalUsers = User::count();

        return view('admin.dashboard', compact('stats', 'totalUsers'));
    }
}
