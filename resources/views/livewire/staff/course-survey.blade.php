<div class="space-y-6">

    @if($alreadyAnswered || $submitted)
    {{-- Teşekkür ekranı --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8 text-center">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-400 to-green-500 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-emerald-200 dark:shadow-emerald-900/30">
            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </div>
        <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Teşekkürler!</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Geri bildiriminiz kaydedildi. Değerlendirmeleriniz eğitim kalitesini artırmamıza yardımcı olur.</p>
        <a href="{{ route('staff.courses.index') }}" wire:navigate class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-primary-500 to-primary-700 rounded-xl hover:from-primary-600 hover:to-primary-800 transition-all">
            Eğitimlerime Dön
        </a>
    </div>

    @else
    {{-- Anket Formu --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-primary-400 to-emerald-500"></div>
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1">Eğitimi Değerlendirin</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Eğitim hakkındaki görüşleriniz gelecekteki içeriklerin iyileştirilmesine katkı sağlar.</p>
            </div>

            <div class="space-y-6">

                {{-- Genel Memnuniyet --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Genel Memnuniyetiniz <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-2" x-data="{ hovered: 0 }">
                        @for($star = 1; $star <= 5; $star++)
                        <button type="button"
                            wire:click="$set('ratingOverall', {{ $star }})"
                            @mouseenter="hovered = {{ $star }}"
                            @mouseleave="hovered = 0"
                            class="w-10 h-10 rounded-xl transition-all duration-150 flex items-center justify-center">
                            <svg class="w-7 h-7 transition-colors"
                                :class="({{ $star }} <= hovered || {{ $star }} <= {{ $ratingOverall }}) ? 'text-amber-400' : 'text-gray-200 dark:text-gray-700'"
                                fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </button>
                        @endfor
                        @if($ratingOverall > 0)
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">
                                {{ ['', 'Çok Kötü', 'Kötü', 'Orta', 'İyi', 'Mükemmel'][$ratingOverall] }}
                            </span>
                        @endif
                    </div>
                    @error('ratingOverall') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- İçerik Kalitesi --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        İçerik Kalitesi <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-2" x-data="{ hovered: 0 }">
                        @for($star = 1; $star <= 5; $star++)
                        <button type="button"
                            wire:click="$set('ratingContent', {{ $star }})"
                            @mouseenter="hovered = {{ $star }}"
                            @mouseleave="hovered = 0"
                            class="w-10 h-10 rounded-xl transition-all duration-150 flex items-center justify-center">
                            <svg class="w-7 h-7 transition-colors"
                                :class="({{ $star }} <= hovered || {{ $star }} <= {{ $ratingContent }}) ? 'text-amber-400' : 'text-gray-200 dark:text-gray-700'"
                                fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </button>
                        @endfor
                        @if($ratingContent > 0)
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">
                                {{ ['', 'Çok Kötü', 'Kötü', 'Orta', 'İyi', 'Mükemmel'][$ratingContent] }}
                            </span>
                        @endif
                    </div>
                    @error('ratingContent') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Pratik Fayda --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Pratik Fayda <span class="text-red-500">*</span>
                        <span class="font-normal text-gray-400 dark:text-gray-500 ml-1">(İşinize ne kadar yaradı?)</span>
                    </label>
                    <div class="flex items-center gap-2" x-data="{ hovered: 0 }">
                        @for($star = 1; $star <= 5; $star++)
                        <button type="button"
                            wire:click="$set('ratingUsefulness', {{ $star }})"
                            @mouseenter="hovered = {{ $star }}"
                            @mouseleave="hovered = 0"
                            class="w-10 h-10 rounded-xl transition-all duration-150 flex items-center justify-center">
                            <svg class="w-7 h-7 transition-colors"
                                :class="({{ $star }} <= hovered || {{ $star }} <= {{ $ratingUsefulness }}) ? 'text-amber-400' : 'text-gray-200 dark:text-gray-700'"
                                fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </button>
                        @endfor
                        @if($ratingUsefulness > 0)
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">
                                {{ ['', 'Hiç Faydalı Değil', 'Az Faydalı', 'Orta', 'Faydalı', 'Çok Faydalı'][$ratingUsefulness] }}
                            </span>
                        @endif
                    </div>
                    @error('ratingUsefulness') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Eğitim Süresi --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Eğitim Süresi <span class="text-red-500">*</span>
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['too_short' => 'Çok Kısa', 'appropriate' => 'Uygun', 'too_long' => 'Çok Uzun'] as $val => $label)
                        <label class="flex items-center gap-2 cursor-pointer px-4 py-2.5 rounded-xl border-2 transition-all
                            {{ $ratingDuration === $val ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}">
                            <input wire:model="ratingDuration" type="radio" value="{{ $val }}" class="sr-only" />
                            <span class="text-sm font-medium {{ $ratingDuration === $val ? 'text-primary-700 dark:text-primary-300' : 'text-gray-600 dark:text-gray-400' }}">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Yazılı Geri Bildirim --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Görüş ve Öneriler
                        <span class="font-normal text-gray-400 dark:text-gray-500 ml-1">(İsteğe bağlı)</span>
                    </label>
                    <textarea wire:model="feedback"
                        rows="4"
                        placeholder="Eğitimle ilgili görüş, öneri veya eleştirilerinizi paylaşabilirsiniz..."
                        class="w-full px-4 py-3 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-400 focus:border-primary-400 resize-none transition-colors placeholder-gray-400 dark:placeholder-gray-500"
                    ></textarea>
                    @error('feedback') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

            </div>

            <div class="flex items-center justify-between mt-6 pt-5 border-t border-gray-200 dark:border-gray-700">
                <button type="button" wire:click="skip" class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                    Şimdi Değil
                </button>
                <button type="button" wire:click="submit" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-primary-500 to-primary-700 rounded-xl hover:from-primary-600 hover:to-primary-800 transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="submit">
                        <svg class="w-4 h-4 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Gönder
                    </span>
                    <span wire:loading wire:target="submit">Gönderiliyor...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
