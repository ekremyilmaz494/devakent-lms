<x-guest-layout>
<div x-data="loginPage()" x-init="init()" class="relative w-full">

    {{-- ═══ ANIMATED LOGIN CARD ═══ --}}
    <div class="lc-card"
         @mousemove="onMouseMove($event)"
         @mouseleave="onMouseLeave()"
         :style="cardStyle">

        {{-- Spotlight glow --}}
        <div class="lc-spotlight" :style="spotlightStyle"></div>

        {{-- Border beam --}}
        <div class="lc-border-beam"></div>

        <div class="relative z-10 space-y-5 p-8">

            {{-- ── Logo + Dark Mode ── --}}
            <div class="lc-enter lc-enter--0 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="lc-logo-ring">
                        <img src="{{ asset('images/logo.png') }}" alt="Devakent Hastanesi" class="h-8 w-auto max-w-[120px] object-contain relative z-10">
                    </div>
                </div>
                <button @click="toggleDark()"
                        class="lc-dark-btn group"
                        title="Karanlık/Aydınlık Mod">
                    <span class="lc-dark-btn__track" :class="darkMode ? 'translate-x-4' : 'translate-x-0'"></span>
                    <svg x-show="darkMode" x-cloak x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 rotate-90 scale-50"
                         x-transition:enter-end="opacity-100 rotate-0 scale-100"
                         class="w-4 h-4 relative z-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg x-show="!darkMode" x-cloak x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -rotate-90 scale-50"
                         x-transition:enter-end="opacity-100 rotate-0 scale-100"
                         class="w-4 h-4 relative z-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
            </div>

            {{-- ── Başlık ── --}}
            <div class="lc-enter lc-enter--1 space-y-1">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                    Hoş Geldiniz
                    <span class="lc-wave" aria-hidden="true">👋</span>
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Eğitim Yönetim Sistemine giriş yapınız
                </p>
            </div>

            {{-- ── Role Tabs ── --}}
            <div class="lc-enter lc-enter--2">
                <div class="lc-tabs">
                    <div class="lc-tabs__indicator"
                         :style="role === 'admin'
                             ? 'transform: translateX(0); background: linear-gradient(135deg, #d97706, #f59e0b);'
                             : 'transform: translateX(100%); background: linear-gradient(135deg, #1d4ed8, #3b82f6);'">
                    </div>
                    <button @click="switchRole('admin')" type="button"
                            class="lc-tabs__btn"
                            :class="role === 'admin' ? 'text-white' : 'text-gray-500 dark:text-gray-400 hover:text-amber-600'">
                        <svg class="w-4 h-4 transition-transform duration-300"
                             :class="role === 'admin' ? 'scale-110' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="font-semibold text-sm">Admin</span>
                        <span x-show="role === 'admin'" x-cloak
                              class="w-1.5 h-1.5 rounded-full bg-white/70 animate-pulse"></span>
                    </button>
                    <button @click="switchRole('staff')" type="button"
                            class="lc-tabs__btn"
                            :class="role === 'staff' ? 'text-white' : 'text-gray-500 dark:text-gray-400 hover:text-blue-600'">
                        <svg class="w-4 h-4 transition-transform duration-300"
                             :class="role === 'staff' ? 'scale-110' : ''"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="font-semibold text-sm">Personel</span>
                        <span x-show="role === 'staff'" x-cloak
                              class="w-1.5 h-1.5 rounded-full bg-white/70 animate-pulse"></span>
                    </button>
                </div>
            </div>

            {{-- ── Form ── --}}
            <div class="lc-enter lc-enter--3">
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-4"
                      @submit="submitting = true">
                    @csrf

                    {{-- Email --}}
                    <div class="lc-field" :class="focusedField === 'email' ? 'lc-field--focused' : ''">
                        <div class="lc-field__icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 relative">
                            <input id="email" name="email" type="email"
                                   value="{{ old('email') }}"
                                   required autofocus autocomplete="username"
                                   placeholder=" "
                                   @focus="focusedField = 'email'"
                                   @blur="focusedField = ''"
                                   class="lc-input peer focus:outline-none focus:ring-0 focus:border-transparent" />
                            <label for="email" class="lc-label">E-posta Adresi</label>
                        </div>
                        <div class="lc-field__glow"
                             :class="role === 'admin' ? 'lc-field__glow--amber' : 'lc-field__glow--blue'"></div>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs" />

                    {{-- Password --}}
                    <div class="lc-field" :class="focusedField === 'password' ? 'lc-field--focused' : ''"
                         x-data="{ showPass: false }">
                        <div class="lc-field__icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div class="flex-1 relative">
                            <input id="password" name="password"
                                   :type="showPass ? 'text' : 'password'"
                                   required autocomplete="current-password"
                                   placeholder=" "
                                   @focus="focusedField = 'password'"
                                   @blur="focusedField = ''"
                                   class="lc-input peer pr-8 focus:outline-none focus:ring-0 focus:border-transparent" />
                            <label for="password" class="lc-label">Şifre</label>
                        </div>
                        <button type="button" @click="showPass = !showPass"
                                class="lc-eye-btn">
                            <svg x-show="!showPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                        <div class="lc-field__glow"
                             :class="role === 'admin' ? 'lc-field__glow--amber' : 'lc-field__glow--blue'"></div>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />

                    {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="flex items-center gap-2 cursor-pointer group">
                            <div class="lc-checkbox-wrap">
                                <input id="remember_me" type="checkbox" name="remember"
                                       class="sr-only peer" />
                                <div class="lc-checkbox peer-checked:lc-checkbox--checked"
                                     :class="role === 'admin' ? 'peer-checked:border-amber-500 peer-checked:bg-amber-500'
                                                              : 'peer-checked:border-blue-500 peer-checked:bg-blue-500'">
                                    <svg class="w-2.5 h-2.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition-colors select-none">
                                Beni hatırla
                            </span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-sm font-medium transition-all duration-200 hover:underline"
                               :class="role === 'admin'
                                   ? 'text-amber-600 dark:text-amber-400 hover:text-amber-700'
                                   : 'text-blue-600 dark:text-blue-400 hover:text-blue-700'">
                                Şifremi unuttum
                            </a>
                        @endif
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                            class="lc-submit-btn"
                            :class="submitting ? 'lc-submit-btn--loading' : ''"
                            :disabled="submitting"
                            :style="role === 'admin'
                                ? 'background: linear-gradient(135deg, #d97706 0%, #f59e0b 50%, #d97706 100%); background-size: 200% 100%; box-shadow: 0 8px 25px -5px rgba(217,119,6,0.45), 0 0 0 0 rgba(245,158,11,0);'
                                : 'background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 50%, #1d4ed8 100%); background-size: 200% 100%; box-shadow: 0 8px 25px -5px rgba(59,130,246,0.45), 0 0 0 0 rgba(59,130,246,0);'">
                        <span class="lc-submit-btn__content" :class="submitting ? 'opacity-0' : 'opacity-100'">
                            <svg x-show="role === 'admin'" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <svg x-show="role === 'staff'" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span x-text="role === 'admin' ? 'Admin Girişi' : 'Personel Girişi'"></span>
                            <svg class="w-4 h-4 lc-submit-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </span>
                        {{-- Loading spinner --}}
                        <span class="lc-submit-btn__spinner absolute inset-0 flex items-center justify-center"
                              :class="submitting ? 'opacity-100' : 'opacity-0'">
                            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                        </span>
                        {{-- Ripple effect --}}
                        <span class="lc-submit-btn__shimmer"></span>
                    </button>
                </form>
            </div>

            {{-- ── Footer ── --}}
            <div class="lc-enter lc-enter--4 pt-4 border-t border-gray-200/50 dark:border-gray-700/40 text-center">
                <p class="text-xs text-gray-400 dark:text-gray-500 flex items-center justify-center gap-1.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    &copy; {{ date('Y') }} Devakent Hastanesi — Güvenli Bağlantı
                </p>
            </div>

        </div>
    </div>
</div>

<script>
function loginPage() {
    return {
        role: 'admin',
        darkMode: localStorage.getItem('darkMode') === 'true',
        focusedField: '',
        submitting: false,
        mouseX: 0,
        mouseY: 0,
        cardRect: null,

        init() {
            this.$nextTick(() => {
                document.querySelectorAll('.lc-enter').forEach((el, i) => {
                    setTimeout(() => {
                        el.classList.add('lc-enter--visible');
                    }, 80 + i * 90);
                });
            });
        },

        toggleDark() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },

        switchRole(r) {
            this.role = r;
        },

        onMouseMove(e) {
            const el = e.currentTarget;
            const rect = el.getBoundingClientRect();
            this.mouseX = e.clientX - rect.left;
            this.mouseY = e.clientY - rect.top;
        },

        onMouseLeave() {
            this.mouseX = -999;
            this.mouseY = -999;
        },

        get cardStyle() {
            if (this.mouseX === -999) return '';
            const el = this.$el.querySelector('.lc-card');
            if (!el) return '';
            const rect = el.getBoundingClientRect();
            const cx = rect.width / 2;
            const cy = rect.height / 2;
            const dx = (this.mouseX - cx) / cx;
            const dy = (this.mouseY - cy) / cy;
            const rx = -dy * 4;
            const ry = dx * 4;
            return `transform: perspective(800px) rotateX(${rx}deg) rotateY(${ry}deg) scale(1.005); transition: transform 0.15s ease;`;
        },

        get spotlightStyle() {
            if (this.mouseX === -999) return 'opacity: 0;';
            return `background: radial-gradient(300px circle at ${this.mouseX}px ${this.mouseY}px, rgba(${this.role === 'admin' ? '245,158,11' : '59,130,246'},0.08) 0%, transparent 70%); opacity: 1;`;
        },
    };
}
</script>
</x-guest-layout>
