<x-guest-layout>
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold text-slate-900">Selamat Datang Kembali</h1>
        <p class="text-slate-500 mt-1 text-sm">Silakan masuk untuk melanjutkan akses Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form id="login-form" method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username -->
        <div class="space-y-1">
            <x-input-label for="username" :value="__('Username')" class="font-medium text-slate-700" />
            <div class="relative flex items-center border border-gray-300 rounded-lg shadow-sm bg-white focus-within:ring-2 focus-within:ring-slate-900 focus-within:border-slate-900 transition-all overflow-hidden">
                <div class="pl-4 text-slate-400">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <input id="username" class="block w-full border-none focus:ring-0 text-sm py-2.5 pl-2 pr-4" type="text" name="username" :value="old('username')" required autofocus placeholder="Masukkan username Anda" />
            </div>
        </div>

        <!-- Password -->
        <div class="mt-6 space-y-1">
            <x-input-label for="password" :value="__('Kata Sandi')" class="font-medium text-slate-700" />

            <div class="relative mt-1 flex items-center border border-gray-300 rounded-lg shadow-sm bg-white focus-within:ring-2 focus-within:ring-slate-900 focus-within:border-slate-900 transition-all overflow-hidden">
                <div class="pl-4 text-slate-400">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input id="password" 
                        class="block w-full border-none focus:ring-0 text-sm py-2.5 pl-2 pr-14"
                        type="password"
                        name="password"
                        required placeholder="••••••••" />
                
                <button type="button" onclick="togglePassword('password', 'eye-icon')" class="pr-6 flex items-center text-gray-400 hover:text-slate-900 transition-colors outline-none focus:outline-none">
                    <svg id="eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-6">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-slate-900 shadow-sm focus:ring-slate-900" name="remember">
                <span class="ms-2 text-sm text-slate-600">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-600 hover:text-indigo-500 font-medium transition-colors" href="{{ route('password.request') }}">
                    Lupa sandi?
                </a>
            @endif
        </div>

        <div class="mt-8">
            <button type="submit" id="submit-btn" class="w-full btn-primary py-3 text-base shadow-lg shadow-slate-200 flex items-center justify-center gap-2">
                <span id="btn-text">Masuk ke Akun</span>
                <div id="btn-loader" class="hidden w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
            </button>
        </div>

        <div class="mt-8 text-center border-t border-slate-100 pt-6">
            <p class="text-sm text-slate-600">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                    Daftar Sekarang
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

        document.getElementById('login-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            const btnLoader = document.getElementById('btn-loader');
            const formData = new FormData(this);

            btn.disabled = true;
            btnText.textContent = 'Memproses...';
            btnLoader.classList.remove('hidden');

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });

                const result = await response.json();

                if (response.ok) {
                    showToast('Login berhasil! Mengalihkan...', 'success');
                    setTimeout(() => window.location.href = result.redirect || '{{ route("dashboard") }}', 800);
                } else {
                    btn.disabled = false;
                    btnText.textContent = 'Masuk ke Akun';
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
                btnText.textContent = 'Masuk ke Akun';
                btnLoader.classList.add('hidden');
                showToast('Terjadi kesalahan jaringan atau server.', 'error');
            }
        });
    </script>
</x-guest-layout>
