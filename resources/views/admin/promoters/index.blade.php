<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Daftar Promotor & Website') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Info Promotor</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Kontak (WhatsApp)</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Total Website</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Daftar Website yang Dipegang</th>
                                    <th class="px-6 py-4 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Bergabung Sejak</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($promoters as $promoter)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center">
                                                    {{ substr($promoter->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-slate-900">{{ $promoter->name }}</div>
                                                    <div class="text-xs text-slate-500">{{ $promoter->username }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                            @if($promoter->whatsapp)
                                                @php
                                                    $wa = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $promoter->whatsapp));
                                                @endphp
                                                <a href="https://wa.me/{{ $wa }}" target="_blank" class="text-green-600 hover:text-green-700 hover:underline flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.029 18.88c-1.161 0-2.305-.292-3.318-.844l-3.677.964.984-3.595c-.607-1.052-.927-2.246-.926-3.468.001-3.825 3.113-6.937 6.937-6.937 3.825 0 6.938 3.112 6.938 6.937 0 3.825-3.113 6.938-6.938 6.938z"/></svg>
                                                    {{ $promoter->whatsapp }}
                                                </a>
                                            @else
                                                <span class="text-xs text-slate-400 italic">Belum ada WA</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 text-xs font-bold bg-slate-100 text-slate-700 rounded-full">
                                                {{ $promoter->websites->count() }} Web
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($promoter->websites->count() > 0)
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($promoter->websites as $website)
                                                        <a href="{{ $website->url }}" target="_blank" title="{{ $website->name }}" class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded border border-blue-100 hover:bg-blue-100 transition">
                                                            {{ Str::limit($website->name, 15) }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-xs text-slate-400 italic">Belum ada website</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                            {{ $promoter->created_at->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-slate-500 font-medium">Belum ada promotor yang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                {{ $promoters->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
