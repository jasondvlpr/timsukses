@props(['users', 'activeUser' => null])

@php
    use App\Models\ChatMessage;
    $unreadCounts = ChatMessage::whereNotNull('receiver_id')
        ->where('receiver_id', auth()->id())
        ->where('is_read', false)
        ->selectRaw('user_id, count(*) as total')
        ->groupBy('user_id')
        ->pluck('total', 'user_id');

    $adminsStaff = $users->whereIn('role', ['admin', 'staff']);
    $promoters = $users->where('role', 'promoter');
@endphp

<style>
    :root {
        --chat-h: calc(100vh - 120px);
    }

    .chat-root {
        display: flex;
        height: var(--chat-h);
        min-height: 500px;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08), 0 1px 4px rgba(0, 0, 0, 0.06);
        border: 1px solid #e2e8f0;
        background: #fff;
    }

    /* ── SIDEBAR ── */
    .chat-sidebar {
        width: 300px;
        min-width: 260px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        background: #1e293b;
        border-right: 1px solid #0f172a;
    }

    .chat-sidebar-head {
        padding: 18px 20px 14px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.07);
    }

    .chat-sidebar-head h3 {
        font-size: 16px;
        font-weight: 700;
        color: #f8fafc;
        margin: 0 0 2px;
    }

    .chat-sidebar-head p {
        font-size: 11px;
        color: #64748b;
        margin: 0;
    }

    .chat-user-list {
        flex: 1;
        overflow-y: auto;
        padding: 8px 0;
    }

    .chat-user-list::-webkit-scrollbar {
        width: 4px;
    }

    .chat-user-list::-webkit-scrollbar-thumb {
        background: #334155;
        border-radius: 4px;
    }

    .chat-section-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #475569;
        padding: 12px 20px 6px;
    }

    .chat-user-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        cursor: pointer;
        text-decoration: none;
        transition: background .15s;
        border-left: 3px solid transparent;
        position: relative;
    }

    .chat-user-row:hover {
        background: rgba(255, 255, 255, 0.06);
    }

    .chat-user-row.active {
        background: rgba(99, 102, 241, 0.18);
        border-left-color: #6366f1;
    }

    .chat-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
        color: #fff;
        flex-shrink: 0;
        position: relative;
    }

    .chat-avatar.admin {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }

    .chat-avatar.staff {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .chat-avatar.promoter {
        background: linear-gradient(135deg, #64748b, #475569);
    }

    .online-dot {
        position: absolute;
        bottom: 1px;
        right: 1px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2.5px solid #1e293b;
    }

    .online-dot.on {
        background: #22c55e;
    }

    .online-dot.off {
        background: #475569;
    }

    .chat-user-info {
        flex: 1;
        min-width: 0;
    }

    .chat-user-name {
        font-size: 13px;
        font-weight: 600;
        color: #f1f5f9;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.2;
    }

    .chat-user-status {
        font-size: 10px;
        color: #64748b;
        margin-top: 3px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .chat-user-status.online {
        color: #22c55e;
    }

    .chat-role-badge {
        display: inline-block;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        padding: 1px 6px;
        border-radius: 4px;
    }

    .chat-role-badge.admin {
        background: rgba(139, 92, 246, .25);
        color: #c4b5fd;
    }

    .chat-role-badge.staff {
        background: rgba(59, 130, 246, .25);
        color: #93c5fd;
    }

    .chat-role-badge.promoter {
        background: rgba(100, 116, 139, .25);
        color: #94a3b8;
    }

    .unread-badge {
        background: #ef4444;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        border-radius: 99px;
        min-width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
        flex-shrink: 0;
    }

    .chat-me-bar {
        padding: 12px 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.07);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chat-me-name {
        font-size: 12px;
        font-weight: 700;
        color: #e2e8f0;
    }

    .chat-me-status {
        font-size: 10px;
        color: #22c55e;
        margin-top: 1px;
    }

    /* ── MAIN AREA ── */
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0;
        background: #f8fafc;
        overflow: hidden; /* Hanya chat-messages yang boleh scroll */
    }

    /* Header */
    .chat-head {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 24px;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        flex-shrink: 0;
    }

    .chat-head-name {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
    }

    .chat-head-status {
        font-size: 11px;
        margin-top: 2px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .chat-head-status.online {
        color: #16a34a;
    }

    .chat-head-status.offline {
        color: #94a3b8;
    }

    .chat-wa-btn {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 6px;
        background: #dcfce7;
        color: #16a34a;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: background .15s;
        flex-shrink: 0;
    }

    .chat-wa-btn:hover {
        background: #bbf7d0;
    }

    /* Messages */
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px 24px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .chat-messages::-webkit-scrollbar {
        width: 5px;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .msg-row-me {
        display: flex;
        justify-content: flex-end;
    }

    .msg-row-other {
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }

    .msg-row-me>div {
        max-width: 75%;
    }

    .msg-row-other>div:last-child {
        max-width: 75%;
    }

    .bubble-me {
        width: 100%;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
        border-radius: 18px 18px 4px 18px;
        padding: 10px 16px;
        box-shadow: 0 2px 8px rgba(99, 102, 241, .3);
    }

    .bubble-other {
        width: 100%;
        background: #ffffff;
        color: #1e293b;
        border-radius: 18px 18px 18px 4px;
        padding: 10px 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .05);
    }

    .bubble-text {
        font-size: 14px;
        line-height: 1.6;
        word-break: break-word;
        overflow-wrap: anywhere;
        /* handles long strings without spaces */
        white-space: pre-wrap;
        margin: 0;
    }

    .bubble-time {
        font-size: 10px;
        margin-top: 4px;
        opacity: .6;
    }

    .msg-row-me .bubble-time {
        text-align: right;
        color: #fff;
    }

    .msg-row-other .bubble-time {
        color: #64748b;
    }

    .mini-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .msg-new {
        animation: slideUp .22s ease-out;
    }

    /* Input */
    .chat-input-bar {
        padding: 14px 20px;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-shrink: 0;
    }

    .chat-input-field {
        flex: 1;
        border: 1.5px solid #e2e8f0;
        border-radius: 24px;
        padding: 10px 20px;
        font-size: 14px;
        color: #1e293b;
        background: #f8fafc;
        outline: none;
        transition: border .15s, box-shadow .15s;
        font-family: inherit;
    }

    .chat-input-field:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
        background: #fff;
    }

    .chat-input-field::placeholder {
        color: #94a3b8;
    }

    .chat-send-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: none;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(99, 102, 241, .35);
        transition: transform .12s, box-shadow .12s;
    }

    .chat-send-btn:hover {
        transform: scale(1.07);
        box-shadow: 0 4px 14px rgba(99, 102, 241, .45);
    }

    .chat-send-btn:active {
        transform: scale(.95);
    }

    /* Empty state */
    .chat-empty {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        gap: 12px;
        background: #f8fafc;
    }

    /* ── RESPONSIVE ── */
    .chat-wrapper-mobile {
        width: 100%;
    }

    @media (max-width: 480px) {
        /* Kunci scroll body — TANPA position:fixed agar navbar tidak overlap */
        html, body {
            overflow: hidden !important;
            overscroll-behavior: none !important;   /* Cegah pull-to-refresh & bounce */
            height: 100% !important;
            touch-action: none !important;          /* Cegah scroll via touch */
        }

        /* Hide only page header, keep navbar visible */
        header { display: none !important; }

        /* Sembunyikan konten di luar chat */
        .chat-wrapper-mobile {
            padding: 0 !important;
            margin: 0 !important;
            width: 100vw !important;
        }

        .chat-inner-mobile {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
        }

        /* chat-root: fixed ke viewport, tepat di bawah navbar
           z-index: 30 agar di bawah navbar (z-40) sehingga mobile nav drawer 
           bisa menutupi area chat tanpa terpotong */
        .chat-root {
            position: fixed !important;
            top: 64px !important;
            left: 0 !important;
            right: 0 !important;
            bottom: env(safe-area-inset-bottom, 0px) !important;
            width: 100vw !important;
            height: calc(100dvh - 64px) !important;
            overflow: hidden !important;
            border-radius: 0 !important;
            border: none !important;
            box-shadow: none !important;
            margin: 0 !important;
            z-index: 30 !important;
        }

        .chat-sidebar {
            width: 100% !important;
            height: 100% !important;
            display: flex !important;
            border-right: none !important;
            z-index: 10;
        }

        /* Sembunyikan sidebar saat room aktif di mobile */
        .chat-root.room-active .chat-sidebar {
            display: none !important;
        }

        .chat-main, .chat-empty {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100% !important;
            height: 100% !important;
            overflow: hidden !important;
            background: #f8fafc !important;
            z-index: 20 !important;
            display: flex !important;
            flex-direction: column !important;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            transform: translateX(100%);
        }

        /* Saat aktif, geser ke posisi normal */
        .chat-root.room-active .chat-main,
        .chat-root.room-active .chat-empty {
            transform: translateX(0) !important;
        }

        .chat-sidebar-head {
            padding: 18px 20px 14px !important;
        }
        .chat-sidebar-head h3 { font-size: 16px !important; }

        .chat-head {
            padding: 12px 16px !important;
            height: 64px !important;
            box-sizing: border-box !important;
            flex-shrink: 0 !important;
        }

        /* HANYA area pesan yang boleh scroll */
        .chat-messages {
            flex: 1 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            min-height: 0 !important;
            -webkit-overflow-scrolling: touch !important;
            touch-action: pan-y !important;         /* Izinkan scroll vertikal di area pesan */
            overscroll-behavior: contain !important; /* Cegah bounce ke parent */
        }

        .chat-input-bar {
            flex-shrink: 0 !important;
        }

        .chat-user-list {
            touch-action: pan-y !important; /* Izinkan scroll daftar kontak */
            overscroll-behavior: contain !important;
        }
    }

    .mobile-back-btn {
        display: none;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #475569;
        border: none;
        cursor: pointer;
        margin-right: 4px;
    }

    /* ── MOBILE OPTIMIZATIONS (<480px) ── */
    @media (max-width: 480px) {
        .mobile-back-btn {
            display: flex !important;
        }
    }
</style>

<div class="chat-root {{ isset($activeUser) ? 'room-active' : '' }}">
    {{-- ═══ SIDEBAR ═══ --}}
    <aside class="chat-sidebar">
        <div class="chat-sidebar-head">
            <h3>💬 Pesan</h3>
            <p>{{ $users->count() }} kontak tersedia</p>
        </div>

        <div class="chat-user-list">
            @if($adminsStaff->count())
                <div class="chat-section-label">Admin & Staff</div>
                @foreach($adminsStaff as $u)
                    @php $unread = $unreadCounts[$u->id] ?? 0; @endphp
                    <a href="{{ route('chat.private', $u) }}"
                        class="chat-user-row {{ isset($activeUser) && $activeUser->id == $u->id ? 'active' : '' }}">
                        <div class="chat-avatar {{ $u->role }}">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                            <span class="online-dot {{ $u->isOnline() ? 'on' : 'off' }}"></span>
                        </div>
                        <div class="chat-user-info">
                            <div class="chat-user-name">{{ $u->name }}</div>
                            <div class="chat-user-status {{ $u->isOnline() ? 'online' : '' }}">
                                <span class="chat-role-badge {{ $u->role }}">{{ $u->role }}</span>
                                @if($u->isOnline())
                                    Online
                                @elseif($u->last_active_at)
                                    {{ $u->last_active_at->diffForHumans(null, true) }} lalu
                                @else
                                    Belum aktif
                                @endif
                            </div>
                        </div>
                        @if($unread > 0)
                            <span class="unread-badge">{{ $unread > 99 ? '99+' : $unread }}</span>
                        @endif
                    </a>
                @endforeach
            @endif

            @if($promoters->count())
                <div class="chat-section-label">Promotor</div>
                @foreach($promoters as $u)
                    @php $unread = $unreadCounts[$u->id] ?? 0; @endphp
                    <a href="{{ route('chat.private', $u) }}"
                        class="chat-user-row {{ isset($activeUser) && $activeUser->id == $u->id ? 'active' : '' }}">
                        <div class="chat-avatar promoter">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                            <span class="online-dot {{ $u->isOnline() ? 'on' : 'off' }}"></span>
                        </div>
                        <div class="chat-user-info">
                            <div class="chat-user-name">{{ $u->name }}</div>
                            <div class="chat-user-status {{ $u->isOnline() ? 'online' : '' }}">
                                <span class="chat-role-badge promoter">promoter</span>
                                @if($u->isOnline())
                                    Online
                                @elseif($u->last_active_at)
                                    {{ $u->last_active_at->diffForHumans(null, true) }} lalu
                                @else
                                    Belum aktif
                                @endif
                            </div>
                        </div>
                        @if($unread > 0)
                            <span class="unread-badge">{{ $unread > 99 ? '99+' : $unread }}</span>
                        @endif
                    </a>
                @endforeach
            @endif
        </div>

        <div class="chat-me-bar">
            <div class="chat-avatar admin" style="width:34px;height:34px;font-size:12px;position:relative;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                <span class="online-dot on" style="border-color:#1e293b;"></span>
            </div>
            <div>
                <div class="chat-me-name">{{ auth()->user()->name }}</div>
                <div class="chat-me-status">● Online</div>
            </div>
        </div>
    </aside>

    {{-- Main slot --}}
    {{ $slot }}

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Intercept clicks on user rows for AJAX loading
            document.addEventListener('click', e => {
                const row = e.target.closest('.chat-user-row');
                if (!row) return;

                e.preventDefault();
                const url = row.href;
                if (!url) return;

                loadChatRoom(url, row);
            });

            async function loadChatRoom(url, row) {
                // Clear existing poller if any
                if (window.currentChatPoller) {
                    clearTimeout(window.currentChatPoller);
                    window.currentChatPoller = null;
                }

                // Update UI active state
                document.querySelectorAll('.chat-user-row').forEach(r => r.classList.remove('active'));
                row.classList.add('active');

                // Visual feedback
                const contentArea = document.querySelector('.chat-main') || document.querySelector('.chat-empty');
                if (contentArea) contentArea.style.opacity = '0.5';

                try {
                    const response = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    
                    if (!response.ok) throw new Error('Network response was not ok');
                    const html = await response.text();

                    let target = document.querySelector('.chat-main') || document.querySelector('.chat-empty');

                    if (target) {
                        target.outerHTML = `<div class="chat-main">${html}</div>`;
                        
                        // Aktifkan slide-in di mobile
                        document.querySelector('.chat-root').classList.add('room-active');

                        history.pushState({ url }, '', url);

                        const newContainer = document.querySelector('.chat-main');
                        const scripts = newContainer.querySelectorAll('script');
                        scripts.forEach(oldScript => {
                            const newScript = document.createElement('script');
                            Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                            oldScript.parentNode.replaceChild(newScript, oldScript);
                        });
                    } else {
                        window.location.href = url;
                    }
                } catch (err) {
                    console.error('AJAX Load failed:', err);
                    window.location.href = url;
                }
            }

            // Fungsi global untuk menutup chat di mobile
            window.closeChatRoom = function() {
                document.querySelector('.chat-root').classList.remove('room-active');
                history.pushState(null, '', '{{ route('chat.index') }}');
                
                if (window.currentChatPoller) {
                    clearTimeout(window.currentChatPoller);
                    window.currentChatPoller = null;
                }
            };

            // Handle browser back/forward
            window.addEventListener('popstate', (e) => {
                window.location.reload();
            });
        });

        // Helper for toast (global so partials can use it)
        window.showToast = function(msg, isError = false) {
            const t = document.createElement('div');
            t.textContent = msg;
            Object.assign(t.style, {
                position: 'fixed', bottom: '24px', right: '24px', zIndex: 9999,
                background: isError ? '#ef4444' : '#1e293b', color: '#fff',
                padding: '12px 20px', borderRadius: '12px', fontSize: '13px', fontWeight: '600',
                boxShadow: '0 4px 16px rgba(0,0,0,.2)', animation: 'slideUp .25s ease-out'
            });
            document.body.appendChild(t);
            setTimeout(() => t.remove(), 3000);
        }
    </script>
</div>