<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::query()->where('role', 'client')->count();
        $totalBroadcasts = Broadcast::query()->count();
        $recentUsers = User::query()
            ->where('role', 'client')
            ->with('client')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('totalUsers', 'totalBroadcasts', 'recentUsers'));
    }
}
