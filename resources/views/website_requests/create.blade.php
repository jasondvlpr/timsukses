<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Ajukan Website Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200">
                <div class="p-8 text-slate-900">
                    <p class="text-slate-500 mb-8">Silakan masukkan detail website yang ingin Anda promosikan. Admin akan meninjau dan memberikan link final setelah pengajuan disetujui.</p>
                    
                    <form action="{{ route('website-requests.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Website <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition" placeholder="Contoh: Web Portal Promo A" required>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <label for="url" class="block text-sm font-bold text-slate-700 mb-2">URL / Link Website (Referensi) <span class="text-red-500">*</span></label>
                            <input type="url" name="url" id="url" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition" placeholder="https://example.com" required>
                            <p class="mt-1 text-xs text-slate-400 italic">Admin dapat memperbarui link ini saat pengajuan diselesaikan.</p>
                            <x-input-error :messages="$errors->get('url')" class="mt-2" />
                        </div>

                        <div class="mb-8">
                            <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Keterangan / Tambahan Informasi <span class="text-slate-400 font-normal text-xs">(Opsional)</span></label>
                            <textarea name="description" id="description" rows="4" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition" placeholder="Tambahkan informasi tambahan jika ada..."></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('website-requests.index') }}" class="px-6 py-2 border border-slate-300 rounded-lg text-slate-600 hover:bg-slate-50 font-semibold transition">Batal</a>
                            <button type="submit" class="btn-primary px-8 py-2 rounded-lg font-semibold shadow-lg">Kirim Pengajuan</button>
                        </div>
                    </form>
                </div>
            </div>

            @if(isset($requests) && $requests->isNotEmpty())
                <div class="mt-10 bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200">
                    <div class="p-8">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">Riwayat Pengajuan</h3>
                                <p class="mt-1 text-sm text-slate-500">Daftar website yang sudah Anda ajukan.</p>
                            </div>
                            <span class="text-sm font-semibold text-slate-700">{{ $requests->count() }} Pengajuan Terakhir</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead>
                                    <tr>
                                        <th class="px-5 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No.</th>
                                        <th class="px-5 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Website</th>
                                        <th class="px-5 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                        <th class="px-5 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Diajukan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @foreach($requests as $request)
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="px-5 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">{{ $loop->iteration }}</td>
                                            <td class="px-5 py-4">
                                                <div class="text-sm font-semibold text-slate-900 truncate max-w-xs">{{ $request->name }}</div>
                                                <a href="{{ $request->url }}" target="_blank" class="text-xs text-indigo-600 hover:underline truncate block max-w-xs">{{ preg_replace('(^https?://)', '', $request->url) }}</a>
                                            </td>
                                            <td class="px-5 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                                    @if($request->status == 'pending') bg-amber-100 text-amber-700
                                                    @elseif($request->status == 'processing') bg-blue-100 text-blue-700
                                                    @elseif($request->status == 'approved') bg-emerald-100 text-emerald-700
                                                    @else bg-red-100 text-red-700 @endif">
                                                    @if($request->status == 'processing') Sedang Diproses @else {{ ucfirst($request->status) }} @endif
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 whitespace-nowrap text-sm text-slate-500">{{ $request->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
