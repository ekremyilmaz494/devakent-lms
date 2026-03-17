@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Dashboard'],
    ]])
@endsection

@section('content')
<div class="space-y-6">

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- 1. HERO BANNER                                        --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary-700 via-primary-600 to-teal-600 shadow-2xl shadow-primary-500/20">
        {{-- Decorative blobs --}}
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
        <div class="absolute right-32 bottom-0 w-40 h-40 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
        <div class="absolute -left-8 bottom-0 w-32 h-32 rounded-full bg-white/5 blur-xl pointer-events-none"></div>
        {{-- Grid mesh --}}
        <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: url(\"data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M0 38.59l2.83-2.83 1.41 1.41L1.41 40H0v-1.41zM0 1.4l2.83 2.83 1.41-1.41L1.41 0H0v1.41zM38.59 40l-2.83-2.83 1.41-1.41L40 38.59V40h-1.41zM40 1.41l-2.83 2.83-1.41-1.41L38.59 0H40v1.41zM20 18.6l2.83-2.83 1.41 1.41L21.41 20l2.83 2.83-1.41 1.41L20 21.41l-2.83 2.83-1.41-1.41L18.59 20l-2.83-2.83 1.41-1.41L20 18.59z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\")"></div>

        <div class="relative px-6 sm:px-8 py-7 sm:py-8">
            <div class="flex flex-col lg:flex-row lg:items-center gap-6">

                {{-- Left: Greeting + Stats --}}
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 mb-3 px-3 py-1 rounded-full bg-white/15 backdrop-blur-sm border border-white/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                        <span class="text-[11px] font-bold text-white/90 uppercase tracking-widest">Sistem Aktif</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-white leading-tight">
                        Merhaba, {{ auth()->user()->name }} 👋
                    </h2>
                    <p class="text-primary-100 mt-1.5 text-sm">
                        @if($activeCourses > 0)
                            <span class="font-bold text-white">{{ $activeCourses }}</span> aktif eğitim
                        @else
                            Henüz aktif eğitim yok
                        @endif
                        @if($totalStaff > 0)
                            &mdash; <span class="font-bold text-white">{{ $totalStaff }}</span> kayıtlı personel mevcut.
                        @else
                            &mdash; henüz personel kayıtlı değil.
                        @endif
                    </p>

                    {{-- Inline mini stats --}}
                    <div class="flex flex-wrap gap-3 mt-5">
                        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/15 rounded-xl px-3 py-2">
                            <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-xs font-bold text-white">%{{ $completionRate }}</span>
                            <span class="text-[11px] text-primary-200">Tamamlanma</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/15 rounded-xl px-3 py-2">
                            <svg class="w-4 h-4 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            <span class="text-xs font-bold text-white">{{ $certificatesIssued }}</span>
                            <span class="text-[11px] text-primary-200">Sertifika</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/15 rounded-xl px-3 py-2">
                            <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <span class="text-xs font-bold text-white">{{ $totalExams }}</span>
                            <span class="text-[11px] text-primary-200">Sınav</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Quick Actions --}}
                <div class="flex flex-wrap gap-2 lg:flex-col lg:w-auto">
                    <a href="{{ route('admin.courses.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-primary-700 text-[13px] font-bold rounded-xl shadow-lg shadow-black/10 hover:bg-primary-50 hover:shadow-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Yeni Eğitim
                    </a>
                    <a href="{{ route('admin.staff.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 backdrop-blur-sm border border-white/20 text-white text-[13px] font-bold rounded-xl hover:bg-white/25 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Personel Ekle
                    </a>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.reports.index') }}"
                           class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-white/10 border border-white/15 text-white text-[12px] font-semibold rounded-xl hover:bg-white/20 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Raporlar
                        </a>
                        <a href="{{ route('admin.notifications.index') }}"
                           class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-white/10 border border-white/15 text-white text-[12px] font-semibold rounded-xl hover:bg-white/20 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            Bildirim
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- 2. STAT CARDS                                         --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Active Courses --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:shadow-primary-500/10 dark:hover:shadow-primary-900/20 hover:-translate-y-0.5 transition-all duration-300">
            <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span class="flex items-center gap-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-2 py-1 rounded-lg">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Yayında
                    </span>
                </div>
                <p class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $activeCourses }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Aktif Eğitim</p>
                {{-- Mini bar chart --}}
                <div class="flex items-end gap-0.5 mt-3 h-6">
                    @foreach([2, 3, 2.5, 4, 3.5, 5, 4] as $h)
                    <div class="flex-1 rounded-sm opacity-40 group-hover:opacity-70 transition-opacity"
                         style="height: {{ $h * 5 }}px; background: linear-gradient(to top, #14b8a6, #0ea5e9)"></div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Total Staff --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:shadow-teal-500/10 hover:-translate-y-0.5 transition-all duration-300">
            <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-teal-400 to-teal-600"></div>
            <div class="p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 bg-gradient-to-br from-teal-400 to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-bold text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-900/20 px-2 py-1 rounded-lg">Kayıtlı</span>
                </div>
                <p class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $totalStaff }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Toplam Personel</p>
                <div class="flex items-end gap-0.5 mt-3 h-6">
                    @foreach([3, 2, 4, 3.5, 5, 4, 4.5] as $h)
                    <div class="flex-1 rounded-sm opacity-40 group-hover:opacity-70 transition-opacity"
                         style="height: {{ $h * 5 }}px; background: linear-gradient(to top, #14b8a6, #34d399)"></div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Completion Rate --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:shadow-emerald-500/10 hover:-translate-y-0.5 transition-all duration-300">
            <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
            <div class="p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-2 py-1 rounded-lg">{{ $completedEnrollments }}/{{ $totalEnrollments }}</span>
                </div>
                <p class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">%{{ $completionRate }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Tamamlanma Oranı</p>
                {{-- Progress bar --}}
                <div class="mt-3 h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full transition-all duration-700"
                         style="width: {{ $completionRate }}%"></div>
                </div>
            </div>
        </div>

        {{-- Certificates --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-xl hover:shadow-amber-500/10 hover:-translate-y-0.5 transition-all duration-300">
            <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-amber-400 to-amber-600"></div>
            <div class="p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-11 h-11 bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                    <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 px-2 py-1 rounded-lg">Verilen</span>
                </div>
                <p class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $certificatesIssued }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-medium">Sertifika</p>
                <div class="flex items-end gap-0.5 mt-3 h-6">
                    @foreach([1, 2, 2, 3, 2.5, 4, 3.5] as $h)
                    <div class="flex-1 rounded-sm opacity-40 group-hover:opacity-70 transition-opacity"
                         style="height: {{ $h * 5 }}px; background: linear-gradient(to top, #f59e0b, #fb923c)"></div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- 3. SINAV İSTATİSTİKLERİ + BAŞARI DAĞILIMI            --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Exam Stats Table (2/3) --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
            <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-primary-400 to-primary-600 rounded-t-2xl" style="position:relative"></div>
            {{-- Header --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/40 dark:to-primary-800/30 rounded-xl flex items-center justify-center">
                        <svg class="w-4.5 h-4.5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white">Sınav İstatistikleri</h3>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500">Son sınav sonuçları</p>
                    </div>
                </div>
            </div>

            {{-- Mini stat strip --}}
            <div class="grid grid-cols-3 gap-0 border-b border-gray-100 dark:border-gray-700">
                <div class="px-5 py-4 text-center border-r border-gray-100 dark:border-gray-700">
                    <p class="text-xl font-black text-gray-900 dark:text-white">{{ $totalExams }}</p>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500 font-medium mt-0.5">Toplam Sınav</p>
                </div>
                <div class="px-5 py-4 text-center border-r border-gray-100 dark:border-gray-700">
                    <p class="text-xl font-black text-emerald-600 dark:text-emerald-400">%{{ $examPassRate }}</p>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500 font-medium mt-0.5">Başarı Oranı</p>
                </div>
                <div class="px-5 py-4 text-center">
                    <p class="text-xl font-black text-primary-600 dark:text-primary-400">{{ $avgScore }}</p>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500 font-medium mt-0.5">Ort. Puan</p>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/20">
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500">Personel</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500">Eğitim</th>
                            <th class="px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500">Puan</th>
                            <th class="px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500">Sonuç</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @forelse($recentExams as $exam)
                            <tr class="hover:bg-primary-50/30 dark:hover:bg-primary-900/10 transition-colors group/row">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                                            <span class="text-[9px] font-black text-white">{{ strtoupper(substr($exam->enrollment->user->first_name ?? '', 0, 1) . substr($exam->enrollment->user->last_name ?? '', 0, 1)) }}</span>
                                        </div>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $exam->enrollment->user->full_name ?? 'Bilinmeyen' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 max-w-[200px] truncate">{{ $exam->enrollment->course->title ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="font-black text-lg {{ $exam->is_passed ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-500 dark:text-red-400' }}">
                                        {{ $exam->score }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if($exam->is_passed)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-bold bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            Geçti
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[11px] font-bold bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-800">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Kaldı
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center">
                                    <p class="text-sm text-gray-400 dark:text-gray-500">Henüz sınav sonucu yok</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Exam Pass Rate Doughnut (1/3) --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm flex flex-col">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="w-9 h-9 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/40 dark:to-primary-800/30 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white">Sınav Başarı</h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Geçti / Kaldı dağılımı</p>
                </div>
            </div>
            <div class="flex-1 flex items-center justify-center p-5">
                <canvas id="examPassRateChart" height="220"></canvas>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- 4. POPÜLER EĞİTİMLER + DEPARTMANLAR                  --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Top Courses Table (2/3) --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-indigo-100 to-indigo-200 dark:from-indigo-900/40 dark:to-indigo-800/30 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white">Popüler Eğitimler</h3>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500">En fazla kayıtlı eğitimler</p>
                    </div>
                </div>
                <a href="{{ route('admin.courses.index') }}" class="text-[11px] font-bold text-primary-600 dark:text-primary-400 hover:underline">Tümünü gör</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/20">
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500">Eğitim</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500">Kategori</th>
                            <th class="px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500">Kayıt</th>
                            <th class="px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500">Soru</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @forelse($topCourses as $i => $course)
                            <tr class="hover:bg-primary-50/30 dark:hover:bg-primary-900/10 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-5 h-5 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-[10px] font-black text-gray-500 dark:text-gray-400 flex-shrink-0">{{ $i + 1 }}</span>
                                        <span class="font-semibold text-gray-800 dark:text-gray-200 truncate max-w-[180px]">{{ $course->title }}</span>
                                        @if($course->is_mandatory)
                                            <span class="px-1.5 py-0.5 rounded-lg text-[10px] font-bold bg-red-50 dark:bg-red-900/20 text-red-500 flex-shrink-0">Zorunlu</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($course->category)
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg text-[11px] font-semibold"
                                              style="background-color: {{ $course->category->color }}15; color: {{ $course->category->color }};">
                                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background-color: {{ $course->category->color }};"></span>
                                            {{ $course->category->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="font-bold text-gray-700 dark:text-gray-300">{{ $course->enrollments_count }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-gray-500 dark:text-gray-400">{{ $course->questions_count }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-sm text-gray-400 dark:text-gray-500">Henüz eğitim oluşturulmamış</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Department Overview (1/3) --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="w-9 h-9 bg-gradient-to-br from-violet-100 to-violet-200 dark:from-violet-900/40 dark:to-violet-800/30 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white">Departmanlar</h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Personel dağılımı</p>
                </div>
            </div>
            @php $totalDeptStaff = $departmentStats->sum('users_count'); @endphp
            <div class="p-5 space-y-4">
                @forelse($departmentStats as $dept)
                    @php $percent = $totalStaff > 0 ? ($dept->users_count / $totalStaff * 100) : 0; @endphp
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[13px] font-semibold text-gray-700 dark:text-gray-300 truncate max-w-[140px]">{{ $dept->name }}</span>
                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                <span class="text-[11px] font-bold text-gray-600 dark:text-gray-400">{{ $dept->users_count }}</span>
                                <span class="text-[10px] text-gray-400 dark:text-gray-500">kişi</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-violet-400 to-violet-600 transition-all duration-700"
                                 style="width: {{ $dept->users_count > 0 ? max($percent, 2) : 0 }}%"></div>
                        </div>
                        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">%{{ round($percent) }}</p>
                    </div>
                @empty
                    <p class="text-center text-sm text-gray-400 dark:text-gray-500 py-4">Departman verisi yok</p>
                @endforelse
                @if($departmentStats->isNotEmpty() && $totalDeptStaff === 0)
                    <div class="mt-2 flex items-start gap-2 p-3 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800">
                        <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-[11px] text-amber-700 dark:text-amber-300 leading-snug">Personeller henüz departmana atanmamış. Personel profili üzerinden departman ataması yapılabilir.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- 5. DİKKAT GEREKTİREN + SÜRESİ YAKLAŞAN              --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Attention Panel --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm" x-data="{ activeTab: 'inactive' }">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/40 dark:to-red-800/30 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white">Dikkat Gerektiren</h3>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500">Aksiyon gerektiren durumlar</p>
                    </div>
                </div>
                @if($inactiveStaff->count() + $mandatoryIncomplete->count() > 0)
                    <span class="flex items-center gap-1 px-2 py-1 rounded-xl text-[11px] font-black bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                        {{ $inactiveStaff->count() + $mandatoryIncomplete->count() }}
                    </span>
                @endif
            </div>

            {{-- Tab Buttons --}}
            <div class="flex gap-1 p-2 bg-gray-50 dark:bg-gray-700/30 border-b border-gray-100 dark:border-gray-700">
                <button @click="activeTab = 'inactive'"
                        :class="activeTab === 'inactive' ? 'bg-white dark:bg-gray-700 shadow text-gray-800 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                        class="flex-1 px-3 py-2 text-[12px] font-semibold rounded-xl transition-all">
                    Giriş Yapmayan (30+)
                    @if($inactiveStaff->count() > 0)
                    <span class="ml-1 text-[10px] text-red-500">({{ $inactiveStaff->count() }})</span>
                    @endif
                </button>
                <button @click="activeTab = 'mandatory'"
                        :class="activeTab === 'mandatory' ? 'bg-white dark:bg-gray-700 shadow text-gray-800 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'"
                        class="flex-1 px-3 py-2 text-[12px] font-semibold rounded-xl transition-all">
                    Zorunlu Eksik
                    @if($mandatoryIncomplete->count() > 0)
                    <span class="ml-1 text-[10px] text-amber-500">({{ $mandatoryIncomplete->count() }})</span>
                    @endif
                </button>
            </div>

            {{-- Inactive Staff --}}
            <div x-show="activeTab === 'inactive'" class="divide-y divide-gray-50 dark:divide-gray-700/50 max-h-72 overflow-y-auto">
                @forelse($inactiveStaff as $user)
                    <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-red-50/30 dark:hover:bg-red-900/10 transition-colors">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-gray-300 to-gray-500 dark:from-gray-600 dark:to-gray-700 flex items-center justify-center flex-shrink-0">
                            <span class="text-[10px] font-black text-white">{{ strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $user->full_name }}</p>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500">{{ $user->department->name ?? '—' }}</p>
                        </div>
                        <span class="text-[11px] font-semibold text-red-500 dark:text-red-400 whitespace-nowrap bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded-lg">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Hiç giriş yapmadı' }}
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center">
                        <svg class="w-8 h-8 mx-auto text-emerald-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Tüm personel aktif</p>
                    </div>
                @endforelse
            </div>

            {{-- Mandatory Incomplete --}}
            <div x-show="activeTab === 'mandatory'" x-cloak class="divide-y divide-gray-50 dark:divide-gray-700/50 max-h-72 overflow-y-auto">
                @forelse($mandatoryIncomplete as $user)
                    <div class="flex items-center gap-3 px-5 py-3.5 hover:bg-amber-50/30 dark:hover:bg-amber-900/10 transition-colors">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                            <span class="text-[10px] font-black text-white">{{ strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-semibold text-gray-800 dark:text-gray-200 truncate">{{ $user->full_name }}</p>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500">{{ $user->department->name ?? '—' }}</p>
                        </div>
                        <div class="text-right flex-shrink-0 max-w-[130px]">
                            @foreach($user->enrollments->take(2) as $enrollment)
                                <p class="text-[11px] text-amber-600 dark:text-amber-400 truncate font-medium">{{ $enrollment->course->title ?? '—' }}</p>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center">
                        <svg class="w-8 h-8 mx-auto text-emerald-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Tüm zorunlu eğitimler tamamlandı</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Expiring Courses --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/40 dark:to-amber-800/30 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white">Süresi Yaklaşan</h3>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500">Yakın vadeli eğitimler</p>
                    </div>
                </div>
                @if($expiringCourses->count() > 0)
                    <span class="flex items-center gap-1 px-2 py-1 rounded-xl text-[11px] font-black bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        {{ $expiringCourses->count() }}
                    </span>
                @endif
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-700/50 max-h-72 overflow-y-auto">
                @forelse($expiringCourses as $course)
                    @php
                        $daysLeft = now()->diffInDays($course->end_date, false);
                        $completionPercent = $course->enrollments_count > 0 ? round($course->completed_count / $course->enrollments_count * 100) : 0;
                        $urgency = $daysLeft <= 3 ? 'red' : ($daysLeft <= 7 ? 'amber' : 'primary');
                        $urgencyText = [
                            'red' => 'text-red-600 dark:text-red-400',
                            'amber' => 'text-amber-600 dark:text-amber-400',
                            'primary' => 'text-primary-600 dark:text-primary-400',
                        ][$urgency];
                        $urgencyBg = [
                            'red' => 'bg-red-50 dark:bg-red-900/20',
                            'amber' => 'bg-amber-50 dark:bg-amber-900/20',
                            'primary' => 'bg-primary-50 dark:bg-primary-900/20',
                        ][$urgency];
                        $barGradient = [
                            'red' => 'from-red-400 to-red-600',
                            'amber' => 'from-amber-400 to-amber-600',
                            'primary' => 'from-primary-400 to-primary-600',
                        ][$urgency];
                    @endphp
                    <div class="px-5 py-4 hover:bg-amber-50/20 dark:hover:bg-amber-900/10 transition-colors">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <span class="text-[13px] font-semibold text-gray-800 dark:text-gray-200 leading-snug truncate">{{ $course->title }}</span>
                            <span class="flex-shrink-0 text-[11px] font-black {{ $urgencyText }} {{ $urgencyBg }} px-2 py-0.5 rounded-lg">
                                {{ $daysLeft }} gün
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-1 bg-gray-100 dark:bg-gray-700 rounded-full h-1.5 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r {{ $barGradient }} transition-all duration-700"
                                     style="width: {{ max($completionPercent, 2) }}%"></div>
                            </div>
                            <span class="text-[11px] text-gray-400 dark:text-gray-500 whitespace-nowrap font-medium">
                                %{{ $completionPercent }} &middot; {{ $course->completed_count }}/{{ $course->enrollments_count }}
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1.5 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Bitiş: {{ $course->end_date->format('d.m.Y') }}
                        </p>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center">
                        <svg class="w-8 h-8 mx-auto text-emerald-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Süresi yaklaşan eğitim yok</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- 6. HAFTALIK AKTİVİTE + KAYIT DURUMU                  --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Weekly Activity Chart (2/3) --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/40 dark:to-primary-800/30 rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 dark:text-white">Haftalık Aktivite</h3>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500">Kayıt ve sınav eğilimi</p>
                    </div>
                </div>
            </div>
            <div class="p-5">
                <canvas id="weeklyActivityChart" height="200"></canvas>
            </div>
        </div>

        {{-- Enrollment Status Doughnut (1/3) --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm flex flex-col">
            <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="w-9 h-9 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/40 dark:to-primary-800/30 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white">Kayıt Durumu</h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Durum dağılımı</p>
                </div>
            </div>
            <div class="flex-1 flex items-center justify-center p-5">
                <canvas id="enrollmentStatusChart" height="220"></canvas>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- 7. AYLIK TREND                                        --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/30 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white">Aylık Trend</h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Son 6 ayın özeti</p>
                </div>
            </div>
        </div>
        <div class="p-5">
            <canvas id="monthlyTrendChart" height="120"></canvas>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- 8. DEPARTMAN TAMAMLANMA                               --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gradient-to-br from-violet-100 to-violet-200 dark:from-violet-900/40 dark:to-violet-800/30 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white">Departman Tamamlanma</h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Departmana göre başarı oranları</p>
                </div>
            </div>
        </div>
        <div class="p-5">
            <canvas id="deptCompletionChart" height="{{ max(count($deptCompletionRates) * 35, 120) }}"></canvas>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- 9. SON KAYITLAR                                       --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-gradient-to-br from-emerald-100 to-emerald-200 dark:from-emerald-900/40 dark:to-emerald-800/30 rounded-xl flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white">Son Kayıtlar</h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">En son eğitim atamaları</p>
                </div>
            </div>
            <a href="{{ route('admin.staff.index') }}" class="text-[11px] font-bold text-primary-600 dark:text-primary-400 hover:underline">Personelleri Gör</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/20">
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500">Personel</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500">Eğitim</th>
                        <th class="px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500">Durum</th>
                        <th class="px-5 py-3 text-right text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-gray-500">Tarih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($recentEnrollments as $enrollment)
                        <tr class="hover:bg-primary-50/30 dark:hover:bg-primary-900/10 transition-colors">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <span class="text-[9px] font-black text-white">{{ strtoupper(substr($enrollment->user->first_name ?? '', 0, 1) . substr($enrollment->user->last_name ?? '', 0, 1)) }}</span>
                                    </div>
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $enrollment->user->full_name ?? 'Bilinmeyen' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-gray-500 dark:text-gray-400 max-w-[200px] truncate">{{ $enrollment->course->title ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-center">
                                @php
                                    $statusMap = [
                                        'not_started' => ['label' => 'Başlanmadı', 'class' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-600'],
                                        'in_progress' => ['label' => 'Devam Ediyor', 'class' => 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 border-primary-100 dark:border-primary-800'],
                                        'completed'   => ['label' => 'Tamamlandı', 'class' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-800'],
                                        'failed'      => ['label' => 'Başarısız', 'class' => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border-red-100 dark:border-red-800'],
                                    ];
                                    $status = $statusMap[$enrollment->status] ?? $statusMap['not_started'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[11px] font-bold border {{ $status['class'] }}">{{ $status['label'] }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-right text-[12px] text-gray-400 dark:text-gray-500 font-medium">{{ $enrollment->created_at->format('d.m.Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-sm text-gray-400 dark:text-gray-500">Henüz eğitim kaydı yok</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#9ca3af' : '#6b7280';
    const gridColor = isDark ? 'rgba(75,85,99,0.3)' : 'rgba(209,213,219,0.5)';

    Chart.defaults.color = textColor;
    Chart.defaults.font.family = "'Oxanium', sans-serif";
    Chart.defaults.font.size = 12;

    // ── No-data helper ──────────────────────────────────────────
    function showNoData(canvasId, msg) {
        var el = document.getElementById(canvasId);
        if (!el) return;
        el.style.display = 'none';
        var wrap = el.parentElement;
        var nd = document.createElement('div');
        nd.className = 'flex flex-col items-center justify-center py-10 gap-3 text-center';
        nd.innerHTML = '<svg class="w-10 h-10 text-gray-200 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>'
            + '<p class="text-sm text-gray-400 dark:text-gray-500">' + msg + '</p>';
        wrap.appendChild(nd);
    }
    // ────────────────────────────────────────────────────────────

    const tooltipDefaults = {
        backgroundColor: isDark ? '#1f2937' : '#fff',
        titleColor: isDark ? '#f3f4f6' : '#111827',
        bodyColor: isDark ? '#d1d5db' : '#4b5563',
        borderColor: isDark ? '#374151' : '#e5e7eb',
        borderWidth: 1,
        cornerRadius: 10,
        padding: 10,
    };

    // 1. Haftalık Aktivite
    const weeklyData = @json($weeklyActivity);
    const weeklyTotal = weeklyData.reduce(function(s, d) { return s + d.enrollments + d.exams; }, 0);
    if (weeklyTotal === 0) {
        showNoData('weeklyActivityChart', 'Bu hafta henüz kayıt veya sınav aktivitesi yok');
    } else
    new Chart(document.getElementById('weeklyActivityChart'), {
        type: 'bar',
        data: {
            labels: weeklyData.map(d => d.day + '\n' + d.date),
            datasets: [
                {
                    label: 'Kayıt',
                    data: weeklyData.map(d => d.enrollments),
                    backgroundColor: isDark ? 'rgba(99,102,241,0.7)' : 'rgba(79,70,229,0.7)',
                    borderRadius: 8,
                    borderSkipped: false,
                    barPercentage: 0.6,
                    categoryPercentage: 0.7,
                },
                {
                    label: 'Sınav',
                    data: weeklyData.map(d => d.exams),
                    backgroundColor: isDark ? 'rgba(52,211,153,0.7)' : 'rgba(16,185,129,0.7)',
                    borderRadius: 8,
                    borderSkipped: false,
                    barPercentage: 0.6,
                    categoryPercentage: 0.7,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', align: 'end', labels: { usePointStyle: true, pointStyle: 'rectRounded', padding: 16, boxWidth: 8, boxHeight: 8 } },
                tooltip: { ...tooltipDefaults }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                y: { beginAtZero: true, grid: { color: gridColor }, ticks: { stepSize: 1, font: { size: 11 } } }
            }
        }
    });

    // 2. Kayıt Durumu Doughnut
    const statusData = @json($enrollmentStatusDist);
    const statusTotal = Object.values(statusData).reduce((a, b) => a + b, 0);
    if (statusTotal === 0) {
        showNoData('enrollmentStatusChart', 'Henüz kayıt verisi yok');
    } else
    new Chart(document.getElementById('enrollmentStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Başlanmadı', 'Devam Ediyor', 'Tamamlandı', 'Başarısız'],
            datasets: [{
                data: [statusData.not_started, statusData.in_progress, statusData.completed, statusData.failed],
                backgroundColor: [
                    isDark ? 'rgba(156,163,175,0.6)' : 'rgba(107,114,128,0.6)',
                    isDark ? 'rgba(99,102,241,0.7)' : 'rgba(79,70,229,0.7)',
                    isDark ? 'rgba(52,211,153,0.7)' : 'rgba(16,185,129,0.7)',
                    isDark ? 'rgba(248,113,113,0.7)' : 'rgba(239,68,68,0.7)',
                ],
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '68%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 12, boxWidth: 8, boxHeight: 8, font: { size: 11 } } },
                tooltip: {
                    ...tooltipDefaults,
                    callbacks: { label: function(ctx) { const pct = statusTotal > 0 ? Math.round(ctx.raw / statusTotal * 100) : 0; return ctx.label + ': ' + ctx.raw + ' (%' + pct + ')'; } }
                }
            }
        }
    });

    // 3. Sınav Başarı Doughnut
    if ({{ $totalExams }} === 0) {
        showNoData('examPassRateChart', 'Henüz sınav verisi yok');
    } else
    new Chart(document.getElementById('examPassRateChart'), {
        type: 'doughnut',
        data: {
            labels: ['Geçti', 'Kaldı'],
            datasets: [{
                data: [{{ $passedExams }}, {{ $totalExams - $passedExams }}],
                backgroundColor: [
                    isDark ? 'rgba(52,211,153,0.7)' : 'rgba(16,185,129,0.7)',
                    isDark ? 'rgba(248,113,113,0.7)' : 'rgba(239,68,68,0.7)',
                ],
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '68%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 12, boxWidth: 8, boxHeight: 8, font: { size: 11 } } },
                tooltip: { ...tooltipDefaults }
            }
        }
    });

    // 4. Aylık Trend
    const monthlyData = @json($monthlyTrend);
    new Chart(document.getElementById('monthlyTrendChart'), {
        type: 'line',
        data: {
            labels: monthlyData.map(d => d.month),
            datasets: [
                {
                    label: 'Yeni Kayıt',
                    data: monthlyData.map(d => d.new_enrollments),
                    borderColor: isDark ? '#818cf8' : '#4f46e5',
                    backgroundColor: isDark ? 'rgba(129,140,248,0.1)' : 'rgba(79,70,229,0.1)',
                    fill: true, tension: 0.4, borderWidth: 2.5,
                    pointRadius: 4, pointHoverRadius: 7,
                    pointBackgroundColor: isDark ? '#818cf8' : '#4f46e5',
                },
                {
                    label: 'Tamamlanan',
                    data: monthlyData.map(d => d.completions),
                    borderColor: isDark ? '#34d399' : '#10b981',
                    backgroundColor: isDark ? 'rgba(52,211,153,0.1)' : 'rgba(16,185,129,0.1)',
                    fill: true, tension: 0.4, borderWidth: 2.5,
                    pointRadius: 4, pointHoverRadius: 7,
                    pointBackgroundColor: isDark ? '#34d399' : '#10b981',
                },
                {
                    label: 'Başarılı Sınav',
                    data: monthlyData.map(d => d.exams_passed),
                    borderColor: isDark ? '#60a5fa' : '#3b82f6',
                    backgroundColor: isDark ? 'rgba(96,165,250,0.1)' : 'rgba(59,130,246,0.1)',
                    fill: true, tension: 0.4, borderWidth: 2.5,
                    pointRadius: 4, pointHoverRadius: 7,
                    pointBackgroundColor: isDark ? '#60a5fa' : '#3b82f6',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', align: 'end', labels: { usePointStyle: true, pointStyle: 'circle', padding: 16, boxWidth: 8, boxHeight: 8 } },
                tooltip: { ...tooltipDefaults, mode: 'index', intersect: false }
            },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: gridColor }, ticks: { stepSize: 1 } }
            },
            interaction: { mode: 'nearest', axis: 'x', intersect: false }
        }
    });

    // 5. Departman Tamamlanma Horizontal Bar
    const deptData = @json($deptCompletionRates);
    new Chart(document.getElementById('deptCompletionChart'), {
        type: 'bar',
        data: {
            labels: deptData.map(d => d.name),
            datasets: [{
                label: 'Tamamlanma %',
                data: deptData.map(d => d.completion_rate),
                backgroundColor: deptData.map(d => {
                    if (d.completion_rate >= 60) return isDark ? 'rgba(52,211,153,0.7)' : 'rgba(16,185,129,0.7)';
                    if (d.completion_rate >= 30) return isDark ? 'rgba(251,191,36,0.7)' : 'rgba(245,158,11,0.7)';
                    return isDark ? 'rgba(248,113,113,0.7)' : 'rgba(239,68,68,0.7)';
                }),
                borderRadius: 8,
                borderSkipped: false,
                barThickness: 22,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    ...tooltipDefaults,
                    callbacks: { label: function(ctx) { const dept = deptData[ctx.dataIndex]; return '%' + dept.completion_rate + ' (' + dept.completed_enrollments + '/' + dept.total_enrollments + ')'; } }
                }
            },
            scales: {
                x: { beginAtZero: true, max: 100, grid: { color: gridColor }, ticks: { callback: v => '%' + v } },
                y: { grid: { display: false }, ticks: { font: { size: 12 } } }
            }
        }
    });
});
</script>
@endpush
