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

    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="preload" as="style" href="https://fonts.bunny.net/css?family=oxanium:300,400,500,600,700|merriweather:400,700|fira-code:400,500&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.bunny.net/css?family=oxanium:300,400,500,600,700|merriweather:400,700|fira-code:400,500&display=swap" rel="stylesheet"></noscript>

    @vite(['resources/css/app.css', 'resources/css/login.css', 'resources/js/app.js'])
    {{-- Guest layout has no Livewire, so we start Alpine manually after app.js loads --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.Alpine) window.Alpine.start();
        });
    </script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex">
        {{-- Sol: Hastane Gorseli --}}
        <div class="hidden lg:flex lg:w-1/2 xl:w-[55%] relative overflow-hidden" x-data="leftPanel()" x-init="init()">
            <img src="{{ asset('images/building.png') }}"
                 alt="Devakent Hastanesi"
                 class="absolute inset-0 w-full h-full object-cover scale-105"
                 style="animation: lp-subtle-zoom 20s ease-in-out infinite alternate;">
            {{-- Multi-layer gradient --}}
            <div class="absolute inset-0"
                 style="background: linear-gradient(to top, rgba(0,0,0,0.88) 0%, rgba(0,0,0,0.45) 40%, rgba(0,0,0,0.15) 70%, rgba(0,0,0,0.05) 100%);"></div>
            {{-- Ambient color overlay --}}
            <div class="absolute inset-0 opacity-30"
                 style="background: radial-gradient(ellipse at 30% 80%, rgba(217,119,6,0.4) 0%, transparent 60%);"></div>

            {{-- Floating stats cards --}}
            <div class="absolute top-8 right-8 space-y-3 z-10">
                <div class="lp-stat-card lp-stat-card--0">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-white font-bold text-base leading-tight">1.240+</div>
                        <div class="text-white/50 text-xs">Aktif Personel</div>
                    </div>
                </div>
                <div class="lp-stat-card lp-stat-card--1">
                    <div class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-white font-bold text-base leading-tight">96%</div>
                        <div class="text-white/50 text-xs">Tamamlanma Oranı</div>
                    </div>
                </div>
                <div class="lp-stat-card lp-stat-card--2">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-white font-bold text-base leading-tight">340+</div>
                        <div class="text-white/50 text-xs">Eğitim Modülü</div>
                    </div>
                </div>
            </div>

            {{-- Alt Bilgi --}}
            <div class="absolute bottom-0 left-0 right-0 p-10 z-10">
                <div class="lp-bottom-enter lp-bottom-enter--0 flex items-center gap-3 mb-5">
                    <img src="{{ asset('images/logo.png') }}" alt="Devakent" class="h-10 brightness-0 invert drop-shadow-lg">
                    <div class="w-px h-8 bg-white/20"></div>
                    <div>
                        <div class="text-white/90 text-xs font-medium tracking-widest uppercase">Devakent Hastanesi</div>
                    </div>
                </div>
                <h2 class="lp-bottom-enter lp-bottom-enter--1 text-2xl xl:text-3xl font-bold text-white mb-3 leading-tight">
                    Eğitim Yönetim<br>
                    <span class="text-amber-400">Sistemi</span>
                </h2>
                <p class="lp-bottom-enter lp-bottom-enter--2 text-white/65 text-sm max-w-sm leading-relaxed">
                    Personel eğitimlerini yönetin, sınav sonuçlarını takip edin ve sertifika süreçlerini dijital ortamda kolayca yürütün.
                </p>
                <div class="lp-bottom-enter lp-bottom-enter--3 flex items-center gap-4 mt-6 flex-wrap">
                    <div class="lp-badge">
                        <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>Güvenli Erişim</span>
                    </div>
                    <div class="lp-badge">
                        <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.88v6.24a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span>Video Eğitim</span>
                    </div>
                    <div class="lp-badge">
                        <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                        <span>Sertifika</span>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function leftPanel() {
            return {
                init() {
                    this.$nextTick(() => {
                        document.querySelectorAll('.lp-stat-card').forEach((el, i) => {
                            setTimeout(() => el.classList.add('lp-stat-card--visible'), 400 + i * 150);
                        });
                        document.querySelectorAll('.lp-bottom-enter').forEach((el, i) => {
                            setTimeout(() => el.classList.add('lp-bottom-enter--visible'), 200 + i * 120);
                        });
                    });
                }
            };
        }
        </script>

        {{-- Sag: Login Form --}}
        <div class="w-full lg:w-1/2 xl:w-[45%] flex items-center justify-center relative overflow-hidden
                    bg-[var(--color-background)] dark:bg-[var(--color-background)]">

            {{-- Aurora Animated Background --}}
            <div class="absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
                {{-- Canvas: floating light particles --}}
                <canvas id="loginParticles" class="absolute inset-0 w-full h-full"></canvas>
                {{-- Aurora blobs --}}
                <div class="aurora-blob aurora-blob--1"></div>
                <div class="aurora-blob aurora-blob--2"></div>
                <div class="aurora-blob aurora-blob--3"></div>
                <div class="aurora-blob aurora-blob--4"></div>
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

<script>
(function initLoginParticles() {
    var canvas = document.getElementById('loginParticles');
    if (!canvas) return;
    var ctx = canvas.getContext('2d');
    var particles = [];

    function resize() {
        canvas.width  = canvas.parentElement ? canvas.parentElement.offsetWidth  : window.innerWidth / 2;
        canvas.height = canvas.parentElement ? canvas.parentElement.offsetHeight : window.innerHeight;
    }

    function rand(a, b) { return Math.random() * (b - a) + a; }

    function spawn(fromBottom) {
        return {
            x:       rand(0, canvas.width),
            y:       fromBottom ? canvas.height + rand(5, 20) : rand(0, canvas.height),
            r:       rand(0.7, 2.2),
            vx:      rand(-0.18, 0.18),
            vy:      -rand(0.12, 0.45),
            maxAlpha: rand(0.2, 0.6),
            life:    fromBottom ? 0 : rand(0, 300),
            maxLife: rand(220, 450),
        };
    }

    function init() {
        resize();
        particles = [];
        for (var i = 0; i < 65; i++) particles.push(spawn(false));
        window.addEventListener('resize', resize);
    }

    function frame() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        var isDark = document.documentElement.classList.contains('dark');
        var col = isDark ? '251,146,60' : '180,83,9';

        for (var i = 0; i < particles.length; i++) {
            var p = particles[i];
            p.x += p.vx;
            p.y += p.vy;
            p.life++;

            if (p.life >= p.maxLife || p.y < -10) {
                particles[i] = spawn(true);
                continue;
            }

            var t = p.life / p.maxLife;
            var a = t < 0.2  ? p.maxAlpha * (t / 0.2)
                  : t < 0.78 ? p.maxAlpha
                  : p.maxAlpha * ((1 - t) / 0.22);

            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, 6.2832);
            ctx.fillStyle = 'rgba(' + col + ',' + Math.max(0, a).toFixed(3) + ')';
            ctx.fill();
        }
        requestAnimationFrame(frame);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() { init(); frame(); });
    } else { init(); frame(); }
})();
</script>
</body>
</html>
