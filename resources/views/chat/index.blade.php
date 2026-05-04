<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">💬 Chat</h2>
    </x-slot>

    <div class="chat-wrapper-mobile">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 chat-inner-mobile">
            <x-chat-layout :users="$users">
                <div class="chat-empty">
                    <svg width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"
                        style="color:#c7d2fe">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <div style="text-align:center">
                        <p style="font-size:16px;font-weight:700;color:#475569;margin:0">Pilih Percakapan</p>
                        <p style="font-size:13px;color:#94a3b8;margin-top:6px;max-width:240px;line-height:1.5">
                            Pilih kontak di sebelah kiri untuk mulai chat
                        </p>
                    </div>
                </div>
            </x-chat-layout>
        </div>
    </div>
</x-app-layout>