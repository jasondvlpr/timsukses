<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Promoter Dashboard') }}
            </h2>
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-gray-400 bg-gray-100 px-3 py-1.5 rounded-lg uppercase tracking-wider">{{ now()->format('d M Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-[calc(100vh-64px)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Promoter Stats Grid - Aligned with Admin Style -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                @php
                    $stats_config = [
                        ['label' => 'Total Keluhan', 'value' => $stats['total_tickets'], 'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z', 'color' => 'gray'],
                        ['label' => 'Menunggu', 'value' => $stats['open_tickets'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'red'],
                        ['label' => 'Website Aktif', 'value' => $stats['active_websites'], 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9', 'color' => 'green'],
                        ['label' => 'Pengajuan Web', 'value' => $stats['pending_requests'], 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'color' => 'purple']
                    ];

                    $color_map = [
                        'gray' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'icon' => 'text-gray-500'],
                        'red' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'icon' => 'text-red-500'],
                        'green' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'icon' => 'text-green-500'],
                        'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'icon' => 'text-purple-500'],
                    ];
                @endphp

                @foreach($stats_config as $stat)
                    @php $colors = $color_map[$stat['color']]; @endphp
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 premium-card transition-all hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">{{ $stat['label'] }}</p>
                                <h3 class="text-4xl font-extrabold {{ $colors['text'] }}">{{ $stat['value'] }}</h3>
                            </div>
                            <div class="p-3 {{ $colors['bg'] }} rounded-lg">
                                <svg class="w-8 h-8 {{ $colors['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"></path></svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Recent Activity Table - Matching Admin Aesthetics -->
            <div class="bg-white rounded-3xl p-8 sm:p-12 shadow-sm border border-gray-100 mb-10">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 mb-10">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Keluhan Terbaru</h3>
                        <p class="text-sm font-semibold text-gray-400 mt-1 uppercase tracking-wider">Data 5 Keluhan Terakhir</p>
                    </div>
                    <a href="{{ route('tickets.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-500 transition-colors uppercase tracking-widest">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recent_tickets as $ticket)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="py-6">
                                        <div class="flex items-center gap-5">
                                            <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100 group-hover:bg-white transition-colors">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900 mb-1">{{ $ticket->subject }}</div>
                                                <div class="text-[11px] text-gray-400 font-bold uppercase tracking-wider">
                                                    {{ $ticket->website->name ?? 'Keluhan Umum' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-6">
                                        @php
                                            $statusClasses = [
                                                'open' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                'in_progress' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                'resolved' => 'bg-green-50 text-green-600 border-green-100'
                                            ];
                                            $statusClass = $statusClasses[$ticket->status] ?? 'bg-gray-50 text-gray-500 border-gray-100';
                                        @endphp
                                        <span class="px-4 py-1.5 text-[10px] font-bold rounded-xl border uppercase tracking-wider {{ $statusClass }}">
                                            {{ $ticket->status == 'in_progress' ? 'DIPROSES' : $ticket->status }}
                                        </span>
                                    </td>
                                    <td class="py-6 text-right">
                                        <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 text-gray-400 hover:bg-gray-900 hover:text-white transition-all border border-gray-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-20 text-center text-gray-400 font-bold uppercase tracking-widest text-xs italic">
                                        Belum ada riwayat aktivitas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Promoter Welcome Area - Matching Admin Style -->
            <div class="bg-indigo-900 rounded-3xl p-12 sm:p-16 shadow-xl text-white overflow-hidden relative">
                <div class="relative z-10">
                    <h2 class="text-3xl sm:text-4xl font-bold mb-6">Selamat Datang di Panel Promotor!</h2>
                    <p class="text-indigo-200 text-lg max-w-2xl leading-relaxed mb-10">
                        Kelola website Anda dan pantau semua keluhan harian dengan mudah. Gunakan panel ini untuk mengoptimalkan kinerja tim sukses Anda di BossGroup.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('tickets.create') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-white text-indigo-900 rounded-2xl font-bold text-sm hover:bg-indigo-50 transition-all shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            Kirim Keluhan Baru
                        </a>
                    </div>
                </div>
                <!-- Abstract Design Elements -->
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[30rem] h-[30rem] bg-indigo-500 rounded-full opacity-20 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-[25rem] h-[25rem] bg-purple-500 rounded-full opacity-20 blur-3xl"></div>
            </div>
        </div>
    </div>
</x-app-layout>