<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">💬 Chat</h2>
    </x-slot>

    <div class="chat-wrapper-mobile">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 chat-inner-mobile">
            <x-chat-layout :users="$users" :activeUser="$user">
                <div class="chat-main">
                    @include('chat.partials.room')
                </div>
            </x-chat-layout>
        </div>
    </div>

    {{-- Lightbox --}}
    <div id="lightbox" onclick="closeLightbox()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.85);z-index:9999;cursor:zoom-out;align-items:center;justify-content:center;">
        <img id="lightbox-img" src="" alt=""
            style="max-width:90vw;max-height:90vh;border-radius:12px;box-shadow:0 8px 40px rgba(0,0,0,.5);">
    </div>

    <style>
        @keyframes typingBounce {

            0%,
            60%,
            100% {
                transform: translateY(0);
                opacity: .5;
            }

            30% {
                transform: translateY(-5px);
                opacity: 1;
            }
        }
    </style>

    <script>
        // Lightbox helpers
        window.openLightbox = src => {
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox').style.display = 'flex';
        };
        window.closeLightbox = () => {
            document.getElementById('lightbox').style.display = 'none';
        };
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeLightbox();
                if (document.getElementById('clear-modal')) document.getElementById('clear-modal').style.display = 'none';
            }
        });
    </script>
</x-app-layout>