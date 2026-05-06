<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard | Management Overview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Admin Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 premium-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Tiket Keluhan</p>
                            <h3 class="text-4xl font-extrabold text-red-600">{{ $stats['open_tickets'] }}</h3>
                        </div>
                        <div class="p-3 bg-red-50 rounded-lg">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-col gap-2">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Tugas Saya:</span>
                            <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">{{ $stats['my_tickets'] }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Belum Ditugaskan:</span>
                            <span class="font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">{{ $stats['unassigned_tickets'] }}</span>
                        </div>
                        <a href="{{ route('admin.tickets', ['assigned_to' => 'none']) }}" class="mt-2 text-[10px] font-bold text-red-600 hover:underline uppercase">Ambil Tugas Baru &rarr;</a>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 premium-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Pengajuan Web</p>
                            <h3 class="text-4xl font-extrabold text-purple-600">{{ $stats['pending_requests'] }}</h3>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg">
                            <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-col gap-2">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Tugas Saya:</span>
                            <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">{{ $stats['my_requests'] }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Belum Ditugaskan:</span>
                            <span class="font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full">{{ $stats['unassigned_requests'] }}</span>
                        </div>
                        <a href="{{ route('admin.website-requests', ['assigned_to' => 'none']) }}" class="mt-2 text-[10px] font-bold text-purple-600 hover:underline uppercase">Proses Pengajuan &rarr;</a>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 premium-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Total Website</p>
                            <h3 class="text-4xl font-extrabold text-green-600">{{ $stats['total_websites'] }}</h3>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-1.343 3-3s-1.343-3-3-3m0 12c-1.657 0-3-1.343-3-3s1.343-3 3-3m0 0V3m0 18v-3"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-xs font-bold text-green-600 uppercase">Website Aktif Berjalan</span>
                    </div>
                </div>
            </div>

            <!-- Charts and Welcome Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Welcome Area -->
                <div class="lg:col-span-1 bg-indigo-900 rounded-3xl p-8 shadow-xl text-white overflow-hidden relative flex flex-col justify-center">
                    <div class="relative z-10">
                        <h2 class="text-2xl font-bold mb-4">Selamat Datang, {{ auth()->user()->name }}!</h2>
                        <p class="text-indigo-200 text-sm leading-relaxed">
                            @if(auth()->user()->isAdmin())
                                Sebagai Admin, Anda dapat memantau seluruh kinerja Staff dan menyetujui laporan akhir. Pastikan beban kerja terdistribusi dengan merata.
                            @else
                                Sebagai Staff, fokuslah pada tugas yang diberikan kepada Anda atau ambil tugas baru yang belum ditugaskan untuk menjaga produktivitas.
                            @endif
                        </p>
                        <div class="mt-8 flex flex-col gap-3">
                            <a href="{{ route('admin.analytics.chat') }}" class="flex items-center gap-3 px-5 py-3 bg-white/10 hover:bg-white/20 border border-white/10 rounded-2xl transition backdrop-blur-md group">
                                <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/20 text-emerald-300 rounded-xl group-hover:bg-emerald-500/30 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                </div>
                                <span class="text-sm font-bold text-white">Chat Analytics</span>
                            </a>
                            
                            <a href="{{ route('admin.logs.index') }}" class="flex items-center gap-3 px-5 py-3 bg-white/10 hover:bg-white/20 border border-white/10 rounded-2xl transition backdrop-blur-md group">
                                <div class="w-8 h-8 flex items-center justify-center bg-blue-500/20 text-blue-300 rounded-xl group-hover:bg-blue-500/30 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <span class="text-sm font-bold text-white">Lihat Deployment Logs</span>
                            </a>
                        </div>
                    </div>
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-indigo-500 rounded-full opacity-20 blur-2xl"></div>
                </div>

                <!-- Activity Chart -->
                <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Aktivitas Mingguan</h3>
                        <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-widest">
                            <div class="flex items-center gap-1.5 text-indigo-600">
                                <div class="w-2 h-2 rounded-full bg-indigo-600"></div> Pengajuan
                            </div>
                            <div class="flex items-center gap-1.5 text-rose-500">
                                <div class="w-2 h-2 rounded-full bg-rose-500"></div> Keluhan
                            </div>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('activityChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [
                    {
                        label: 'Pengajuan',
                        data: {!! json_encode($chartData['requests']) !!},
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#4f46e5'
                    },
                    {
                        label: 'Keluhan',
                        data: {!! json_encode($chartData['tickets']) !!},
                        borderColor: '#f43f5e',
                        backgroundColor: 'rgba(244, 63, 94, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#f43f5e'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], drawBorder: false },
                        ticks: { stepSize: 1, color: '#94a3b8', font: { size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 10, weight: 'bold' } }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
