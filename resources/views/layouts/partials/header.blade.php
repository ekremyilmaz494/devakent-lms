<header class="flex items-center justify-between h-14 px-4 md:px-6 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm border-b border-gray-100 dark:border-gray-800 sticky top-0 z-30">

    {{-- Left: Hamburger + Breadcrumb --}}
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = !sidebarOpen"
                :aria-label="sidebarOpen ? 'Menüyü kapat' : 'Menüyü aç'"
                :aria-expanded="sidebarOpen ? 'true' : 'false'"
                class="lg:hidden p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-all">
            <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="flex items-center gap-2">
            <div class="hidden sm:flex items-center gap-1.5 text-xs text-gray-400 dark:text-gray-500">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Portal</span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <h1 class="text-[15px] font-semibold text-gray-800 dark:text-white">@yield('page-title', 'Dashboard')</h1>
        </div>
    </div>

    {{-- Right Side --}}
    <div class="flex items-center gap-1.5">

        {{-- Notifications --}}
        @php
            $userId = auth()->id();
            $unreadNotifs = \Illuminate\Support\Facades\Cache::remember(
                "header.unread_notifs.{$userId}", 60,
                fn () => \App\Models\NotificationRecipient::where('user_id', $userId)->where('is_read', false)->count()
            );
            $recentNotifs = \App\Models\NotificationRecipient::where('user_id', $userId)
                ->with('notification')->orderByDesc('notification_id')->take(5)->get();
        @endphp

        <div x-data="{ notifOpen: false }" class="relative">
            <button @click="notifOpen = !notifOpen"
                    aria-label="Bildirimleri göster"
                    :aria-expanded="notifOpen ? 'true' : 'false'"
                    class="relative p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-all">
                <svg class="w-[18px] h-[18px]" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if($unreadNotifs > 0)
                    <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] flex items-center justify-center px-1 text-[10px] font-bold text-white bg-red-500 rounded-full shadow-sm shadow-red-500/40 animate-pulse">{{ $unreadNotifs > 9 ? '9+' : $unreadNotifs }}</span>
                @endif
            </button>

            <div x-show="notifOpen" @click.outside="notifOpen = false" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                 class="absolute right-0 mt-2 w-80 max-w-[calc(100vw-2rem)] bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-200/60 dark:shadow-gray-900/60 border border-gray-100 dark:border-gray-700 z-50 overflow-hidden">

                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-800">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-bold text-gray-800 dark:text-white">Bildirimler</p>
                        @if($unreadNotifs > 0)
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">{{ $unreadNotifs }} yeni</span>
                        @endif
                    </div>
                    <a href="{{ auth()->user()->hasRole('admin') ? route('admin.notifications.index') : route('staff.notifications.index') }}" class="text-[11px] font-medium text-primary-600 dark:text-primary-400 hover:underline">Tümü</a>
                </div>

                @if($recentNotifs->count() > 0)
                    <div class="max-h-72 overflow-y-auto">
                        @foreach($recentNotifs as $rn)
                        <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors border-b border-gray-50 dark:border-gray-700/50 last:border-0 {{ !$rn->is_read ? 'bg-primary-50/60 dark:bg-primary-900/10' : '' }}">
                            <div class="flex items-start gap-3">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5
                                    {{ $rn->notification->type === 'warning' ? 'bg-amber-100 dark:bg-amber-900/30' : ($rn->notification->type === 'success' ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-blue-100 dark:bg-blue-900/30') }}">
                                    <div class="w-1.5 h-1.5 rounded-full {{ !$rn->is_read ? 'bg-primary-500' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-gray-800 dark:text-white truncate">{{ $rn->notification->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-1">{{ $rn->notification->message }}</p>
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">{{ $rn->notification->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center">
                        <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Bildirim yok</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Dark Mode Toggle --}}
        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-all"
                :aria-label="darkMode ? 'Aydınlık moda geç' : 'Karanlık moda geç'">
            <svg x-show="!darkMode" x-cloak class="w-[18px] h-[18px]" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <svg x-show="darkMode" x-cloak class="w-[18px] h-[18px]" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </button>

        <div class="hidden sm:block w-px h-5 bg-gray-200 dark:bg-gray-700 mx-0.5"></div>

        {{-- Language Switcher --}}
        <div x-data="{ langOpen: false }" class="relative">
            <button @click="langOpen = !langOpen"
                    class="flex items-center gap-1.5 px-2.5 py-1.5 text-[12px] font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="hidden sm:inline">{{ strtoupper(app()->getLocale()) }}</span>
                <svg class="hidden sm:block w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="langOpen" @click.outside="langOpen = false" x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="absolute right-0 mt-1.5 w-32 bg-white dark:bg-gray-800 rounded-xl shadow-lg shadow-gray-200/50 dark:shadow-gray-900/50 border border-gray-100 dark:border-gray-700 py-1.5 z-50 overflow-hidden">
                <a href="{{ route('locale.switch', 'tr') }}"
                   class="flex items-center gap-2 px-3 py-2 text-[13px] hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ app()->getLocale() === 'tr' ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                    🇹🇷 Türkçe
                    @if(app()->getLocale() === 'tr')
                        <svg class="w-3.5 h-3.5 ml-auto text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @endif
                </a>
                <a href="{{ route('locale.switch', 'en') }}"
                   class="flex items-center gap-2 px-3 py-2 text-[13px] hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ app()->getLocale() === 'en' ? 'text-primary-600 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                    🇬🇧 English
                    @if(app()->getLocale() === 'en')
                        <svg class="w-3.5 h-3.5 ml-auto text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @endif
                </a>
            </div>
        </div>
    </div>
</header>
