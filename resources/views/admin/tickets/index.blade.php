<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Manajemen Semua Laporan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Keluhan</h3>
                    <form action="{{ route('admin.tickets') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="relative">
                            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari subjek, nomor, atau nama..." class="px-4 py-2 text-sm border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-64 transition-all" />
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="status" class="text-xs font-bold text-slate-500 uppercase whitespace-nowrap">Status:</label>
                            <select name="status" id="status" onchange="this.form.submit()" class="text-sm font-bold text-slate-700 border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="all" {{ $status == 'all' ? 'selected' : '' }}>SEMUA</option>
                                <option value="open" {{ $status == 'open' ? 'selected' : '' }}>OPEN</option>
                                <option value="in_progress" {{ $status == 'in_progress' ? 'selected' : '' }}>PROSES</option>
                                <option value="resolved" {{ $status == 'resolved' ? 'selected' : '' }}>RESOLVED</option>
                                <option value="closed" {{ $status == 'closed' ? 'selected' : '' }}>CLOSED</option>
                            </select>
                        </div>
                        <button type="submit" class="hidden sm:block bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-slate-700 transition">Cari</button>
                    </form>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No. Tiket</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Promotor</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Subjek</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Dikirim</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Assignee</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 bg-slate-50 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($tickets as $ticket)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">{{ $ticket->ticket_number ?? 'TKT-OLD' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-[10px]">
                                                    {{ strtoupper(substr($ticket->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-slate-900">{{ $ticket->user->name }}</div>
                                                    <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $ticket->website->name ?? 'Umum' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-slate-900">{{ $ticket->subject }}</div>
                                            <div class="text-[10px] font-bold {{ $ticket->priority == 'high' ? 'text-red-500' : ($ticket->priority == 'medium' ? 'text-yellow-500' : 'text-blue-500') }} uppercase">
                                                {{ $ticket->priority }} Priority
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-slate-700">{{ $ticket->created_at->format('d M Y') }}</div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $ticket->created_at->format('H:i') }} WIB</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($ticket->assignedTo)
                                                <div class="flex items-center gap-1">
                                                    <div class="w-5 h-5 bg-indigo-100 rounded-full flex items-center justify-center text-[10px] font-bold text-indigo-700">{{ substr($ticket->assignedTo->name, 0, 1) }}</div>
                                                    <span class="text-xs font-bold text-slate-700">{{ $ticket->assignedTo->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-[10px] font-bold text-slate-300 italic uppercase">Belum Assign</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
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
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('admin.tickets.show', $ticket) }}" class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-900 font-bold text-xs bg-indigo-50 px-3 py-1.5 rounded-lg transition">
                                                Buka Tiket
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center text-slate-500 font-medium">Belum ada keluhan masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
