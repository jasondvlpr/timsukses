<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ChatController extends Controller
{
    // ─── Contact List / Default Chat Page ────────────────────────

    public function index()
    {
        $users = $this->getChatableUsers();
        return view('chat.index', compact('users'));
    }

    // ─── Private Chat ─────────────────────────────────────────────

    public function private(User $user)
    {
        if (auth()->user()->isPromoter() && $user->isPromoter()) {
            abort(403, 'Promotor hanya dapat chat dengan Admin atau Staff.');
        }

        ChatMessage::where('user_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = ChatMessage::privateBetween(auth()->id(), $user->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->take(100)
            ->get();

        $users = $this->getChatableUsers();
        $unreadCounts = $this->getUnreadCounts();

        if (request()->ajax()) {
            return view('chat.partials.room', compact('messages', 'user', 'users', 'unreadCounts'));
        }

        return view('chat.private', compact('messages', 'user', 'users', 'unreadCounts'));
    }

    public function sendPrivate(Request $request, User $user)
    {
        if (auth()->user()->isPromoter() && $user->isPromoter()) {
            abort(403);
        }

        $request->validate([
            'message' => 'nullable|string|max:2000',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120', // 5MB
        ]);

        // Must have message OR image
        if (!$request->filled('message') && !$request->hasFile('image')) {
            return response()->json(['error' => 'Pesan atau gambar diperlukan.'], 422);
        }

        // Clear typing indicator when message is sent
        Cache::forget("typing_{$user->id}_to_" . auth()->id());

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat-images', 'public');
        }

        $msg = ChatMessage::create([
            'user_id'     => auth()->id(),
            'receiver_id' => $user->id,
            'message'     => $request->message ?? null,
            'image'       => $imagePath,
        ]);

        $msg->load('sender');

        return response()->json([
            'id'         => $msg->id,
            'message'    => $msg->message,
            'image'      => $imagePath ? asset('storage/' . $imagePath) : null,
            'sender'     => $msg->sender->name,
            'role'       => $msg->sender->role,
            'is_me'      => true,
            'created_at' => $msg->created_at->format('H:i'),
        ]);
    }

    /**
     * Called by JS when user is actively typing (debounced).
     * Stores a cache key that expires in 4 seconds.
     */
    public function typing(User $user)
    {
        // Key: "userX is typing to userY"
        $key = "typing_" . auth()->id() . "_to_{$user->id}";
        Cache::put($key, auth()->user()->name, 4); // 4 seconds TTL

        return response()->json(['ok' => true]);
    }

    /**
     * Clear (permanently delete) all messages between auth user and target user.
     * Also removes uploaded image files from storage.
     */
    public function clearChat(User $user)
    {
        $messages = ChatMessage::privateBetween(auth()->id(), $user->id)->get();

        // Delete associated image files from disk
        foreach ($messages as $msg) {
            if ($msg->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($msg->image);
            }
        }

        // Hard delete all messages in this conversation
        ChatMessage::privateBetween(auth()->id(), $user->id)->delete();

        return response()->json(['ok' => true, 'message' => 'Riwayat chat berhasil dihapus.']);
    }

    public function pollPrivate(Request $request, User $user)
    {
        $since = $request->query('since', 0);

        // Mark messages from the other user as read (triggers read receipt on their end)
        ChatMessage::where('user_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // New messages since last poll
        $messages = ChatMessage::privateBetween(auth()->id(), $user->id)
            ->with('sender')
            ->where('id', '>', $since)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn ($m) => [
                'id'         => $m->id,
                'message'    => $m->message,
                'image'      => $m->image ? asset('storage/' . $m->image) : null,
                'sender'     => $m->sender->name,
                'role'       => $m->sender->role,
                'is_me'      => $m->user_id === auth()->id(),
                'is_read'    => $m->is_read,
                'created_at' => $m->created_at->format('H:i'),
            ]);

        // IDs of MY messages (sent by me to the other user) that have now been read
        // Optimization: only pluck recent ones to avoid massive arrays in production
        $readIds = ChatMessage::where('user_id', auth()->id())
            ->where('receiver_id', $user->id)
            ->where('is_read', true)
            ->where('id', '>', max(0, $since - 100))
            ->limit(100)
            ->pluck('id');

        // Check typing indicator
        $typingKey = "typing_{$user->id}_to_" . auth()->id();
        $isTyping  = Cache::has($typingKey);

        return response()->json([
            'messages' => $messages,
            'typing'   => $isTyping,
            'read_ids' => $readIds,
        ]);
    }

    public function unreadCounts()
    {
        return response()->json($this->getUnreadCounts());
    }

    // ─── Helpers ─────────────────────────────────────────────────

    private function getChatableUsers()
    {
        $query = User::where('id', '!=', auth()->id())
            ->orderByRaw("FIELD(role, 'admin', 'staff', 'promoter')")
            ->orderBy('name');

        if (auth()->user()->isPromoter()) {
            $query->whereIn('role', ['admin', 'staff']);
        }

        return $query->get();
    }

    private function getUnreadCounts(): \Illuminate\Support\Collection
    {
        return ChatMessage::whereNotNull('receiver_id')
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->selectRaw('user_id, count(*) as total')
            ->groupBy('user_id')
            ->pluck('total', 'user_id');
    }
}
