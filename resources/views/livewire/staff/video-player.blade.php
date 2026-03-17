<div x-data="{
    videoElement: null,
    isPlaying: false,
    currentTime: 0,
    duration: 0,
    watchedSeconds: @entangle('watchedSeconds'),
    isCompleted: @entangle('isCompleted'),
    useHls: @js($useHls),
    hlsUrl: @js($hlsUrl),
    hlsInstance: null,
    progressInterval: null,
    init() {
        this.$nextTick(() => {
            this.videoElement = this.$refs.videoEl;
            if (this.videoElement) {
                // HLS.js initialization
                if (this.useHls && this.hlsUrl) {
                    this.initHls();
                }

                this.videoElement.currentTime = {{ $lastPosition }};

                this.videoElement.addEventListener('loadedmetadata', () => {
                    this.duration = this.videoElement.duration;
                    // Süre bilgisi DB'de yoksa, gerçek süreyi Livewire'a bildir
                    if (this.duration > 0) {
                        $wire.setDuration(Math.floor(this.duration));
                    }
                });

                this.videoElement.addEventListener('play', () => {
                    this.isPlaying = true;
                    this.startProgressTracking();
                });

                this.videoElement.addEventListener('pause', () => {
                    this.isPlaying = false;
                    this.stopProgressTracking();
                    this.sendProgress();
                });

                this.videoElement.addEventListener('ended', () => {
                    this.isPlaying = false;
                    this.stopProgressTracking();
                    this.sendProgress();
                });

                // Prevent seeking forward beyond watched position
                this.videoElement.addEventListener('seeking', () => {
                    if (this.videoElement.currentTime > this.watchedSeconds + 2) {
                        this.videoElement.currentTime = this.watchedSeconds;
                    }
                });

                // Disable playback rate changes
                this.videoElement.playbackRate = 1.0;
                this.videoElement.addEventListener('ratechange', () => {
                    this.videoElement.playbackRate = 1.0;
                });
            }
        });
    },
    initHls() {
        if (typeof Hls === 'undefined') return;
        if (Hls.isSupported()) {
            this.hlsInstance = new Hls({
                maxBufferLength: 30,
                maxMaxBufferLength: 60,
            });
            this.hlsInstance.loadSource(this.hlsUrl);
            this.hlsInstance.attachMedia(this.videoElement);
        } else if (this.videoElement.canPlayType('application/vnd.apple.mpegurl')) {
            // Native HLS (Safari)
            this.videoElement.src = this.hlsUrl;
        }
    },
    startProgressTracking() {
        this.progressInterval = setInterval(() => {
            if (this.videoElement && this.isPlaying) {
                this.currentTime = Math.floor(this.videoElement.currentTime);
                this.sendProgress();
            }
        }, 10000); // Every 10 seconds
    },
    stopProgressTracking() {
        if (this.progressInterval) {
            clearInterval(this.progressInterval);
            this.progressInterval = null;
        }
    },
    sendProgress() {
        if (this.videoElement) {
            const current = Math.floor(this.videoElement.currentTime);
            const watched = Math.max(this.watchedSeconds, current);
            $wire.updateProgress(current, watched);
        }
    },
    formatTime(seconds) {
        const m = Math.floor(seconds / 60);
        const s = Math.floor(seconds % 60);
        return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    }
}" class="space-y-4">

    {{-- Video Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-3 md:p-4">
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 md:gap-3 min-w-0">
                <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <div class="min-w-0">
                    <h2 class="font-bold text-sm md:text-base text-gray-800 dark:text-white truncate">{{ $videoTitle ?: 'Video İzleme' }}</h2>
                    <p class="text-[11px] md:text-xs text-gray-600 dark:text-gray-400 hidden sm:block">Videoyu eksiksiz izlemeniz gerekmektedir</p>
                </div>
            </div>

            <div class="flex items-center gap-2 md:gap-3 flex-shrink-0">
                {{-- Progress --}}
                <div class="text-right">
                    <p class="text-sm font-bold {{ $isCompleted ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300' }}">%{{ $progressPercent }}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">İzlendi</p>
                </div>

                @if($isCompleted)
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                @endif
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="mt-3 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all duration-500 {{ $isCompleted ? 'bg-gradient-to-r from-emerald-400 to-green-500' : 'bg-gradient-to-r from-primary-500 to-primary-600' }}"
                 style="width: {{ $progressPercent }}%"></div>
        </div>
    </div>

    {{-- Video Player --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        @if($videoPath)
            <div class="relative bg-black aspect-video">
                <video x-ref="videoEl"
                    class="w-full h-full"
                    controlsList="nodownload noplaybackrate"
                    disablePictureInPicture
                    oncontextmenu="return false;"
                    @if($isCompleted) controls @endif
                    >
                    @if(!$useHls)
                        <source src="{{ route('video.stream', basename($videoPath)) }}" type="video/mp4">
                    @endif
                </video>

                {{-- Custom Controls Overlay (non-completed state) --}}
                @if(!$isCompleted)
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3 md:p-4">
                    <div class="flex items-center gap-3 md:gap-4">
                        {{-- Play/Pause --}}
                        <button @click="isPlaying ? videoElement.pause() : videoElement.play()"
                            class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors">
                            <svg x-show="!isPlaying" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            <svg x-show="isPlaying" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                        </button>

                        {{-- Time --}}
                        <span class="text-white text-sm font-mono" x-text="formatTime(currentTime) + ' / ' + formatTime(duration)"></span>

                        {{-- Spacer --}}
                        <div class="flex-1"></div>

                        {{-- No speed indicator --}}
                        <span class="text-white/60 text-xs">1.0x (sabit)</span>
                    </div>
                </div>
                @endif
            </div>
        @else
            {{-- No video - Demo mode --}}
            <div class="aspect-video bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 font-medium">Video henüz yüklenmemiş</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Demo modunda devam edebilirsiniz</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Instructions & Actions --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 md:p-6">
        <div class="flex items-start gap-3 md:gap-4">
            <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 md:w-5 md:h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-sm md:text-base text-gray-800 dark:text-white mb-1">Video İzleme Kuralları</h3>
                <ul class="text-xs md:text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <li>- Videoyu en az %90 oranında izlemeniz gerekmektedir</li>
                    <li>- İleri sarma engellenmektedir</li>
                    <li>- Hız değiştirme engellenmektedir</li>
                    <li>- İlerlemeniz otomatik olarak kaydedilmektedir</li>
                </ul>
            </div>
        </div>

        @if($isCompleted)
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-sm font-medium">Video tamamlandı</span>
                </div>
                {{-- Auto-dispatch handled by Livewire --}}
            </div>
        @elseif(!$videoPath)
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button wire:click="markCompleted"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-500 to-primary-700 text-white font-semibold rounded-xl text-sm hover:from-primary-600 hover:to-primary-800 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Demo: Videoyu Tamamla
                </button>
            </div>
        @endif
    </div>
</div>

@if($useHls)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/hls.js@1.5.17/dist/hls.min.js"></script>
@endpush
@endif
