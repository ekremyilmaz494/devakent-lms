<aside class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700
              transform transition-transform duration-300 ease-in-out
              lg:static lg:translate-x-0"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    {{-- Logo --}}
    <div class="flex items-center h-16 px-5 border-b border-gray-100 dark:border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div>
                <span class="font-bold text-gray-800 dark:text-white text-sm tracking-tight">Devakent LMS</span>
                <p class="text-[10px] text-gray-400 dark:text-gray-500 -mt-0.5">{{ __('lms.lms_subtitle') }}</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto"
         x-data="{
             openMenu: '',
             updateMenu() {
                 this.$nextTick(() => {
                     const p = window.location.pathname;
                     if (p.includes('/courses') || p.includes('/categories')) this.openMenu = 'courses';
                     else if (p.includes('/staff') || p.includes('/departments')) this.openMenu = 'staff';
                     else this.openMenu = '';
                 });
             }
         }"
         x-init="updateMenu()"
         @popstate.window="updateMenu()"
         x-on:livewire:navigated.window="updateMenu()">

        {{-- Dashboard --}}
        @php $isDashboard = request()->routeIs('admin.dashboard.index'); @endphp
        <a href="{{ route('admin.dashboard.index') }}" wire:navigate
           class="flex items-center px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-150 group {{ $isDashboard ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isDashboard ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z" />
            </svg>
            <span class="ml-3">{{ __('lms.dashboard') }}</span>
        </a>

        {{-- Eğitimler (Expandable) --}}
        <div>
            @php $isCourses = request()->routeIs('admin.courses.*') || request()->routeIs('admin.categories.*'); @endphp
            <button @click="openMenu = openMenu === 'courses' ? '' : 'courses'"
                class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-150 group {{ $isCourses ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">
                <div class="flex items-center">
                    <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isCourses ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="ml-3">{{ __('lms.courses') }}</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="openMenu === 'courses' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="openMenu === 'courses'" x-collapse class="ml-8 mt-0.5 space-y-0.5">
                <a href="{{ route('admin.courses.index') }}" wire:navigate class="block px-3 py-2 rounded-lg text-[13px] transition-colors {{ request()->routeIs('admin.courses.index') ? 'text-primary-700 dark:text-primary-300 font-medium' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">{{ __('lms.course_list') }}</a>
                <a href="{{ route('admin.categories.index') }}" wire:navigate class="block px-3 py-2 rounded-lg text-[13px] transition-colors {{ request()->routeIs('admin.categories.*') ? 'text-primary-700 dark:text-primary-300 font-medium' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">{{ __('lms.categories') }}</a>
            </div>
        </div>

        {{-- Personel (Expandable) --}}
        <div>
            @php $isStaff = request()->routeIs('admin.staff.*') || request()->routeIs('admin.departments.*'); @endphp
            <button @click="openMenu = openMenu === 'staff' ? '' : 'staff'"
                class="flex items-center justify-between w-full px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-150 group {{ $isStaff ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">
                <div class="flex items-center">
                    <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isStaff ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="ml-3">{{ __('lms.staff') }}</span>
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="openMenu === 'staff' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="openMenu === 'staff'" x-collapse class="ml-8 mt-0.5 space-y-0.5">
                <a href="{{ route('admin.staff.index') }}" wire:navigate class="block px-3 py-2 rounded-lg text-[13px] transition-colors {{ request()->routeIs('admin.staff.index') ? 'text-primary-700 dark:text-primary-300 font-medium' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">{{ __('lms.staff_list') }}</a>
                <a href="{{ route('admin.departments.index') }}" wire:navigate class="block px-3 py-2 rounded-lg text-[13px] transition-colors {{ request()->routeIs('admin.departments.*') ? 'text-primary-700 dark:text-primary-300 font-medium' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">{{ __('lms.departments') }}</a>
            </div>
        </div>

        {{-- Raporlar --}}
        @php $isReports = request()->routeIs('admin.reports.*'); @endphp
        <a href="{{ route('admin.reports.index') }}" wire:navigate
           class="flex items-center px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-150 group {{ $isReports ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isReports ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="ml-3">{{ __('lms.reports') }}</span>
        </a>

        {{-- Bildirimler --}}
        @php $isNotifications = request()->routeIs('admin.notifications.*'); @endphp
        <a href="{{ route('admin.notifications.index') }}" wire:navigate
           class="flex items-center px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-150 group {{ $isNotifications ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-white' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isNotifications ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="ml-3">{{ __('lms.notifications') }}</span>
        </a>

    </nav>

    {{-- Bottom Section --}}
    <div class="border-t border-gray-100 dark:border-gray-700">
        {{-- Settings & Activity Log --}}
        <div class="px-3 py-2 space-y-0.5">
            @php $isActivityLog = request()->routeIs('admin.activity-log.*'); @endphp
            <a href="{{ route('admin.activity-log.index') }}" wire:navigate
               class="flex items-center px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-150 group {{ $isActivityLog ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-700 dark:hover:text-gray-300' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isActivityLog ? 'text-primary-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="ml-3">{{ __('lms.activity_log') }}</span>
            </a>

            @php $isSettings = request()->routeIs('admin.settings.*'); @endphp
            <a href="{{ route('admin.settings.index') }}" wire:navigate
               class="flex items-center px-3 py-2.5 rounded-lg text-[13px] font-medium transition-all duration-150 group {{ $isSettings ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-700 dark:hover:text-gray-300' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isSettings ? 'text-primary-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="ml-3">{{ __('lms.settings') }}</span>
            </a>
        </div>

        {{-- User Profile --}}
        <div class="px-3 py-3 border-t border-gray-100 dark:border-gray-700">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center w-full px-2 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <span class="text-xs font-semibold text-white">{{ strtoupper(substr(auth()->user()->first_name ?? 'A', 0, 1) . substr(auth()->user()->last_name ?? 'Y', 0, 1)) }}</span>
                    </div>
                    <div class="ml-3 text-left flex-1 min-w-0">
                        <p class="text-[13px] font-semibold text-gray-700 dark:text-gray-200 truncate">{{ auth()->user()->full_name }}</p>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 truncate">{{ __('lms.admin') }}</p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                </button>

                {{-- Dropdown --}}
                <div x-show="open" @click.outside="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                     class="absolute bottom-full left-0 right-0 mb-1 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1.5 z-50">
                    <div class="px-3 py-2 border-b border-gray-100 dark:border-gray-700">
                        <p class="text-xs font-medium text-gray-800 dark:text-white">{{ auth()->user()->full_name }}</p>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-3 py-2 text-[13px] text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            {{ __('lms.sign_out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>
