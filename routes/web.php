<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\WebsiteRequestController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PromoterDashboardController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Webhook\GitHubWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/github', [GitHubWebhookController::class, 'handle'])->name('webhooks.github');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin() || auth()->user()->isStaff()) {
        return redirect()->route('admin.dashboard');
    }
    return app(PromoterDashboardController::class)->index();
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Promoter Routes
    Route::resource('tickets', TicketController::class);
    Route::post('tickets/{ticket}/message', [TicketController::class, 'storeMessage'])->name('tickets.message');
    
    Route::resource('website-requests', WebsiteRequestController::class);
    Route::get('/my-websites', [WebsiteRequestController::class, 'myWebsites'])->name('my-websites');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Chat Routes (Private only — no room chat)
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/unread', [ChatController::class, 'unreadCounts'])->name('chat.unread');
    Route::get('/chat/{user}', [ChatController::class, 'private'])->name('chat.private');
    Route::post('/chat/{user}/send', [ChatController::class, 'sendPrivate'])->name('chat.private.send');
    Route::post('/chat/forward/{message}', [ChatController::class, 'forward'])->name('chat.forward');
    Route::post('/chat/{user}/typing', [ChatController::class, 'typing'])->name('chat.private.typing');
    Route::delete('/chat/{user}/clear', [ChatController::class, 'clearChat'])->name('chat.private.clear');
    Route::get('/chat/{user}/poll', [ChatController::class, 'pollPrivate'])->name('chat.private.poll');
});

// Admin & Staff Routes
Route::middleware(['auth', 'staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/tickets', [AdminDashboardController::class, 'tickets'])->name('tickets');
    Route::get('/tickets/{ticket}', [AdminDashboardController::class, 'showTicket'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [AdminDashboardController::class, 'replyTicket'])->name('tickets.reply');
    Route::post('/tickets/{ticket}/forward', [AdminDashboardController::class, 'forwardTicket'])->name('tickets.forward');
    
    Route::get('/website-requests', [AdminDashboardController::class, 'websiteRequests'])->name('website-requests');
    Route::post('/website-requests/{websiteRequest}/assign', [AdminDashboardController::class, 'assignWebsiteRequest'])->name('website-requests.assign');
    Route::post('/website-requests/{websiteRequest}/process', [AdminDashboardController::class, 'processWebsite'])->name('website-requests.process');
    Route::post('/website-requests/{websiteRequest}/approve', [AdminDashboardController::class, 'approveWebsite'])->name('website-requests.approve');
    Route::post('/website-requests/{websiteRequest}/reject', [AdminDashboardController::class, 'rejectWebsite'])->name('website-requests.reject');
    Route::get('/websites', [AdminDashboardController::class, 'websites'])->name('websites');
    Route::post('/websites', [AdminDashboardController::class, 'storeWebsite'])->name('websites.store');
    Route::get('/notifications/check', [AdminDashboardController::class, 'checkNotifications'])->name('notifications.check');

    // Admin ONLY Routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        
        Route::post('/websites/{website}/transfer', [UserController::class, 'transferWebsite'])->name('websites.transfer');
        Route::delete('/websites/{website}', [AdminDashboardController::class, 'destroyWebsite'])->name('websites.destroy');
        Route::get('/promoters', [AdminDashboardController::class, 'promoters'])->name('promoters');

        // Analytics
        Route::get('/analytics/chat', [\App\Http\Controllers\Admin\ChatAnalyticsController::class, 'index'])->name('analytics.chat');

        // Log Viewer
        Route::get('/logs', [\App\Http\Controllers\Admin\LogViewerController::class, 'index'])->name('logs.index');
        Route::post('/logs/clear', [\App\Http\Controllers\Admin\LogViewerController::class, 'clear'])->name('logs.clear');
    });
});


require __DIR__.'/auth.php';
