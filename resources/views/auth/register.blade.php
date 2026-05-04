<x-guest-layout>
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold text-slate-900">Buat Akun Baru</h1>
        <p class="text-slate-500 mt-1 text-sm">Bergabunglah dengan BossGroupHub sekarang</p>
    </div>

    <form method="POST" action="{{ route('register') }}" id="register-form">
        @csrf

        <!-- Name -->
        <div class="space-y-1">
            <x-input-label for="name" :value="__('Nama Lengkap')" class="font-medium text-slate-700" />
            <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required autofocus
                placeholder="Masukkan nama lengkap" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Username -->
        <div class="mt-5 space-y-1">
            <x-input-label for="username" :value="__('Username')" class="font-medium text-slate-700" />
            <x-text-input id="username" class="block w-full" type="text" name="username" :value="old('username')"
                required placeholder="Buat username unik" />
            <x-input-error :messages="$errors->get('username')" class="mt-1" />
        </div>

        <!-- WhatsApp Number -->
        <div class="mt-5 space-y-1">
            <x-input-label for="whatsapp" :value="__('Nomor WhatsApp')" class="font-medium text-slate-700" />
            <x-text-input id="whatsapp" class="block w-full" type="text" name="whatsapp" :value="old('whatsapp')"
                required placeholder="Contoh: 08123456789" />
            <p class="text-[10px] text-slate-500 mt-1 italic">* Pastikan nomor WhatsApp dalam keadaan aktif</p>
            <x-input-error :messages="$errors->get('whatsapp')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="mt-5 space-y-1">
            <x-input-label for="password" :value="__('Kata Sandi')" class="font-medium text-slate-700" />

            <div
                class="relative mt-1 flex items-center border border-gray-300 rounded-lg shadow-sm bg-white focus-within:ring-2 focus-within:ring-slate-900 focus-within:border-slate-900 transition-all overflow-hidden">
                <input id="password" class="block w-full border-none focus:ring-0 text-sm py-2.5 pl-4 pr-14"
                    type="password" name="password" required placeholder="Minimal 8 karakter" />

                <button type="button" onclick="togglePassword('password', 'eye-icon-1')"
                    class="pr-6 flex items-center text-gray-400 hover:text-slate-900 transition-colors outline-none focus:outline-none">
                    <svg id="eye-icon-1" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-5 space-y-1">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')"
                class="font-medium text-slate-700" />

            <div
                class="relative mt-1 flex items-center border border-gray-300 rounded-lg shadow-sm bg-white focus-within:ring-2 focus-within:ring-slate-900 focus-within:border-slate-900 transition-all overflow-hidden">
                <input id="password_confirmation"
                    class="block w-full border-none focus:ring-0 text-sm py-2.5 pl-4 pr-14" type="password"
                    name="password_confirmation" required placeholder="Ulangi kata sandi" />

                <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-2')"
                    class="pr-6 flex items-center text-gray-400 hover:text-slate-900 transition-colors outline-none focus:outline-none">
                    <svg id="eye-icon-2" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Websites (Dynamic Inputs) -->
        <div class="mt-6 border-t border-slate-100 pt-6" x-data="{ 
            websites: [''],
            addWebsite() {
                if (this.websites.length < 10) {
                    this.websites.push('');
                } else {
                    showToast('Maksimal 10 website', 'error');
                }
            },
            removeWebsite(index) {
                if (this.websites.length > 1) {
                    this.websites.splice(index, 1);
                } else {
                    this.websites[0] = '';
                }
            }
        }">
            <div class="flex items-center justify-between mb-4">
                <x-input-label :value="__('Website Anda (Opsional)')" class="font-semibold text-slate-800" />
                <button type="button" @click="addWebsite()"
                    class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Website
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(website, index) in websites" :key="index">
                    <div class="flex gap-2">
                        <div
                            class="flex-1 flex items-center gap-3 px-4 py-3 border border-slate-200 focus-within:border-indigo-500 focus-within:ring-4 focus-within:ring-indigo-500/10 rounded-xl transition-all shadow-sm bg-white group">
                            <div
                                class="flex-shrink-0 text-slate-400 group-focus-within:text-indigo-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                </svg>
                            </div>
                            <input type="url" name="websites[]" x-model="websites[index]"
                                class="flex-1 p-0 text-sm border-none focus:ring-0 bg-transparent placeholder:text-slate-300"
                                placeholder="https://example.com">
                        </div>
                        <button type="button" @click="removeWebsite(index)"
                            class="p-2.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all"
                            title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </template>
            </div>
            <p class="mt-2 text-[10px] text-slate-400 italic">Tambahkan domain jika sudah ada</p>
        </div>

        <div class="mt-8">
            <button type="submit" id="submit-btn"
                class="w-full btn-primary py-3 text-base shadow-lg shadow-slate-200 flex items-center justify-center gap-2">
                <span id="btn-text">Daftar Sekarang</span>
                <div id="btn-loader"
                    class="hidden w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
            </button>
        </div>

        <div class="mt-8 text-center border-t border-slate-100 pt-6">
            <p class="text-sm text-slate-600">
                Sudah punya akun?
                <a href="{{ route('login') }}"
                    class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                    Masuk ke Akun
                </a>
            </p>
        </div>
    </form>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />';
            } else {
                input.type = "password";
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }

        document.getElementById('register-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const btn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            const btnLoader = document.getElementById('btn-loader');
            const formData = new FormData(this);

            btn.disabled = true;
            btnText.textContent = 'Mendaftarkan...';
            btnLoader.classList.remove('hidden');

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });

                const result = await response.json();

                if (response.ok) {
                    showToast('Pendaftaran berhasil!', 'success');
                    setTimeout(() => window.location.href = result.redirect || '{{ route("dashboard") }}', 800);
                } else {
                    btn.disabled = false;
                    btnText.textContent = 'Daftar Sekarang';
                    btnLoader.classList.add('hidden');

                    let message = 'Terjadi kesalahan.';
                    if (result.errors) {
                        message = Object.values(result.errors)[0][0];
                    } else if (result.message) {
                        message = result.message;
                    }
                    showToast(message, 'error');
                }
            } catch (error) {
                btn.disabled = false;
                btnText.textContent = 'Daftar Sekarang';
                btnLoader.classList.add('hidden');
                showToast('Terjadi kesalahan jaringan atau server.', 'error');
            }
        });
    </script>
</x-guest-layout>