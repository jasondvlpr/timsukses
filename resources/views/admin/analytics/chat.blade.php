<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Chat Analytics & Staff Performance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Performance Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Staff Response Ranking -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Performa Staff (Waktu Respon)</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            @foreach($analytics as $item)
                                <div>
                                    <div class="flex justify-between items-end mb-2">
                                        <div>
                                            <span class="text-sm font-bold text-slate-800">{{ $item['name'] }}</span>
                                            <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded uppercase ml-2">{{ $item['role'] }}</span>
                                        </div>
                                        <span class="text-xs font-bold {{ $item['avg_response'] < 30 ? 'text-emerald-600' : 'text-amber-600' }}">
                                            {{ $item['avg_response'] }} Menit
                                        </span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                        @php 
                                            // 60 minutes = 100% for the bar visual
                                            $width = min(100, max(10, 100 - ($item['avg_response'] * 1.5))); 
                                        @endphp
                                        <div class="h-full {{ $item['avg_response'] < 30 ? 'bg-emerald-500' : 'bg-amber-500' }} rounded-full transition-all duration-1000" style="width: {{ $width }}%"></div>
                                    </div>
                                    <p class="mt-1 text-[10px] text-slate-400 font-medium">Total Pesan Terkirim: {{ $item['total_sent'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Chat Volume Chart -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 flex flex-col">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-8 text-center">Volume Chat 7 Hari Terakhir</h3>
                    <div class="flex-grow">
                        <canvas id="chatVolumeChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Promoters -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-50">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Promotor Paling Aktif (Minggu Ini)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase">Nama Promotor</th>
                                <th class="px-6 py-3 text-center text-[10px] font-black text-slate-400 uppercase">Total Chat</th>
                                <th class="px-6 py-3 text-right text-[10px] font-black text-slate-400 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($topPromoters as $promoter)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                                {{ substr($promoter->name, 0, 1) }}
                                            </div>
                                            <span class="text-sm font-bold text-slate-700">{{ $promoter->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-sm font-mono font-bold text-slate-600">{{ $promoter->sent_messages_count }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="px-2 py-0.5 text-[10px] font-bold bg-green-50 text-green-600 rounded-full border border-green-100">Aktif</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chatVolumeChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($volumeData['labels']) !!},
                datasets: [{
                    label: 'Pesan',
                    data: {!! json_encode($volumeData['counts']) !!},
                    backgroundColor: '#6366f1',
                    borderRadius: 8,
                    barThickness: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 10 } } },
                    x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 10, weight: 'bold' } } }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
