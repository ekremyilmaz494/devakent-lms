<header class="flex items-center justify-between h-14 px-6 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    {{-- Left: Page Title + Breadcrumb --}}
    <div>
        <h1 class="text-[15px] font-semibold text-gray-800 dark:text-white">@yield('page-title', 'Dashboard')</h1>
    </div>

    {{-- Right Side --}}
    <div class="flex items-center gap-2">
        {{-- Search --}}
        <div class="relative hidden md:block" x-data="{ searchOpen: false }">
            <button @click="searchOpen = !searchOpen" x-show="!searchOpen" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </button>
            <div x-show="searchOpen" x-transition @click.away="searchOpen = false" class="absolute right-0 top-0">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" placeholder="Ara..." autofocus class="w-64 pl-9 pr-4 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-violet-500 focus:border-transparent" />
                </div>
            </div>
        </div>

        {{-- Notifications --}}
        <div x-data="{ notifOpen: false }" class="relative">
            <button @click="notifOpen = !notifOpen" class="relative p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                {{-- Badge --}}
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <div x-show="notifOpen" @click.away="notifOpen = false" x-transition class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                    <p class="text-sm font-semibold text-gray-800 dark:text-white">Bildirimler</p>
                </div>
                <div class="p-4 text-center text-sm text-gray-400 dark:text-gray-500">
                    <p>Henüz bildirim yok</p>
                </div>
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
        <div class="w-px h-6 bg-gray-200 dark:bg-gray-700 mx-1"></div>

        {{-- Language --}}
        <button class="flex items-center gap-1.5 px-2.5 py-1.5 text-[13px] text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>TR</span>
        </button>
    </div>
</header>
