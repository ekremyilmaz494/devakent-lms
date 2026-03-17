<x-guest-layout>
<div x-data="loginPage()" x-init="$nextTick(() => showElements = true)" class="relative">

    {{-- Animated Card --}}
    <div class="login-card relative rounded-2xl border border-gray-200/60 dark:border-gray-700/40
                bg-white/80 dark:bg-gray-800/70 p-8 shadow-xl
                transition-shadow duration-500 hover:shadow-2xl">

        <div class="relative z-10 space-y-6">

            {{-- Logo + Dark Mode --}}
            <div class="transition-all duration-500 ease-out"
                 :class="showElements ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                 style="transition-delay: 0.05s;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Devakent Hastanesi" class="h-12">
                    </div>

                    <button
                        @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                        class="p-2.5 rounded-xl bg-gray-100 dark:bg-gray-700
                               hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-300
                               text-gray-500 dark:text-gray-400 hover:rotate-12"
                        title="Karanlik/Aydinlik Mod"
                    >
                        <svg x-show="darkMode" x-cloak x-transition class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg x-show="!darkMode" x-cloak x-transition class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Baslik --}}
            <div class="transition-all duration-500 ease-out"
                 :class="showElements ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                 style="transition-delay: 0.1s;">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Hos Geldiniz</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Egitim Yonetim Sistemine giris yapiniz</p>
            </div>

            {{-- Tabs: Admin / Personel --}}
            <div class="transition-all duration-500 ease-out"
                 :class="showElements ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                 style="transition-delay: 0.15s;">
                <div class="relative flex bg-gray-100 dark:bg-gray-700/50 rounded-xl p-1">
                    <div class="tab-indicator absolute top-1 bottom-1 rounded-lg shadow-md"
                         :class="role === 'admin'
                             ? 'bg-gradient-to-r from-primary-600 to-primary-500'
                             : 'bg-gradient-to-r from-blue-600 to-blue-500'"
                         :style="role === 'admin'
                             ? 'transform: translateX(0); width: calc(50% - 4px); left: 4px;'
                             : 'transform: translateX(100%); width: calc(50% - 4px); left: 4px;'"
                    ></div>

                    <button @click="role = 'admin'" type="button"
                            class="relative z-10 flex-1 flex items-center justify-center gap-2 py-2.5
                                   rounded-lg text-sm font-semibold transition-all duration-200"
                            :class="role === 'admin'
                                ? 'text-white'
                                : 'text-gray-400 dark:text-gray-500 hover:text-primary-700 dark:hover:text-primary-300 hover:-translate-y-0.5 hover:bg-primary-50/80 dark:hover:bg-primary-900/30'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Admin</span>
                    </button>

                    <button @click="role = 'staff'" type="button"
                            class="relative z-10 flex-1 flex items-center justify-center gap-2 py-2.5
                                   rounded-lg text-sm font-semibold transition-all duration-200"
                            :class="role === 'staff'
                                ? 'text-white'
                                : 'text-gray-400 dark:text-gray-500 hover:text-blue-700 dark:hover:text-blue-300 hover:-translate-y-0.5 hover:bg-blue-50/80 dark:hover:bg-blue-900/30'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Personel</span>
                    </button>
                </div>
            </div>

            {{-- Form --}}
            <div class="transition-all duration-500 ease-out"
                 :class="showElements ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                 style="transition-delay: 0.2s;">
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div class="floating-label-group">
                        <input
                            id="email" name="email" type="email"
                            value="{{ old('email') }}"
                            required autofocus autocomplete="username"
                            placeholder=" "
                            class="peer w-full px-3.5 pt-5 pb-2 rounded-xl border border-gray-200
                                   dark:border-gray-600 bg-white dark:bg-gray-800
                                   text-gray-900 dark:text-gray-100 text-sm
                                   focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500
                                   dark:focus:border-primary-400 transition-all duration-200 outline-none"
                        />
                        <label for="email"
                               class="text-sm text-gray-400 dark:text-gray-500
                                      peer-focus:text-primary-500 dark:peer-focus:text-primary-400
                                      peer-[:not(:placeholder-shown)]:text-primary-500
                                      dark:peer-[:not(:placeholder-shown)]:text-primary-400">
                            E-posta Adresi
                        </label>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                    </div>

                    {{-- Password --}}
                    <div class="floating-label-group" x-data="{ showPass: false }">
                        <input
                            id="password" name="password"
                            :type="showPass ? 'text' : 'password'"
                            required autocomplete="current-password"
                            placeholder=" "
                            class="peer w-full px-3.5 pt-5 pb-2 pr-11 rounded-xl border border-gray-200
                                   dark:border-gray-600 bg-white dark:bg-gray-800
                                   text-gray-900 dark:text-gray-100 text-sm
                                   focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500
                                   dark:focus:border-primary-400 transition-all duration-200 outline-none"
                        />
                        <label for="password"
                               class="text-sm text-gray-400 dark:text-gray-500
                                      peer-focus:text-primary-500 dark:peer-focus:text-primary-400
                                      peer-[:not(:placeholder-shown)]:text-primary-500
                                      dark:peer-[:not(:placeholder-shown)]:text-primary-400">
                            Sifre
                        </label>
                        <button type="button" @click="showPass = !showPass"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400
                                       hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-1">
                            <svg x-show="!showPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                    </div>

                    {{-- Remember Me + Forgot Password --}}
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center gap-2 cursor-pointer group">
                            <input id="remember_me" type="checkbox" name="remember"
                                   class="w-4 h-4 rounded border-gray-300 dark:border-gray-600
                                          text-primary-600 bg-white dark:bg-gray-800
                                          focus:ring-primary-500 focus:ring-offset-0 transition-colors" />
                            <span class="text-sm text-gray-500 dark:text-gray-400
                                         group-hover:text-gray-700 dark:group-hover:text-gray-300
                                         transition-colors select-none">
                                Beni hatirla
                            </span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-sm text-primary-600 dark:text-primary-400
                                      hover:text-primary-700 dark:hover:text-primary-300
                                      hover:underline transition-colors">
                                Sifremi unuttum
                            </a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="login-submit-btn relative z-[2] w-full py-3.5 px-4 rounded-xl
                                   text-white font-semibold text-sm
                                   shadow-lg hover:scale-[1.02] active:scale-[0.98]
                                   transition-all duration-300
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                            :style="role === 'admin'
                                ? 'background: linear-gradient(to right, rgb(217,119,6), rgb(245,158,11)); box-shadow: 0 4px 15px -3px rgba(245,158,11,0.4);'
                                : 'background: linear-gradient(to right, rgb(37,99,235), rgb(59,130,246)); box-shadow: 0 4px 15px -3px rgba(59,130,246,0.4);'">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <svg x-show="role === 'admin'" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <svg x-show="role === 'staff'" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            <span x-text="role === 'admin' ? 'Admin Girisi' : 'Personel Girisi'">Giris Yap</span>
                        </span>
                    </button>
                </form>
            </div>

            {{-- Footer --}}
            <div class="text-center pt-4 border-t border-gray-200/60 dark:border-gray-700/40
                        transition-all duration-500 ease-out"
                 :class="showElements ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                 style="transition-delay: 0.3s;">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    &copy; {{ date('Y') }} Devakent Hastanesi. Tum haklari saklidir.
                </p>
            </div>

        </div>
    </div>
</div>

<script>
function loginPage() {
    return {
        role: 'admin',
        showElements: false
    };
}
</script>
</x-guest-layout>
