<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" x-data>
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Pengajuan Website') }}
            </h2>
            <button @click="$dispatch('open-request-modal')" class="btn-primary flex items-center gap-2 px-5 py-2.5 rounded-xl shadow-lg shadow-indigo-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Ajukan Website
            </button>
        </div>
    </x-slot>

    <div x-data="{ showModal: {{ $errors->any() ? 'true' : 'false' }} }" 
         @open-request-modal.window="showModal = true">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl shadow-sm border border-emerald-100 flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-800">Riwayat Pengajuan</h3>
                        <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">{{ $requests->total() }} Total</span>
                    </div>
                    <div class="p-0">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No. Pengajuan</th>
                                        <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Detail Website</th>
                                        <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Catatan Admin</th>
                                        <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tgl Pengajuan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @forelse($requests as $request)
                                        <tr class="hover:bg-slate-50/50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">{{ $request->ticket_number ?? 'REQ-OLD' }}</td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-semibold text-slate-900">{{ $request->name }}</div>
                                                <div class="text-xs text-blue-600 font-medium truncate max-w-xs mt-0.5">
                                                    <a href="{{ $request->url }}" target="_blank" class="hover:underline flex items-center gap-1">
                                                        {{ preg_replace('(^https?://)', '', $request->url) }}
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                    </a>
                                                </div>
                                                @if($request->description)
                                                    <div class="text-[11px] text-slate-500 mt-1 max-w-xs truncate italic" title="{{ $request->description }}">
                                                        "{{ $request->description }}"
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 text-[11px] font-bold rounded-full 
                                                    @if($request->status == 'pending') bg-slate-100 text-slate-600 
                                                    @elseif($request->status == 'processing') bg-blue-100 text-blue-700
                                                    @elseif($request->status == 'approved') bg-emerald-100 text-emerald-700 
                                                    @else bg-rose-100 text-rose-700 @endif">
                                                    @if($request->status == 'processing')
                                                        SEDANG DIPROSES
                                                    @else
                                                        {{ strtoupper($request->status) }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-500">
                                                <div class="max-w-xs overflow-hidden text-xs">
                                                    {{ $request->admin_note ?? '-' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">
                                                {{ $request->created_at->format('d M Y') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center">
                                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                    </div>
                                                    <p class="text-slate-500 font-medium">Belum ada pengajuan website.</p>
                                                    <button @click="showModal = true" class="btn-primary mt-6 inline-flex items-center gap-2 px-6 py-3 rounded-xl shadow-lg shadow-indigo-200 transition-transform hover:-translate-y-0.5 active:scale-95 text-sm">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                                        Ajukan Website Sekarang
                                                    </button>
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
                    {{ $requests->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Overlay -->
        <div x-show="showModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;">
            
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="showModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white shadow-2xl rounded-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                    
                    <div class="bg-white p-6 sm:p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Ajukan Website Baru</h3>
                                <p class="text-sm text-slate-500 mt-1">Lengkapi detail website yang ingin dipromosikan.</p>
                            </div>
                            <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <form action="{{ route('website-requests.store') }}" method="POST">
                            @csrf
                            
                            <div class="space-y-5">
                                <div>
                                    <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Nama Website <span class="text-rose-500">*</span></label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                           class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition py-3 px-4 text-sm" 
                                           placeholder="Contoh: Web Portal Promo A" required>
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="url" class="block text-sm font-bold text-slate-700 mb-1.5">URL / Link Website <span class="text-rose-500">*</span></label>
                                    <input type="url" name="url" id="url" value="{{ old('url') }}"
                                           class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition py-3 px-4 text-sm" 
                                           placeholder="https://example.com" required>
                                    <p class="mt-1.5 text-[11px] text-slate-400 italic flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Admin dapat memperbarui link ini saat diselesaikan.
                                    </p>
                                    <x-input-error :messages="$errors->get('url')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-bold text-slate-700 mb-1.5">Keterangan <span class="text-slate-400 font-normal text-xs">(Opsional)</span></label>
                                    <textarea name="description" id="description" rows="3" 
                                              class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition py-3 px-4 text-sm" 
                                              placeholder="Tambahkan catatan jika ada...">{{ old('description') }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>
                            </div>

                            <div class="mt-8 flex gap-3">
                                <button type="button" @click="showModal = false" 
                                        class="flex-1 px-4 py-3 border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 font-bold transition text-sm">
                                    Batal
                                </button>
                                <button type="submit" 
                                        class="flex-1 btn-primary py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 text-sm">
                                    Kirim Pengajuan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
