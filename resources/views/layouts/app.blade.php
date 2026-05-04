<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BossGroupHub') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
        <script src="{{ asset('js/toast.js') }}"></script>
    </head>
    <body class="font-sans antialiased text-slate-900 bg-slate-50">
        <div class="min-h-screen bg-slate-50">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white border-b border-slate-200">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>

    @auth
    <script>
    // ═══════════════════════════════════════════════════════════
    //  BossGroupHub — Global Chat Notification System
    // ═══════════════════════════════════════════════════════════
    (function () {
        const POLL_INTERVAL  = 8000;   // poll every 8s
        const UNREAD_URL     = "{{ route('chat.unread') }}";
        const CHAT_URL       = "{{ route('chat.index') }}";
        const ON_CHAT_PAGE   = window.location.pathname.startsWith('/chat');

        let prevTotal        = null;   // null = first load (don't notify)
        let audioCtx         = null;
        let notifPermission  = Notification.permission;

        // ── 1. Request notification permission on first poll ─────
        if (notifPermission === 'default') {
            Notification.requestPermission().then(p => { notifPermission = p; });
        }

        // ── 2. Web Audio API beep (no external file needed) ──────
        function playBeep() {
            try {
                audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
                // Two-tone "ding" notification sound
                const now = audioCtx.currentTime;
                [880, 1100].forEach((freq, i) => {
                    const osc  = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.type     = 'sine';
                    osc.frequency.setValueAtTime(freq, now + i * 0.12);
                    gain.gain.setValueAtTime(0, now + i * 0.12);
                    gain.gain.linearRampToValueAtTime(0.25, now + i * 0.12 + 0.02);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.12 + 0.35);
                    osc.start(now + i * 0.12);
                    osc.stop(now + i * 0.12 + 0.4);
                });
            } catch {}
        }

        // ── 3. Browser desktop notification ──────────────────────
        function showNotification(count) {
            if (notifPermission !== 'granted') return;
            if (document.hasFocus()) return; // only when window not focused
            const n = new Notification('💬 BossGroupHub — Pesan Baru', {
                body: `Anda memiliki ${count} pesan belum dibaca`,
                icon: '/favicon.ico',
                badge: '/favicon.ico',
                tag: 'chat-notif',   // replace old notifications
            });
            n.onclick = () => { window.focus(); window.location.href = CHAT_URL; n.close(); };
            setTimeout(() => n.close(), 5000);
        }

        // ── 4. Update navbar badge ────────────────────────────────
        function updateBadge(total) {
            const badges = [
                document.getElementById('nav-chat-badge'),
                document.getElementById('nav-chat-badge-mobile')
            ];
            
            badges.forEach(badge => {
                if (!badge) return;
                if (total > 0) {
                    badge.textContent = total > 99 ? '99+' : total;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            });
        }

        // ── 5. Update page title ──────────────────────────────────
        const baseTitle = document.title;
        function updateTitle(total) {
            document.title = total > 0 ? `(${total}) ${baseTitle}` : baseTitle;
        }

        // ── 6. Main poll loop ─────────────────────────────────────
        async function checkUnread() {
            // Don't notify if already on chat page (polling there handles it)
            const isOnChat = window.location.pathname.startsWith('/chat');

            try {
                const res    = await fetch(UNREAD_URL);
                const counts = await res.json();   // {userId: count, ...}

                const total = Object.values(counts).reduce((s, v) => s + Number(v), 0);

                updateBadge(total);
                updateTitle(total);

                // On first load, store baseline — don't notify for existing unread
                if (prevTotal === null) { prevTotal = total; return; }

                // New messages arrived since last poll
                if (total > prevTotal && !isOnChat) {
                    playBeep();
                    showNotification(total);
                }

                prevTotal = total;
            } catch {}
        }

        // Start polling immediately then every 8s
        checkUnread();
        setInterval(checkUnread, POLL_INTERVAL);

        // Reset badge + title when user navigates to chat page
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden && window.location.pathname.startsWith('/chat')) {
                updateBadge(0);
                updateTitle(0);
                prevTotal = 0;
            }
        });
    })();
    </script>
        @endauth

    </body>
</html>
