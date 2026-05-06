<style>
    .forward-btn { position: absolute; right: 8px; top: 8px; opacity: 0; transition: all .2s; border: none; background: rgba(255,255,255,0.9); color: #6366f1; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); cursor: pointer; z-index: 10; }
    .bubble-me:hover .forward-btn, .bubble-other:hover .forward-btn { opacity: 1; }
    .bubble-other .forward-btn { left: 8px; right: auto; color: #64748b; }
    .bubble-me, .bubble-other { position: relative !important; }
</style>
{{-- Header --}}
<div class="chat-head">
    <button onclick="closeChatRoom()" class="mobile-back-btn">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
    </button>
    <div class="chat-head-info" style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">
        <div class="chat-avatar {{ $user->role }} chat-head-avatar-mobile"
            style="width:44px;height:44px;font-size:15px;position:relative;flex-shrink:0;">
            {{ strtoupper(substr($user->name, 0, 1)) }}
            <span class="online-dot {{ $user->isOnline() ? 'on' : 'off' }}"
                style="border-color:#fff;"></span>
        </div>
        <div class="chat-head-text-mobile" style="flex:1;min-width:0">
            <div class="chat-head-name">{{ $user->name }}</div>
            <div class="chat-head-status {{ $user->isOnline() ? 'online' : 'offline' }}">
                <span
                    style="width:7px;height:7px;border-radius:50%;background:{{ $user->isOnline() ? '#16a34a' : '#94a3b8' }};display:inline-block;flex-shrink:0;"></span>
                @if($user->isOnline())
                    <span class="status-text">Sedang Online</span>
                @elseif($user->last_active_at)
                    <span class="status-text">Terakhir aktif
                        {{ $user->last_active_at->diffForHumans() }}</span>
                @else
                    <span class="status-text">Belum pernah aktif</span>
                @endif
            </div>
        </div>
    </div>
    @if($user->whatsapp)
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->whatsapp) }}" target="_blank"
            class="chat-wa-btn">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
            </svg>
            WhatsApp
        </a>
    @endif

    {{-- Clear Chat Button --}}
    <button onclick="document.getElementById('clear-modal').style.display='flex'"
        title="Hapus Riwayat Chat"
        style="width:36px;height:36px;border-radius:50%;border:1.5px solid #fee2e2;background:#fff5f5;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#ef4444;transition:all .15s;flex-shrink:0;"
        onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fff5f5'">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
    </button>
</div>

{{-- ═══ Clear Chat Confirmation Modal ═══ --}}
<div id="clear-modal"
    style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.55);z-index:9998;align-items:center;justify-content:center;backdrop-filter:blur(3px);"
    onclick="if(event.target===this) this.style.display='none'">
    <div
        style="background:#fff;border-radius:20px;padding:32px;max-width:400px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.2);text-align:center;">
        <div
            style="width:56px;height:56px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
            <svg width="26" height="26" fill="none" stroke="#ef4444" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>
        <h3 style="font-size:17px;font-weight:700;color:#0f172a;margin:0 0 8px;">Hapus Riwayat Chat?
        </h3>
        <p style="font-size:13px;color:#64748b;margin:0 0 24px;line-height:1.6;">
            Semua pesan dengan <strong>{{ $user->name }}</strong> akan dihapus secara permanen,
            termasuk gambar yang dikirim. Tindakan ini <strong>tidak dapat dibatalkan</strong>.
        </p>
        <div style="display:flex;gap:10px;justify-content:center;">
            <button onclick="document.getElementById('clear-modal').style.display='none'"
                style="flex:1;padding:10px 20px;border-radius:12px;border:1.5px solid #e2e8f0;background:#f8fafc;color:#475569;font-size:14px;font-weight:600;cursor:pointer;transition:.15s;"
                onmouseover="this.style.background='#e2e8f0'"
                onmouseout="this.style.background='#f8fafc'">
                Batal
            </button>
            <button id="confirm-clear-btn" onclick="confirmClear()"
                style="flex:1;padding:10px 20px;border-radius:12px;border:none;background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;font-size:14px;font-weight:600;cursor:pointer;transition:.15s;box-shadow:0 2px 8px rgba(239,68,68,.3);"
                onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">
                🗑️ Hapus Semua
            </button>
        </div>
    </div>
