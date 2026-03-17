@extends('layouts.staff')
@section('title', 'Eğitimlerim')
@section('page-title', 'Eğitimlerim')

@section('content')
<div class="space-y-5" x-data="{ filter: 'all' }">

    {{-- Filter bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
        {{-- Tabs --}}
        <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0 flex-1">
            <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-xl p-1 w-max"
                 role="group" aria-label="Eğitim durumu filtresi">
                @php
                    $filters = [
                        'all'          => ['label' => 'Tümü',       'count' => $enrollments->count(),                                          'color' => 'gray'],
                        'in_progress'  => ['label' => 'Devam Eden', 'count' => $enrollments->where('status', 'in_progress')->count(),           'color' => 'primary'],
                        'not_started'  => ['label' => 'Bekleyen',   'count' => $enrollments->where('status', 'not_started')->count(),           'color' => 'amber'],
                        'completed'    => ['label' => 'Tamamlanan', 'count' => $enrollments->where('status', 'completed')->count(),             'color' => 'emerald'],
                        'failed'       => ['label' => 'Başarısız',  'count' => $enrollments->where('status', 'failed')->count(),                'color' => 'red'],
                    ];
                @endphp
                @foreach($filters as $key => $f)
                    <button @click="filter = '{{ $key }}'"
                        :aria-pressed="filter === '{{ $key }}' ? 'true' : 'false'"
                        :class="filter === '{{ $key }}'
                            ? 'bg-white dark:bg-gray-700 shadow text-gray-800 dark:text-white'
                            : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                        class="relative px-3 py-2 text-xs font-semibold rounded-lg transition-all duration-200 whitespace-nowrap">
                        {{ $f['label'] }}
                        @if($f['count'] > 0)
                            <span class="ml-1 text-[10px] opacity-60">({{ $f['count'] }})</span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Summary chip --}}
        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 flex-shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <span>{{ $enrollments->count() }} eğitim</span>
        </div>
    </div>

    {{-- Course Grid --}}
    @if($enrollments->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($enrollments as $enrollment)
        <div x-show="filter === 'all' || filter === '{{ $enrollment->status }}'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <a href="{{ route('staff.courses.show', $enrollment->course_id) }}"
               class="group flex flex-col h-full rounded-2xl border overflow-hidden transition-all duration-300
               {{ $enrollment->status === 'failed'
                   ? 'bg-gray-50 dark:bg-gray-800/40 border-gray-200 dark:border-gray-700 opacity-70'
                   : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 hover:shadow-xl hover:shadow-gray-200/50 dark:hover:shadow-gray-900/50 hover:-translate-y-0.5' }}">

                {{-- Top color bar --}}
                <div class="h-1 flex-shrink-0"
                     style="background-color: {{ $enrollment->status === 'failed' ? '#9CA3AF' : ($enrollment->course->category?->color ?? '#14B8A6') }}"></div>

                <div class="p-5 flex flex-col flex-1">
                    {{-- Category & Status --}}
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            {{ $enrollment->course->category?->name ?? 'Genel' }}
                        </span>

                        @if($enrollment->status === 'completed')
                            <span class="flex items-center gap-1 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-2 py-0.5 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tamamlandı
                            </span>
                        @elseif($enrollment->status === 'in_progress')
                            <span class="flex items-center gap-1 text-[11px] font-semibold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20 px-2 py-0.5 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary-500 animate-pulse"></span> Devam Ediyor
                            </span>
                        @elseif($enrollment->status === 'failed')
                            <span class="flex items-center gap-1 text-[11px] font-semibold text-red-500 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Başarısız
                            </span>
                        @else
                            <span class="flex items-center gap-1 text-[11px] font-semibold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Başlanmadı
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
                    @php
                        $currentAttempt = $enrollment->current_attempt ?: 1;
                        $preExam = $enrollment->examAttempts->where('exam_type', 'pre_exam')->where('attempt_number', 1)->whereNotNull('finished_at')->first();
                        $postExam = $enrollment->examAttempts->where('exam_type', 'post_exam')->sortByDesc('attempt_number')->first();
                    @endphp
                    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <div class="grid grid-cols-2 gap-2">
                            <div class="text-center bg-gray-50 dark:bg-gray-700/30 rounded-xl py-2">
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-0.5">Ön Sınav</p>
                                <p class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $preExam ? $preExam->score . '/100' : '—' }}</p>
                            </div>
                            <div class="text-center bg-gray-50 dark:bg-gray-700/30 rounded-xl py-2">
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mb-0.5">Son Sınav</p>
                                <p class="text-sm font-bold {{ $postExam && $postExam->is_passed ? 'text-emerald-600 dark:text-emerald-400' : ($postExam ? 'text-red-500 dark:text-red-400' : 'text-gray-700 dark:text-gray-300') }}">
                                    {{ $postExam ? $postExam->score . '/100' : '—' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-[11px] text-gray-400 dark:text-gray-500">Deneme: {{ $currentAttempt }}/{{ $enrollment->course->max_attempts }}</span>
                            @if($enrollment->status === 'failed')
                                <span class="text-[11px] text-red-400 dark:text-red-500 flex items-center gap-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    Hak doldu
                                </span>
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
                    @if($enrollment->course->end_date && $enrollment->status !== 'completed')
                    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <span class="text-[11px] text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Son: <strong>{{ $enrollment->course->end_date->format('d.m.Y') }}</strong>
                            @if($enrollment->course->end_date->isPast())
                                <span class="text-red-500 font-semibold">(Dolmuş)</span>
                            @elseif($enrollment->course->end_date->diffInDays(now()) <= 7)
                                <span class="text-amber-500 font-semibold">({{ $enrollment->course->end_date->diffInDays(now()) }} gün)</span>
                            @endif
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Action footer --}}
                <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700/50
                    {{ $enrollment->status === 'failed' ? 'bg-gray-100/50 dark:bg-gray-700/20' : 'bg-gray-50/50 dark:bg-gray-700/10' }}
                    flex items-center justify-between">
                    <span class="text-xs font-bold {{ $enrollment->status === 'failed' ? 'text-gray-400 dark:text-gray-500' : 'text-primary-600 dark:text-primary-400' }}">
                        @if($enrollment->status === 'completed') Detayları Gör
                        @elseif($enrollment->status === 'in_progress') Devam Et
                        @elseif($enrollment->status === 'failed') Kilitli
                        @else Eğitime Başla
                        @endif
                    </span>
                    @if($enrollment->status !== 'failed')
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
    <div class="flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 p-14 text-center">
        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        </div>
        <p class="font-semibold text-gray-700 dark:text-gray-300">Henüz eğitim atanmadı</p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Eğitimler yöneticiniz tarafından atanacaktır.</p>
    </div>
    @endif

</div>
@endsection
