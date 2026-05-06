<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChatAnalyticsController extends Controller
{
    public function index()
    {
        // 1. Staff Response Time Analytics
        $staffs = User::whereIn('role', ['admin', 'staff'])->get();
        $analytics = [];

        foreach ($staffs as $staff) {
            $responseTime = $this->calculateAverageResponseTime($staff->id);
            $totalMessages = ChatMessage::where('user_id', $staff->id)->count();
            
            $analytics[] = [
                'name' => $staff->name,
                'role' => $staff->role,
                'avg_response' => $responseTime, // in minutes
                'total_sent' => $totalMessages,
            ];
        }

        // 2. Chat Volume Trend (Last 7 Days)
        $days = collect(range(6, 0))->map(function($i) {
            return now()->subDays($i)->format('Y-m-d');
        });

        $volumeData = [
            'labels' => $days->map(fn($d) => date('D', strtotime($d))),
            'counts' => $days->map(fn($d) => ChatMessage::whereDate('created_at', $d)->count()),
        ];

        // 3. Top Active Promoters
        $topPromoters = User::where('role', 'promoter')
            ->withCount(['sentMessages' => function($q) {
                $q->whereNotNull('receiver_id');
            }])
            ->orderBy('sent_messages_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.analytics.chat', compact('analytics', 'volumeData', 'topPromoters'));
    }

    /**
     * Logic: For every message received by the staff from a promoter, 
     * find the first message sent back by this staff to that same promoter.
     */
    private function calculateAverageResponseTime($staffId)
    {
        $receivedMessages = ChatMessage::where('receiver_id', $staffId)
            ->whereHas('sender', fn($q) => $q->where('role', 'promoter'))
            ->orderBy('created_at', 'asc')
            ->get();

        if ($receivedMessages->isEmpty()) return 0;

        $totalDiff = 0;
        $count = 0;

        foreach ($receivedMessages as $msg) {
            // Find the FIRST reply from this staff to this promoter AFTER the received message
            $reply = ChatMessage::where('user_id', $staffId)
                ->where('receiver_id', $msg->user_id)
                ->where('created_at', '>', $msg->created_at)
                ->orderBy('created_at', 'asc')
                ->first();

            if ($reply) {
                $diff = $msg->created_at->diffInMinutes($reply->created_at);
                // Cap at 24 hours to avoid outliers (e.g. staff replying after days)
                if ($diff < 1440) {
                    $totalDiff += $diff;
                    $count++;
                }
            }
        }

        return $count > 0 ? round($totalDiff / $count) : 0;
    }
}