</div>


{{-- Messages --}}
<div id="chat-messages" class="chat-messages">
    @forelse($messages as $msg)
        @if($msg->user_id === auth()->id())
            <div class="msg-row-me" data-msg-id="{{ $msg->id }}">
                <div>
                    <div class="bubble-me" style="position:relative">
                        @if($msg->image)
                            <img src="{{ asset('storage/' . $msg->image) }}" alt="Gambar"
                                onclick="openLightbox(this.src)"
                                style="max-width:240px;max-height:200px;border-radius:10px;display:block;cursor:zoom-in;margin-bottom:{{ $msg->message ? '8px' : '0' }}">
                        @endif
                        @if($msg->message)
                            <p class="bubble-text">{{ $msg->message }}</p>
                        @endif
                        <button onclick="openForwardModal({{ $msg->id }}, '{{ addslashes($msg->message) }}')" class="forward-btn" title="Teruskan Pesan">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                    <div class="bubble-time"
                        style="display:flex;align-items:center;justify-content:flex-end;gap:3px;">
                        {{ $msg->created_at->format('H:i') }}
                        {{-- Read receipt tick --}}
                        @if($msg->is_read)
                            <span class="read-tick" data-id="{{ $msg->id }}" title="Sudah dibaca">
                                <svg width="16" height="10" viewBox="0 0 16 10" fill="none"
                                    style="display:inline-block;">
                                    <path d="M1 5L4 8L9 1" stroke="#6366f1" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M5 5L8 8L15 1" stroke="#6366f1" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        @else
                            <span class="read-tick" data-id="{{ $msg->id }}" title="Terkirim">
                                <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                    style="display:inline-block;">
                                    <path d="M1 5L3.5 7.5L9 2" stroke="#94a3b8" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="msg-row-other" data-msg-id="{{ $msg->id }}">
                <div class="mini-avatar"
                    style="background:{{ $msg->sender->isAdmin() ? 'linear-gradient(135deg,#8b5cf6,#7c3aed)' : ($msg->sender->isStaff() ? 'linear-gradient(135deg,#3b82f6,#2563eb)' : 'linear-gradient(135deg,#64748b,#475569)') }}">
                    {{ strtoupper(substr($msg->sender->name, 0, 1)) }}
                </div>
                <div>
                    <div class="bubble-other" style="position:relative">
                        @if($msg->image)
                            <img src="{{ asset('storage/' . $msg->image) }}" alt="Gambar"
                                onclick="openLightbox(this.src)"
                                style="max-width:240px;max-height:200px;border-radius:10px;display:block;cursor:zoom-in;margin-bottom:{{ $msg->message ? '8px' : '0' }}">
                        @endif
                        @if($msg->message)
                            <p class="bubble-text">{{ $msg->message }}</p>
                        @endif
                        <button onclick="openForwardModal({{ $msg->id }}, '{{ addslashes($msg->message) }}')" class="forward-btn" title="Teruskan Pesan">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                    <div class="bubble-time">{{ $msg->created_at->format('H:i') }}</div>
                </div>
            </div>
        @endif
    @empty
        <div id="empty-state"
            style="display:flex;flex-direction:column;align-items:center;justify-content:center;flex:1;gap:12px;color:#94a3b8;min-height:200px;">
            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5"
                viewBox="0 0 24 24" style="opacity:.4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <div style="text-align:center;">
                <p style="font-size:14px;font-weight:600;">Belum ada pesan</p>
                <p style="font-size:12px;margin-top:4px;">Mulai percakapan dengan mengetik di bawah</p>
            </div>
        </div>
    @endforelse
</div>

