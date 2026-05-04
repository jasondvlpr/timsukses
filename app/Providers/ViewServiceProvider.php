<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Models\WebsiteRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('layouts.navigation', function ($view) {
            if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isStaff())) {
                $view->with('adminStats', [
                    'pending_tickets' => Ticket::where('status', '!=', 'closed')->count(),
                    'pending_websites' => WebsiteRequest::whereIn('status', ['pending', 'processing'])->count(),
                ]);
            }
        });
    }
}
