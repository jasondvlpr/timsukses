<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Website;
use App\Models\WebsiteRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'open_tickets' => Ticket::where('status', 'open')->count(),
            'pending_requests' => WebsiteRequest::where('status', 'pending')->count(),
            'total_websites' => Website::count(),
            'total_promoters' => User::where('role', 'promoter')->count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }

    public function promoters()
    {
        $promoters = User::where('role', 'promoter')->with('websites')->latest()->paginate(10);
        return view('admin.promoters.index', compact('promoters'));
    }

    public function tickets(Request $request)
    {
        $status = $request->get('status', 'open');
        $search = $request->get('search');
        
        $tickets = Ticket::with('user', 'website', 'assignedTo')
            ->when($status !== 'all', function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($search, function($query) use ($search) {
                return $query->where(function($q) use ($search) {
                    $q->where('subject', 'like', "%{$search}%")
                      ->orWhere('ticket_number', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->oldest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.tickets.index', compact('tickets', 'status', 'search'));
    }

    public function showTicket(Ticket $ticket)
    {
        $ticket->load('messages.user', 'website', 'user', 'assignedTo');
        $backofficeUsers = User::whereIn('role', ['admin', 'staff'])->get();
        return view('admin.tickets.show', compact('ticket', 'backofficeUsers'));
    }

    public function replyTicket(Request $request, Ticket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin_reply' => true,
        ]);

        $ticket->update(['status' => $request->status]);

        return back()->with('success', 'Balasan berhasil dikirim dan status diperbarui.');
    }

    public function forwardTicket(Request $request, Ticket $ticket)
    {
        $request->validate([
            'assigned_to_id' => 'required|exists:users,id',
            'note' => 'nullable|string',
        ]);

        $newAssignee = User::find($request->assigned_to_id);
        
        $ticket->update([
            'assigned_to_id' => $request->assigned_to_id,
            'status' => 'in_progress'
        ]);

        // Add internal message about forwarding
        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => "--- Keluhan ini diteruskan kepada {$newAssignee->name} (" . strtoupper($newAssignee->role) . ") ---" . ($request->note ? "\nCatatan: " . $request->note : ""),
            'is_admin_reply' => true,
        ]);

        return back()->with('success', "Keluhan berhasil diteruskan kepada {$newAssignee->name}.");
    }

    public function websiteRequests()
    {
        $requests = WebsiteRequest::with('user')
            ->whereIn('status', ['pending', 'processing'])
            ->latest()
            ->paginate(10);
        return view('admin.website_requests.index', compact('requests'));
    }

    public function processWebsite(WebsiteRequest $websiteRequest)
    {
        $websiteRequest->update(['status' => 'processing']);
        return back()->with('success', 'Status pengajuan web diubah menjadi Sedang Diproses.');
    }

    public function approveWebsite(Request $request, WebsiteRequest $websiteRequest)
    {
        if (in_array($websiteRequest->status, ['approved', 'rejected'])) {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'url' => 'required|url|max:255',
            'admin_note' => 'required|string'
        ]);

        $websiteRequest->update([
            'url' => $request->url,
            'status' => 'approved',
            'admin_note' => $request->admin_note,
        ]);

        Website::create([
            'user_id' => $websiteRequest->user_id,
            'name' => $websiteRequest->name,
            'url' => $websiteRequest->url,
        ]);

        return back()->with('success', 'Website telah disetujui dan ditambahkan ke daftar promotor.');
    }

    public function rejectWebsite(Request $request, WebsiteRequest $websiteRequest)
    {
        $request->validate(['admin_note' => 'required|string']);

        $websiteRequest->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'Pengajuan website ditolak.');
    }

    public function websites()
    {
        $websites = Website::with('user')->latest()->paginate(15);
        $promoters = User::where('role', 'promoter')->get();
        return view('admin.websites.index', compact('websites', 'promoters'));
    }

    public function destroyWebsite(Website $website)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Hanya Admin yang dapat menghapus website.');
        }

        $website->delete();
        return back()->with('success', 'Website berhasil dihapus.');
    }

    public function storeWebsite(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'url' => 'required|url|max:255',
        ]);

        Website::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'url' => $request->url,
        ]);

        return back()->with('success', 'Website berhasil ditambahkan secara manual.');
    }
}