{{-- Typing Indicator --}}
<div id="typing-indicator" style="display:none; padding: 8px 24px 0; flex-shrink:0;">
    <div
        style="display:inline-flex; align-items:center; gap:8px; background:#fff; border:1px solid #e2e8f0; border-radius:18px 18px 18px 4px; padding:10px 14px; box-shadow:0 1px 4px rgba(0,0,0,.05);">
        <div style="display:flex;gap:4px;align-items:center;">
            <span
                style="width:7px;height:7px;border-radius:50%;background:#94a3b8;display:inline-block;animation:typingBounce 1.2s infinite ease-in-out;"></span>
            <span
                style="width:7px;height:7px;border-radius:50%;background:#94a3b8;display:inline-block;animation:typingBounce 1.2s infinite ease-in-out .2s;"></span>
            <span
                style="width:7px;height:7px;border-radius:50%;background:#94a3b8;display:inline-block;animation:typingBounce 1.2s infinite ease-in-out .4s;"></span>
        </div>
        <span style="font-size:11px;color:#94a3b8;font-style:italic;">{{ $user->name }} sedang
            mengetik...</span>
    </div>
</div>

{{-- Image Preview Bar --}}
<div id="img-preview-bar"
    style="display:none; padding:8px 20px 0; flex-shrink:0; background:#fff; border-top:1px solid #e2e8f0;">
    <div
        style="display:flex;align-items:center;gap:10px;background:#f8fafc;border:1px dashed #c7d2fe;border-radius:12px;padding:8px 12px;">
        <img id="img-preview-thumb" src="" alt=""
            style="width:48px;height:48px;object-fit:cover;border-radius:8px;">
        <div style="flex:1;min-width:0;">
            <p id="img-preview-name"
                style="font-size:12px;font-weight:600;color:#4f46e5;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            </p>
            <p id="img-preview-size" style="font-size:11px;color:#94a3b8;margin:0;margin-top:2px;"></p>
        </div>
        <button onclick="clearImagePreview()" type="button"
            style="width:24px;height:24px;border-radius:50%;background:#fee2e2;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#ef4444;flex-shrink:0;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

{{-- Input --}}
<div class="chat-input-bar">
    <input type="file" id="img-input" accept="image/*" style="display:none">
    <button type="button" onclick="document.getElementById('img-input').click()"
        style="width:40px;height:40px;border-radius:50%;border:1.5px solid #e2e8f0;background:#f8fafc;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:background .15s;color:#6366f1;"
        title="Kirim Gambar" onmouseover="this.style.background='#eef2ff'"
        onmouseout="this.style.background='#f8fafc'">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
    </button>
    <form id="chat-form" style="display:flex;align-items:center;gap:12px;flex:1">
        @csrf
        <input type="text" id="chat-input" name="message" autocomplete="off"
            class="chat-input-field" placeholder="Tulis pesan ke {{ $user->name }}...">
        <button type="submit" class="chat-send-btn">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
            </svg>
        </button>
    </form>
</div>

{{-- ═══ Forward Message Modal ═══ --}}
<div id="forward-modal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.55);z-index:9998;align-items:center;justify-content:center;backdrop-filter:blur(3px);" onclick="if(event.target===this) this.style.display='none'">
    <div style="background:#fff;border-radius:20px;padding:24px;max-width:400px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.2);">
        <div style="display:flex;justify-content:between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:16px;font-weight:700;color:#0f172a;">Teruskan Pesan</h3>
            <button onclick="document.getElementById('forward-modal').style.display='none'" style="border:none;background:none;cursor:pointer;color:#94a3b8;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div id="forward-msg-preview" style="background:#f8fafc;padding:12px;border-radius:12px;font-size:12px;color:#64748b;margin-bottom:20px;max-height:80px;overflow:hidden;border-left:4px solid #6366f1;">
        </div>
        <div style="max-height:300px;overflow-y:auto;display:flex;flex-direction:column;gap:8px;">
            <p style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:4px;">Pilih Admin/Staff</p>
            @foreach($users->whereIn('role', ['admin', 'staff'])->where('id', '!=', auth()->id()) as $target)
                <button onclick="confirmForward({{ $target->id }}, '{{ $target->name }}')" style="display:flex;align-items:center;gap:12px;width:100%;padding:10px;border-radius:12px;border:1px solid #f1f5f9;background:#fff;cursor:pointer;text-align:left;transition:all .2s;" onmouseover="this.style.background='#f8fafc';this.style.borderColor='#e2e8f0'" onmouseout="this.style.background='#fff';this.style.borderColor='#f1f5f9'">
                    <div style="width:32px;height:32px;border-radius:50%;background:{{ $target->isAdmin() ? '#8b5cf6' : '#3b82f6' }};color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;">{{ substr($target->name, 0, 1) }}</div>
                    <div style="flex:1;">
                        <div style="font-size:13px;font-weight:600;color:#1e293b;">{{ $target->name }}</div>
                        <div style="font-size:10px;color:#94a3b8;text-transform:uppercase;">{{ $target->role }}</div>
                    </div>
                </button>
            @endforeach
        </div>
    </div>
