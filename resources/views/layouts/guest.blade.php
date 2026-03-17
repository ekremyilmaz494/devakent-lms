<!DOCTYPE html>
<html lang="tr"
    x-data="{
        darkMode: localStorage.getItem('darkMode') === 'true'
    }"
    x-effect="darkMode ? $el.classList.add('dark') : $el.classList.remove('dark')"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Giris - {{ config('app.name', 'Devakent LMS') }}</title>
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=oxanium:300,400,500,600,700|merriweather:400,700|fira-code:400,500&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex">
        {{-- Sol: Hastane Gorseli --}}
        <div class="hidden lg:flex lg:w-1/2 xl:w-[55%] relative overflow-hidden">
            <img src="{{ asset('images/building.png') }}"
                 alt="Devakent Hastanesi"
                 class="absolute inset-0 w-full h-full object-cover">
            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/20"></div>
            {{-- Alt Bilgi --}}
            <div class="absolute bottom-0 left-0 right-0 p-10 z-10">
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Devakent" class="h-10 brightness-0 invert">
                </div>
                <h2 class="text-2xl xl:text-3xl font-bold text-white mb-2">Egitim Yonetim Sistemi</h2>
                <p class="text-white/70 text-sm max-w-md">Personel egitimlerini yonetin, sinav sonuclarini takip edin ve sertifika sureclerini dijital ortamda kolayca yurutin.</p>
                <div class="flex items-center gap-6 mt-6">
                    <div class="flex items-center gap-2 text-white/60 text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Guvenli Erisim
                    </div>
                    <div class="flex items-center gap-2 text-white/60 text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Online Egitim
                    </div>
                    <div class="flex items-center gap-2 text-white/60 text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        Sertifika
                    </div>
                </div>
            </div>
        </div>

        {{-- Sag: Login Form --}}
        <div class="w-full lg:w-1/2 xl:w-[45%] flex items-center justify-center relative overflow-hidden
                    bg-[var(--color-background)] dark:bg-[var(--color-background)]">

            {{-- Animated Background Gradient Mesh --}}
            <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
                {{-- Floating Orb 1 - Top Right --}}
                <div class="login-orb login-orb--1"></div>
                {{-- Floating Orb 2 - Bottom Left --}}
                <div class="login-orb login-orb--2"></div>
                {{-- Floating Orb 3 - Center --}}
                <div class="login-orb login-orb--3"></div>
                {{-- Grid Pattern Overlay --}}
                <div class="login-grid-pattern"></div>
            </div>

            {{-- Mobil: Arka Plan Gorsel --}}
            <div class="lg:hidden absolute inset-0">
                <img src="{{ asset('images/building.png') }}"
                     alt="" class="w-full h-full object-cover opacity-10 dark:opacity-5">
            </div>

            <div class="relative z-10 w-full max-w-md px-6 py-8 lg:px-10">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
