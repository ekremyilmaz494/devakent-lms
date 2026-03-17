@extends('layouts.staff')
@section('title', 'Ana Sayfa')
@section('page-title', 'Ana Sayfa')

@section('content')
<div class="space-y-6">

    {{-- Welcome Hero Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary-600 via-primary-500 to-teal-500 shadow-xl shadow-primary-500/20">
        {{-- Decorative circles --}}
        <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-white/10 blur-xl"></div>
        <div class="absolute top-0 left-1/2 w-24 h-24 rounded-full bg-white/5 blur-lg"></div>

        <div class="relative px-6 py-7">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 mb-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                        <span class="text-[11px] font-semibold text-white/90 uppercase tracking-wide">Çevrimiçi</span>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Hoş geldin, {{ $user->first_name }}! 👋</h2>
                    <p class="mt-1 text-primary-100 text-sm">{{ $user->title ?? 'Personel' }} &middot; {{ $user->department?->name ?? 'Departman atanmamış' }}</p>
                </div>

                @if($stats['total'] > 0)
                <div class="sm:text-right flex-shrink-0">
                    @php $pct = $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0; @endphp
                    <div class="inline-flex flex-col items-end">
                        <span class="text-3xl font-black text-white">%{{ $pct }}</span>
                        <span class="text-xs text-primary-100">Genel İlerleme</span>
                    </div>
                </div>
                @endif
            </div>

            @if($stats['total'] > 0)
            <div class="mt-5">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs text-primary-100">{{ $stats['completed'] }}/{{ $stats['total'] }} eğitim tamamlandı</span>
                    <span class="text-xs font-bold text-white">%{{ $pct }}</span>
                </div>
                <div class="w-full h-2 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full transition-all duration-700 shadow-sm"
                         style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        {{-- Completed --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:shadow-emerald-500/10 transition-all duration-300">
            <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-11 h-11 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-md shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-500/70 dark:text-emerald-400/60">Tamamlanan</span>
                </div>
                <p class="text-3xl font-black text-gray-800 dark:text-white">{{ $stats['completed'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">eğitim başarıyla tamamlandı</p>
            </div>
        </div>

        {{-- In Progress --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:shadow-primary-500/10 transition-all duration-300">
            <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-11 h-11 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center shadow-md shadow-primary-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-primary-500/70 dark:text-primary-400/60">Devam Eden</span>
                </div>
                <p class="text-3xl font-black text-gray-800 dark:text-white">{{ $stats['in_progress'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">eğitim şu an devam ediyor</p>
            </div>
        </div>

        {{-- Not Started --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:shadow-amber-500/10 transition-all duration-300">
            <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-amber-400 to-amber-600"></div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-11 h-11 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center shadow-md shadow-amber-500/30 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-amber-500/70 dark:text-amber-400/60">Bekleyen</span>
                </div>
                <p class="text-3xl font-black text-gray-800 dark:text-white">{{ $stats['not_started'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">eğitim henüz başlanmadı</p>
            </div>
        </div>

    </div>

    {{-- Course Cards --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-gray-800 dark:text-white">Eğitimlerim</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Son eklenen eğitimler</p>
            </div>
            @if($enrollments->count() > 6)
                <a href="{{ route('staff.courses.index') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20 hover:bg-primary-100 dark:hover:bg-primary-900/40 rounded-xl transition-colors">
                    Tümünü Gör
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endif
        </div>

        @if($recentEnrollments->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($recentEnrollments as $enrollment)
            <a href="{{ route('staff.courses.show', $enrollment->course_id) }}"
               class="group flex flex-col bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:shadow-gray-200/50 dark:hover:shadow-gray-900/50 hover:-translate-y-0.5 transition-all duration-300">

                {{-- Top color stripe --}}
                <div class="h-1" style="background-color: {{ $enrollment->course->category?->color ?? '#14B8A6' }}"></div>

                <div class="p-5 flex flex-col flex-1">
                    {{-- Category & Status --}}
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            {{ $enrollment->course->category?->name ?? 'Genel' }}
                        </span>
                        <span class="flex items-center gap-1 text-[11px] font-semibold px-2 py-0.5 rounded-full
                            @if($enrollment->status === 'completed') bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400
                            @elseif($enrollment->status === 'in_progress') bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400
                            @elseif($enrollment->status === 'failed') bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400
                            @else bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400
                            @endif">
                            @if($enrollment->status === 'completed')
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tamamlandı
                            @elseif($enrollment->status === 'in_progress')
                                <span class="w-1.5 h-1.5 rounded-full bg-primary-500 animate-pulse"></span> Devam Ediyor
                            @elseif($enrollment->status === 'failed')
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Başarısız
                            @else
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Başlanmadı
                            @endif
                        </span>
                    </div>

                    {{-- Title --}}
                    <h4 class="font-bold text-gray-800 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors line-clamp-2 leading-snug flex-1">
                        {{ $enrollment->course->title }}
                    </h4>

                    {{-- Info chips --}}
                    <div class="flex items-center gap-2 mt-3 text-xs text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1 bg-gray-50 dark:bg-gray-700/50 px-2 py-1 rounded-lg">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $enrollment->course->exam_duration_minutes ?? 30 }} dk
                        </span>
                        <span class="flex items-center gap-1 bg-gray-50 dark:bg-gray-700/50 px-2 py-1 rounded-lg">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $enrollment->course->questions->count() }} soru
                        </span>
                        @if($enrollment->course->is_mandatory)
                            <span class="bg-red-50 dark:bg-red-900/20 text-red-500 dark:text-red-400 px-2 py-1 rounded-lg font-semibold">Zorunlu</span>
                        @endif
                    </div>

                    {{-- Deadline --}}
                    @if($enrollment->course->end_date)
                    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <span class="text-[11px] text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Son tarih: <strong>{{ $enrollment->course->end_date->format('d.m.Y') }}</strong>
                            @if($enrollment->course->end_date->isPast())
                                <span class="text-red-500 font-semibold">(Süresi dolmuş)</span>
                            @elseif($enrollment->course->end_date->diffInDays(now()) <= 7)
                                <span class="text-amber-500 font-semibold">({{ $enrollment->course->end_date->diffInDays(now()) }} gün kaldı)</span>
                            @endif
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Footer action --}}
                <div class="px-5 py-3 border-t border-gray-100 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/20 flex items-center justify-between">
                    <span class="text-xs font-semibold text-primary-600 dark:text-primary-400 group-hover:underline">
                        @if($enrollment->status === 'completed') Detayları Gör
                        @elseif($enrollment->status === 'in_progress') Devam Et
                        @elseif($enrollment->status === 'failed') Kilitli
                        @else Eğitime Başla
                        @endif
                    </span>
                    <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 group-hover:text-primary-500 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
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
</div>
@endsection
