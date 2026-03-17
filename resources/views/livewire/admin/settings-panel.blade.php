<div>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Vertical Tab Menu (1/4) --}}
        <div class="lg:col-span-1">
            <nav class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
                @foreach([
                    'general' => [__('lms.general_settings'), 'M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z'],
                    'exam' => [__('lms.exam_defaults'), 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z'],
                    'security' => [__('lms.security_settings'), 'M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z'],
                    'email' => [__('lms.email_settings'), 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75'],
                    'backup' => [__('lms.backup_restore'), 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125'],
                    'system' => [__('lms.system_info'), 'M11.42 15.17l-5.658-5.658a1.125 1.125 0 010-1.591L12.36 1.32a1.125 1.125 0 011.59 0l5.66 5.659a1.125 1.125 0 010 1.59L13.01 15.17a1.125 1.125 0 01-1.59 0z'],
                ] as $tab => $info)
                    <button wire:click="$set('activeTab', '{{ $tab }}')"
                        class="w-full flex items-center gap-3 px-4 py-3.5 text-sm font-medium transition-colors border-l-2 {{ $activeTab === $tab ? 'bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 border-primary-600' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/30 border-transparent hover:text-gray-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $info[1] }}" /></svg>
                        {{ $info[0] }}
                    </button>
                @endforeach
            </nav>
        </div>

        {{-- Settings Form Panel (3/4) --}}
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>

                {{-- General Settings --}}
                @if($activeTab === 'general')
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('lms.general_settings') }}</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Kurum bilgileri ve temel yapılandırma</p>
                    </div>
                    <form wire:submit="saveGeneral" class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5 block">{{ __('lms.institution_name') }}</label>
                                <input wire:model="institution_name" type="text" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                                @error('institution_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5 block">{{ __('lms.institution_subtitle') }}</label>
                                <input wire:model="institution_subtitle" type="text" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5 block">{{ __('lms.logo_url') }}</label>
                            <input wire:model="logo_url" type="text" placeholder="https://..." class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500 placeholder-gray-400" />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5 block">{{ __('lms.timezone') }}</label>
                                <select wire:model="timezone" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="Europe/Istanbul">Europe/Istanbul (UTC+3)</option>
                                    <option value="UTC">UTC</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5 block">{{ __('lms.default_language') }}</label>
                                <select wire:model="default_language" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="tr">Türkçe</option>
                                    <option value="en">English</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-3 px-4 bg-amber-100 dark:bg-amber-900/30 rounded-lg border border-amber-200 dark:border-amber-800/30">
                            <div>
                                <p class="text-sm font-medium text-amber-800 dark:text-amber-300">{{ __('lms.maintenance_mode') }}</p>
                                <p class="text-xs text-amber-600 dark:text-amber-400">Aktifken sadece adminler giriş yapabilir</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input wire:model="maintenance_mode" type="checkbox" class="sr-only peer" />
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-300 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>
                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm disabled:opacity-50" wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ __('lms.save') }}</span>
                                <span wire:loading class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    {{ __('lms.saving') }}
                                </span>
                            </button>
                        </div>
                    </form>
                @endif

                {{-- Exam Defaults --}}
                @if($activeTab === 'exam')
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('lms.exam_defaults') }}</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Yeni eğitim oluştururken kullanılacak varsayılan değerler</p>
                    </div>
                    <form wire:submit="saveExam" class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5 block">{{ __('lms.exam_duration') }}</label>
                                <input wire:model="default_exam_duration" type="number" min="5" max="180" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5 block">{{ __('lms.passing_score_label') }}</label>
                                <input wire:model="default_passing_score" type="number" min="1" max="100" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5 block">{{ __('lms.max_attempts') }}</label>
                                <input wire:model="default_max_attempts" type="number" min="1" max="10" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="flex items-center justify-between py-3 px-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg cursor-pointer">
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Soruları Karıştır</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Her denemede soruların sırası değişir</p>
                                </div>
                                <input wire:model="shuffle_questions" type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                            </label>
                            <label class="flex items-center justify-between py-3 px-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg cursor-pointer">
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Sonuçları Hemen Göster</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">Sınav bitince sonuç hemen gösterilir</p>
                                </div>
                                <input wire:model="show_results_immediately" type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                            </label>
                        </div>
                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm disabled:opacity-50" wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ __('lms.save') }}</span>
                                <span wire:loading class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    {{ __('lms.saving') }}
                                </span>
                            </button>
                        </div>
                    </form>
                @endif

                {{-- Security --}}
                @if($activeTab === 'security')
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('lms.security_settings') }}</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Oturum ve şifre güvenlik politikaları</p>
                    </div>
                    <form wire:submit="saveSecurity" class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5 block">Oturum Zaman Aşımı (dk)</label>
                                <input wire:model="session_timeout" type="number" min="5" max="480" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5 block">Maks. Giriş Denemesi</label>
                                <input wire:model="max_login_attempts" type="number" min="1" max="20" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5 block">Min. Şifre Uzunluğu</label>
                                <input wire:model="password_min_length" type="number" min="4" max="32" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                            </div>
                        </div>
                        <label class="flex items-center justify-between py-3 px-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg cursor-pointer">
                            <div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">İlk Girişte Şifre Değiştirme Zorla</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">Yeni oluşturulan personel ilk girişte şifre değiştirmek zorunda kalır</p>
                            </div>
                            <input wire:model="force_password_change" type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                        </label>
                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm disabled:opacity-50" wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ __('lms.save') }}</span>
                                <span wire:loading class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    {{ __('lms.saving') }}
                                </span>
                            </button>
                        </div>
                    </form>
                @endif

                {{-- Email Notifications --}}
                @if($activeTab === 'email')
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('lms.email_settings') }}</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Otomatik e-posta bildirim tercihleri</p>
                    </div>
                    <form wire:submit="saveEmail" class="p-6 space-y-3">
                        @foreach([
                            ['email_on_enrollment', 'Eğitim Kaydı', 'Personel yeni bir eğitime kaydedildiğinde'],
                            ['email_on_completion', 'Eğitim Tamamlama', 'Personel bir eğitimi tamamladığında'],
                            ['email_on_certificate', 'Sertifika Düzenleme', 'Sertifika düzenlendiğinde'],
                            ['email_weekly_report', 'Haftalık Rapor', 'Her hafta başında özet rapor gönderilir'],
                        ] as $item)
                            <label class="flex items-center justify-between py-3 px-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg cursor-pointer">
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $item[1] }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $item[2] }}</p>
                                </div>
                                <input wire:model="{{ $item[0] }}" type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                            </label>
                        @endforeach
                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm disabled:opacity-50" wire:loading.attr="disabled">
                                <span wire:loading.remove>{{ __('lms.save') }}</span>
                                <span wire:loading class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    {{ __('lms.saving') }}
                                </span>
                            </button>
                        </div>
                    </form>
                @endif

                {{-- Backup --}}
                @if($activeTab === 'backup')
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('lms.backup_restore') }}</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Veritabanı yedeklerini oluşturun, indirin veya silin</p>
                    </div>
                    <div class="p-6 space-y-6">
                        {{-- Flash Messages --}}
                        @if(session('success'))
                            <div class="flex items-center gap-3 p-3.5 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl">
                                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm text-emerald-700 dark:text-emerald-300 font-medium">{{ session('success') }}</p>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="flex items-center gap-3 p-3.5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-sm text-red-700 dark:text-red-300 font-medium">{{ session('error') }}</p>
                            </div>
                        @endif

                        {{-- Create Backup Button --}}
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-600 dark:text-gray-400">MySQL veritabanının tam yedeğini oluşturur (.sql)</p>
                            <button wire:click="createBackup" wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm disabled:opacity-50">
                                <span wire:loading.remove wire:target="createBackup">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                </span>
                                <svg wire:loading wire:target="createBackup" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                <span wire:loading.remove wire:target="createBackup">{{ __('lms.create_backup') }}</span>
                                <span wire:loading wire:target="createBackup">Oluşturuluyor...</span>
                            </button>
                        </div>

                        {{-- Backups Table --}}
                        @if(count($backups) > 0)
                            <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Dosya Adı</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">{{ __('lms.backup_size') }}</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Tarih</th>
                                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($backups as $backup)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-primary-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                                                        <span class="text-sm font-medium text-gray-900 dark:text-white font-mono">{{ $backup['name'] }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                                    @if($backup['size'] < 1024)
                                                        {{ $backup['size'] }} B
                                                    @elseif($backup['size'] < 1048576)
                                                        {{ number_format($backup['size'] / 1024, 1) }} KB
                                                    @else
                                                        {{ number_format($backup['size'] / 1048576, 2) }} MB
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::createFromTimestamp($backup['date'])->format('d.m.Y H:i') }}
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <button wire:click="downloadBackup('{{ $backup['name'] }}')"
                                                            class="p-1.5 text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                                                            title="{{ __('lms.download') }}">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                        </button>
                                                        <button wire:click="confirmDeleteBackup('{{ $backup['name'] }}')"
                                                            class="p-1.5 text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                                                            title="{{ __('lms.delete') }}">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-10 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                                <svg class="mx-auto h-10 w-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">{{ __('lms.backup_empty') }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Yukarıdaki butona tıklayarak ilk yedeğinizi oluşturun</p>
                            </div>
                        @endif
                    </div>

                    {{-- Delete Backup Modal --}}
                    @if($showDeleteBackupModal)
                        @teleport('body')
                            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" wire:click.self="$set('showDeleteBackupModal', false)">
                                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-sm w-full p-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('lms.backup_delete_title') }}</h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Bu işlem geri alınamaz</p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-5">
                                        <span class="font-mono text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">{{ $deletingBackupFile }}</span>
                                        dosyasını silmek istediğinize emin misiniz?
                                    </p>
                                    <div class="flex items-center justify-end gap-3">
                                        <button wire:click="$set('showDeleteBackupModal', false)"
                                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                            Vazgeç
                                        </button>
                                        <button wire:click="deleteBackup"
                                            class="px-4 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                                            Evet, Sil
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endteleport
                    @endif
                @endif

                {{-- System Info --}}
                @if($activeTab === 'system')
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('lms.system_info') }}</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Uygulama ve sunucu bilgileri</p>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-3">
                            @foreach([
                                ['Uygulama', 'Devakent LMS'],
                                ['Laravel Sürümü', app()->version()],
                                ['PHP Sürümü', PHP_VERSION],
                                ['Sunucu', request()->server('SERVER_SOFTWARE', 'N/A')],
                                ['Zaman Dilimi', config('app.timezone')],
                                ['Ortam', config('app.env')],
                                [__('lms.debug_mode'), config('app.debug') ? __('lms.on') : __('lms.off')],
                                ['Önbellek Sürücüsü', config('cache.default')],
                                ['Oturum Sürücüsü', config('session.driver')],
                            ] as $item)
                                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                                    <dt class="text-sm text-gray-600 dark:text-gray-400">{{ $item[0] }}</dt>
                                    <dd class="text-sm font-medium text-gray-900 dark:text-white font-mono">{{ $item[1] }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
