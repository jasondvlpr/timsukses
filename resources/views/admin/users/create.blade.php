<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Tambah Akun Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="p-8">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Nama Lengkap" required>
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <label for="username" class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                                <input type="text" name="username" id="username" value="{{ old('username') }}" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Username" required>
                                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            </div>

                            <div>
                                <label for="whatsapp" class="block text-sm font-bold text-slate-700 mb-2">Nomor WhatsApp</label>
                                <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="08123456789">
                                <x-input-error :messages="$errors->get('whatsapp')" class="mt-2" />
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-bold text-slate-700 mb-2">Role / Level Akun</label>
                                <select name="role" id="role" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="promoter" selected>Promoter (User)</option>
                                    <option value="staff">Staff (Ticketing & Requests)</option>
                                    <option value="admin">Admin (Full Access)</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                                <input type="password" name="password" id="password" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border-slate-200 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 border border-slate-300 rounded-lg text-slate-600 hover:bg-slate-50 font-semibold transition">Batal</a>
                            <button type="submit" class="btn-primary px-8 py-2 rounded-lg font-semibold shadow-lg">Buat Akun</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
