<section class="h-full flex flex-col">
    <form method="post" action="{{ route('password.update') }}" class="mt-0 space-y-6 flex flex-col flex-grow">
        @csrf
        @method('put')

        <div class="space-y-6">
            <div>
                <x-input-label for="update_password_current_password" :value="__('Kata Sandi Saat Ini')" />
                <div class="relative mt-1 flex items-center border border-gray-300 rounded-lg shadow-sm bg-white focus-within:ring-2 focus-within:ring-slate-900 focus-within:border-slate-900 transition-all overflow-hidden">
                    <input id="update_password_current_password" name="current_password" type="password" class="block w-full border-none focus:ring-0 text-sm py-2.5 pl-4 pr-14" autocomplete="current-password" />
                    <button type="button" onclick="togglePassword('update_password_current_password', 'eye-curr')" class="pr-6 flex items-center text-gray-400 hover:text-slate-900 transition-colors outline-none focus:outline-none">
                        <svg id="eye-curr" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password" :value="__('Kata Sandi Baru')" />
                <div class="relative mt-1 flex items-center border border-gray-300 rounded-lg shadow-sm bg-white focus-within:ring-2 focus-within:ring-slate-900 focus-within:border-slate-900 transition-all overflow-hidden">
                    <input id="update_password_password" name="password" type="password" class="block w-full border-none focus:ring-0 text-sm py-2.5 pl-4 pr-14" autocomplete="new-password" />
                    <button type="button" onclick="togglePassword('update_password_password', 'eye-new')" class="pr-6 flex items-center text-gray-400 hover:text-slate-900 transition-colors outline-none focus:outline-none">
                        <svg id="eye-new" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" />
                <div class="relative mt-1 flex items-center border border-gray-300 rounded-lg shadow-sm bg-white focus-within:ring-2 focus-within:ring-slate-900 focus-within:border-slate-900 transition-all overflow-hidden">
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full border-none focus:ring-0 text-sm py-2.5 pl-4 pr-14" autocomplete="new-password" />
                    <button type="button" onclick="togglePassword('update_password_password_confirmation', 'eye-conf')" class="pr-6 flex items-center text-gray-400 hover:text-slate-900 transition-colors outline-none focus:outline-none">
                        <svg id="eye-conf" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <script>
            if (typeof togglePassword === 'undefined') {
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
            }
        </script>

        <div class="flex items-center gap-4 mt-auto pt-6">
            <button type="submit" class="btn-primary flex items-center gap-2 px-6 py-2.5 rounded-xl shadow-lg shadow-indigo-100 transition-transform active:scale-95">{{ __('Update Password') }}</button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
