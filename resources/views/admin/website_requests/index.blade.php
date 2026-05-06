<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Persetujuan Website Baru (Admin)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg shadow-sm border border-green-200 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Pengajuan</h3>
                    <form action="{{ route('admin.website-requests') }}" method="GET" class="flex flex-wrap items-center gap-4">
                        <div class="relative">
                            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama, url, atau promotor..." 
                                   class="px-4 py-2 text-sm border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-64 transition-all" />
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="status" class="text-xs font-bold text-slate-500 uppercase whitespace-nowrap">Status:</label>
                            <select name="status" id="status" onchange="this.form.submit()" class="text-sm font-bold text-slate-700 border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="unapproved" {{ $status == 'unapproved' ? 'selected' : '' }}>BELUM APPROVE</option>
                                <option value="all" {{ $status == 'all' ? 'selected' : '' }}>SEMUA STATUS</option>
                                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>PENDING</option>
                                <option value="processing" {{ $status == 'processing' ? 'selected' : '' }}>PROCESSING</option>
                                <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>APPROVED</option>
                                <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>REJECTED</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="assigned_to" class="text-xs font-bold text-slate-500 uppercase whitespace-nowrap">Penugasan:</label>
                            <select name="assigned_to" id="assigned_to" onchange="this.form.submit()" class="text-sm font-bold text-slate-700 border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="all" {{ $assignedTo == 'all' ? 'selected' : '' }}>SEMUA</option>
                                <option value="me" {{ $assignedTo == 'me' ? 'selected' : '' }}>TUGAS SAYA</option>
                                <option value="none" {{ $assignedTo == 'none' ? 'selected' : '' }}>BELUM DITUGASKAN</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-slate-700 transition">Filter</button>
                    </form>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No. Pengajuan</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Promotor</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Detail Website</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Petugas (Staff)</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Dikirim</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 bg-slate-50 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($requests as $request)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">{{ $request->ticket_number ?? 'REQ-OLD' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-[10px]">
                                                    {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-slate-900">{{ $request->user->name }}</div>
                                                    <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $request->user->username }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-slate-900">{{ $request->name }}</div>
                                            <div class="text-xs text-slate-400 mb-1">Ref: <a href="{{ $request->url }}" target="_blank" class="text-blue-600 hover:underline">{{ $request->url }}</a></div>
                                            @if($request->description)
                                                <div class="text-xs text-slate-500 italic bg-slate-50 p-2 rounded border border-slate-100 mt-1">
                                                    "{{ $request->description }}"
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($request->assigned_to_id)
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 text-[9px] font-bold">
                                                        {{ strtoupper(substr($request->assignedTo->name, 0, 1)) }}
                                                    </div>
                                                    <span class="text-xs font-bold text-slate-700">{{ $request->assignedTo->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-[10px] font-bold text-rose-500 bg-rose-50 px-2 py-1 rounded italic uppercase">Belum Ditugaskan</span>
                                            @endif

                                            @if(auth()->user()->isAdmin())
                                                <div class="mt-2">
                                                    <form action="{{ route('admin.website-requests.assign', $request) }}" method="POST" class="flex items-center gap-1">
                                                        @csrf
                                                        <select name="assigned_to_id" class="text-[10px] border-slate-200 rounded p-1 w-24">
                                                            <option value="">Pilih Staff</option>
                                                            @foreach($backofficeUsers as $staff)
                                                                <option value="{{ $staff->id }}" {{ $request->assigned_to_id == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit" class="p-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif(!$request->assigned_to_id)
                                                <form action="{{ route('admin.website-requests.assign', $request) }}" method="POST" class="mt-2">
                                                    @csrf
                                                    <input type="hidden" name="assigned_to_id" value="{{ auth()->id() }}">
                                                    <button type="submit" class="text-[10px] font-bold text-white bg-indigo-600 px-2 py-1 rounded hover:bg-indigo-700 transition">Ambil Tugas</button>
                                                </form>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-slate-700">{{ $request->created_at->format('d M Y') }}</div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $request->created_at->format('H:i') }} WIB</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 text-xs font-bold rounded-full 
                                                @if($request->status == 'pending') bg-slate-100 text-slate-600 
                                                @elseif($request->status == 'processing') bg-blue-100 text-blue-700
                                                @elseif($request->status == 'approved') bg-green-100 text-green-700 
                                                @else bg-red-100 text-red-700 @endif">
                                                @if($request->status == 'processing')
                                                    Sedang Diproses
                                                @else
                                                    {{ strtoupper($request->status) }}
                                                @endif
                                            </span>
                                            @if($request->admin_note && $request->status != 'pending' && $request->status != 'processing')
                                                <div class="mt-2 text-xs text-slate-500 bg-slate-50 p-2 rounded border border-slate-100">
                                                    <strong>Ket Admin:</strong> {{ $request->admin_note }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if($request->status == 'pending')
                                                <div class="flex justify-end gap-2">
                                                    @if($request->assigned_to_id == auth()->id() || auth()->user()->isAdmin())
                                                        <form action="{{ route('admin.website-requests.process', $request) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn-primary px-3 py-1 text-xs bg-blue-600 hover:bg-blue-700 border-none">Proses</button>
                                                        </form>
                                                    @endif
                                                    <button onclick="document.getElementById('reject-form-{{ $request->id }}').classList.toggle('hidden'); document.getElementById('approve-form-{{ $request->id }}').classList.add('hidden');" class="btn-danger px-3 py-1 text-xs">Tolak</button>
                                                </div>
                                            @elseif($request->status == 'processing')
                                                <div class="flex justify-end gap-2">
                                                    @if($request->assigned_to_id == auth()->id() || auth()->user()->isAdmin())
                                                        <button onclick="document.getElementById('approve-form-{{ $request->id }}').classList.toggle('hidden'); document.getElementById('reject-form-{{ $request->id }}').classList.add('hidden');" class="btn-primary px-3 py-1 text-xs bg-green-600 hover:bg-green-700 border-none">Setujui & Selesaikan</button>
                                                    @endif
                                                    <button onclick="document.getElementById('reject-form-{{ $request->id }}').classList.toggle('hidden'); document.getElementById('approve-form-{{ $request->id }}').classList.add('hidden');" class="btn-danger px-3 py-1 text-xs">Tolak</button>
                                                </div>
                                            @else
                                                <span class="text-slate-400 text-xs font-medium bg-slate-100 px-3 py-1 rounded-full">Selesai</span>
                                            @endif
                                            
                                            <!-- Approve Form -->
                                            <div id="approve-form-{{ $request->id }}" class="hidden mt-4 text-left p-4 bg-green-50 rounded-lg border border-green-200">
                                                <form id="approve-form-el-{{ $request->id }}" action="{{ route('admin.website-requests.approve', $request) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="send_whatsapp" class="wa-input" value="no">
                                                    <div class="mb-3">
                                                        <label class="block text-xs font-bold text-green-800 mb-1">Final URL / Link Website</label>
                                                        <input type="url" name="url" value="{{ $request->url }}" class="w-full text-sm border-green-300 focus:ring-green-500 focus:border-green-500 rounded-md" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="flex justify-between items-center mb-1">
                                                            <label class="block text-xs font-bold text-green-800">Keterangan / Info Akses Web</label>
                                                            <div class="relative inline-block text-left" x-data="{ open: false }">
                                                                <button type="button" @click="open = !open" class="text-[9px] font-bold text-green-700 bg-green-100 px-1.5 py-0.5 rounded flex items-center gap-1">
                                                                    Quick Reply
                                                                </button>
                                                                <div x-show="open" @click.away="open = false" class="absolute right-0 bottom-full mb-1 w-48 rounded-lg bg-white shadow-xl border border-slate-100 py-1 z-50">
                                                                    @foreach($quickReplies as $reply)
                                                                        <button type="button" onclick="insertQuickReply(this, '{{ addslashes($reply->content) }}')" @click="open = false" class="w-full text-left px-3 py-1.5 text-[10px] text-slate-600 hover:bg-slate-50 transition border-b border-slate-50 last:border-0">
                                                                            <span class="font-bold block">{{ $reply->title }}</span>
                                                                        </button>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                         </div>
                                                        <textarea name="admin_note" class="w-full text-sm border-green-300 focus:ring-green-500 focus:border-green-500 rounded-md" placeholder="Contoh: Username: admin, Password: 123" required></textarea>
                                                    </div>
                                                    <div class="flex justify-end">
                                                        <button type="button" onclick="document.getElementById('approve-form-{{ $request->id }}').classList.add('hidden')" class="mr-2 text-xs text-slate-500 font-medium">Batal</button>
                                                        <button type="button" onclick="confirmWA('approve-form-el-{{ $request->id }}', '{{ $request->user->name }}')" class="btn-primary px-3 py-1 text-xs bg-green-600 hover:bg-green-700 border-none">Simpan & Setujui</button>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- Reject Form -->
                                            <div id="reject-form-{{ $request->id }}" class="hidden mt-4 text-left p-4 bg-slate-50 rounded-lg border border-slate-200">
                                                <form id="reject-form-el-{{ $request->id }}" action="{{ route('admin.website-requests.reject', $request) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="send_whatsapp" class="wa-input" value="no">
                                                    <div class="flex justify-between items-center mb-1">
                                                        <label class="block text-xs font-bold text-slate-700">Alasan Penolakan</label>
                                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                                            <button type="button" @click="open = !open" class="text-[9px] font-bold text-slate-600 bg-slate-200 px-1.5 py-0.5 rounded flex items-center gap-1">
                                                                Quick Reply
                                                            </button>
                                                            <div x-show="open" @click.away="open = false" class="absolute right-0 bottom-full mb-1 w-48 rounded-lg bg-white shadow-xl border border-slate-100 py-1 z-50">
                                                                @foreach($quickReplies as $reply)
                                                                    <button type="button" onclick="insertQuickReply(this, '{{ addslashes($reply->content) }}')" @click="open = false" class="w-full text-left px-3 py-1.5 text-[10px] text-slate-600 hover:bg-slate-50 transition border-b border-slate-50 last:border-0">
                                                                        <span class="font-bold block">{{ $reply->title }}</span>
                                                                    </button>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                     </div>
                                                    <textarea name="admin_note" class="w-full text-sm border-slate-300 focus:ring-blue-500 focus:border-blue-500 rounded-md mb-3" placeholder="Alasan penolakan..." required></textarea>
                                                    <div class="flex justify-end">
                                                        <button type="button" onclick="document.getElementById('reject-form-{{ $request->id }}').classList.add('hidden')" class="mr-2 text-xs text-slate-500 font-medium">Batal</button>
                                                        <button type="button" onclick="confirmWA('reject-form-el-{{ $request->id }}', '{{ $request->user->name }}')" class="btn-danger text-xs px-3 py-1">Kirim Penolakan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-slate-500 font-medium">Belum ada pengajuan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                {{ $requests->links() }}
            </div>
        </div>
    </div>

    <!-- Modal WhatsApp Confirmation -->
    <div id="wa-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-[2px] transition-opacity" aria-hidden="true" onclick="closeWAModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-3xl text-center overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full border border-slate-100 p-8">
                <div class="mb-6">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-50 mb-4">
                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2" id="modal-title">Kirim ke WhatsApp?</h3>
                    <p class="text-sm text-slate-500 leading-relaxed px-2">
                        Apakah Anda ingin mengirimkan notifikasi pengajuan ini langsung ke WhatsApp promotor <span class="font-bold text-slate-700" id="wa-user-name"></span>?
                    </p>
                </div>
                <div class="flex flex-col gap-3">
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" onclick="submitWithWA('yes')" class="inline-flex justify-center items-center rounded-xl px-4 py-3 bg-green-600 text-[13px] font-bold text-white hover:bg-green-700 shadow-md shadow-green-100 transition-all active:scale-95">
                            Ya, Kirim WA
                        </button>
                        <button type="button" onclick="submitWithWA('no')" class="inline-flex justify-center items-center rounded-xl px-4 py-3 bg-white text-[13px] font-bold text-slate-700 border border-slate-200 hover:bg-slate-50 transition-all active:scale-95">
                            Hanya Balas
                        </button>
                    </div>
                    <button type="button" onclick="closeWAModal()" class="w-full py-2 text-[11px] font-bold text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-wider">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentForm = null;

        function confirmWA(formId, userName) {
            const form = document.getElementById(formId);
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            currentForm = form;
            document.getElementById('wa-user-name').textContent = '(' + userName + ')';
            document.getElementById('wa-modal').classList.remove('hidden');
        }

        function closeWAModal() {
            document.getElementById('wa-modal').classList.add('hidden');
        }

        function submitWithWA(value) {
            if (currentForm) {
                currentForm.querySelector('.wa-input').value = value;
                closeWAModal();
                currentForm.submit();
            }
        }

        function insertQuickReply(btn, content) {
            const container = btn.closest('.mb-3') || btn.closest('form');
            const textarea = container.querySelector('textarea');
            if (textarea) {
                textarea.value = content;
                textarea.focus();
            }
        }
    </script>
</x-app-layout>
