<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" x-data="{ showModal: false }">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Daftar Website Promotor') }}
            </h2>
            <button @click="showModal = true" class="btn-primary flex items-center gap-2">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Manual
            </button>

            <!-- Modal Tambah Website -->
            <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showModal" @click="showModal = false" class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-60 backdrop-blur-sm"
                         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                    <div x-show="showModal" 
                         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                         class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-8 border border-slate-200">
                        
                        <div class="mb-6">
                            <h3 class="text-xl font-bold text-slate-900">Tambah Website Manual</h3>
                            <p class="text-sm text-slate-500 mt-1">Daftarkan website baru secara langsung untuk promotor.</p>
                        </div>

                        <form action="{{ route('admin.websites.store') }}" method="POST">
                            @csrf
                            <div class="space-y-5">
                                <div>
                                    <label for="user_id" class="block text-sm font-bold text-slate-700 mb-2">Pilih Promotor</label>
                                    <select name="user_id" id="user_id" required class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                        <option value="">-- Pilih Pemilik Website --</option>
                                        @foreach($promoters as $promoter)
                                            <option value="{{ $promoter->id }}">{{ $promoter->name }} ({{ $promoter->username }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Website</label>
                                    <input type="text" name="name" id="name" required placeholder="Contoh: BossGroup Official"
                                           class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                </div>

                                <div>
                                    <label for="url" class="block text-sm font-bold text-slate-700 mb-2">URL Website</label>
                                    <input type="url" name="url" id="url" required placeholder="https://example.com"
                                           class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all">
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100">
                                <button type="button" @click="showModal = false" class="px-6 py-2 border border-slate-300 rounded-xl text-slate-600 hover:bg-slate-50 font-bold transition-all">Batal</button>
                                <button type="submit" class="btn-primary px-8 py-2 rounded-xl font-bold shadow-lg shadow-indigo-100">Simpan Website</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Website</h3>
                    <form action="{{ route('admin.websites') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="relative">
                            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama, url, atau promotor..." 
                                   class="px-4 py-2 text-sm border-slate-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-64 transition-all" />
                        </div>
                        <button type="submit" class="hidden sm:block bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-slate-700 transition">Cari</button>
                    </form>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Website</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">URL</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Pemilik (Promotor)</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Dikirim</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($websites as $index => $web)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-slate-900">{{ $web->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ $web->url }}" target="_blank" class="text-sm text-indigo-600 hover:underline flex items-center gap-1 font-medium">
                                                {{ $web->url }}
                                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                                    {{ substr($web->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-slate-900">{{ $web->user->name }}</div>
                                                    <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $web->user->username }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-slate-700">{{ $web->created_at->format('d M Y') }}</div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase">{{ $web->created_at->format('H:i') }} WIB</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($web->is_active)
                                                <span class="px-2.5 py-1 text-[10px] font-bold bg-green-100 text-green-700 rounded-full uppercase tracking-wider">Aktif</span>
                                            @else
                                                <span class="px-2.5 py-1 text-[10px] font-bold bg-red-100 text-red-700 rounded-full uppercase tracking-wider">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center gap-3">
                                            <a href="{{ route('admin.users.edit', $web->user_id) }}" class="text-slate-400 hover:text-indigo-600 transition" title="Lihat Profil Pemilik">
                                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </a>
                                            
                                            @if(auth()->user()->isAdmin())
                                                <form action="{{ route('admin.websites.destroy', $web) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus website ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-slate-400 hover:text-red-600 transition" title="Hapus Website">
                                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-slate-400">
                                                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mb-2 opacity-20"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                                <p class="text-sm font-medium">Belum ada website yang terdaftar.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
