<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Edit Akun: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Basic Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="p-8">
                    <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Informasi Dasar
                    </h3>
                    
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <label for="username" class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            </div>

                            <div>
                                <label for="whatsapp" class="block text-sm font-bold text-slate-700 mb-2">Nomor WhatsApp</label>
                                <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $user->whatsapp) }}" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <x-input-error :messages="$errors->get('whatsapp')" class="mt-2" />
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-bold text-slate-700 mb-2">Role / Level Akun</label>
                                <select name="role" id="role" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin (Full Access)</option>
                                    <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Staff (Ticketing & Requests)</option>
                                    <option value="promoter" {{ $user->role === 'promoter' ? 'selected' : '' }}>Promoter (User)</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100">
                            <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Ganti Password (Kosongkan jika tidak ingin diubah)
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password Baru</label>
                                    <input type="password" name="password" id="password" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="••••••••">
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 border border-slate-300 rounded-lg text-slate-600 hover:bg-slate-50 font-semibold transition">Batal</a>
                            <button type="submit" class="btn-primary px-8 py-2 rounded-lg font-semibold shadow-lg">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Websites Management (Ownership Transfer) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="p-8">
                    <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-1.343 3-3s-1.343-3-3-3m0 12c-1.657 0-3-1.343-3-3s1.343-3 3-3m0 0V3m0 18v-3"></path></svg>
                        Daftar Website & Pindah Kepemilikan
                    </h3>
                    
                    @if($user->websites->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase">Nama Website</th>
                                        <th class="px-4 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase">URL</th>
                                        <th class="px-4 py-3 bg-slate-50 text-right text-xs font-bold text-slate-500 uppercase">Transfer Ke</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($user->websites as $website)
                                        <tr>
                                            <td class="px-4 py-4 text-sm font-bold text-slate-900">{{ $website->name }}</td>
                                            <td class="px-4 py-4 text-xs text-blue-600 truncate max-w-xs">{{ $website->url }}</td>
                                            <td class="px-4 py-4 text-right">
                                                <form action="{{ route('admin.websites.transfer', $website) }}" method="POST" class="flex items-center justify-end gap-2">
                                                    @csrf
                                                    <select name="user_id" class="text-xs border-slate-200 rounded-md py-1 focus:ring-indigo-500">
                                                        <option value="">-- Pilih Penerima --</option>
                                                        @foreach(App\Models\User::where('id', '!=', $user->id)->where('role', 'promoter')->get() as $potentialOwner)
                                                            <option value="{{ $potentialOwner->id }}">{{ $potentialOwner->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" onclick="return confirm('Pindahkan website ini?')" class="text-[10px] font-bold bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700 transition">Pindahkan</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6 bg-slate-50 rounded-lg border border-slate-100">
                            <p class="text-slate-500 text-sm italic">User ini belum memiliki website.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
