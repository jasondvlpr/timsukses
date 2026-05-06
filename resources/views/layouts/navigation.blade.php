<nav x-data="{ open: false }" class="bg-white border-b border-slate-200 sticky top-0 z-40">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Hamburger (Mobile Only) -->
                <div class="flex items-center sm:hidden mr-4">
                    <button @click="open = true" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2">
                    <div class="w-8 h-8 bg-slate-900 rounded flex items-center justify-center font-bold text-white text-sm shadow-sm shrink-0">B</div>
                    <a href="{{ route('dashboard') }}" class="hidden md:block text-xl font-bold tracking-tight text-slate-900 whitespace-nowrap">
                        BossGroupHub
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="font-medium text-slate-600">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <a href="{{ route('chat.index') }}"
                       class="inline-flex items-center gap-1.5 border-b-2 px-1 pt-1 text-sm font-medium leading-5 transition duration-150 ease-in-out
                              {{ request()->routeIs('chat*')
                                 ? 'border-indigo-400 text-gray-900 focus:border-indigo-700'
                                 : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 focus:border-gray-300 focus:text-gray-700' }}">
                        Chat
                        <span id="nav-chat-badge"
                              style="display:none;background:#ef4444;color:#fff;font-size:10px;font-weight:700;border-radius:99px;min-width:18px;height:18px;padding:0 5px;line-height:18px;text-align:center;">
                        </span>
                    </a>

                    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                        @if(auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users*')" class="font-medium text-slate-600">
                                {{ __('Kelola Akun') }}
                            </x-nav-link>
                        @endif
                        
                        <x-nav-link :href="route('admin.tickets')" :active="request()->routeIs('admin.tickets*')" class="font-medium text-slate-600 flex items-center gap-1.5">
                            {{ __('Semua Laporan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.websites')" :active="request()->routeIs('admin.websites*')" class="font-medium text-slate-600">
                            {{ __('Daftar Website') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.website-requests')" :active="request()->routeIs('admin.website-requests*')" class="font-medium text-slate-600 flex items-center gap-1.5">
                            {{ __('Pengajuan Website') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('my-websites')" :active="request()->routeIs('my-websites')" class="font-medium text-slate-600">
                            {{ __('Website Saya') }}
                        </x-nav-link>
                        <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets*')" class="font-medium text-slate-600">
                            {{ __('Laporan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('website-requests.index')" :active="request()->routeIs('website-requests.index')" class="font-medium text-slate-600">
                            {{ __('Pengajuan Website') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="flex items-center ms-3 sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 px-3 py-2 border border-slate-200 rounded-xl bg-white hover:bg-slate-50 hover:border-slate-300 transition-all duration-200 focus:outline-none shadow-sm group">
                            <!-- Avatar Circle -->
                            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm transition-transform group-hover:scale-105 shrink-0">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            
                            <!-- Desktop Info (Hidden on Mobile) -->
                            <div class="hidden sm:flex flex-col items-start text-left">
                                <span class="text-[13px] font-bold text-slate-900 leading-none">{{ Auth::user()->name }}</span>
                                <span class="text-[10px] font-medium text-slate-500 uppercase tracking-[0.05em] mt-1">{{ Auth::user()->role }}</span>
                            </div>

                            <!-- Caret Icon -->
                            <div class="ml-1 text-slate-400 group-hover:text-slate-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                            <p class="text-xs font-black text-slate-900 leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1.5">{{ Auth::user()->role }}</p>
                        </div>
                        
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 text-slate-600">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <div class="border-t border-slate-100 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2 text-rose-600 hover:bg-rose-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                <span class="font-bold text-xs">{{ __('Log Out') }}</span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>

    <!-- ── MOBILE SIDEBAR DRAWER ── -->
    <div x-show="open" class="fixed inset-0 z-[9999] sm:hidden" style="display:none; height: 100vh; height: 100dvh;">
        <!-- Overlay -->
        <div @click="open = false" x-show="open" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        
        <!-- Sidebar container -->
        <div x-show="open" 
             x-transition:enter="transform transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 w-80 bg-slate-900 shadow-2xl flex flex-col overflow-hidden border-r border-white/10"
             style="height: 100vh; height: 100dvh;">
             
            <!-- Header Sidebar -->
            <div class="p-6 bg-slate-950 flex items-center justify-between border-b border-white/5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center font-bold text-white text-xl border border-white/20">B</div>
                    <div>
                        <div class="text-white font-bold leading-tight">BossGroupHub</div>
                        <div class="text-slate-500 text-[10px] uppercase tracking-widest">Platform Admin</div>
                    </div>
                </div>
                <button @click="open = false" class="text-slate-500 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Content Sidebar -->
            <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1 bg-slate-900">
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 px-3">Main Menu</div>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('chat.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('chat*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Chat
                    <span id="nav-chat-badge-mobile" style="display:none" class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full"></span>
                </a>

                <div class="pt-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 px-3">Management</div>

                @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.users*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Kelola Akun
                        </a>
                    @endif
                    <a href="{{ route('admin.tickets') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.tickets*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Semua Laporan
                    </a>
                    <a href="{{ route('admin.websites') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.websites*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        Daftar Website
                    </a>
                    <a href="{{ route('admin.website-requests') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.website-requests*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Pengajuan Website
                    </a>
                @else
                    <a href="{{ route('my-websites') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('my-websites') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        Website Saya
                    </a>
                    <a href="{{ route('tickets.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('tickets*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Laporan
                    </a>
                    <a href="{{ route('website-requests.index') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('website-requests.index') ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        Pengajuan Website
                    </a>
                @endif
            </div>

            <!-- Footer Sidebar -->
            <div class="p-4 border-t border-white/5 bg-slate-950">
                <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold border border-white/20">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <div class="flex-1 min-width-0">
                        <div class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] text-slate-500 uppercase tracking-widest">{{ Auth::user()->role }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-red-500/10 text-red-500 text-sm font-bold hover:bg-red-500/20 transition border border-red-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
