<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Persetujuan Website Baru (Admin)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg shadow-sm border border-green-200 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No. Pengajuan</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Promotor</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Detail Website</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 bg-slate-50 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($requests as $request)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">{{ $request->ticket_number ?? 'REQ-OLD' }}</td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-slate-900">{{ $request->user->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $request->user->username }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-slate-900">{{ $request->name }}</div>
                                            <div class="text-xs text-slate-400 mb-1">Ref: <a href="{{ $request->url }}" target="_blank" class="text-blue-600 hover:underline">{{ $request->url }}</a></div>
                                            @if($request->description)
                                                <div class="text-xs text-slate-500 italic bg-slate-50 p-2 rounded border border-slate-100 mt-1">
                                                    "{{ $request->description }}"
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 text-xs font-bold rounded-full 
                                                @if($request->status == 'pending') bg-slate-100 text-slate-600 
                                                @elseif($request->status == 'processing') bg-blue-100 text-blue-700
                                                @elseif($request->status == 'approved') bg-green-100 text-green-700 
                                                @else bg-red-100 text-red-700 @endif">
                                                @if($request->status == 'processing')
                                                    Sedang Diproses
                                                @else
                                                    {{ strtoupper($request->status) }}
                                                @endif
                                            </span>
                                            @if($request->admin_note && $request->status != 'pending' && $request->status != 'processing')
                                                <div class="mt-2 text-xs text-slate-500 bg-slate-50 p-2 rounded border border-slate-100">
                                                    <strong>Ket Admin:</strong> {{ $request->admin_note }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if($request->status == 'pending')
                                                <div class="flex justify-end gap-2">
                                                    <form action="{{ route('admin.website-requests.process', $request) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn-primary px-3 py-1 text-xs bg-blue-600 hover:bg-blue-700 border-none">Proses</button>
                                                    </form>
                                                    <button onclick="document.getElementById('reject-form-{{ $request->id }}').classList.toggle('hidden'); document.getElementById('approve-form-{{ $request->id }}').classList.add('hidden');" class="btn-danger px-3 py-1 text-xs">Tolak</button>
                                                </div>
                                            @elseif($request->status == 'processing')
                                                <div class="flex justify-end gap-2">
                                                    <button onclick="document.getElementById('approve-form-{{ $request->id }}').classList.toggle('hidden'); document.getElementById('reject-form-{{ $request->id }}').classList.add('hidden');" class="btn-primary px-3 py-1 text-xs bg-green-600 hover:bg-green-700 border-none">Setujui & Selesaikan</button>
                                                    <button onclick="document.getElementById('reject-form-{{ $request->id }}').classList.toggle('hidden'); document.getElementById('approve-form-{{ $request->id }}').classList.add('hidden');" class="btn-danger px-3 py-1 text-xs">Tolak</button>
                                                </div>
                                            @else
                                                <span class="text-slate-400 text-xs font-medium bg-slate-100 px-3 py-1 rounded-full">Selesai</span>
                                            @endif
                                            
                                            <!-- Approve Form -->
                                            <div id="approve-form-{{ $request->id }}" class="hidden mt-4 text-left p-4 bg-green-50 rounded-lg border border-green-200">
                                                <form action="{{ route('admin.website-requests.approve', $request) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label class="block text-xs font-bold text-green-800 mb-1">Final URL / Link Website</label>
                                                        <input type="url" name="url" value="{{ $request->url }}" class="w-full text-sm border-green-300 focus:ring-green-500 focus:border-green-500 rounded-md" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="block text-xs font-bold text-green-800 mb-1">Keterangan / Info Akses Web</label>
                                                        <textarea name="admin_note" class="w-full text-sm border-green-300 focus:ring-green-500 focus:border-green-500 rounded-md" placeholder="Contoh: Username: admin, Password: 123" required></textarea>
                                                    </div>
                                                    <div class="flex justify-end">
                                                        <button type="button" onclick="document.getElementById('approve-form-{{ $request->id }}').classList.add('hidden')" class="mr-2 text-xs text-slate-500 font-medium">Batal</button>
                                                        <button type="submit" class="btn-primary px-3 py-1 text-xs bg-green-600 hover:bg-green-700 border-none">Simpan & Setujui</button>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- Reject Form -->
                                            <div id="reject-form-{{ $request->id }}" class="hidden mt-4 text-left p-4 bg-slate-50 rounded-lg border border-slate-200">
                                                <form action="{{ route('admin.website-requests.reject', $request) }}" method="POST">
                                                    @csrf
                                                    <label class="block text-xs font-bold text-slate-700 mb-1">Alasan Penolakan</label>
                                                    <textarea name="admin_note" class="w-full text-sm border-slate-300 focus:ring-blue-500 focus:border-blue-500 rounded-md mb-3" placeholder="Alasan penolakan..." required></textarea>
                                                    <div class="flex justify-end">
                                                        <button type="button" onclick="document.getElementById('reject-form-{{ $request->id }}').classList.add('hidden')" class="mr-2 text-xs text-slate-500 font-medium">Batal</button>
                                                        <button type="submit" class="btn-danger text-xs px-3 py-1">Kirim Penolakan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-slate-500 font-medium">Belum ada pengajuan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
