<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Erisim Engellendi | Devakent LMS</title>
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=oxanium:400,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-[var(--color-background)] min-h-screen flex items-center justify-center p-6">
    <div class="text-center max-w-lg">
        <div class="w-24 h-24 bg-gradient-to-br from-red-400 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-lg shadow-red-200">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Erisim Engellendi</h1>
        <p class="text-gray-500 mb-8">Bu sayfaya erisim yetkiniz bulunmamaktadir. Yetkili oldugunu dusunuyorsaniz yoneticinize basvurunuz.</p>
        <div class="flex items-center justify-center gap-3">
            <a href="{{ url('/') }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-500 text-white font-semibold text-sm rounded-xl hover:from-primary-700 hover:to-primary-600 transition-all shadow-lg shadow-primary-500/25">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Ana Sayfa
            </a>
            <button onclick="history.back()"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-semibold text-sm rounded-xl hover:bg-gray-200 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Geri Don
            </button>
        </div>
        <p class="text-xs text-gray-400 mt-12">&copy; {{ date('Y') }} Devakent Hastanesi</p>
    </div>
</body>
</html>
