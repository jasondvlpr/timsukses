<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = auth()->user()->tickets()->with('website')->latest()->paginate(10);
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $websites = auth()->user()->websites;
        return view('tickets.create', compact('websites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'website_id' => 'nullable|exists:websites,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $data['attachment'] = $path;
        }

        $ticket = auth()->user()->tickets()->create($data);

        return redirect()->route('tickets.index')->with('success', 'Keluhan berhasil dikirim.');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->load('messages.user', 'website');
        return view('tickets.show', compact('ticket'));
    }

    public function storeMessage(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_admin_reply' => false,
        ]);

        return back()->with('success', 'Pesan berhasil dikirim.');
    }
}
