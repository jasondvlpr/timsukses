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
    //  BossGroupHub — Global Notification System
    // ═══════════════════════════════════════════════════════════
    (function () {
        const POLL_INTERVAL  = 8000;
        const UNREAD_URL     = "{{ route('chat.unread') }}";
        const CHAT_URL       = "{{ route('chat.index') }}";
        const NOTIF_URL      = "{{ route('admin.notifications.check') }}";
        const ADMIN_DASH_URL = "{{ route('admin.dashboard') }}";
        const IS_BACKOFFICE  = {{ auth()->user()->isAdmin() || auth()->user()->isStaff() ? 'true' : 'false' }};

        let prevTotal        = null;
        let prevAdminTotal   = null;
        let audioCtx         = null;
        let notifPermission  = Notification.permission;

        if (notifPermission === 'default') {
            Notification.requestPermission().then(p => { notifPermission = p; });
        }

        function playBeep(isUrgent = false) {
            try {
                audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
                const now = audioCtx.currentTime;
                const tones = isUrgent ? [880, 1100, 1320] : [880, 1100];
                tones.forEach((freq, i) => {
                    const osc  = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    osc.type     = 'sine';
                    osc.frequency.setValueAtTime(freq, now + i * 0.12);
                    gain.gain.setValueAtTime(0, now + i * 0.12);
                    gain.gain.linearRampToValueAtTime(0.25, now + i * 0.12 + 0.02);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.12 + (isUrgent ? 0.45 : 0.35));
                    osc.start(now + i * 0.12);
                    osc.stop(now + i * 0.12 + 0.5);
                });
            } catch {}
        }

        function showNotification(title, body, url) {
            if (notifPermission !== 'granted') return;
            const n = new Notification(title, {
                body: body,
                icon: '/favicon.ico',
                tag: 'admin-notif',
            });
            n.onclick = () => { window.focus(); window.location.href = url; n.close(); };
            setTimeout(() => n.close(), 8000);
        }

        function updateBadge(total) {
            const badges = [document.getElementById('nav-chat-badge'), document.getElementById('nav-chat-badge-mobile')];
            badges.forEach(badge => {
                if (!badge) return;
                badge.textContent = total > 99 ? '99+' : total;
                badge.style.display = total > 0 ? 'inline-block' : 'none';
            });
        }

        const baseTitle = document.title;
        function updateTitle(total) {
            document.title = total > 0 ? `(${total}) ${baseTitle}` : baseTitle;
        }

        async function checkAdminNotifications() {
            if (!IS_BACKOFFICE) return;
            try {
                const res = await fetch(NOTIF_URL);
                const data = await res.json();
                
                if (prevAdminTotal === null) { prevAdminTotal = data.total; return; }

                if (data.total > prevAdminTotal) {
                    playBeep(true);
                    let message = '';
                    if (data.requests > 0) message += `${data.requests} Pengajuan Web baru. `;
                    if (data.tickets > 0) message += `${data.tickets} Tiket Keluhan baru. `;
                    
                    showNotification('🔔 Tugas Baru Tersedia!', message + 'Silakan ambil tugas sekarang.', ADMIN_DASH_URL);
                    
                    if (window.showToast) {
                        showToast(message, 'info');
                    }
                }
                prevAdminTotal = data.total;
            } catch {}
        }

        async function checkUnread() {
            const isOnChat = window.location.pathname.startsWith('/chat');
            try {
                const res    = await fetch(UNREAD_URL);
                const counts = await res.json();
                const total = Object.values(counts).reduce((s, v) => s + Number(v), 0);

                updateBadge(total);
                updateTitle(total);

                if (prevTotal === null) { prevTotal = total; return; }

                if (total > prevTotal && !isOnChat) {
                    playBeep();
                    showNotification('💬 BossGroupHub — Pesan Baru', `Anda memiliki ${total} pesan belum dibaca`, CHAT_URL);
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
