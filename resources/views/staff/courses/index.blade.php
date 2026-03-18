@extends('layouts.staff')
@section('title', 'Eğitimlerim')
@section('page-title', 'Eğitimlerim')

@section('content')
<div class="space-y-5" x-data="{ filter: 'all' }">

    {{-- ═══ Filter Tabs ═══ --}}
    <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0">
        <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-xl p-1 w-max"
             role="group" aria-label="Eğitim durumu filtresi">
            @php
                $filters = [
                    'all'          => ['label' => 'Tümü',       'count' => $enrollments->count(),                                          'dot' => 'bg-gray-400'],
                    'in_progress'  => ['label' => 'Devam Eden', 'count' => $enrollments->where('status', 'in_progress')->count(),           'dot' => 'bg-primary-500'],
                    'not_started'  => ['label' => 'Bekleyen',   'count' => $enrollments->where('status', 'not_started')->count(),           'dot' => 'bg-amber-400'],
                    'completed'    => ['label' => 'Tamamlanan', 'count' => $enrollments->where('status', 'completed')->count(),             'dot' => 'bg-emerald-500'],
                    'failed'       => ['label' => 'Başarısız',  'count' => $enrollments->where('status', 'failed')->count(),                'dot' => 'bg-red-500'],
                ];
            @endphp
            @foreach($filters as $key => $f)
                <button @click="filter = '{{ $key }}'"
                    :aria-pressed="filter === '{{ $key }}' ? 'true' : 'false'"
                    :class="filter === '{{ $key }}'
                        ? 'bg-white dark:bg-gray-700 shadow-sm text-gray-800 dark:text-white'
                        : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                    class="relative flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg transition-all duration-200 whitespace-nowrap">
                    {{ $f['label'] }}
                    @if($f['count'] > 0)
                        <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold rounded-full
                            {{ $key === 'all' ? 'bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300' : '' }}
                            {{ $key === 'in_progress' ? 'bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300' : '' }}
                            {{ $key === 'not_started' ? 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300' : '' }}
                            {{ $key === 'completed' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' : '' }}
                            {{ $key === 'failed' ? 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300' : '' }}
                        ">{{ $f['count'] }}</span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    {{-- ═══ Course Grid ═══ --}}
    @if($enrollments->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($enrollments as $enrollment)
        @php
            $isFailed = $enrollment->status === 'failed';
            $isCompleted = $enrollment->status === 'completed';
            $isInProgress = $enrollment->status === 'in_progress';
            $isNotStarted = $enrollment->status === 'not_started';

            // Sınav verileri
            $currentAttempt = $enrollment->current_attempt ?: 1;
            $preExam = $enrollment->examAttempts->where('exam_type', 'pre_exam')->where('attempt_number', 1)->whereNotNull('finished_at')->first();
            $postExam = $enrollment->examAttempts->where('exam_type', 'post_exam')->sortByDesc('attempt_number')->first();

            // Trend hesapla (Son Sınav vs Ön Sınav)
            $hasBothScores = $preExam && $postExam;
            $scoreTrend = $hasBothScores ? ($postExam->score - $preExam->score) : 0;

            // İlerleme yüzdesi
            $hasPreExam = $enrollment->examAttempts->where('exam_type', 'pre_exam')->whereNotNull('finished_at')->isNotEmpty();
            $hasPostExam = $enrollment->examAttempts->where('exam_type', 'post_exam')->whereNotNull('finished_at')->isNotEmpty();
            $progressPercent = match(true) {
                $isCompleted => 100,
                $isFailed || $isNotStarted => 0,
                $hasPostExam => 85,
                $hasPreExam => 40,
                $isInProgress => 10,
                default => 0,
            };

            // Son tarih hesaplaması (float hata düzeltmesi)
            $endDate = $enrollment->course->end_date;
            $kalanGun = null;
            $deadlineClass = '';
            $deadlineText = '';
            if ($endDate && !$isCompleted) {
                $kalanGun = (int) now()->startOfDay()->diffInDays($endDate->copy()->startOfDay(), false);
                if ($kalanGun < 0) {
                    $deadlineClass = 'text-gray-400 dark:text-gray-500';
                    $deadlineText = 'Süre doldu';
                } elseif ($kalanGun === 0) {
                    $deadlineClass = 'text-red-500 dark:text-red-400';
                    $deadlineText = 'Bugün son gün';
                } elseif ($kalanGun <= 7) {
                    $deadlineClass = 'text-amber-500 dark:text-amber-400';
                    $deadlineText = $kalanGun . ' gün kaldı';
                }
            }
        @endphp
        <div x-show="filter === 'all' || filter === '{{ $enrollment->status }}'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <a href="{{ route('staff.courses.show', $enrollment->course_id) }}"
               class="group relative flex flex-col h-full rounded-xl border overflow-hidden transition-all duration-200
               {{ $isFailed
                   ? 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 opacity-60'
                   : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg hover:shadow-gray-200/60 dark:hover:shadow-gray-900/60 hover:-translate-y-1' }}">

                {{-- Kilitli Overlay (Başarısız) --}}
                @if($isFailed)
                <div class="absolute inset-0 z-10 bg-gray-100/40 dark:bg-gray-900/40 flex items-center justify-center pointer-events-none">
                    <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                </div>
                @endif

                {{-- Top color bar --}}
                <div class="h-1 flex-shrink-0"
                     style="background-color: {{ $isFailed ? '#9CA3AF' : ($enrollment->course->category?->color ?? '#14B8A6') }}"></div>

                <div class="p-5 flex flex-col flex-1">
                    {{-- Category & Status --}}
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            {{ $enrollment->course->category?->name ?? 'Genel' }}
                        </span>

                        @if($isCompleted)
                            <span class="flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 px-2.5 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Tamamlandı
                            </span>
                        @elseif($isInProgress)
                            <span class="flex items-center gap-1 text-[11px] font-bold text-primary-600 dark:text-primary-400 bg-primary-100 dark:bg-primary-900/30 px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary-500 animate-pulse"></span>
                                Devam Ediyor
                            </span>
                        @elseif($isFailed)
                            <span class="flex items-center gap-1 text-[11px] font-bold text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30 px-2.5 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Başarısız
                            </span>
                        @else
                            <span class="flex items-center gap-1 text-[11px] font-bold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                Bekliyor
                            </span>
                        @endif
                    </div>

                    {{-- Title --}}
                    <h4 class="font-bold text-sm text-gray-800 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2 leading-snug">
                        {{ $enrollment->course->title }}
                    </h4>

                    {{-- Description --}}
                    @if($enrollment->course->description)
                        <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mt-1.5 leading-relaxed">{{ $enrollment->course->description }}</p>
                    @endif

                    {{-- Info chips --}}
                    <div class="flex flex-wrap items-center gap-1.5 mt-3">
                        <span class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 px-2 py-1 rounded-lg">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $enrollment->course->exam_duration_minutes ?? 30 }} dk
                        </span>
                        <span class="flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 px-2 py-1 rounded-lg">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $enrollment->course->questions->count() }} soru
                        </span>
                        @if($enrollment->course->is_mandatory)
                            <span class="text-[11px] font-bold text-red-500 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded-lg">Zorunlu</span>
                        @endif
                    </div>

                    {{-- Exam scores --}}
                    @if(in_array($enrollment->status, ['in_progress', 'completed', 'failed']))
                    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <div class="grid grid-cols-2 gap-2">
                            <div class="text-center bg-gray-50 dark:bg-gray-700/30 rounded-xl py-2.5 px-2">
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-1 uppercase tracking-wider font-medium">Ön Sınav</p>
                                <p class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $preExam ? $preExam->score . '/100' : '—' }}</p>
                            </div>
                            <div class="text-center bg-gray-50 dark:bg-gray-700/30 rounded-xl py-2.5 px-2">
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-1 uppercase tracking-wider font-medium">Son Sınav</p>
                                <div class="flex items-center justify-center gap-1">
                                    <p class="text-sm font-bold {{ $postExam && $postExam->is_passed ? 'text-emerald-600 dark:text-emerald-400' : ($postExam ? 'text-red-500 dark:text-red-400' : 'text-gray-700 dark:text-gray-300') }}">
                                        {{ $postExam ? $postExam->score . '/100' : '—' }}
                                    </p>
                                    @if($hasBothScores && $scoreTrend !== 0)
                                        @if($scoreTrend > 0)
                                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg>
                                        @else
                                            <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Deneme hakkı (görsel daireler) --}}
                        <div class="flex items-center justify-between mt-2.5">
                            <div class="flex items-center gap-1.5">
                                <span class="text-[11px] text-gray-400 dark:text-gray-500 mr-0.5">Deneme:</span>
                                @for($a = 1; $a <= $enrollment->course->max_attempts; $a++)
                                    <span class="w-2.5 h-2.5 rounded-full transition-colors {{ $a <= $currentAttempt ? 'bg-primary-500' : 'bg-gray-200 dark:bg-gray-600' }}"></span>
                                @endfor
                                <span class="text-[11px] text-gray-400 dark:text-gray-500 ml-0.5">{{ $currentAttempt }}/{{ $enrollment->course->max_attempts }}</span>
                            </div>
                            @if($isFailed)
                                <span class="text-[10px] font-semibold text-red-400 dark:text-red-500">Hak doldu</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Certificate --}}
                    @if($enrollment->certificate)
                    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-xs text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-3 py-1.5 rounded-lg">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            <span class="font-semibold truncate">{{ $enrollment->certificate->certificate_number }}</span>
                        </div>
                    </div>
                    @endif

                    {{-- Deadline --}}
                    @if($endDate && !$isCompleted)
                    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Son: <strong>{{ $endDate->format('d.m.Y') }}</strong>
                            </span>
                            @if($deadlineText)
                                <span class="text-[10px] font-bold {{ $deadlineClass }}">{{ $deadlineText }}</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Progress Bar --}}
                    @if($isInProgress || $isCompleted)
                    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">İlerleme</span>
                            <span class="text-[11px] font-bold {{ $isCompleted ? 'text-emerald-600 dark:text-emerald-400' : 'text-primary-600 dark:text-primary-400' }}">%{{ $progressPercent }}</span>
                        </div>
                        <div class="h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $isCompleted ? 'bg-emerald-500' : 'bg-gradient-to-r from-primary-500 to-primary-400' }}"
                                 style="width: {{ $progressPercent }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Action footer --}}
                <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700/50
                    {{ $isFailed ? 'bg-gray-50 dark:bg-gray-800/50' : 'bg-gray-50/70 dark:bg-gray-700/10' }}
                    flex items-center justify-between">
                    <span class="text-xs font-bold {{ $isFailed ? 'text-gray-400 dark:text-gray-500' : 'text-primary-600 dark:text-primary-400' }}">
                        @if($isCompleted) Detayları Gör
                        @elseif($isInProgress) Devam Et
                        @elseif($isFailed) Kilitli
                        @else Eğitime Başla
                        @endif
                    </span>
                    @if(!$isFailed)
                    <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 group-hover:text-primary-500 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    @else
                    <svg class="w-4 h-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    @endif
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @else
    <div class="flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-xl border border-dashed border-gray-200 dark:border-gray-700 p-14 text-center shadow-sm">
        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        </div>
        <p class="font-semibold text-gray-700 dark:text-gray-300">Henüz eğitim atanmadı</p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Eğitimler yöneticiniz tarafından atanacaktır.</p>
    </div>
    @endif

</div>
@endsection
