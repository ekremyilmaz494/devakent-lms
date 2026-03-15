@extends('layouts.staff')
@section('title', 'Eğitimlerim')
@section('page-title', 'Eğitimlerim')

@section('content')
<div class="space-y-6">
    {{-- Filter Tabs --}}
    <div x-data="{ filter: 'all' }" class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-700/50 rounded-xl p-1">
                @php
                    $filters = [
                        'all' => ['label' => 'Tümü', 'count' => $enrollments->count()],
                        'in_progress' => ['label' => 'Devam Eden', 'count' => $enrollments->where('status', 'in_progress')->count()],
                        'not_started' => ['label' => 'Başlanmamış', 'count' => $enrollments->where('status', 'not_started')->count()],
                        'completed' => ['label' => 'Tamamlanan', 'count' => $enrollments->where('status', 'completed')->count()],
                    ];
                @endphp
                @foreach($filters as $key => $f)
                    <button @click="filter = '{{ $key }}'"
                        :class="filter === '{{ $key }}' ? 'bg-white dark:bg-gray-800 shadow-sm text-gray-800 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                        class="px-4 py-2 text-[13px] font-medium rounded-lg transition-all">
                        {{ $f['label'] }}
                        <span class="ml-1 text-xs opacity-60">({{ $f['count'] }})</span>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Course Grid --}}
        @if($enrollments->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($enrollments as $enrollment)
            <div x-show="filter === 'all' || filter === '{{ $enrollment->status }}'" x-transition>
                <a href="{{ route('staff.courses.show', $enrollment->course_id) }}"
                   class="block bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:border-teal-200 dark:hover:border-teal-800 transition-all group h-full">
                    {{-- Color Bar --}}
                    <div class="h-1.5" style="background-color: {{ $enrollment->course->category?->color ?? '#14B8A6' }}"></div>

                    <div class="p-5">
                        {{-- Category & Status --}}
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ $enrollment->course->category?->name ?? 'Genel' }}</span>

                            @if($enrollment->status === 'completed')
                                <span class="flex items-center gap-1 text-xs font-medium text-emerald-600 dark:text-emerald-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tamamlandı
                                </span>
                            @elseif($enrollment->status === 'in_progress')
                                <span class="flex items-center gap-1 text-xs font-medium text-blue-600 dark:text-blue-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Devam Ediyor
                                </span>
                            @elseif($enrollment->status === 'failed')
                                <span class="flex items-center gap-1 text-xs font-medium text-red-600 dark:text-red-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Başarısız
                                </span>
                            @else
                                <span class="flex items-center gap-1 text-xs font-medium text-gray-400 dark:text-gray-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Başlanmadı
                                </span>
                            @endif
                        </div>

                        {{-- Title --}}
                        <h4 class="font-semibold text-gray-800 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors line-clamp-2 mb-2">{{ $enrollment->course->title }}</h4>

                        {{-- Description --}}
                        @if($enrollment->course->description)
                            <p class="text-xs text-gray-400 dark:text-gray-500 line-clamp-2 mb-3">{{ $enrollment->course->description }}</p>
                        @endif

                        {{-- Info Row --}}
                        <div class="flex items-center gap-3 text-xs text-gray-400 dark:text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $enrollment->course->exam_duration_minutes ?? 30 }} dk
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $enrollment->course->questions->count() }} soru
                            </span>
                            @if($enrollment->course->is_mandatory)
                                <span class="text-red-400 font-medium">Zorunlu</span>
                            @endif
                        </div>

                        {{-- Attempt Info --}}
                        @if($enrollment->status === 'in_progress' || $enrollment->status === 'failed')
                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                Deneme: {{ $enrollment->current_attempt ?: 1 }}/{{ $enrollment->course->max_attempts }}
                            </span>
                            @php
                                $lastAttempt = $enrollment->examAttempts->where('exam_type', 'post_exam')->last();
                            @endphp
                            @if($lastAttempt)
                                <span class="text-xs font-medium {{ $lastAttempt->is_passed ? 'text-emerald-600' : 'text-red-500' }}">
                                    Son puan: %{{ $lastAttempt->score }}
                                </span>
                            @endif
                        </div>
                        @endif

                        {{-- Certificate Badge --}}
                        @if($enrollment->certificate)
                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-2 text-xs text-emerald-600 dark:text-emerald-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                <span class="font-medium">Sertifika: {{ $enrollment->certificate->certificate_number }}</span>
                            </div>
                        </div>
                        @endif

                        {{-- Deadline --}}
                        @if($enrollment->course->end_date && $enrollment->status !== 'completed')
                        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                Son tarih: {{ $enrollment->course->end_date->format('d.m.Y') }}
                                @if($enrollment->course->end_date->isPast())
                                    <span class="text-red-500 font-medium">(Süresi dolmuş)</span>
                                @elseif($enrollment->course->end_date->diffInDays(now()) <= 7)
                                    <span class="text-amber-500 font-medium">({{ $enrollment->course->end_date->diffInDays(now()) }} gün kaldı)</span>
                                @endif
                            </span>
                        </div>
                        @endif
                    </div>

                    {{-- Action Footer --}}
                    <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700">
                        <span class="text-xs font-medium text-teal-600 dark:text-teal-400 group-hover:underline">
                            @if($enrollment->status === 'completed') Detayları Gör
                            @elseif($enrollment->status === 'in_progress') Devam Et
                            @elseif($enrollment->status === 'failed') Detayları Gör
                            @else Eğitime Başla
                            @endif
                            <svg class="inline w-3.5 h-3.5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white dark:bg-gray-800 rounded-xl p-12 border border-gray-200 dark:border-gray-700 text-center">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
            <p class="text-gray-500 dark:text-gray-400 font-medium">Henüz atanmış bir eğitim bulunmuyor</p>
            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Eğitimler yöneticiniz tarafından atanacaktır.</p>
        </div>
        @endif
    </div>
</div>
@endsection
