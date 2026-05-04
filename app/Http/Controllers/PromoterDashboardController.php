<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PromoterDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $stats = [
            'total_tickets' => $user->tickets()->count(),
            'open_tickets' => $user->tickets()->where('status', 'open')->count(),
            'resolved_tickets' => $user->tickets()->where('status', 'resolved')->count(),
            'active_websites' => $user->websites()->where('is_active', true)->count(),
            'pending_requests' => $user->websiteRequests()->where('status', 'pending')->count(),
        ];

        $recent_tickets = $user->tickets()->with('website')->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recent_tickets'));
    }
}
