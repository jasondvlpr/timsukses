<section class="h-full flex flex-col">
    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-0 space-y-6 flex flex-col flex-grow">
        @csrf
        @method('patch')

        <div class="space-y-6">
            <div>
                <x-input-label for="username" :value="__('Username')" />
                <x-text-input id="username" name="username" type="text" class="mt-1 block w-full bg-slate-50 text-slate-500 cursor-not-allowed" :value="old('username', $user->username)" readonly />
            </div>

            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-slate-50 text-slate-500 cursor-not-allowed" :value="old('name', $user->name)" readonly />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
            <div>
                <x-input-label for="whatsapp" :value="__('WhatsApp Number')" />
                <x-text-input id="whatsapp" name="whatsapp" type="text" class="mt-1 block w-full" :value="old('whatsapp', $user->whatsapp)" required placeholder="Example: 08123456789" />
                <x-input-error class="mt-2" :messages="$errors->get('whatsapp')" />
            </div>
        </div>

        <div class="flex items-center gap-4 mt-auto pt-6">
            <button type="submit" class="btn-primary flex items-center gap-2 px-6 py-2.5 rounded-xl shadow-lg shadow-indigo-100 transition-transform active:scale-95">{{ __('Save Changes') }}</button>

            @if (session('status') === 'profile-updated')
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
