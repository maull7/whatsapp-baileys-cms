<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $client = $user->client;

        $totalBroadcasts = Broadcast::query()
            ->where('client_id', $client->id)
            ->count();

        $recentBroadcasts = Broadcast::query()
            ->where('client_id', $client->id)
            ->with('messageTemplate')
            ->latest()
            ->limit(5)
            ->get();

        return view('client.dashboard', compact('client', 'totalBroadcasts', 'recentBroadcasts', 'user'));
    }
}
