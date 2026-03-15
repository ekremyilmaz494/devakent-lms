@extends('layouts.staff')
@section('title', 'Ana Sayfa')
@section('page-title', 'Ana Sayfa')

@section('content')
<div class="space-y-6">
    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-teal-500 via-emerald-500 to-green-500 rounded-2xl p-6 text-white relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 opacity-10">
            <svg viewBox="0 0 200 200" fill="currentColor"><path d="M100 0C44.8 0 0 44.8 0 100s44.8 100 100 100 100-44.8 100-100S155.2 0 100 0zm0 180c-44.2 0-80-35.8-80-80s35.8-80 80-80 80 35.8 80 80-35.8 80-80 80z"/><path d="M100 40c-33.1 0-60 26.9-60 60s26.9 60 60 60 60-26.9 60-60-26.9-60-60-60zm0 100c-22.1 0-40-17.9-40-40s17.9-40 40-40 40 17.9 40 40-17.9 40-40 40z"/></svg>
        </div>
        <div class="relative">
            <h2 class="text-2xl font-bold">Hoş geldin, {{ $user->first_name }}!</h2>
            <p class="mt-1 text-teal-100">{{ $user->title ?? 'Personel' }} &middot; {{ $user->department?->name ?? 'Departman atanmamış' }}</p>
        </div>
        @if($stats['total'] > 0)
        <div class="relative mt-4 flex items-center gap-2">
            <div class="flex-1 h-2 bg-white/20 rounded-full overflow-hidden">
                <div class="h-full bg-white rounded-full transition-all" style="width: {{ $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0 }}%"></div>
            </div>
            <span class="text-sm font-semibold">%{{ $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0 }}</span>
        </div>
        <p class="relative text-xs text-teal-100 mt-1">Genel ilerleme: {{ $stats['completed'] }}/{{ $stats['total'] }} eğitim tamamlandı</p>
        @endif
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        {{-- Completed --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['completed'] }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tamamlanan</p>
        </div>

        {{-- In Progress --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['in_progress'] }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Devam Eden</p>
        </div>

        {{-- Not Started --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['not_started'] }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Başlanmamış</p>
        </div>

        {{-- Certificates --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['certificates'] }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Sertifika</p>
        </div>
    </div>

    {{-- Course Cards --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Eğitimlerim</h3>
            @if($enrollments->count() > 6)
                <a href="{{ route('staff.courses.index') }}" class="text-sm text-teal-600 dark:text-teal-400 hover:underline font-medium">Tümünü Gör</a>
            @endif
        </div>

        @if($recentEnrollments->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($recentEnrollments as $enrollment)
            <a href="{{ route('staff.courses.show', $enrollment->course_id) }}"
               class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md hover:border-teal-200 dark:hover:border-teal-800 transition-all group">
                {{-- Color Bar --}}
                <div class="h-1.5" style="background-color: {{ $enrollment->course->category?->color ?? '#14B8A6' }}"></div>

                <div class="p-5">
                    {{-- Category & Status --}}
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ $enrollment->course->category?->name ?? 'Genel' }}</span>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full
                            @if($enrollment->status === 'completed') bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300
                            @elseif($enrollment->status === 'in_progress') bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300
                            @elseif($enrollment->status === 'failed') bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300
                            @else bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400
                            @endif">
                            @if($enrollment->status === 'completed') Tamamlandı
                            @elseif($enrollment->status === 'in_progress') Devam Ediyor
                            @elseif($enrollment->status === 'failed') Başarısız
                            @else Başlanmadı
                            @endif
                        </span>
                    </div>

                    {{-- Title --}}
                    <h4 class="font-semibold text-gray-800 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors line-clamp-2">{{ $enrollment->course->title }}</h4>

                    {{-- Info --}}
                    <div class="flex items-center gap-3 mt-3 text-xs text-gray-400 dark:text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $enrollment->course->exam_duration_minutes ?? 30 }} dk
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $enrollment->course->questions->count() }} soru
                        </span>
                        @if($enrollment->course->is_mandatory)
                            <span class="text-red-400 dark:text-red-500 font-medium">Zorunlu</span>
                        @endif
                    </div>

                    {{-- Deadline --}}
                    @if($enrollment->course->end_date)
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
            </a>
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
