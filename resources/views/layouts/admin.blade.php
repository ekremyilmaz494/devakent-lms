<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}"
    x-data="{
        darkMode: localStorage.getItem('darkMode') === 'true',
        sidebarOpen: false
    }"
    x-effect="darkMode ? $el.classList.add('dark') : $el.classList.remove('dark')"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Yönetici Paneli') — {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="preload" as="style" href="https://fonts.bunny.net/css?family=oxanium:300,400,500,600,700|merriweather:400,700|fira-code:400,500&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.bunny.net/css?family=oxanium:300,400,500,600,700|merriweather:400,700|fira-code:400,500&display=swap" rel="stylesheet"></noscript>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="flex h-screen overflow-hidden">
        {{-- Mobile sidebar backdrop --}}
        <div x-show="sidebarOpen"
             x-cloak
             @click="sidebarOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

        {{-- Sidebar --}}
        @include('layouts.partials.admin-sidebar')

        {{-- Main Content --}}
        <div class="flex flex-col flex-1 overflow-hidden">
            {{-- Header --}}
            @include('layouts.partials.header')

            {{-- Info Banner --}}
            @if(session('success') || session('error'))
                <div class="px-6 pt-4">
                    @if(session('success'))
                        <div class="flex items-center gap-3 p-3 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm"
                             x-data="{ show: true }" x-show="show"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="flex-1">{{ session('success') }}</span>
                            <button @click="show = false" class="text-emerald-400 hover:text-emerald-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="flex items-center gap-3 p-3 bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl text-sm"
                             x-data="{ show: true }" x-show="show"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="flex-1">{{ session('error') }}</span>
                            <button @click="show = false" class="text-red-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Breadcrumb --}}
            @hasSection('breadcrumb')
                <div class="px-6 pt-4 pb-0">
                    @yield('breadcrumb')
                </div>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-6 @hasSection('breadcrumb') pt-3 @endif">
                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
    @livewireScriptConfig
    @stack('scripts')
</body>
</html>
