<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Laporan') }}
            </h2>
            <a href="{{ route('tickets.create') }}" class="btn-primary flex items-center gap-2 px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Kirim Laporan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-2xl shadow-sm border border-emerald-100 flex items-center gap-3">
                    <div class="bg-emerald-500 text-white p-1 rounded-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800">Tiket Laporan Saya</h3>
                    <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">{{ $tickets->total() }} Total</span>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No. Tiket</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Detail Laporan</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Prioritas</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tgl Dibuat</th>
                                    <th class="px-6 py-4 bg-slate-50 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($tickets as $ticket)
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">{{ $ticket->ticket_number ?? 'TKT-OLD' }}</td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-slate-900 mb-0.5">{{ $ticket->subject }}</div>
                                            <div class="flex items-center gap-1.5 text-[11px] text-slate-500 font-medium">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                                {{ $ticket->website->name ?? 'Umum' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-wider
                                                {{ $ticket->priority == 'high' ? 'bg-rose-100 text-rose-700' : ($ticket->priority == 'medium' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                                                {{ $ticket->priority }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-wider
                                                @if($ticket->status == 'open') bg-slate-100 text-slate-600
                                                @elseif($ticket->status == 'in_progress') bg-blue-100 text-blue-700
                                                @elseif($ticket->status == 'resolved') bg-emerald-100 text-emerald-700
                                                @else bg-slate-100 text-slate-400 @endif">
                                                @if($ticket->status == 'in_progress')
                                                    DIPROSES
                                                @else
                                                    {{ $ticket->status }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-[11px] font-bold text-slate-400">
                                            {{ $ticket->created_at->format('d M Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-slate-100 hover:bg-indigo-600 text-slate-700 hover:text-white rounded-lg font-bold text-xs transition-all">
                                                Detail
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                                </div>
                                                <p class="text-slate-500 font-medium italic">Belum ada tiket laporan yang dikirim.</p>
                                                <a href="{{ route('tickets.create') }}" class="btn-primary mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-xl shadow-lg shadow-indigo-200 text-sm">
                                                    + Buat Laporan Baru
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-6">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
