<x-guest-layout>
    <div class="text-center py-10">
        <!-- Icon -->
        <div class="w-20 h-20 bg-indigo-50 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner">
            <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h1 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Coming Soon</h1>
        
        <div class="space-y-4">
            <p class="text-slate-500 text-sm leading-relaxed max-w-xs mx-auto">
                Fitur pemulihan kata sandi otomatis sedang dalam tahap pengembangan untuk keamanan yang lebih maksimal.
            </p>
            
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 mt-6 text-left">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Bantuan Cepat</p>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Jika Anda lupa kata sandi, silakan hubungi <span class="font-bold text-slate-900">Administrator</span> atau <span class="font-bold text-slate-900">Staff</span> melalui grup koordinasi untuk reset manual.
                </p>
            </div>
        </div>

        <div class="mt-10">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="transform: rotate(180deg)">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
                Kembali ke Login
            </a>
        </div>
    </div>
</x-guest-layout>
