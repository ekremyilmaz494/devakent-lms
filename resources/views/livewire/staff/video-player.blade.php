<div x-data="{
    videoElement: null,
    isPlaying: false,
    currentTime: 0,
    duration: 0,
    watchedSeconds: @js($watchedSeconds),
    isCompleted: @entangle('isCompleted'),
    useHls: @js($useHls),
    hlsUrl: @js($hlsUrl),
    hlsInstance: null,
    progressInterval: null,
    isSyncing: false,
    showOverlay: false,
    countdown: 5,
    countdownInterval: null,
    init() {
        this.$nextTick(() => {
            this.videoElement = this.$refs.videoEl;
            if (this.videoElement) {
                if (this.useHls && this.hlsUrl) {
                    this.initHls();
                }

                this.videoElement.currentTime = {{ $lastPosition }};

                this.videoElement.addEventListener('loadedmetadata', () => {
                    this.duration = this.videoElement.duration;
                    if (this.duration > 0) {
                        $wire.setDuration(Math.floor(this.duration));
                    }
                });

                this.videoElement.addEventListener('timeupdate', () => {
                    this.currentTime = this.videoElement.currentTime;
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

                this.videoElement.addEventListener('seeking', () => {
                    if (!this.isSyncing && this.videoElement.currentTime > this.watchedSeconds + 2) {
                        this.videoElement.currentTime = this.watchedSeconds;
                    }
                });

                this.videoElement.playbackRate = 1.0;
                this.videoElement.addEventListener('ratechange', () => {
                    this.videoElement.playbackRate = 1.0;
                });
            }
        });

        this.$watch('isCompleted', (val) => {
            if (val && !this.showOverlay) {
                this.showOverlay = true;
                this.countdown = 5;
                if (this.videoElement && !this.videoElement.paused) {
                    this.videoElement.pause();
                }
                this.countdownInterval = setInterval(() => {
                    this.countdown--;
                    if (this.countdown <= 0) {
                        clearInterval(this.countdownInterval);
                        $wire.goToNext();
                    }
                }, 1000);
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
            this.videoElement.src = this.hlsUrl;
        }
    },
    startProgressTracking() {
        this.progressInterval = setInterval(() => {
            if (this.videoElement && this.isPlaying) {
                this.sendProgress();
            }
        }, 15000);
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
            this.watchedSeconds = Math.max(this.watchedSeconds, current);
            this.isSyncing = true;
            $wire.updateProgress(current, this.watchedSeconds).then(() => {
                this.isSyncing = false;
            });
        }
    },
    formatTime(seconds) {
        const m = Math.floor(seconds / 60);
        const s = Math.floor(seconds % 60);
        return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    },
    skipCountdown() {
        if (this.countdownInterval) clearInterval(this.countdownInterval);
        $wire.goToNext();
    }
}">

    {{-- ═══ Theater Mode Container ═══ --}}
    <div class="bg-gray-900 rounded-2xl overflow-hidden shadow-2xl shadow-black/25 ring-1 ring-white/[0.06]">

        {{-- ─── Title Bar ─── --}}
        <div class="px-4 md:px-5 py-3 flex items-center justify-between bg-gray-900/80 border-b border-white/[0.06]">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-primary-500/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-sm font-semibold text-gray-100 truncate">{{ $videoTitle ?: 'Video' }}</h3>
                    <p class="text-[11px] text-gray-500 hidden sm:block">Videoyu eksiksiz izlemeniz gerekmektedir</p>
                </div>
            </div>
            <div class="flex items-center gap-2.5 flex-shrink-0">
                <span class="text-xs font-mono text-gray-500 hidden sm:inline" x-text="formatTime(currentTime) + ' / ' + formatTime(duration)"></span>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                    {{ $isCompleted ? 'bg-emerald-500/15 text-emerald-400' : 'bg-primary-500/15 text-primary-400' }}">
                    %{{ $progressPercent }} <span class="font-normal hidden sm:inline">İzlendi</span>
                </span>
            </div>
        </div>

        {{-- ─── Video Player ─── --}}
        @if($videoPath)
            <div class="relative bg-black aspect-video group">
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

                {{-- Custom Controls (video tamamlanmamışken) --}}
                @if(!$isCompleted)
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent p-3 md:p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                     :class="{ '!opacity-100': !isPlaying }">
                    <div class="flex items-center gap-3 md:gap-4">
                        <button @click="isPlaying ? videoElement.pause() : videoElement.play()"
                            class="w-10 h-10 rounded-full bg-white/15 hover:bg-white/25 backdrop-blur-sm flex items-center justify-center text-white transition-all hover:scale-110 active:scale-95">
                            <svg x-show="!isPlaying" class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            <svg x-show="isPlaying" x-cloak class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                        </button>
                        <span class="text-white/70 text-sm font-mono tabular-nums" x-text="formatTime(currentTime) + ' / ' + formatTime(duration)"></span>
                        <div class="flex-1"></div>
                        <span class="text-white/30 text-xs font-medium tracking-wide">1.0x</span>
                    </div>
                </div>
                @endif

                {{-- ═══ Tamamlanma Overlay ═══ --}}
                <div x-show="showOverlay"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-cloak
                     class="absolute inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-20">
                    <div class="text-center px-4">
                        {{-- Animated Checkmark --}}
                        <div class="relative w-20 h-20 mx-auto mb-5">
                            <div class="absolute inset-0 rounded-full bg-emerald-500/20 animate-ping"></div>
                            <div class="relative w-20 h-20 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-white mb-1">Video Tamamlandı!</h3>
                        <p class="text-sm text-gray-400 mb-6">Sonraki adıma geçebilirsiniz</p>

                        <button @click="skipCountdown()"
                                class="inline-flex items-center gap-2.5 px-7 py-3 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-primary-500/25 hover:shadow-primary-500/40 hover:-translate-y-0.5 active:translate-y-0">
                            <span>Devam Et</span>
                            <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full tabular-nums min-w-[28px]" x-text="countdown + 's'"></span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        @else
            {{-- Video Yok — Placeholder --}}
            <div class="aspect-video bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gray-700/60 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-gray-400 font-medium">Video henüz yüklenmemiş</p>
                    <p class="text-sm text-gray-500 mt-1">Demo modunda devam edebilirsiniz</p>
                </div>
            </div>
        @endif

        {{-- ─── Progress Bar ─── --}}
        <div class="h-1.5 bg-gray-800">
            <div class="h-full rounded-r-full transition-all duration-700 ease-out
                {{ $isCompleted ? 'bg-gradient-to-r from-emerald-500 to-emerald-400' : 'bg-gradient-to-r from-primary-600 via-primary-500 to-primary-400' }}"
                 style="width: {{ $progressPercent }}%"></div>
        </div>
    </div>

    {{-- ─── Alt Bilgi Satırı ─── --}}
    <div class="mt-2.5 flex items-center justify-between px-1">
        <div class="flex items-center gap-2 text-[11px] text-gray-500 dark:text-gray-500">
            <span class="inline-flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                İleri sarma engelli
            </span>
            <span class="text-gray-600 dark:text-gray-600">&middot;</span>
            <span>Hız: 1.0x sabit</span>
            <span class="text-gray-600 dark:text-gray-600">&middot;</span>
            <span>Otomatik kayıt</span>
        </div>

        @if(!$videoPath && !$isCompleted)
            <button wire:click="markCompleted"
                class="text-xs px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors font-medium">
                Demo: Tamamla
            </button>
        @endif
    </div>

</div>

@if($useHls)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/hls.js@1.5.17/dist/hls.min.js"></script>
@endpush
@endif
