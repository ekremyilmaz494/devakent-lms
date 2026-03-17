<header class="flex items-center justify-between h-14 px-4 md:px-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    {{-- Left: Hamburger + Page Title --}}
    <div class="flex items-center gap-3">
        {{-- Hamburger (sadece mobil) --}}
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h1 class="text-[15px] font-semibold text-gray-800 dark:text-white">@yield('page-title', 'Dashboard')</h1>
    </div>

    {{-- Right Side --}}
    <div class="flex items-center gap-2">
        {{-- Notifications --}}
        @php
            $unreadNotifs = \App\Models\NotificationRecipient::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count();
            $recentNotifs = \App\Models\NotificationRecipient::where('user_id', auth()->id())
                ->with('notification')
                ->orderByDesc('notification_id')
                ->take(5)
                ->get();
        @endphp
        <div x-data="{ notifOpen: false }" class="relative">
            <button @click="notifOpen = !notifOpen" class="relative p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                @if($unreadNotifs > 0)
                    <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] flex items-center justify-center px-1 text-[10px] font-bold text-white bg-red-500 rounded-full">{{ $unreadNotifs > 9 ? '9+' : $unreadNotifs }}</span>
                @endif
            </button>

            <div x-show="notifOpen" @click.away="notifOpen = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                 class="absolute right-0 mt-2 w-80 max-w-[calc(100vw-2rem)] bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <p class="text-sm font-semibold text-gray-800 dark:text-white">Bildirimler</p>
                    @if($unreadNotifs > 0)
                        <span class="text-xs font-medium px-1.5 py-0.5 rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">{{ $unreadNotifs }} yeni</span>
                    @endif
                </div>
                @if($recentNotifs->count() > 0)
                    <div class="max-h-64 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($recentNotifs as $rn)
                        <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ !$rn->is_read ? 'bg-primary-50/50 dark:bg-primary-900/10' : '' }}">
                            <div class="flex items-start gap-2">
                                <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0 {{ !$rn->is_read ? 'bg-primary-500' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-gray-800 dark:text-white truncate">{{ $rn->notification->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1">{{ $rn->notification->message }}</p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">{{ $rn->notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="px-4 py-2.5 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ auth()->user()->hasRole('admin') ? '#' : route('staff.notifications') }}" class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline">Tümünü Gör</a>
                    </div>
                @else
                    <div class="p-4 text-center text-sm text-gray-500 dark:text-gray-400">
                        <p>Henüz bildirim yok</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Dark Mode Toggle --}}
        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
            <svg x-show="!darkMode" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <svg x-show="darkMode" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </button>

        {{-- Separator --}}
        <div class="hidden sm:block w-px h-6 bg-gray-200 dark:bg-gray-700 mx-1"></div>

        {{-- Language Switcher --}}
        <div x-data="{ langOpen: false }" class="relative">
            <button @click="langOpen = !langOpen" class="flex items-center gap-1.5 px-2.5 py-1.5 text-[13px] text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span class="hidden sm:inline">{{ strtoupper(app()->getLocale()) }}</span>
                <svg class="hidden sm:block w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="langOpen" @click.away="langOpen = false" x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="absolute right-0 mt-1.5 w-32 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                <a href="{{ route('locale.switch', 'tr') }}" class="flex items-center gap-2 px-3 py-2 text-[13px] hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ app()->getLocale() === 'tr' ? 'text-primary-600 dark:text-primary-400 font-medium' : 'text-gray-600 dark:text-gray-400' }}">
                    Türkçe
                    @if(app()->getLocale() === 'tr')
                        <svg class="w-3.5 h-3.5 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    @endif
                </a>
                <a href="{{ route('locale.switch', 'en') }}" class="flex items-center gap-2 px-3 py-2 text-[13px] hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ app()->getLocale() === 'en' ? 'text-primary-600 dark:text-primary-400 font-medium' : 'text-gray-600 dark:text-gray-400' }}">
                    English
                    @if(app()->getLocale() === 'en')
                        <svg class="w-3.5 h-3.5 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    @endif
                </a>
            </div>
        </div>
    </div>
</header>
