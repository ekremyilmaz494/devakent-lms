<div class="space-y-4 md:space-y-6">
    {{-- ═══ Course Header ═══ --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-primary-500 via-primary-400 to-primary-600" style="background: linear-gradient(90deg, {{ $course->category?->color ?? '#D97706' }}, #F59E0B, {{ $course->category?->color ?? '#D97706' }})"></div>
        <div class="p-4 md:p-6">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300">{{ $course->category?->name ?? 'Genel' }}</span>
                        @if($course->is_mandatory)
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400">Zorunlu</span>
                        @endif
                    </div>
                    <h1 class="text-lg md:text-xl font-bold text-gray-800 dark:text-white">{{ $course->title }}</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $course->description }}</p>
                </div>
                <a href="{{ route('staff.courses.index') }}" class="flex items-center gap-1.5 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Geri
                </a>
            </div>

            {{-- ─── Info Cards Grid ─── --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2 md:gap-3 mt-4 md:mt-5">
                <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3 flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-[18px] h-[18px] text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-gray-400 dark:text-gray-500 font-medium">Sınav Süresi</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $course->exam_duration_minutes ?? 30 }} dk</p>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3 flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-[18px] h-[18px] text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-gray-400 dark:text-gray-500 font-medium">Geçme Notu</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">%{{ $course->passing_score }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3 flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-[18px] h-[18px] text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-gray-400 dark:text-gray-500 font-medium">Deneme Hakkı</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $course->max_attempts }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3 flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-[18px] h-[18px] text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-gray-400 dark:text-gray-500 font-medium">Soru Sayısı</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $course->questions->count() }}</p>
                    </div>
                </div>

                @if($course->end_date)
                <div class="bg-gray-50 dark:bg-gray-700/40 rounded-xl p-3 flex items-center gap-2.5 col-span-2 sm:col-span-1">
                    <div class="w-9 h-9 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-[18px] h-[18px] text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-gray-400 dark:text-gray-500 font-medium">Son Tarih</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $course->end_date->format('d.m.Y') }}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- ─── Genel İlerleme Barı ─── --}}
            @if($enrollment && !in_array($step, ['intro', 'completed', 'failed']))
                @php
                    $totalVideoCount = $course->videos->count();
                    $completedVideoCount = $enrollment->videoProgress->where('is_completed', true)->count();
                    $overallPercent = match(true) {
                        in_array($step, ['pre_exam_warning', 'pre_exam']) => 10,
                        $step === 'video' && $totalVideoCount > 0 => 10 + round(($completedVideoCount / $totalVideoCount) * 60),
                        $step === 'video' => 40,
                        in_array($step, ['post_exam_warning', 'post_exam']) => 80,
                        $step === 'result' => 90,
                        default => 0,
                    };
                @endphp
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Genel İlerleme</span>
                        <span class="text-sm font-bold text-primary-600 dark:text-primary-400">%{{ $overallPercent }}</span>
                    </div>
                    <div class="h-2.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-primary-600 via-primary-500 to-primary-400 transition-all duration-700 ease-out"
                             style="width: {{ $overallPercent }}%"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- ═══ Progress Stepper ═══ --}}
    @if(!in_array($step, ['intro', 'completed', 'failed', 'pre_exam_warning', 'post_exam_warning']))
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 md:p-6">
        <div class="flex items-center justify-between max-w-xl w-full mx-auto">
            @foreach($progressSteps as $i => $ps)
                <div class="flex items-center {{ $i < count($progressSteps) - 1 ? 'flex-1' : '' }}">
                    <div class="flex flex-col items-center relative">
                        {{-- Step Circle --}}
                        <div class="relative">
                            @if($ps['status'] === 'current')
                                <div class="absolute inset-0 rounded-full bg-primary-400/30 animate-ping"></div>
                            @endif
                            <div class="relative w-11 h-11 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300
                                @if($ps['status'] === 'completed') bg-emerald-500 text-white shadow-md shadow-emerald-500/25
                                @elseif($ps['status'] === 'current') bg-primary-500 text-white shadow-lg shadow-primary-500/30 ring-4 ring-primary-100 dark:ring-primary-900/50
                                @else bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400
                                @endif">
                                @if($ps['status'] === 'completed')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                @else
                                    {{ $i + 1 }}
                                @endif
                            </div>
                        </div>
                        <span class="text-xs font-semibold mt-2.5 whitespace-nowrap
                            @if($ps['status'] === 'completed') text-emerald-600 dark:text-emerald-400
                            @elseif($ps['status'] === 'current') text-primary-600 dark:text-primary-400
                            @else text-gray-400 dark:text-gray-500
                            @endif">{{ $ps['label'] }}</span>
                    </div>
                    {{-- Connecting Line --}}
                    @if($i < count($progressSteps) - 1)
                        <div class="flex-1 mx-3 md:mx-5 mt-[-22px]">
                            <div class="h-[3px] rounded-full {{ $ps['status'] === 'completed' ? 'bg-emerald-400 dark:bg-emerald-500' : 'bg-gray-200 dark:bg-gray-700' }} transition-colors duration-500"></div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if($enrollment)
        <div class="text-center mt-4">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Deneme: {{ $enrollment->current_attempt ?: 1 }} / {{ $course->max_attempts }}
            </span>
        </div>
        @endif
    </div>
    @endif

    {{-- Step Content --}}
    @if($step === 'intro')
        {{-- Intro / Start --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 md:p-8 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-primary-400 to-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-primary-200 dark:shadow-primary-900/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white mb-2">Eğitime Başla</h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto mb-6">
                Bu eğitim 3 aşamadan oluşmaktadır: Ön Sınav, Video İzleme ve Son Sınav.
                Başarılı olmanız durumunda sertifikanız otomatik olarak oluşturulacaktır.
            </p>

            <div class="grid grid-cols-3 gap-2 md:gap-4 max-w-sm mx-auto mb-6 md:mb-8">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3 md:p-4">
                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/50 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300">Ön Sınav</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3 md:p-4">
                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/50 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300">Video</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3 md:p-4">
                    <div class="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-xs font-medium text-gray-600 dark:text-gray-300">Son Sınav</p>
                </div>
            </div>

            <button wire:click="startCourse" class="inline-flex items-center gap-2 px-6 py-3 md:px-8 bg-gradient-to-r from-primary-500 to-primary-700 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-primary-800 transition-all shadow-lg shadow-primary-200 dark:shadow-primary-900/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                Eğitime Başla
            </button>
        </div>

    @elseif($step === 'pre_exam_warning')
        {{-- Ön Sınav Uyarı Ekranı --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 md:p-8 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-amber-200 dark:shadow-amber-900/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white mb-2">Ön Sınav</h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto mb-6">
                Bu eğitimin <span class="font-semibold text-gray-800 dark:text-white">ön sınavına</span> katılmak üzeresiniz.
            </p>

            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/50 rounded-xl p-5 max-w-sm w-full mx-auto mb-6 md:mb-8">
                <div class="flex items-center gap-3 mb-3">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span class="text-sm font-semibold text-amber-800 dark:text-amber-300">Dikkat</span>
                </div>
                <ul class="text-sm text-amber-700 dark:text-amber-300 space-y-2 text-left">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Sınav süresi: <span class="font-bold">{{ $course->exam_duration_minutes }} dakika</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Toplam soru: <span class="font-bold">{{ $course->questions->count() }} soru</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Geçme puanı: <span class="font-bold">%{{ $course->passing_score }}</span>
                    </li>
                </ul>
            </div>

            <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">
                "Sınava Başla" butonuna tıkladığınızda süreniz başlayacaktır.
            </p>

            <button wire:click="startExam" class="inline-flex items-center gap-2 px-6 py-3 md:px-8 bg-gradient-to-r from-primary-500 to-primary-700 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-primary-800 transition-all shadow-lg shadow-primary-200 dark:shadow-primary-900/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                Sınava Başla
            </button>
        </div>

    @elseif($step === 'pre_exam')
        @livewire('staff.exam-engine', [
            'enrollmentId' => $enrollment->id,
            'examType' => 'pre_exam',
        ], key('pre-exam-' . ($enrollment->current_attempt ?? 1)))

    @elseif($step === 'video')
        @php
            $courseVideos = $course->videos;
            $attemptNumber = $enrollment->current_attempt ?: 1;
            $sidebarCompletedCount = $enrollment->videoProgress->where('is_completed', true)->count();
        @endphp

        @if($courseVideos->count() > 1)
            {{-- ═══ Çoklu Video: Cinema Layout ═══ --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 md:gap-5">

                {{-- ─── Video Listesi Sidebar (Dark) ─── --}}
                <div class="lg:col-span-3 order-2 lg:order-1">
                    <div class="bg-gray-900 rounded-2xl overflow-hidden shadow-xl ring-1 ring-white/[0.06]">
                        {{-- Sidebar Header --}}
                        <div class="px-4 py-3.5 border-b border-white/[0.06]">
                            <h3 class="text-sm font-bold text-gray-100 flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                Video Listesi
                            </h3>
                            {{-- Mini Progress --}}
                            <div class="flex items-center gap-2.5 mt-2.5">
                                <div class="flex-1 h-1.5 bg-gray-800 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full bg-emerald-500 transition-all duration-500"
                                         style="width: {{ $courseVideos->count() > 0 ? round(($sidebarCompletedCount / $courseVideos->count()) * 100) : 0 }}%"></div>
                                </div>
                                <span class="text-[11px] font-semibold text-gray-500 tabular-nums">{{ $sidebarCompletedCount }}/{{ $courseVideos->count() }}</span>
                            </div>
                        </div>

                        {{-- Video Items --}}
                        <div class="p-2 space-y-0.5 max-h-[420px] overflow-y-auto scrollbar-thin">
                            @foreach($courseVideos as $i => $video)
                                @php
                                    $vProgress = $enrollment->videoProgress
                                        ->where('course_video_id', $video->id)
                                        ->first();
                                    $vCompleted = $vProgress?->is_completed ?? false;
                                    $vStarted = $vProgress !== null;
                                    $isActive = $video->id === $currentVideoId;
                                    $videoDuration = $video->video_duration_seconds ?? 0;

                                    // Kilit kontrolü
                                    $isLocked = false;
                                    if ($i > 0) {
                                        $prevVideo = $courseVideos[$i - 1];
                                        $prevProgress = $enrollment->videoProgress
                                            ->where('course_video_id', $prevVideo->id)
                                            ->first();
                                        if (!$prevProgress || !$prevProgress->is_completed) {
                                            $isLocked = true;
                                        }
                                    }
                                @endphp
                                <button
                                    wire:click="playVideo({{ $video->id }})"
                                    @if($isLocked) disabled @endif
                                    class="w-full text-left p-3 rounded-xl flex items-center gap-3 transition-all duration-200
                                        {{ $isActive ? 'bg-primary-500/10 border-l-[3px] border-primary-500 pl-[9px]' : 'border-l-[3px] border-transparent pl-[9px]' }}
                                        {{ $isLocked ? 'opacity-40 cursor-not-allowed' : 'hover:bg-white/[0.04] cursor-pointer' }}
                                    ">
                                    {{-- Status Circle --}}
                                    @if($vCompleted)
                                        <div class="w-8 h-8 rounded-full bg-emerald-500/15 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    @elseif($isLocked)
                                        <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                        </div>
                                    @elseif($isActive)
                                        <div class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center flex-shrink-0 shadow-md shadow-primary-500/30">
                                            <svg class="w-3.5 h-3.5 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center flex-shrink-0">
                                            <span class="text-xs font-bold text-gray-400">{{ $i + 1 }}</span>
                                        </div>
                                    @endif

                                    {{-- Title + Meta --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium truncate {{ $isActive ? 'text-gray-100' : ($vCompleted ? 'text-gray-300' : 'text-gray-400') }}">{{ $video->title }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            {{-- Duration --}}
                                            @if($videoDuration > 0)
                                                <span class="text-[10px] text-gray-600 tabular-nums">
                                                    {{ floor($videoDuration / 60) }}:{{ str_pad($videoDuration % 60, 2, '0', STR_PAD_LEFT) }}
                                                </span>
                                            @endif
                                            {{-- Status Badge --}}
                                            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-full
                                                @if($vCompleted) bg-emerald-500/10 text-emerald-400
                                                @elseif($isActive) bg-primary-500/10 text-primary-400
                                                @elseif($vStarted && !$isLocked) bg-amber-500/10 text-amber-400
                                                @elseif($isLocked) text-gray-600
                                                @else text-gray-600
                                                @endif">
                                                @if($vCompleted) Tamamlandı
                                                @elseif($isActive) İzleniyor
                                                @elseif($vStarted && !$isLocked) Devam
                                                @elseif($isLocked) Kilitli
                                                @else Başlanmadı
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ─── Video Player ─── --}}
                <div class="lg:col-span-9 order-1 lg:order-2">
                    @if($currentVideoId)
                        @livewire('staff.video-player', [
                            'enrollmentId' => $enrollment->id,
                            'courseVideoId' => $currentVideoId,
                        ], key('video-' . $currentVideoId . '-' . $attemptNumber))
                    @endif
                </div>
            </div>
        @elseif($courseVideos->count() === 1)
            {{-- Tekli Video: Sadece Player --}}
            <div class="max-w-5xl mx-auto">
                @livewire('staff.video-player', [
                    'enrollmentId' => $enrollment->id,
                    'courseVideoId' => $courseVideos->first()->id,
                ], key('video-' . $courseVideos->first()->id . '-' . $attemptNumber))
            </div>
        @else
            {{-- Video Yok: Demo Tamamlama --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 md:p-8 text-center">
                <p class="text-gray-500 dark:text-gray-400 mb-4">Bu eğitime henüz video eklenmemiş.</p>
                <button wire:click="$dispatch('videoCompleted')" class="px-6 py-3 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                    Demo: Videoyu Tamamla
                </button>
            </div>
        @endif

    @elseif($step === 'post_exam_warning')
        {{-- Son Sınav Uyarı Ekranı --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 md:p-8 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-red-400 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-red-200 dark:shadow-red-900/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white mb-2">Son Sınav</h2>
            <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto mb-6">
                Tüm videoları tamamladınız. Şimdi <span class="font-semibold text-gray-800 dark:text-white">son sınava</span> katılmak üzeresiniz.
            </p>

            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700/50 rounded-xl p-5 max-w-sm w-full mx-auto mb-6 md:mb-8">
                <div class="flex items-center gap-3 mb-3">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span class="text-sm font-semibold text-red-800 dark:text-red-300">Önemli</span>
                </div>
                <ul class="text-sm text-red-700 dark:text-red-300 space-y-2 text-left">
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Sınav süresi: <span class="font-bold">{{ $course->exam_duration_minutes }} dakika</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Toplam soru: <span class="font-bold">{{ $course->questions->count() }} soru</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Geçme puanı: <span class="font-bold">%{{ $course->passing_score }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Kalan deneme hakkı: <span class="font-bold">{{ $course->max_attempts - ($enrollment->current_attempt ?? 1) + 1 }}</span>
                    </li>
                </ul>
            </div>

            <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">
                Bu sınavın sonucu eğitim başarınızı belirleyecektir. "Sınava Başla" butonuna tıkladığınızda süreniz başlayacaktır.
            </p>

            <button wire:click="startExam" class="inline-flex items-center gap-2 px-6 py-3 md:px-8 bg-gradient-to-r from-red-500 to-red-700 text-white font-semibold rounded-xl hover:from-red-600 hover:to-red-800 transition-all shadow-lg shadow-red-200 dark:shadow-red-900/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                Sınava Başla
            </button>
        </div>

    @elseif($step === 'post_exam')
        @livewire('staff.exam-engine', [
            'enrollmentId' => $enrollment->id,
            'examType' => 'post_exam',
        ], key('post-exam-' . ($enrollment->current_attempt ?? 1)))

    @elseif($step === 'result' && $examResult)
        {{-- Result screen with retry option --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 md:p-8 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-amber-200 dark:shadow-amber-900/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white mb-2">Sınav Sonucu</h2>
            <p class="text-3xl md:text-4xl font-black {{ $examResult['passed'] ? 'text-emerald-500' : 'text-red-500' }} mb-2">%{{ $examResult['score'] }}</p>
            <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $examResult['message'] }}</p>

            @if(!$examResult['passed'] && isset($examResult['attempts_remaining']) && $examResult['attempts_remaining'] > 0)
                <div class="bg-amber-100 dark:bg-amber-900/30 rounded-xl p-4 max-w-sm mx-auto mb-6">
                    <p class="text-sm text-amber-700 dark:text-amber-300">
                        <span class="font-bold">{{ $examResult['attempts_remaining'] }}</span> deneme hakkınız kaldı.
                    </p>
                </div>

                {{-- Önceki Denemeler Tablosu --}}
                @if($previousAttempts->count() > 0)
                <div class="max-w-lg w-full mx-auto mb-6">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Deneme Geçmişi</h4>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Deneme</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Ön Sınav</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Son Sınav</th>
                                    <th class="px-3 py-2.5 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Sonuç</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                @foreach($previousAttempts as $pa)
                                <tr>
                                    <td class="px-3 py-2.5 text-gray-700 dark:text-gray-300">{{ $pa->attempt_number }}.</td>
                                    <td class="px-3 py-2.5 text-center font-medium text-gray-700 dark:text-gray-300">{{ $pa->has_pre_exam ? $pa->pre_exam_score . '/100' : '—' }}</td>
                                    <td class="px-3 py-2.5 text-center font-bold text-gray-800 dark:text-white">{{ $pa->post_exam_score !== null ? $pa->post_exam_score . '/100' : '—' }}</td>
                                    <td class="px-3 py-2.5 text-right">
                                        @if($pa->is_passed)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">Geçti</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">Kaldı</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <button wire:click="retryFromBeginning" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-700 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-primary-800 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Tekrar Dene
                </button>
            @endif
        </div>

    @elseif($step === 'completed')
        {{-- Completed / Certificate --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 md:p-8 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-emerald-400 to-green-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-emerald-200 dark:shadow-emerald-900/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white mb-2">Tebrikler!</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-2">Bu eğitimi başarıyla tamamladınız.</p>

            @if($enrollment?->certificate)
                <div class="bg-emerald-100 dark:bg-emerald-900/30 rounded-xl p-4 max-w-sm mx-auto mb-6">
                    <p class="text-sm text-emerald-700 dark:text-emerald-300">
                        Sertifika No: <span class="font-bold font-mono">{{ $enrollment->certificate->certificate_number }}</span>
                    </p>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">
                        Puan: %{{ $enrollment->certificate->final_score }} &middot; {{ $enrollment->certificate->issued_at->format('d.m.Y') }}
                    </p>
                </div>
            @endif

            <a href="{{ route('staff.courses.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary-500 to-primary-700 text-white font-semibold rounded-xl hover:from-primary-600 hover:to-primary-800 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Eğitimlerime Dön
            </a>
        </div>

    @elseif($step === 'failed')
        {{-- Failed - No attempts left (gri tema) --}}
        <div class="bg-gray-100 dark:bg-gray-800/80 rounded-2xl border border-gray-300 dark:border-gray-600 p-5 md:p-8 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-gray-400 to-gray-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-gray-300 dark:shadow-gray-900/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-700 dark:text-gray-300 mb-2">Başarısız</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-6">
                Deneme hakkınız dolmuştur.
            </p>

            {{-- Önceki Denemeler Tablosu --}}
            @if($previousAttempts->count() > 0)
            <div class="max-w-md w-full mx-auto mb-6">
                <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-3">Deneme Geçmişi</h4>
                <div class="bg-white dark:bg-gray-700/50 rounded-xl overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Deneme</th>
                                <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Ön Sınav</th>
                                <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Son Sınav</th>
                                <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Sonuç</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($previousAttempts as $pa)
                            <tr>
                                <td class="px-4 py-2.5 text-gray-700 dark:text-gray-300">{{ $pa->attempt_number }}.</td>
                                <td class="px-4 py-2.5 text-center font-semibold text-gray-800 dark:text-white">{{ $pa->has_pre_exam ? $pa->pre_exam_score . '/100' : '—' }}</td>
                                <td class="px-4 py-2.5 text-center font-bold text-gray-800 dark:text-white">{{ $pa->post_exam_score !== null ? $pa->post_exam_score . '/100' : '—' }}</td>
                                <td class="px-4 py-2.5 text-right">
                                    @if($pa->is_passed)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">Geçti</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">Kaldı</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <a href="{{ route('staff.courses.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Eğitimlerime Dön
            </a>
        </div>
    @endif
</div>
