<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Tanggapi Keluhan: ') }} {{ $ticket->subject }}
            </h2>
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-slate-400">#{{ $ticket->ticket_number }}</span>
                <span class="px-3 py-1 text-xs font-bold rounded-full 
                    @if($ticket->status == 'open') bg-yellow-100 text-yellow-700
                    @elseif($ticket->status == 'in_progress') bg-blue-100 text-blue-700
                    @elseif($ticket->status == 'resolved') bg-green-100 text-green-700
                    @else bg-slate-100 text-slate-600 @endif">
                    @if($ticket->status == 'in_progress')
                        Sedang Diproses
                    @else
                        {{ strtoupper($ticket->status) }}
                    @endif
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="p-4 bg-green-50 text-green-700 rounded-lg shadow-sm border border-green-200 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-2xl overflow-hidden border border-slate-200">
                <!-- Original Description -->
                <div class="p-8 bg-slate-50 border-b border-slate-200">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-800 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($ticket->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $ticket->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $ticket->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                        @if($ticket->assignedTo)
                            <div class="flex flex-col items-end">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Ditangani Oleh:</span>
                                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">{{ $ticket->assignedTo->name }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="text-slate-800 leading-relaxed mb-6">
                        {!! nl2br(e($ticket->description)) !!}
                    </div>

                    @if($ticket->attachment)
                        <div class="mb-6">
                            <p class="text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Lampiran dari Promotor</p>
                            <a href="{{ Storage::url($ticket->attachment) }}" target="_blank" class="inline-block group relative">
                                <img src="{{ Storage::url($ticket->attachment) }}" alt="Attachment" class="max-w-md rounded-lg shadow-md border border-slate-200 hover:opacity-90 transition">
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition bg-black/20 rounded-lg">
                                    <span class="bg-white px-3 py-1 rounded-full text-xs font-bold text-slate-900 shadow-lg">Klik untuk Zoom</span>
                                </div>
                            </a>
                        </div>
                    @endif 

                    @if($ticket->website)
                        <div class="p-3 bg-blue-50 rounded-lg inline-flex items-center gap-2 border border-blue-100">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-1.343 3-3s-1.343-3-3-3m0 12c-1.657 0-3-1.343-3-3s1.343-3 3-3m0 0V3m0 18v-3"></path></svg>
                            <span class="text-xs font-bold text-blue-700">{{ $ticket->website->name }} ({{ $ticket->website->url }})</span>
                        </div>
                    @endif
                </div>

                <!-- Forward Ticket Section (Staff & Admin) -->
                <div class="px-8 py-4 bg-indigo-50 border-b border-indigo-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        <span class="text-sm font-bold text-indigo-900">Teruskan Keluhan Ini</span>
                    </div>
                    <form action="{{ route('admin.tickets.forward', $ticket) }}" method="POST" class="flex items-center gap-2 w-full sm:w-auto">
                        @csrf
                        <select name="assigned_to_id" class="text-xs border-indigo-200 rounded-lg focus:ring-indigo-500 w-full sm:w-48" required>
                            <option value="">-- Pilih Admin/Staff --</option>
                            @foreach($backofficeUsers as $boUser)
                                <option value="{{ $boUser->id }}" {{ $ticket->assigned_to_id == $boUser->id ? 'selected' : '' }}>
                                    {{ $boUser->name }} ({{ strtoupper($boUser->role) }})
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-indigo-700 transition shadow-sm whitespace-nowrap">Teruskan</button>
                    </form>
                </div>

                <!-- Messages Thread -->
                <div class="p-8 space-y-6 min-h-[150px]">
                    @foreach($ticket->messages as $message)
                        <div class="flex {{ $message->is_admin_reply ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[85%] p-4 rounded-2xl shadow-lg {{ $message->is_admin_reply ? 'rounded-tr-none' : 'bg-slate-100 text-slate-800 border border-slate-200 rounded-tl-none' }}" 
                                 style="{{ $message->is_admin_reply ? 'background-color: #1e293b !important; color: #ffffff !important;' : '' }}">
                                <p class="text-xs font-bold mb-1 opacity-75" style="{{ $message->is_admin_reply ? 'color: #ffffff !important;' : '' }}">{{ $message->user->name }}</p>
                                <p class="text-sm leading-relaxed {{ str_starts_with($message->message, '---') ? 'italic text-indigo-300' : '' }}" 
                                   style="{{ $message->is_admin_reply && !str_starts_with($message->message, '---') ? 'color: #ffffff !important;' : '' }}">
                                    {!! nl2br(e($message->message)) !!}
                                </p>
                                <p class="text-[9px] mt-2 opacity-50" style="{{ $message->is_admin_reply ? 'color: #ffffff !important; opacity: 0.6;' : '' }}">{{ $message->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Reply Form -->
                <div class="p-8 bg-slate-50 border-t border-slate-200">
                    <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tanggapan Admin/Staff</label>
                            <textarea name="message" rows="4" class="w-full border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Tulis balasan Anda..." required></textarea>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <label class="text-sm font-bold text-slate-700 whitespace-nowrap">Status Tiket:</label>
                                <select name="status" class="text-sm border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>Sedang Diproses</option>
                                    <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-primary px-8 py-2 rounded-xl font-bold shadow-lg w-full sm:w-auto">Kirim Balasan</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-6 flex justify-center">
                <a href="{{ route('admin.tickets') }}" class="text-slate-400 hover:text-slate-600 text-sm font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar Semua Laporan
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
