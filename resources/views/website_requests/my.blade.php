<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Website Saya') }}
            </h2>
            <a href="{{ route('website-requests.index') }}" class="btn-primary flex items-center gap-2 px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Ajukan Website
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Stats Summary Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Website</p>
                    <div class="flex items-end gap-2">
                        <span class="text-2xl font-black text-slate-900 leading-none">{{ $stats['total'] }}</span>
                        <span class="text-[10px] text-slate-400 font-bold mb-0.5">TERDAFTAR</span>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm border-l-4 border-l-emerald-500">
                    <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Aktif</p>
                    <div class="flex items-end gap-2">
                        <span class="text-2xl font-black text-emerald-700 leading-none">{{ $stats['approved'] }}</span>
                        <span class="text-[10px] text-emerald-400 font-bold mb-0.5">READY</span>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm border-l-4 border-l-blue-500">
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Diproses</p>
                    <div class="flex items-end gap-2">
                        <span class="text-2xl font-black text-blue-700 leading-none">{{ $stats['pending'] + $stats['processing'] }}</span>
                        <span class="text-[10px] text-blue-400 font-bold mb-0.5">QUEUE</span>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm border-l-4 border-l-rose-500">
                    <p class="text-xs font-bold text-rose-600 uppercase tracking-wider mb-1">Ditolak</p>
                    <div class="flex items-end gap-2">
                        <span class="text-2xl font-black text-rose-700 leading-none">{{ $stats['rejected'] }}</span>
                        <span class="text-[10px] text-rose-400 font-bold mb-0.5">REJECTED</span>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 text-emerald-700 rounded-2xl shadow-sm border border-emerald-100 flex items-center gap-3">
                    <div class="bg-emerald-500 text-white p-1 rounded-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @php
                $activeWebsites = $websites['approved'] ?? collect();
            @endphp

            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        Daftar Website Aktif
                    </h3>
                </div>

                @if($activeWebsites->isNotEmpty())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-slate-200">
                        <div class="p-0">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-4 bg-slate-50 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">No. Tiket</th>
                                            <th class="px-6 py-4 bg-slate-50 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama Website</th>
                                            <th class="px-6 py-4 bg-slate-50 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Catatan Admin</th>
                                            <th class="px-6 py-4 bg-slate-50 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                                            <th class="px-6 py-4 bg-slate-50 text-right text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tgl Disetujui</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50 bg-white">
                                        @foreach($activeWebsites as $web)
                                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                                <td class="px-6 py-5 whitespace-nowrap">
                                                    <span class="text-xs font-bold text-indigo-600">{{ $web->ticket_number }}</span>
                                                </td>
                                                <td class="px-6 py-5">
                                                    <div class="text-sm font-black text-slate-900">{{ $web->name }}</div>
                                                </td>
                                                <td class="px-6 py-5">
                                                    @if($web->admin_note)
                                                        <div class="text-xs text-slate-600 max-w-xs leading-relaxed italic bg-slate-50 p-2 rounded-xl border border-slate-100">
                                                            "{{ $web->admin_note }}"
                                                        </div>
                                                    @else
                                                        <span class="text-slate-300 text-[10px] italic">Tidak ada catatan</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-5 whitespace-nowrap">
                                                    <span class="px-3 py-1 text-[10px] font-black rounded-full bg-emerald-100 text-emerald-700 uppercase tracking-wider">AKTIF</span>
                                                </td>
                                                <td class="px-6 py-5 whitespace-nowrap text-right text-[11px] font-bold text-slate-400">
                                                    {{ $web->created_at->format('d M Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-[40px] border border-dashed border-slate-300 p-16 text-center shadow-sm">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 mb-3">Belum Ada Website Aktif</h3>
                        <p class="text-slate-500 max-w-md mx-auto mb-10 font-medium">Saat ini belum ada website yang siap untuk promosi. Silakan ajukan website baru melalui tombol di bawah ini.</p>
                        <a href="{{ route('website-requests.index') }}" class="btn-primary inline-flex items-center gap-3 px-8 py-4 rounded-2xl text-base shadow-xl shadow-indigo-200 transition-transform hover:-translate-y-1 active:scale-95">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Ajukan Website Sekarang
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
