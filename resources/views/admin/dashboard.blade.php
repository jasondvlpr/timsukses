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
                            <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Keluhan Terbuka</p>
                            <h3 class="text-4xl font-extrabold text-red-600">{{ $stats['open_tickets'] }}</h3>
                        </div>
                        <div class="p-3 bg-red-50 rounded-lg">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.tickets') }}" class="text-xs font-bold text-red-600 hover:underline uppercase">Tangani Sekarang &rarr;</a>
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
                    <div class="mt-4">
                        <a href="{{ route('admin.website-requests') }}" class="text-xs font-bold text-purple-600 hover:underline uppercase">Review Pengajuan &rarr;</a>
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

            <!-- Admin Welcome Area -->
            <div class="bg-indigo-900 rounded-3xl p-12 shadow-xl text-white overflow-hidden relative">
                <div class="relative z-10">
                    <h2 class="text-3xl font-bold mb-4">Selamat Datang di Panel Kendali, Admin!</h2>
                    <p class="text-indigo-200 text-lg max-w-2xl">
                        Gunakan panel ini untuk mengelola semua laporan promotor dan menyetujui pengajuan website baru. Pastikan semua tiket segera ditanggapi untuk menjaga produktivitas tim sukses Anda.
                    </p>
                </div>
                <!-- Abstract Design Elements -->
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-indigo-500 rounded-full opacity-20 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-72 h-72 bg-purple-500 rounded-full opacity-20 blur-3xl"></div>
            </div>
        </div>
    </div>
</x-app-layout>
