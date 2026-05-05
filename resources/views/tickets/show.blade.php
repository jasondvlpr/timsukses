<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Detail Keluhan: ') }} {{ $ticket->subject }}
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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-2xl overflow-hidden border border-slate-200">
                <!-- Ticket Info -->
                <div class="p-8 bg-slate-50 border-b border-slate-200">
                    <p class="text-xs font-bold text-slate-500 uppercase mb-3 tracking-wider">Deskripsi Masalah</p>
                    <div class="text-slate-800 leading-relaxed mb-6">
                        {!! nl2br(e($ticket->description)) !!}
                    </div>
                    
                    @if($ticket->attachment)
                        <div class="mb-6">
                            <p class="text-xs font-bold text-slate-500 uppercase mb-2 tracking-wider">Lampiran / Screenshot</p>
                            <a href="{{ Storage::url($ticket->attachment) }}" target="_blank" class="inline-block group relative">
                                <img src="{{ Storage::url($ticket->attachment) }}" alt="Attachment" class="max-w-md rounded-lg shadow-md border border-slate-200 hover:opacity-90 transition">
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition bg-black/20 rounded-lg">
                                    <span class="bg-white px-3 py-1 rounded-full text-xs font-bold text-slate-900 shadow-lg">Klik untuk Zoom</span>
                                </div>
                            </a>
                        </div>
                    @endif

                    <div class="flex flex-wrap gap-3">
                        @if($ticket->website)
                            <div class="p-3 bg-blue-50 rounded-lg inline-flex items-center gap-2 border border-blue-100">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-1.343 3-3s-1.343-3-3-3m0 12c-1.657 0-3-1.343-3-3s1.343-3 3-3m0 0V3m0 18v-3"></path></svg>
                                <span class="text-xs font-bold text-blue-700">{{ $ticket->website->name }}</span>
                            </div>
                        @endif
                        <div class="p-3 bg-slate-100 rounded-lg inline-flex items-center gap-2 border border-slate-200">
                            <span class="text-[10px] font-bold text-slate-400 uppercase">Prioritas:</span>
                            <span class="text-xs font-bold {{ $ticket->priority == 'high' ? 'text-red-600' : ($ticket->priority == 'medium' ? 'text-yellow-600' : 'text-blue-600') }}">
                                {{ strtoupper($ticket->priority) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Chat History -->
                <div class="p-8 space-y-6 min-h-[200px]">
                    @forelse($ticket->messages as $message)
                        <div class="flex {{ $message->is_admin_reply ? 'justify-start' : 'justify-end' }}">
                            <div class="max-w-[85%] p-4 rounded-2xl shadow-lg {{ $message->is_admin_reply ? 'rounded-tl-none' : 'bg-blue-50 text-slate-800 border border-blue-100 rounded-tr-none' }}"
                                 style="{{ $message->is_admin_reply ? 'background-color: #1e293b !important; color: #ffffff !important;' : '' }}">
                                <div class="flex items-center gap-2 mb-1">
                                    @if($message->is_admin_reply)
                                        <span class="bg-blue-600 text-[8px] px-1 rounded text-white font-bold uppercase tracking-tighter">Support Team</span>
                                    @endif
                                    <p class="text-xs font-bold {{ $message->is_admin_reply ? 'text-slate-300' : 'text-slate-600' }}"
                                       style="{{ $message->is_admin_reply ? 'color: #cbd5e1 !important;' : '' }}">{{ $message->user->name }}</p>
                                </div>
                                <p class="text-sm leading-relaxed" style="{{ $message->is_admin_reply ? 'color: #ffffff !important;' : '' }}">
                                    {!! nl2br(e($message->message)) !!}
                                </p>
                                <p class="text-[9px] mt-2 opacity-50 flex items-center gap-1" style="{{ $message->is_admin_reply ? 'color: #ffffff !important; opacity: 0.6;' : '' }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message->created_at->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-slate-400 text-sm">Belum ada balasan untuk keluhan ini.</p>
                        </div>
                    @endforelse
                </div>

                <!-- New Message for Promoter -->
                @if($ticket->status != 'closed')
                    <div class="p-6 bg-slate-50 border-t border-slate-200">
                        <form action="{{ route('tickets.message', $ticket) }}" method="POST">
                            @csrf
                            <div class="flex gap-4">
                                <textarea name="message" rows="1" class="flex-1 border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 py-3 px-4 transition" placeholder="Ketik balasan Anda..." required></textarea>
                                <button type="submit" class="btn-primary px-8 py-2 rounded-xl font-bold shadow-lg">Kirim</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
            
            <div class="mt-6 flex justify-center">
                <a href="{{ route('tickets.index') }}" class="text-slate-400 hover:text-slate-600 text-sm font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Daftar Keluhan
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
