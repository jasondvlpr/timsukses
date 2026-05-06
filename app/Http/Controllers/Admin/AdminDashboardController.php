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
            'my_tickets' => Ticket::where('assigned_to_id', auth()->id())->whereIn('status', ['open', 'in_progress'])->count(),
            'unassigned_tickets' => Ticket::whereNull('assigned_to_id')->where('status', 'open')->count(),
            
            'pending_requests' => WebsiteRequest::whereIn('status', ['pending', 'processing'])->count(),
            'my_requests' => WebsiteRequest::where('assigned_to_id', auth()->id())->whereIn('status', ['pending', 'processing'])->count(),
            'unassigned_requests' => WebsiteRequest::whereNull('assigned_to_id')->where('status', 'pending')->count(),
            
            'total_websites' => Website::count(),
            'total_promoters' => User::where('role', 'promoter')->count(),
        ];

        // Weekly data for chart
        $days = collect(range(6, 0))->map(function($i) {
            return now()->subDays($i)->format('Y-m-d');
        });

        $chartData = [
            'labels' => $days->map(fn($d) => date('D', strtotime($d))),
            'requests' => $days->map(fn($d) => WebsiteRequest::whereDate('created_at', $d)->count()),
            'tickets' => $days->map(fn($d) => Ticket::whereDate('created_at', $d)->count()),
        ];

        return view('admin.dashboard', compact('stats', 'chartData'));
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
        $assignedTo = $request->get('assigned_to');
        
        $tickets = Ticket::with('user', 'website', 'assignedTo')
            ->when($status !== 'all', function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($assignedTo === 'me', function($query) {
                return $query->where('assigned_to_id', auth()->id());
            })
            ->when($assignedTo === 'none', function($query) {
                return $query->whereNull('assigned_to_id');
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

        return view('admin.tickets.index', compact('tickets', 'status', 'search', 'assignedTo'));
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
            'send_whatsapp' => 'nullable|string'
        ]);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin_reply' => true,
        ]);

        $ticket->update(['status' => $request->status]);

        // Send WhatsApp if requested
        if ($request->send_whatsapp === 'yes') {
            $whatsAppService = new \App\Services\WhatsAppService();
            // Clean the number from any non-numeric characters except maybe plus
            $target = preg_replace('/[^0-9]/', '', $ticket->user->whatsapp);
            
            if ($target) {
                $waMessage = "Halo {$ticket->user->name},\n\nAdmin telah membalas tiket Anda [#{$ticket->ticket_number}].\n\nSubjek: {$ticket->subject}\nStatus: " . strtoupper($request->status) . "\n\nBalasan Admin:\n\"{$request->message}\"\n\nSilakan cek dashboard untuk detail selengkapnya.";
                $whatsAppService->sendMessage($target, $waMessage);
            }
        }

        return back()->with('success', 'Balasan berhasil dikirim' . ($request->send_whatsapp === 'yes' ? ' dan dikirim ke WhatsApp.' : '.'));
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

    public function websiteRequests(Request $request)
    {
        $status = $request->get('status', 'unapproved');
        $search = $request->get('search');
        $assignedTo = $request->get('assigned_to');

        $requests = WebsiteRequest::with('user', 'assignedTo')
            ->when($status === 'unapproved', function($query) {
                return $query->whereIn('status', ['pending', 'processing']);
            })
            ->when($status !== 'unapproved' && $status !== 'all', function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($assignedTo === 'me', function($query) {
                return $query->where('assigned_to_id', auth()->id());
            })
            ->when($assignedTo === 'none', function($query) {
                return $query->whereNull('assigned_to_id');
            })
            ->when($search, function($query) use ($search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('url', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('username', 'like', "%{$search}%");
                      });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $backofficeUsers = User::whereIn('role', ['admin', 'staff'])->get();

        return view('admin.website_requests.index', compact('requests', 'status', 'search', 'backofficeUsers', 'assignedTo'));
    }

    public function assignWebsiteRequest(Request $request, WebsiteRequest $websiteRequest)
    {
        $request->validate([
            'assigned_to_id' => 'required|exists:users,id',
        ]);

        $assignee = User::find($request->assigned_to_id);
        $websiteRequest->update(['assigned_to_id' => $request->assigned_to_id]);

        return back()->with('success', "Pengajuan berhasil ditugaskan kepada {$assignee->name}.");
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
            'admin_note' => 'required|string',
            'send_whatsapp' => 'nullable|string'
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

        // Send WhatsApp if requested
        if ($request->send_whatsapp === 'yes') {
            $whatsAppService = new \App\Services\WhatsAppService();
            $target = preg_replace('/[^0-9]/', '', $websiteRequest->user->whatsapp);
            if ($target) {
                $waMessage = "Halo {$websiteRequest->user->name},\n\nPengajuan website Anda telah DISETUJUI oleh Admin.\n\nNama Web: {$websiteRequest->name}\nLink: {$request->url}\n\nCatatan Admin:\n\"{$request->admin_note}\"\n\nWebsite Anda kini sudah aktif di dashboard.";
                $whatsAppService->sendMessage($target, $waMessage);
            }
        }

        return back()->with('success', 'Website telah disetujui' . ($request->send_whatsapp === 'yes' ? ' dan dikirim ke WhatsApp.' : '.'));
    }

    public function rejectWebsite(Request $request, WebsiteRequest $websiteRequest)
    {
        $request->validate([
            'admin_note' => 'required|string',
            'send_whatsapp' => 'nullable|string'
        ]);

        $websiteRequest->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
        ]);

        // Send WhatsApp if requested
        if ($request->send_whatsapp === 'yes') {
            $whatsAppService = new \App\Services\WhatsAppService();
            $target = preg_replace('/[^0-9]/', '', $websiteRequest->user->whatsapp);
            if ($target) {
                $waMessage = "Halo {$websiteRequest->user->name},\n\nMohon maaf, pengajuan website Anda ({$websiteRequest->name}) telah DITOLAK oleh Admin.\n\nAlasan Penolakan:\n\"{$request->admin_note}\"\n\nSilakan cek dashboard untuk informasi lebih lanjut atau hubungi admin jika ada pertanyaan.";
                $whatsAppService->sendMessage($target, $waMessage);
            }
        }

        return back()->with('success', 'Pengajuan website ditolak' . ($request->send_whatsapp === 'yes' ? ' dan dikirim ke WhatsApp.' : '.'));
    }

    public function websites(Request $request)
    {
        $search = $request->get('search');
        
        $websites = Website::with('user')
            ->when($search, function($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('url', 'like', "%{$search}%")
                    ->orWhereHas('user', function($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                                 ->orWhere('username', 'like', "%{$search}%");
                    });
            })
            ->oldest()
            ->get();
            
        $promoters = User::where('role', 'promoter')->get();
        return view('admin.websites.index', compact('websites', 'promoters', 'search'));
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
    public function checkNotifications()
    {
        $unassignedRequests = WebsiteRequest::whereNull('assigned_to_id')->where('status', 'pending')->count();
        $unassignedTickets = Ticket::whereNull('assigned_to_id')->where('status', 'open')->count();

        return response()->json([
            'total' => $unassignedRequests + $unassignedTickets,
            'requests' => $unassignedRequests,
            'tickets' => $unassignedTickets,
        ]);
    }
}
