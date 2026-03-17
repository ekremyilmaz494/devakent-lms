<div>
    {{-- Flash Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mb-4 flex items-center gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-800 dark:text-emerald-200 text-sm font-medium">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Tab Navigation --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm mb-6">
        <nav class="flex overflow-x-auto scrollbar-hide" aria-label="Sekmeler">
            @foreach([
                'overview'     => ['label' => 'Genel Bilgiler',   'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                'videos'       => ['label' => 'Video İzle',       'icon' => 'M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.89L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
                'participants' => ['label' => 'Katılımcılar',     'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                'results'      => ['label' => 'Sınav Sonuçları',  'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                'questions'    => ['label' => 'Soru & Cevaplar',  'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ] as $tab => $meta)
                <button wire:click="switchTab('{{ $tab }}')"
                        class="flex items-center gap-2 px-5 py-4 text-sm font-medium whitespace-nowrap border-b-2 transition-all duration-150 focus:outline-none
                            {{ $activeTab === $tab
                                ? 'border-primary-500 text-primary-600 dark:text-primary-400'
                                : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $meta['icon'] }}" />
                    </svg>
                    {{ $meta['label'] }}
                </button>
            @endforeach
        </nav>
    </div>

    {{-- Loading Spinner --}}
    <div wire:loading class="flex items-center justify-center py-8">
        <svg class="animate-spin h-8 w-8 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>

    <div wire:loading.remove>

    {{-- ================================================================== --}}
    {{-- TAB 1: GENEL BİLGİLER                                              --}}
    {{-- ================================================================== --}}
    @if($activeTab === 'overview')
        <div class="space-y-6">
            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Toplam Katılımcı</p>
                    <p class="text-3xl font-black text-gray-900 dark:text-white mt-1">{{ number_format($total) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tamamlayan</p>
                    <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($completed) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ön Sınav Ort.</p>
                    <p class="text-3xl font-black text-blue-600 dark:text-blue-400 mt-1">{{ $preAvg > 0 ? '%'.$preAvg : '—' }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Son Sınav Ort.</p>
                    <p class="text-3xl font-black text-primary-600 dark:text-primary-400 mt-1">{{ $postAvg > 0 ? '%'.$postAvg : '—' }}</p>
                </div>
            </div>

            {{-- Tamamlanma Oranı --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Tamamlanma Oranı</p>
                    <span class="text-sm font-black text-primary-600 dark:text-primary-400">%{{ $rate }}</span>
                </div>
                <div class="h-3 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-3 rounded-full bg-gradient-to-r from-primary-400 via-primary-500 to-primary-600 transition-all duration-500"
                         style="width: {{ $rate }}%"></div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $completed }} / {{ $total }} katılımcı tamamladı</p>
            </div>

            {{-- Kurs Bilgileri --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Eğitim Bilgileri</h3>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $course->status === 'published' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                            {{ $course->status === 'published' ? 'Yayında' : 'Taslak' }}
                        </span>
                        @if($course->is_mandatory)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                Zorunlu
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Eğitim Adı</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $course->title }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Kategori</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $course->category?->name ?? '—' }}</p>
                    </div>
                    @if($course->description)
                        <div class="md:col-span-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Açıklama</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $course->description }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Oluşturulma Tarihi</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $course->created_at->format('d.m.Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Son Güncelleme</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $course->updated_at->format('d.m.Y') }}</p>
                    </div>
                    @if($course->start_date)
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Başlangıç Tarihi</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $course->start_date->format('d.m.Y') }}</p>
                        </div>
                    @endif
                    @if($course->end_date)
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Bitiş Tarihi</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $course->end_date->format('d.m.Y') }}</p>
                        </div>
                    @endif
                    @if($course->passing_score)
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Geçme Notu</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">%{{ $course->passing_score }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Soru Sayısı / Video Sayısı</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $course->questions_count ?? 0 }} soru &middot; {{ $course->videos_count ?? 0 }} video</p>
                    </div>
                    @if($course->departments->isNotEmpty())
                        <div class="md:col-span-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Atanan Departmanlar</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($course->departments as $dept)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">
                                        {{ $dept->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <div class="px-5 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('admin.courses.edit', $course->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Eğitimi Düzenle
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- ================================================================== --}}
    {{-- TAB 2: VİDEO İZLE                                                  --}}
    {{-- ================================================================== --}}
    @if($activeTab === 'videos')
        @if($videos->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-12 text-center shadow-sm">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.89L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">Video Bulunamadı</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Bu eğitime henüz video eklenmemiş.</p>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Video Listesi --}}
                <div class="lg:col-span-1 space-y-2">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 px-1">
                        Video Listesi ({{ $videos->count() }})
                    </h3>
                    @foreach($videos as $video)
                        @php
                            $duration = $video->video_duration_seconds ?? 0;
                            $durationStr = $duration > 0
                                ? sprintf('%d:%02d', intdiv($duration, 60), $duration % 60)
                                : '—';
                        @endphp
                        <button wire:click="selectVideo({{ $video->id }})"
                                class="w-full text-left flex items-start gap-3 p-3 rounded-xl border transition-all
                                    {{ $selectedVideoId === $video->id
                                        ? 'border-primary-400 bg-primary-50 dark:bg-primary-900/20 dark:border-primary-600 shadow-sm'
                                        : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-primary-200 hover:bg-primary-50/50 dark:hover:bg-gray-700' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5
                                    {{ $selectedVideoId === $video->id ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                <span class="text-xs font-black">{{ $video->sort_order ?? $loop->iteration }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $video->title }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $durationStr }}</span>
                                    @if($video->isHlsReady())
                                        <span class="px-1.5 py-0.5 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 text-xs font-medium rounded">HLS</span>
                                    @endif
                                </div>
                            </div>
                            @if($selectedVideoId === $video->id)
                                <svg class="w-4 h-4 text-primary-500 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            @endif
                        </button>
                    @endforeach
                </div>

                {{-- Video Player --}}
                <div class="lg:col-span-2">
                    @if($selectedVideo)
                        <div class="bg-black rounded-2xl overflow-hidden shadow-xl mb-4">
                            @if($selectedVideo->isHlsReady())
                                <div x-data="{ player: null }"
                                     x-init="
                                         if (typeof Hls !== 'undefined' && Hls.isSupported()) {
                                             player = new Hls();
                                             player.loadSource('{{ Storage::url($selectedVideo->hls_path) }}');
                                             player.attachMedia($refs.hlsVideo);
                                         } else {
                                             $refs.hlsVideo.src = '{{ Storage::url($selectedVideo->hls_path) }}';
                                         }
                                     ">
                                    <video x-ref="hlsVideo" controls class="w-full aspect-video" preload="metadata">
                                        Tarayıcınız video oynatmayı desteklemiyor.
                                    </video>
                                </div>
                            @else
                                <video controls class="w-full aspect-video" preload="metadata">
                                    <source src="{{ Storage::url($selectedVideo->video_path) }}" type="video/mp4">
                                    Tarayıcınız video oynatmayı desteklemiyor.
                                </video>
                            @endif
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $selectedVideo->title }}</h4>
                            @php
                                $d = $selectedVideo->video_duration_seconds ?? 0;
                            @endphp
                            @if($d > 0)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Süre: {{ sprintf('%d:%02d', intdiv($d, 60), $d % 60) }}
                                </p>
                            @endif
                        </div>
                    @else
                        <div class="flex items-center justify-center h-48 bg-gray-100 dark:bg-gray-700 rounded-2xl">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Oynatmak için bir video seçin.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
        @endpush
    @endif

    {{-- ================================================================== --}}
    {{-- TAB 3: KATILIMCILAR                                                --}}
    {{-- ================================================================== --}}
    @if($activeTab === 'participants')
        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            @php
                $summaryCards = [
                    ['label' => 'Toplam',         'value' => $summaryStats['total'],       'color' => 'gray'],
                    ['label' => 'Tamamlayan',      'value' => $summaryStats['completed'],   'color' => 'emerald'],
                    ['label' => 'Devam Eden',      'value' => $summaryStats['in_progress'], 'color' => 'blue'],
                    ['label' => 'Başlamayan',      'value' => $summaryStats['not_started'], 'color' => 'amber'],
                ];
            @endphp
            @foreach($summaryCards as $card)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $card['label'] }}</p>
                    <p class="text-3xl font-black mt-1
                        {{ $card['color'] === 'emerald' ? 'text-emerald-600 dark:text-emerald-400' : '' }}
                        {{ $card['color'] === 'blue' ? 'text-blue-600 dark:text-blue-400' : '' }}
                        {{ $card['color'] === 'amber' ? 'text-amber-600 dark:text-amber-400' : '' }}
                        {{ $card['color'] === 'gray' ? 'text-gray-900 dark:text-white' : '' }}">
                        {{ number_format($card['value']) }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Filtreler --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 mb-4 shadow-sm">
            <div class="flex flex-col sm:flex-row gap-3">
                {{-- Arama --}}
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           placeholder="İsme göre ara..."
                           class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-400">
                </div>

                {{-- Departman Filtresi --}}
                <select wire:model.live="departmentFilter"
                        class="px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                    <option value="">Tüm Departmanlar</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>

                {{-- Durum Filtresi --}}
                <select wire:model.live="statusFilter"
                        class="px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                    <option value="">Tüm Durumlar</option>
                    <option value="not_started">Başlamadı</option>
                    <option value="in_progress">Devam Ediyor</option>
                    <option value="completed">Tamamlandı</option>
                    <option value="failed">Başarısız</option>
                </select>

                {{-- Export --}}
                <a href="{{ route('admin.courses.export.participants', $course->id) }}?department={{ $departmentFilter }}&status={{ $statusFilter }}&search={{ $search }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:hover:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 text-sm font-semibold rounded-xl transition-colors whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Excel İndir
                </a>
            </div>
        </div>

        {{-- Tablo --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            @if($enrollments->isEmpty())
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">Katılımcı bulunamadı</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Seçili filtrelere uygun katılımcı yok.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                @php
                                    $cols = [
                                        ['field' => 'name',        'label' => 'Ad Soyad'],
                                        ['field' => 'department',   'label' => 'Departman'],
                                        ['field' => 'enrolled_at', 'label' => 'Kayıt Tarihi'],
                                        ['field' => null,          'label' => 'Son Aktivite'],
                                        ['field' => 'status',      'label' => 'Durum'],
                                        ['field' => null,          'label' => 'Tamamlanma'],
                                        ['field' => 'pre_score',   'label' => 'Ön Sınav'],
                                        ['field' => 'post_score',  'label' => 'Son Sınav'],
                                        ['field' => 'improvement', 'label' => 'Gelişim'],
                                    ];
                                @endphp
                                @foreach($cols as $col)
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider
                                               {{ $col['field'] ? 'cursor-pointer hover:text-gray-700 dark:hover:text-gray-200 select-none' : '' }}"
                                        @if($col['field']) wire:click="sortBy('{{ $col['field'] }}')" @endif>
                                        <div class="flex items-center gap-1">
                                            {{ $col['label'] }}
                                            @if($col['field'] && $sortField === $col['field'])
                                                <svg class="w-3 h-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if($sortDirection === 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                                    @endif
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($enrollments as $enrollment)
                                @php
                                    $preScore  = $enrollment->pre_score  !== null ? (float) $enrollment->pre_score  : null;
                                    $postScore = $enrollment->post_score !== null ? (float) $enrollment->post_score : null;
                                    $improvement = ($preScore !== null && $postScore !== null) ? round($postScore - $preScore, 1) : null;
                                    $statusConfig = [
                                        'not_started' => ['label' => 'Başlamadı',     'class' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400'],
                                        'in_progress' => ['label' => 'Devam Ediyor',  'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'],
                                        'completed'   => ['label' => 'Tamamlandı',    'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'],
                                        'failed'      => ['label' => 'Başarısız',     'class' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'],
                                    ];
                                    $sc = $statusConfig[$enrollment->status] ?? $statusConfig['not_started'];
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-4 py-3">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $enrollment->user?->first_name }} {{ $enrollment->user?->last_name }}
                                        </p>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $enrollment->user?->department?->name ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $enrollment->created_at->format('d.m.Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $enrollment->updated_at->format('d.m.Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $sc['class'] }}">
                                            {{ $sc['label'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $enrollment->completed_at?->format('d.m.Y') ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium {{ $preScore !== null ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400' }}">
                                        {{ $preScore !== null ? '%'.number_format($preScore, 1) : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium {{ $postScore !== null ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400' }}">
                                        {{ $postScore !== null ? '%'.number_format($postScore, 1) : '—' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($improvement !== null)
                                            <div class="flex items-center gap-1">
                                                @if($improvement > 0)
                                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                                                    </svg>
                                                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">+{{ number_format($improvement, 1) }}%</span>
                                                @elseif($improvement < 0)
                                                    <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                    <span class="text-xs font-bold text-red-600 dark:text-red-400">{{ number_format($improvement, 1) }}%</span>
                                                @else
                                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">±0%</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Pagination --}}
                <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                    {{ $enrollments->links() }}
                </div>
            @endif
        </div>
    @endif

    {{-- ================================================================== --}}
    {{-- TAB 4: SINAV SONUÇLARI                                             --}}
    {{-- ================================================================== --}}
    @if($activeTab === 'results')
        {{-- İstatistik Kartları --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
            @php
                $statCards = [
                    ['label' => 'Ön Sınav Ort.',   'value' => $examStats['avg_pre'] > 0 ? '%'.$examStats['avg_pre'] : '—',    'color' => 'blue'],
                    ['label' => 'Son Sınav Ort.',   'value' => $examStats['avg_post'] > 0 ? '%'.$examStats['avg_post'] : '—',  'color' => 'primary'],
                    ['label' => 'Ort. Gelişim',     'value' => $examStats['avg_improve'] != 0 ? ($examStats['avg_improve'] > 0 ? '+' : '').'%'.$examStats['avg_improve'] : '—', 'color' => $examStats['avg_improve'] >= 0 ? 'emerald' : 'red'],
                    ['label' => 'En Yüksek',        'value' => $examStats['highest_post'] > 0 ? '%'.$examStats['highest_post'] : '—', 'color' => 'emerald'],
                    ['label' => 'En Düşük',         'value' => $examStats['lowest_post'] > 0 ? '%'.$examStats['lowest_post'] : '—',   'color' => 'amber'],
                    ['label' => 'Geçme Oranı',      'value' => $examStats['pass_rate'] > 0 ? '%'.$examStats['pass_rate'] : '—',        'color' => 'primary'],
                ];
            @endphp
            @foreach($statCards as $sc)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider leading-tight">{{ $sc['label'] }}</p>
                    <p class="text-2xl font-black mt-1.5
                        {{ $sc['color'] === 'blue'    ? 'text-blue-600 dark:text-blue-400' : '' }}
                        {{ $sc['color'] === 'primary' ? 'text-primary-600 dark:text-primary-400' : '' }}
                        {{ $sc['color'] === 'emerald' ? 'text-emerald-600 dark:text-emerald-400' : '' }}
                        {{ $sc['color'] === 'amber'   ? 'text-amber-600 dark:text-amber-400' : '' }}
                        {{ $sc['color'] === 'red'     ? 'text-red-600 dark:text-red-400' : '' }}">
                        {{ $sc['value'] }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Filtreler --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 mb-4 shadow-sm">
            <div class="flex flex-wrap gap-3">
                <select wire:model.live="departmentFilter"
                        class="px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                    <option value="">Tüm Departmanlar</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="examFilter"
                        class="px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                    <option value="">Tüm Sınavlar</option>
                    <option value="pre_only">Sadece Ön Sınav</option>
                    <option value="post_only">Sadece Son Sınav</option>
                    <option value="both">Her İkisi de Var</option>
                </select>

                <div class="flex items-center gap-2">
                    <input type="number" wire:model.live.debounce.400ms="minScore" min="0" max="100" placeholder="Min %"
                           class="w-20 px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                    <span class="text-gray-400">—</span>
                    <input type="number" wire:model.live.debounce.400ms="maxScore" min="0" max="100" placeholder="Max %"
                           class="w-20 px-3 py-2.5 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                </div>

                <div class="flex items-center gap-2 ml-auto">
                    <a href="{{ route('admin.courses.export.results', $course->id) }}?department={{ $departmentFilter }}&exam_filter={{ $examFilter }}&format=excel"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 text-sm font-semibold rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Excel
                    </a>
                    <a href="{{ route('admin.courses.export.results', $course->id) }}?department={{ $departmentFilter }}&exam_filter={{ $examFilter }}&format=pdf"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800 text-sm font-semibold rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        PDF
                    </a>
                </div>
            </div>
        </div>

        {{-- Karşılaştırma Tablosu --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            @if($resultRows->isEmpty())
                <div class="text-center py-12">
                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">Sınav sonucu bulunamadı</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Seçili filtrelere uygun sonuç yok.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                @php
                                    $resultCols = [
                                        ['field' => 'name',       'label' => 'Ad Soyad'],
                                        ['field' => 'department', 'label' => 'Departman'],
                                        ['field' => null,         'label' => 'Ön Sınav Tarihi'],
                                        ['field' => 'pre_score',  'label' => 'Ön Puan'],
                                        ['field' => null,         'label' => 'Son Sınav Tarihi'],
                                        ['field' => 'post_score', 'label' => 'Son Puan'],
                                        ['field' => 'improvement','label' => 'Fark'],
                                        ['field' => null,         'label' => 'Durum'],
                                    ];
                                @endphp
                                @foreach($resultCols as $col)
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider
                                               {{ $col['field'] ? 'cursor-pointer hover:text-gray-700 dark:hover:text-gray-200 select-none' : '' }}"
                                        @if($col['field']) wire:click="sortBy('{{ $col['field'] }}')" @endif>
                                        <div class="flex items-center gap-1">
                                            {{ $col['label'] }}
                                            @if($col['field'] && $sortField === $col['field'])
                                                <svg class="w-3 h-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if($sortDirection === 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                                    @endif
                                                </svg>
                                            @endif
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($resultRows as $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $row['user']?->first_name }} {{ $row['user']?->last_name }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $row['user']?->department?->name ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $row['pre_attempt']?->finished_at?->format('d.m.Y') ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium {{ $row['pre_score'] !== null ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400' }}">
                                        {{ $row['pre_score'] !== null ? '%'.number_format($row['pre_score'], 1) : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $row['post_attempt']?->finished_at?->format('d.m.Y') ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium {{ $row['post_score'] !== null ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400' }}">
                                        {{ $row['post_score'] !== null ? '%'.number_format($row['post_score'], 1) : '—' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($row['improvement'] !== null)
                                            <span class="text-sm font-bold {{ $row['improvement'] > 0 ? 'text-emerald-600 dark:text-emerald-400' : ($row['improvement'] < 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-500') }}">
                                                {{ $row['improvement'] > 0 ? '+' : '' }}{{ number_format($row['improvement'], 1) }}%
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($row['post_attempt'])
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $row['is_passed'] ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                                {{ $row['is_passed'] ? 'Geçti' : 'Kaldı' }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

    {{-- ================================================================== --}}
    {{-- TAB 5: SORU & CEVAPLAR                                             --}}
    {{-- ================================================================== --}}
    @if($activeTab === 'questions')
        {{-- Sıralama Kontrolü --}}
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ isset($questionData) && is_countable($questionData) ? count($questionData) : 0 }} soru
            </p>
            <select wire:model.live="questionSort"
                    class="px-3 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500/20">
                <option value="hardest">En Zor Önce</option>
                <option value="easiest">En Kolay Önce</option>
                <option value="sort_order">Sıra Numarasına Göre</option>
            </select>
        </div>

        @if(empty($questionData) || count($questionData) === 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-12 text-center shadow-sm">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">Soru Bulunamadı</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Bu eğitime henüz soru eklenmemiş.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($questionData as $idx => $item)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                        <div class="p-5">
                            {{-- Soru Başlığı --}}
                            <div class="flex items-start gap-3 mb-4">
                                <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-lg flex items-center justify-center flex-shrink-0 text-xs font-black">
                                    {{ $idx + 1 }}
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white leading-snug">
                                        {{ $item['question']->question_text }}
                                    </p>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                            Çoktan Seçmeli
                                        </span>
                                        @if($item['correct_rate'] !== null)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                {{ $item['correct_rate'] < 40 ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' : ($item['correct_rate'] < 70 ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400') }}">
                                                %{{ number_format($item['correct_rate'], 1) }} doğru
                                            </span>
                                        @endif
                                        <span class="text-xs text-gray-400">{{ $item['total_answers'] }} yanıt</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Şıklar --}}
                            @if($item['total_answers'] === 0)
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 text-center">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Henüz hiç katılımcı bu soruyu cevaplamamış.</p>
                                </div>
                            @else
                                <div class="space-y-2">
                                    @foreach($item['options'] as $letter => $opt)
                                        @if($opt['text'])
                                            <div class="flex items-center gap-3">
                                                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 text-xs font-black
                                                    {{ $opt['is_correct'] ? 'bg-emerald-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                                    {{ strtoupper($letter) }}
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex justify-between text-xs mb-1">
                                                        <span class="{{ $opt['is_correct'] ? 'font-semibold text-emerald-700 dark:text-emerald-400' : 'text-gray-600 dark:text-gray-400' }}">
                                                            {{ $opt['text'] }}
                                                            @if($opt['is_correct'])
                                                                <span class="ml-1 text-emerald-500">✓</span>
                                                            @endif
                                                        </span>
                                                        <span class="text-gray-400 flex-shrink-0 ml-2">{{ $opt['count'] }} (%{{ number_format($opt['percentage'], 1) }})</span>
                                                    </div>
                                                    <div class="h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                                        <div class="h-1.5 rounded-full transition-all duration-300
                                                            {{ $opt['is_correct'] ? 'bg-emerald-500' : 'bg-gray-300 dark:bg-gray-500' }}"
                                                             style="width: {{ $opt['percentage'] }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif

    </div>{{-- end wire:loading.remove --}}
</div>
