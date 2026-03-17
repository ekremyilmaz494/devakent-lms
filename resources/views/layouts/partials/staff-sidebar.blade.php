<div class="flex flex-col w-full h-full bg-white dark:bg-gray-900 border-r border-gray-100 dark:border-gray-800">

    {{-- Logo --}}
    <div class="flex items-center justify-between h-16 px-5 border-b border-gray-100 dark:border-gray-800">
        <div class="flex items-center gap-3">
            <div class="relative w-9 h-9 flex-shrink-0">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-400 to-primary-700 rounded-xl shadow-lg shadow-primary-500/25"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <div>
                <span class="font-bold text-gray-900 dark:text-white text-sm tracking-tight">Devakent LMS</span>
                <p class="text-[10px] text-gray-400 dark:text-gray-500 -mt-0.5 font-medium">Eğitim Portalı</p>
            </div>
        </div>
        <button @click="sidebarOpen = false" aria-label="Menüyü kapat" class="lg:hidden p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
            <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5">
        @php
            $menuItems = [
                ['route' => 'staff.dashboard',      'label' => 'Ana Sayfa',     'icon' => 'M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z'],
                ['route' => 'staff.courses.index',  'label' => 'Eğitimlerim',   'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['route' => 'staff.calendar',       'label' => 'Takvim',        'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['route' => 'staff.certificates',   'label' => 'Sertifikalarım','icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
                ['route' => 'staff.notifications.index',  'label' => 'Bildirimler',   'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                ['route' => 'staff.leaderboard',    'label' => 'Liderlik',      'icon' => 'M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0116.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.003 6.003 0 01-3.77 1.522m0 0a6.003 6.003 0 01-3.77-1.522'],
                ['route' => 'staff.badges',         'label' => 'Rozetlerim',    'icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z'],
                ['route' => 'staff.profile',        'label' => 'Profilim',      'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ];
        @endphp

        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-600 px-3 pb-2 pt-1">Menü</p>

        @foreach($menuItems as $item)
            @php $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*'); @endphp
            <a href="{{ route($item['route']) }}" wire:navigate @click="sidebarOpen = false"
               class="relative flex items-center px-3 py-2.5 rounded-xl text-[13px] font-medium transition-all duration-150 group
                      {{ $isActive
                          ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300'
                          : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white' }}">

                @if($isActive)
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-primary-500 rounded-r-full"></span>
                @endif

                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mr-3 transition-all duration-150
                            {{ $isActive ? 'bg-primary-100 dark:bg-primary-900/40' : 'group-hover:bg-gray-100 dark:group-hover:bg-gray-800' }}">
                    <svg class="w-[17px] h-[17px] {{ $isActive ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $item['icon'] }}"/>
                    </svg>
                </div>

                <span class="flex-1 truncate">{{ $item['label'] }}</span>

                @if($item['route'] === 'staff.notifications.index')
                    @php
                        $userId = auth()->id();
                        $unreadCount = \Illuminate\Support\Facades\Cache::remember(
                            "staff.unread_notifications.{$userId}", 60,
                            fn () => auth()->user()->notificationRecipients()->where('is_read', false)->count()
                        );
                    @endphp
                    @if($unreadCount > 0)
                        <span class="ml-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center leading-none shadow-sm">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    @endif
                @endif

                @if($isActive)
                    <svg class="ml-1 w-3 h-3 text-primary-400 dark:text-primary-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                @endif
            </a>
        @endforeach
    </nav>

    {{-- User Profile --}}
    <div class="border-t border-gray-100 dark:border-gray-800 p-3">
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="flex items-center w-full px-3 py-2.5 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-150">
                <div class="relative flex-shrink-0">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-md shadow-primary-500/25">
                        <span class="text-xs font-bold text-white">{{ strtoupper(substr(auth()->user()->first_name ?? 'A', 0, 1) . substr(auth()->user()->last_name ?? 'Y', 0, 1)) }}</span>
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-400 border-2 border-white dark:border-gray-900 rounded-full"></span>
                </div>
                <div class="ml-3 text-left flex-1 min-w-0">
                    <p class="text-[13px] font-semibold text-gray-800 dark:text-white truncate">{{ auth()->user()->full_name }}</p>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 truncate font-medium">{{ auth()->user()->title ?? 'Personel' }}</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                </svg>
            </button>

            <div x-show="open" @click.away="open = false" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute bottom-full left-0 right-0 mb-2 bg-white dark:bg-gray-800 rounded-2xl shadow-xl shadow-gray-300/40 dark:shadow-gray-900/60 border border-gray-100 dark:border-gray-700 overflow-hidden z-50">

                <div class="px-4 py-3 bg-gradient-to-br from-primary-50 to-white dark:from-primary-900/10 dark:to-transparent border-b border-gray-100 dark:border-gray-700">
                    <p class="text-xs font-bold text-gray-900 dark:text-white">{{ auth()->user()->full_name }}</p>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">{{ auth()->user()->email }}</p>
                </div>

                <div class="p-2 space-y-0.5">
                    <a href="{{ route('staff.profile') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-[13px] text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white transition-all">
                        <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        Profilim
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-2.5 w-full px-3 py-2 rounded-xl text-[13px] text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 hover:text-red-600 transition-all">
                            <div class="w-7 h-7 rounded-lg bg-red-50 dark:bg-red-900/20 flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            Oturumu Kapat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
