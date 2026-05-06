<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('System Logs & Deployment History') }}
            </h2>
            <form action="{{ route('admin.logs.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua log?')">
                @csrf
                <button type="submit" class="btn-danger px-4 py-2 text-xs">Bersihkan Log</button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 overflow-hidden">
                <div class="p-4 bg-slate-800 border-b border-slate-700 flex items-center gap-2">
                    <div class="flex gap-1.5">
                        <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                    </div>
                    <span class="text-xs font-mono text-slate-400 ml-2">laravel.log — Last 500 lines</span>
                </div>
                <div class="p-6 font-mono text-xs text-emerald-400 overflow-x-auto leading-relaxed h-[600px] overflow-y-auto bg-slate-950">
                    <pre>{{ $logs }}</pre>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
