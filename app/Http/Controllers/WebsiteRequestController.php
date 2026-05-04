<?php

namespace App\Http\Controllers;

use App\Models\WebsiteRequest;
use Illuminate\Http\Request;

class WebsiteRequestController extends Controller
{
    public function index()
    {
        $requests = auth()->user()->websiteRequests()->latest()->paginate(10);
        return view('website_requests.index', compact('requests'));
    }

    public function myWebsites()
    {
        $websites = auth()->user()->websiteRequests()
            ->latest()
            ->get()
            ->groupBy('status');

        $stats = [
            'total'      => auth()->user()->websiteRequests()->count(),
            'approved'   => auth()->user()->websiteRequests()->where('status', 'approved')->count(),
            'pending'    => auth()->user()->websiteRequests()->where('status', 'pending')->count(),
            'processing' => auth()->user()->websiteRequests()->where('status', 'processing')->count(),
            'rejected'   => auth()->user()->websiteRequests()->where('status', 'rejected')->count(),
        ];

        return view('website_requests.my', compact('websites', 'stats'));
    }

    public function create()
    {
        return redirect()->route('website-requests.index', ['open_modal' => 1]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'url'         => [
                'required', 
                'url', 
                'max:255', 
                'unique:websites,url', 
                'unique:website_requests,url'
            ],
            'description' => 'nullable|string',
        ], [
            'url.unique' => 'Website ini sudah terdaftar atau sedang dalam proses pengajuan.',
        ]);

        auth()->user()->websiteRequests()->create([
            'name'        => $request->name,
            'url'         => $request->url,
            'description' => $request->description,
            'status'      => 'pending',
        ]);

        return redirect()->route('my-websites')->with('success', 'Pengajuan website baru berhasil dikirim.');
    }
}
