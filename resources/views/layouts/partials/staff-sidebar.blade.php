<aside class="flex flex-col w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-screen">
    {{-- Logo --}}
    <div class="flex items-center h-16 px-5 border-b border-gray-100 dark:border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div>
                <span class="font-bold text-gray-800 dark:text-white text-sm tracking-tight">Devakent LMS</span>
                <p class="text-[10px] text-gray-400 dark:text-gray-500 -mt-0.5">Eğitim Portalı</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
        @php
            $menuItems = [
                ['route' => 'staff.dashboard', 'label' => 'Ana Sayfa', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z" />'],
                ['route' => 'staff.courses.index', 'label' => 'Eğitimlerim', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />'],
                ['route' => 'staff.calendar', 'label' => 'Takvim', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />'],
                ['route' => 'staff.certificates', 'label' => 'Sertifikalarım', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />'],
                ['route' => 'staff.notifications', 'label' => 'Bildirimler', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />'],
                ['route' => 'staff.profile', 'label' => 'Profilim', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />'],
            ];
        @endphp

        @foreach($menuItems as $item)
            @php $isActive = request()->routeIs($item['route'] . '*'); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-150 group {{ $isActive ? 'bg-teal-50 dark:bg-teal-900/20 text-teal-700 dark:text-teal-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isActive ? 'text-teal-500' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $item['icon'] !!}</svg>
                <span class="ml-3">{{ $item['label'] }}</span>
                @if($item['route'] === 'staff.notifications')
                    @php
                        $unreadCount = auth()->user()->notificationRecipients()->where('is_read', false)->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    @endif
                @endif
            </a>
        @endforeach
    </nav>

    {{-- User Profile --}}
    <div class="border-t border-gray-100 dark:border-gray-700">
        <div class="px-3 py-3">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center w-full px-2 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <span class="text-xs font-semibold text-white">{{ strtoupper(substr(auth()->user()->first_name ?? 'A', 0, 1) . substr(auth()->user()->last_name ?? 'Y', 0, 1)) }}</span>
                    </div>
                    <div class="ml-3 text-left flex-1 min-w-0">
                        <p class="text-[13px] font-semibold text-gray-700 dark:text-gray-200 truncate">{{ auth()->user()->full_name }}</p>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 truncate">{{ auth()->user()->title ?? 'Personel' }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                </button>

                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute bottom-full left-0 right-0 mb-1 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1.5 z-50">
                    <div class="px-3 py-2 border-b border-gray-100 dark:border-gray-700">
                        <p class="text-xs font-medium text-gray-800 dark:text-white">{{ auth()->user()->full_name }}</p>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('staff.profile') }}" class="flex items-center w-full px-3 py-2 text-[13px] text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Profilim
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-3 py-2 text-[13px] text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            Oturumu Kapat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>