</div>

<script>
    // Re-initialize variables because they might have been declared in global scope
    // We use a block scope or just assign to window/existing variables
    (function() {
        const sendUrl = "{{ route('chat.private.send', $user) }}";
        const pollUrl = "{{ route('chat.private.poll', $user) }}";
        const typingUrl = "{{ route('chat.private.typing', $user) }}";
        const csrf = "{{ csrf_token() }}";
        let lastId = {{ $messages->count() ? $messages->last()->id : 0 }};

        const box = document.getElementById('chat-messages');
        const form = document.getElementById('chat-form');
        const input = document.getElementById('chat-input');
        const typingEl = document.getElementById('typing-indicator');
        const imgInput = document.getElementById('img-input');
        const previewBar = document.getElementById('img-preview-bar');

        let selectedFile = null;

        const scrollDown = () => { box.scrollTop = box.scrollHeight; };
        const esc = t => { const d = document.createElement('div'); d.appendChild(document.createTextNode(t)); return d.innerHTML; };
        const avatarGrad = role => role === 'admin' ? 'linear-gradient(135deg,#8b5cf6,#7c3aed)' : role === 'staff' ? 'linear-gradient(135deg,#3b82f6,#2563eb)' : 'linear-gradient(135deg,#64748b,#475569)';

        const tickSent = `<svg width="10" height="10" viewBox="0 0 10 10" fill="none" style="display:inline-block;vertical-align:middle;" title="Terkirim"><path d="M1 5L3.5 7.5L9 2" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
        const tickRead = `<svg width="16" height="10" viewBox="0 0 16 10" fill="none" style="display:inline-block;vertical-align:middle;" title="Sudah dibaca"><path d="M1 5L4 8L9 1" stroke="#6366f1" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 5L8 8L15 1" stroke="#6366f1" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>`;

        const buildImg = url => `<img src="${url}" alt="Gambar" onclick="openLightbox(this.src)" style="max-width:240px;max-height:200px;border-radius:10px;display:block;cursor:zoom-in;margin-bottom:4px">`;

        const buildMsg = m => {
            const imgHtml = m.image ? buildImg(m.image) : '';
            const txtHtml = m.message ? `<p class="bubble-text">${esc(m.message)}</p>` : '';
            const fwdBtn = `<button onclick="openForwardModal(${m.id}, '${esc(m.message).replace(/'/g, "\\'")}')" class="forward-btn" title="Teruskan Pesan"><svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg></button>`;
            
            if (m.is_me) {
                const tick = (m.is_read) ? tickRead : tickSent;
                return `<div class="msg-row-me msg-new" data-msg-id="${m.id}"><div>
                    <div class="bubble-me" style="position:relative">${imgHtml}${txtHtml}${fwdBtn}</div>
                    <div class="bubble-time" style="display:flex;align-items:center;justify-content:flex-end;gap:3px;">
                        ${m.created_at}
                        <span class="read-tick" data-id="${m.id}">${tick}</span>
                    </div>
                </div></div>`;
            } else {
                return `<div class="msg-row-other msg-new" data-msg-id="${m.id}">
                    <div class="mini-avatar" style="background:${avatarGrad(m.role)}">${m.sender.charAt(0).toUpperCase()}</div>
                    <div><div class="bubble-other" style="position:relative">${imgHtml}${txtHtml}${fwdBtn}</div>
                    <div class="bubble-time">${m.created_at}</div></div>
                </div>`;
            }
        };

        const applyReadReceipts = readIds => {
            if (!readIds || !readIds.length) return;
            readIds.forEach(id => {
                const tick = document.querySelector(`.read-tick[data-id="${id}"]`);
                if (tick && !tick.dataset.read) {
                    tick.innerHTML = tickRead;
                    tick.dataset.read = '1';
                }
            });
        };

        imgInput.addEventListener('change', () => {
            const file = imgInput.files[0];
            if (!file) return;
            if (file.size > 5 * 1024 * 1024) { alert('Ukuran gambar maksimal 5MB'); imgInput.value = ''; return; }
            selectedFile = file;
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('img-preview-thumb').src = e.target.result;
                document.getElementById('img-preview-name').textContent = file.name;
                document.getElementById('img-preview-size').textContent = (file.size / 1024).toFixed(1) + ' KB';
                previewBar.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });

        window.clearImagePreview = () => {
            selectedFile = null;
            imgInput.value = '';
            previewBar.style.display = 'none';
        };

        form.addEventListener('submit', async e => {
            e.preventDefault();
            const msg = input.value.trim();
            if (!msg && !selectedFile) return;

            const fd = new FormData();
            fd.append('_token', csrf);
            if (msg) fd.append('message', msg);
            if (selectedFile) fd.append('image', selectedFile);

            input.value = '';
            clearImagePreview();
            input.focus();

            try {
                const r = await fetch(sendUrl, { method: 'POST', body: fd });
                const d = await r.json();
                if (d.error) { alert(d.error); return; }
                lastId = d.id;
                document.getElementById('empty-state')?.remove();
                box.insertAdjacentHTML('beforeend', buildMsg(d));
                scrollDown();
            } catch { }
        });

        let typingTimer = null;
        input.addEventListener('input', () => {
            clearTimeout(typingTimer);
            fetch(typingUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' } }).catch(() => { });
            typingTimer = setTimeout(() => { }, 1500);
        });

        let wasTyping = false;
        const poll = async () => {
            if (!document.getElementById('chat-messages')) return; // Stop polling if room changed
            try {
                const r = await fetch(`${pollUrl}?since=${lastId}`);
                const data = await r.json();
                applyReadReceipts(data.read_ids);
                const isTyping = data.typing ?? false;
                if (isTyping !== wasTyping) {
                    typingEl.style.display = isTyping ? 'block' : 'none';
                    wasTyping = isTyping;
                    if (isTyping) scrollDown();
                }
                const msgs = data.messages ?? [];
                msgs.forEach(m => {
                    if (!document.querySelector(`[data-msg-id="${m.id}"]`)) {
                        document.getElementById('empty-state')?.remove();
                        box.insertAdjacentHTML('beforeend', buildMsg(m));
                        lastId = m.id;
                    }
                });
                if (msgs.length) { typingEl.style.display = 'none'; wasTyping = false; scrollDown(); }
            } catch { }
        };

        input.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); form.dispatchEvent(new Event('submit')); }
        });

        const clearUrl = "{{ route('chat.private.clear', $user) }}";
        window.confirmClear = async () => {
            // ... (keep existing confirmClear code)
        };

        let activeForwardMsgId = null;
        window.openForwardModal = (id, text) => {
            activeForwardMsgId = id;
            document.getElementById('forward-msg-preview').textContent = text || '[Gambar]';
            document.getElementById('forward-modal').style.display = 'flex';
        };

        window.confirmForward = async (targetId, targetName) => {
            if (!activeForwardMsgId) return;
            const fwdUrl = "{{ route('chat.forward', ':id') }}".replace(':id', activeForwardMsgId);
            try {
                const r = await fetch(fwdUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' },
                    body: JSON.stringify({ receiver_id: targetId })
                });
                const d = await r.json();
                if (d.ok) {
                    document.getElementById('forward-modal').style.display = 'none';
                    showToast('✅ Pesan diteruskan ke ' + targetName);
                }
            } catch { showToast('❌ Gagal meneruskan pesan', true); }
        };

        scrollDown();
        
        // Recursive Timeout pattern (better than setInterval for production)
        // prevents multiple requests from stacking if one takes too long.
        let isPolling = false;
        const pollCycle = async () => {
            if (isPolling || !document.getElementById('chat-messages')) return;
            isPolling = true;
            await poll();
            isPolling = false;
            window.currentChatPoller = setTimeout(pollCycle, 3000);
        };
        window.currentChatPoller = setTimeout(pollCycle, 3000);

        // Only focus on desktop to avoid intrusive keyboard on mobile
        if (window.innerWidth > 768) {
            input.focus();
        }
    })();
</script>
