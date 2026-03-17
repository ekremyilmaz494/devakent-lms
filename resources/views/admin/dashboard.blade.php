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

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- 1. HOŞ GELDİNİZ + HIZLI AKSİYONLAR                       --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-primary-600 to-primary-800 dark:from-gray-800 dark:to-gray-900 dark:border dark:border-gray-700 shadow-lg">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 400 120" preserveAspectRatio="none"><path d="M0,60 Q100,20 200,60 T400,60 V120 H0 Z" fill="currentColor" class="text-white"/></svg>
        </div>
        <div class="relative p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white">Merhaba, {{ auth()->user()->name }} 👋</h2>
                    <p class="text-primary-100 dark:text-gray-400 mt-1 text-sm">Bugün <strong>{{ $activeCourses }}</strong> aktif eğitim ve <strong>{{ $totalStaff }}</strong> personel mevcut.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white/90 hover:bg-white dark:bg-gray-700 dark:hover:bg-gray-600 text-primary-700 dark:text-gray-200 text-[13px] font-semibold rounded-lg shadow-sm hover:shadow transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Eğitim Ekle
                    </a>
                    <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white/90 hover:bg-white dark:bg-gray-700 dark:hover:bg-gray-600 text-primary-700 dark:text-gray-200 text-[13px] font-semibold rounded-lg shadow-sm hover:shadow transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Personel
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white/90 hover:bg-white dark:bg-gray-700 dark:hover:bg-gray-600 text-primary-700 dark:text-gray-200 text-[13px] font-semibold rounded-lg shadow-sm hover:shadow transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Raporlar
                    </a>
                    <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white/90 hover:bg-white dark:bg-gray-700 dark:hover:bg-gray-600 text-primary-700 dark:text-gray-200 text-[13px] font-semibold rounded-lg shadow-sm hover:shadow transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        Bildirim
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- 2. STAT KARTLARI (MEVCUT)                                  --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Total Courses --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-[18px] h-[18px] text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <span class="text-[13px] text-gray-600 dark:text-gray-400 font-medium">Aktif Eğitim</span>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activeCourses }}</p>
                <div class="flex items-center gap-4 mt-2">
                    <div class="flex items-center gap-1 text-[12px]">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                        <span class="text-emerald-600 dark:text-emerald-400 font-medium">Yayında</span>
                    </div>
                    <div class="flex items-end gap-0.5 ml-auto">
                        <div class="w-1 h-2 bg-primary-200 dark:bg-primary-800 rounded-sm"></div>
                        <div class="w-1 h-3 bg-primary-200 dark:bg-primary-800 rounded-sm"></div>
                        <div class="w-1 h-2.5 bg-primary-300 dark:bg-primary-700 rounded-sm"></div>
                        <div class="w-1 h-4 bg-primary-400 dark:bg-primary-600 rounded-sm"></div>
                        <div class="w-1 h-3.5 bg-primary-500 rounded-sm"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Staff --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-300 to-primary-500"></div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-[18px] h-[18px] text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                        <span class="text-[13px] text-gray-600 dark:text-gray-400 font-medium">Toplam Personel</span>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalStaff }}</p>
                <div class="flex items-center gap-4 mt-2">
                    <div class="flex items-center gap-1 text-[12px]">
                        <span class="text-gray-600 dark:text-gray-400">Kayıtlı</span>
                    </div>
                    <div class="flex items-end gap-0.5 ml-auto">
                        <div class="w-1 h-3 bg-primary-200 dark:bg-primary-800 rounded-sm"></div>
                        <div class="w-1 h-2 bg-primary-200 dark:bg-primary-800 rounded-sm"></div>
                        <div class="w-1 h-4 bg-primary-300 dark:bg-primary-700 rounded-sm"></div>
                        <div class="w-1 h-3 bg-primary-400 dark:bg-primary-600 rounded-sm"></div>
                        <div class="w-1 h-3.5 bg-primary-500 rounded-sm"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Completion Rate --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-[18px] h-[18px] text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <span class="text-[13px] text-gray-600 dark:text-gray-400 font-medium">Tamamlanma</span>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">%{{ $completionRate }}</p>
                <div class="flex items-center gap-4 mt-2">
                    <div class="flex items-center gap-1 text-[12px]">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                        <span class="text-emerald-600 dark:text-emerald-400 font-medium">{{ $completedEnrollments }}/{{ $totalEnrollments }}</span>
                    </div>
                    <div class="flex items-end gap-0.5 ml-auto">
                        <div class="w-1 h-2 bg-primary-200 dark:bg-primary-800 rounded-sm"></div>
                        <div class="w-1 h-3.5 bg-primary-300 dark:bg-primary-700 rounded-sm"></div>
                        <div class="w-1 h-2.5 bg-primary-300 dark:bg-primary-700 rounded-sm"></div>
                        <div class="w-1 h-4 bg-primary-400 dark:bg-primary-600 rounded-sm"></div>
                        <div class="w-1 h-3 bg-primary-500 rounded-sm"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Certificates --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-500 to-primary-700"></div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-[18px] h-[18px] text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                        </div>
                        <span class="text-[13px] text-gray-600 dark:text-gray-400 font-medium">Sertifika</span>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $certificatesIssued }}</p>
                <div class="flex items-center gap-4 mt-2">
                    <div class="flex items-center gap-1 text-[12px]">
                        <span class="text-gray-600 dark:text-gray-400">Verilen</span>
                    </div>
                    <div class="flex items-end gap-0.5 ml-auto">
                        <div class="w-1 h-3 bg-primary-200 dark:bg-primary-800 rounded-sm"></div>
                        <div class="w-1 h-2.5 bg-primary-200 dark:bg-primary-800 rounded-sm"></div>
                        <div class="w-1 h-4 bg-primary-300 dark:bg-primary-700 rounded-sm"></div>
                        <div class="w-1 h-3 bg-primary-400 dark:bg-primary-600 rounded-sm"></div>
                        <div class="w-1 h-3.5 bg-primary-500 rounded-sm"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- 3. SINAV İSTATİSTİKLERİ + DEPARTMAN TAMAMLANMA             --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Sınav İstatistikleri (2/3) --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                    </div>
                    <h3 class="text-[14px] font-semibold text-gray-800 dark:text-white">Sınav İstatistikleri</h3>
                </div>
            </div>

            {{-- Mini Stats --}}
            <div class="grid grid-cols-3 divide-x divide-gray-200 dark:divide-gray-700 border-b border-gray-200 dark:border-gray-700">
                <div class="px-5 py-3 text-center">
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $totalExams }}</p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Toplam Sınav</p>
                </div>
                <div class="px-5 py-3 text-center">
                    <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">%{{ $examPassRate }}</p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Başarı Oranı</p>
                </div>
                <div class="px-5 py-3 text-center">
                    <p class="text-lg font-bold text-primary-600 dark:text-primary-400">{{ $avgScore }}</p>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Ort. Puan</p>
                </div>
            </div>

            {{-- Son Sınav Sonuçları --}}
            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="px-5 py-3 text-left font-semibold text-gray-700 dark:text-gray-400">Personel</th>
                            <th class="px-5 py-3 text-left font-semibold text-gray-700 dark:text-gray-400">Eğitim</th>
                            <th class="px-5 py-3 text-center font-semibold text-gray-700 dark:text-gray-400">Puan</th>
                            <th class="px-5 py-3 text-center font-semibold text-gray-700 dark:text-gray-400">Durum</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentExams as $exam)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center flex-shrink-0">
                                            <span class="text-[9px] font-semibold text-white">{{ strtoupper(substr($exam->enrollment->user->first_name ?? '', 0, 1) . substr($exam->enrollment->user->last_name ?? '', 0, 1)) }}</span>
                                        </div>
                                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $exam->enrollment->user->full_name ?? 'Bilinmeyen' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ Str::limit($exam->enrollment->course->title ?? '—', 30) }}</td>
                                <td class="px-5 py-3 text-center font-medium {{ $exam->is_passed ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">{{ $exam->score }}</td>
                                <td class="px-5 py-3 text-center">
                                    @if($exam->is_passed)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">Geçti</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">Kaldı</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-600 dark:text-gray-400">
                                    Henüz sınav sonucu yok
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Sınav Başarı Dağılımı (1/3) --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-300 to-primary-500"></div>
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </div>
                    <h3 class="text-[14px] font-semibold text-gray-800 dark:text-white">Sınav Başarı Dağılımı</h3>
                </div>
            </div>
            <div class="p-5 flex items-center justify-center">
                <canvas id="examPassRateChart" height="220"></canvas>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- 4. POPÜLER EĞİTİMLER + DEPARTMANLAR (MEVCUT)              --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Top Performance Courses --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </div>
                    <h3 class="text-[14px] font-semibold text-gray-800 dark:text-white">Popüler Eğitimler</h3>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="px-5 py-3 text-left font-semibold text-gray-700 dark:text-gray-400">Eğitim</th>
                            <th class="px-5 py-3 text-left font-semibold text-gray-700 dark:text-gray-400">Kategori</th>
                            <th class="px-5 py-3 text-center font-semibold text-gray-700 dark:text-gray-400">Kayıt</th>
                            <th class="px-5 py-3 text-center font-semibold text-gray-700 dark:text-gray-400">Soru</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($topCourses as $course)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $course->title }}</span>
                                        @if($course->is_mandatory)
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 dark:bg-red-900/30 text-red-500">Zorunlu</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    @if($course->category)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium" style="background-color: {{ $course->category->color }}12; color: {{ $course->category->color }};">
                                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $course->category->color }};"></span>
                                            {{ $course->category->name }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span class="text-gray-600 dark:text-gray-300 font-medium">{{ $course->enrollments_count }}</span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <span class="text-gray-600 dark:text-gray-300">{{ $course->questions_count }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-600 dark:text-gray-400">
                                    Henüz eğitim oluşturulmamış
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Department Overview --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-300 to-primary-500"></div>
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <h3 class="text-[14px] font-semibold text-gray-800 dark:text-white">Departmanlar</h3>
                </div>
            </div>
            <div class="p-5 space-y-4">
                @forelse($departmentStats as $dept)
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[13px] font-medium text-gray-700 dark:text-gray-300">{{ $dept->name }}</span>
                            <span class="text-[12px] text-gray-600 dark:text-gray-400">{{ $dept->users_count }} kişi</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                            @php $percent = $totalStaff > 0 ? ($dept->users_count / $totalStaff * 100) : 0; @endphp
                            <div class="h-1.5 rounded-full bg-gradient-to-r from-primary-400 to-primary-600" style="width: {{ max($percent, 2) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-600 dark:text-gray-400 text-sm py-4">Departman verisi yok</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- 5. DİKKAT GEREKTİREN + SÜRESİ YAKLAŞAN                   --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {{-- Dikkat Gerektiren Personeller --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden" x-data="{ activeTab: 'inactive' }">
            <div class="h-1.5 bg-gradient-to-r from-red-400 to-amber-500"></div>
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                    </div>
                    <h3 class="text-[14px] font-semibold text-gray-800 dark:text-white">Dikkat Gerektiren</h3>
                </div>
                @if($inactiveStaff->count() + $mandatoryIncomplete->count() > 0)
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">{{ $inactiveStaff->count() + $mandatoryIncomplete->count() }}</span>
                @endif
            </div>

            {{-- Tab Buttons --}}
            <div class="flex border-b border-gray-200 dark:border-gray-700">
                <button @click="activeTab = 'inactive'" :class="activeTab === 'inactive' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="flex-1 px-4 py-2.5 text-[12px] font-medium border-b-2 transition-colors">
                    Giriş Yapmayan (30+ gün)
                </button>
                <button @click="activeTab = 'mandatory'" :class="activeTab === 'mandatory' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'" class="flex-1 px-4 py-2.5 text-[12px] font-medium border-b-2 transition-colors">
                    Zorunlu Eğitim Eksik
                </button>
            </div>

            {{-- Tab: Inactive Staff --}}
            <div x-show="activeTab === 'inactive'" class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($inactiveStaff as $user)
                    <div class="flex items-center gap-3 px-5 py-3">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-gray-400 to-gray-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-[10px] font-semibold text-white">{{ strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-medium text-gray-800 dark:text-gray-200 truncate">{{ $user->full_name }}</p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ $user->department->name ?? '—' }}</p>
                        </div>
                        <span class="text-[11px] text-red-600 dark:text-red-400 whitespace-nowrap">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Hiç giriş yapmadı' }}
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center">
                        <svg class="w-8 h-8 mx-auto text-emerald-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="text-[13px] text-gray-500 dark:text-gray-400">Tüm personel aktif</p>
                    </div>
                @endforelse
            </div>

            {{-- Tab: Mandatory Incomplete --}}
            <div x-show="activeTab === 'mandatory'" x-cloak class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($mandatoryIncomplete as $user)
                    <div class="flex items-center gap-3 px-5 py-3">
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-[10px] font-semibold text-white">{{ strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-medium text-gray-800 dark:text-gray-200 truncate">{{ $user->full_name }}</p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ $user->department->name ?? '—' }}</p>
                        </div>
                        <div class="text-right">
                            @foreach($user->enrollments->take(2) as $enrollment)
                                <p class="text-[11px] text-amber-600 dark:text-amber-400 truncate max-w-[120px]">{{ $enrollment->course->title ?? '—' }}</p>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center">
                        <svg class="w-8 h-8 mx-auto text-emerald-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="text-[13px] text-gray-500 dark:text-gray-400">Tüm zorunlu eğitimler tamamlanmış</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Süresi Yaklaşan Eğitimler --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-amber-400 to-amber-600"></div>
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-[14px] font-semibold text-gray-800 dark:text-white">Süresi Yaklaşan Eğitimler</h3>
                </div>
                @if($expiringCourses->count() > 0)
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">{{ $expiringCourses->count() }}</span>
                @endif
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($expiringCourses as $course)
                    @php
                        $daysLeft = now()->diffInDays($course->end_date, false);
                        $completionPercent = $course->enrollments_count > 0 ? round($course->completed_count / $course->enrollments_count * 100) : 0;
                        $urgencyClass = $daysLeft <= 3
                            ? 'text-red-600 dark:text-red-400'
                            : ($daysLeft <= 7
                                ? 'text-amber-600 dark:text-amber-400'
                                : 'text-primary-600 dark:text-primary-400');
                        $barColor = $daysLeft <= 3
                            ? 'from-red-400 to-red-600'
                            : ($daysLeft <= 7
                                ? 'from-amber-400 to-amber-600'
                                : 'from-primary-400 to-primary-600');
                    @endphp
                    <div class="px-5 py-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[13px] font-medium text-gray-800 dark:text-gray-200 truncate mr-2">{{ $course->title }}</span>
                            <span class="text-[11px] font-semibold {{ $urgencyClass }} whitespace-nowrap">{{ $daysLeft }} gün kaldı</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full bg-gradient-to-r {{ $barColor }}" style="width: {{ max($completionPercent, 2) }}%"></div>
                            </div>
                            <span class="text-[11px] text-gray-500 dark:text-gray-400 whitespace-nowrap">%{{ $completionPercent }} · {{ $course->completed_count }}/{{ $course->enrollments_count }}</span>
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Bitiş: {{ $course->end_date->format('d.m.Y') }}</p>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center">
                        <svg class="w-8 h-8 mx-auto text-emerald-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="text-[13px] text-gray-500 dark:text-gray-400">Süresi yaklaşan eğitim yok</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- 6. HAFTALIK AKTİVİTE + AYLIK TREND (Chart.js)              --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Haftalık Aktivite Bar Chart (2/3) --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </div>
                    <h3 class="text-[14px] font-semibold text-gray-800 dark:text-white">Haftalık Aktivite</h3>
                </div>
            </div>
            <div class="p-5">
                <canvas id="weeklyActivityChart" height="200"></canvas>
            </div>
        </div>

        {{-- Kayıt Durum Dağılımı Doughnut (1/3) --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-300 to-primary-500"></div>
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                    </div>
                    <h3 class="text-[14px] font-semibold text-gray-800 dark:text-white">Kayıt Durumu</h3>
                </div>
            </div>
            <div class="p-5 flex items-center justify-center">
                <canvas id="enrollmentStatusChart" height="220"></canvas>
            </div>
        </div>
    </div>

    {{-- Aylık Trend Line Chart --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" /></svg>
                </div>
                <h3 class="text-[14px] font-semibold text-gray-800 dark:text-white">Aylık Trend (Son 6 Ay)</h3>
            </div>
        </div>
        <div class="p-5">
            <canvas id="monthlyTrendChart" height="120"></canvas>
        </div>
    </div>

    {{-- Departman Tamamlanma Horizontal Bar Chart --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-primary-300 to-primary-500"></div>
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
                <h3 class="text-[14px] font-semibold text-gray-800 dark:text-white">Departman Tamamlanma Oranları</h3>
            </div>
        </div>
        <div class="p-5">
            <canvas id="deptCompletionChart" height="{{ max(count($deptCompletionRates) * 35, 120) }}"></canvas>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- 7. SON KAYITLAR (MEVCUT)                                   --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-[14px] font-semibold text-gray-800 dark:text-white">Son Kayıtlar</h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-5 py-3 text-left font-semibold text-gray-700 dark:text-gray-400">Personel</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-700 dark:text-gray-400">Eğitim</th>
                        <th class="px-5 py-3 text-center font-semibold text-gray-700 dark:text-gray-400">Durum</th>
                        <th class="px-5 py-3 text-right font-semibold text-gray-700 dark:text-gray-400">Tarih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentEnrollments as $enrollment)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center flex-shrink-0">
                                        <span class="text-[10px] font-semibold text-white">{{ strtoupper(substr($enrollment->user->first_name ?? '', 0, 1) . substr($enrollment->user->last_name ?? '', 0, 1)) }}</span>
                                    </div>
                                    <span class="font-medium text-gray-800 dark:text-gray-200">{{ $enrollment->user->full_name ?? 'Bilinmeyen' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $enrollment->course->title ?? '—' }}</td>
                            <td class="px-5 py-3 text-center">
                                @php
                                    $statusMap = [
                                        'not_started' => ['label' => 'Başlanmadı', 'class' => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'],
                                        'in_progress' => ['label' => 'Devam Ediyor', 'class' => 'bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400'],
                                        'completed' => ['label' => 'Tamamlandı', 'class' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400'],
                                        'failed' => ['label' => 'Başarısız', 'class' => 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400'],
                                    ];
                                    $status = $statusMap[$enrollment->status] ?? $statusMap['not_started'];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium {{ $status['class'] }}">{{ $status['label'] }}</span>
                            </td>
                            <td class="px-5 py-3 text-right text-gray-600 dark:text-gray-400 text-[12px]">{{ $enrollment->created_at->format('d.m.Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-gray-600 dark:text-gray-400">
                                Henüz eğitim kaydı yok
                            </td>
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

    // ─── 1. Haftalık Aktivite Bar Chart ───
    const weeklyData = @json($weeklyActivity);
    new Chart(document.getElementById('weeklyActivityChart'), {
        type: 'bar',
        data: {
            labels: weeklyData.map(d => d.day + '\n' + d.date),
            datasets: [
                {
                    label: 'Kayıt',
                    data: weeklyData.map(d => d.enrollments),
                    backgroundColor: isDark ? 'rgba(249,115,22,0.7)' : 'rgba(180,83,9,0.7)',
                    borderRadius: 6,
                    borderSkipped: false,
                    barPercentage: 0.6,
                    categoryPercentage: 0.7,
                },
                {
                    label: 'Sınav',
                    data: weeklyData.map(d => d.exams),
                    backgroundColor: isDark ? 'rgba(52,211,153,0.7)' : 'rgba(16,185,129,0.7)',
                    borderRadius: 6,
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
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: { usePointStyle: true, pointStyle: 'rectRounded', padding: 16, boxWidth: 8, boxHeight: 8 }
                },
                tooltip: {
                    backgroundColor: isDark ? '#1f2937' : '#fff',
                    titleColor: isDark ? '#f3f4f6' : '#111827',
                    bodyColor: isDark ? '#d1d5db' : '#4b5563',
                    borderColor: isDark ? '#374151' : '#e5e7eb',
                    borderWidth: 1,
                    cornerRadius: 8,
                    padding: 10,
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                y: { beginAtZero: true, grid: { color: gridColor }, ticks: { stepSize: 1, font: { size: 11 } } }
            }
        }
    });

    // ─── 2. Kayıt Durum Dağılımı Doughnut ───
    const statusData = @json($enrollmentStatusDist);
    const statusTotal = Object.values(statusData).reduce((a, b) => a + b, 0);
    new Chart(document.getElementById('enrollmentStatusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Başlanmadı', 'Devam Ediyor', 'Tamamlandı', 'Başarısız'],
            datasets: [{
                data: [statusData.not_started, statusData.in_progress, statusData.completed, statusData.failed],
                backgroundColor: [
                    isDark ? 'rgba(156,163,175,0.6)' : 'rgba(107,114,128,0.6)',
                    isDark ? 'rgba(249,115,22,0.7)' : 'rgba(180,83,9,0.7)',
                    isDark ? 'rgba(52,211,153,0.7)' : 'rgba(16,185,129,0.7)',
                    isDark ? 'rgba(248,113,113,0.7)' : 'rgba(239,68,68,0.7)',
                ],
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, pointStyle: 'circle', padding: 12, boxWidth: 8, boxHeight: 8, font: { size: 11 } }
                },
                tooltip: {
                    backgroundColor: isDark ? '#1f2937' : '#fff',
                    titleColor: isDark ? '#f3f4f6' : '#111827',
                    bodyColor: isDark ? '#d1d5db' : '#4b5563',
                    borderColor: isDark ? '#374151' : '#e5e7eb',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(ctx) {
                            const pct = statusTotal > 0 ? Math.round(ctx.raw / statusTotal * 100) : 0;
                            return ctx.label + ': ' + ctx.raw + ' (%' + pct + ')';
                        }
                    }
                }
            }
        }
    });

    // ─── 3. Sınav Başarı Dağılımı Doughnut ───
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
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, pointStyle: 'circle', padding: 12, boxWidth: 8, boxHeight: 8, font: { size: 11 } }
                },
                tooltip: {
                    backgroundColor: isDark ? '#1f2937' : '#fff',
                    titleColor: isDark ? '#f3f4f6' : '#111827',
                    bodyColor: isDark ? '#d1d5db' : '#4b5563',
                    borderColor: isDark ? '#374151' : '#e5e7eb',
                    borderWidth: 1,
                    cornerRadius: 8,
                }
            }
        }
    });

    // ─── 4. Aylık Trend Line Chart ───
    const monthlyData = @json($monthlyTrend);
    new Chart(document.getElementById('monthlyTrendChart'), {
        type: 'line',
        data: {
            labels: monthlyData.map(d => d.month),
            datasets: [
                {
                    label: 'Yeni Kayıt',
                    data: monthlyData.map(d => d.new_enrollments),
                    borderColor: isDark ? '#F97316' : '#B45309',
                    backgroundColor: isDark ? 'rgba(249,115,22,0.1)' : 'rgba(180,83,9,0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: isDark ? '#F97316' : '#B45309',
                },
                {
                    label: 'Tamamlanan',
                    data: monthlyData.map(d => d.completions),
                    borderColor: isDark ? '#34d399' : '#10b981',
                    backgroundColor: isDark ? 'rgba(52,211,153,0.1)' : 'rgba(16,185,129,0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: isDark ? '#34d399' : '#10b981',
                },
                {
                    label: 'Başarılı Sınav',
                    data: monthlyData.map(d => d.exams_passed),
                    borderColor: isDark ? '#60a5fa' : '#3b82f6',
                    backgroundColor: isDark ? 'rgba(96,165,250,0.1)' : 'rgba(59,130,246,0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: isDark ? '#60a5fa' : '#3b82f6',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: { usePointStyle: true, pointStyle: 'circle', padding: 16, boxWidth: 8, boxHeight: 8 }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: isDark ? '#1f2937' : '#fff',
                    titleColor: isDark ? '#f3f4f6' : '#111827',
                    bodyColor: isDark ? '#d1d5db' : '#4b5563',
                    borderColor: isDark ? '#374151' : '#e5e7eb',
                    borderWidth: 1,
                    cornerRadius: 8,
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, grid: { color: gridColor }, ticks: { stepSize: 1 } }
            },
            interaction: { mode: 'nearest', axis: 'x', intersect: false }
        }
    });

    // ─── 5. Departman Tamamlanma Horizontal Bar ───
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
                borderRadius: 6,
                borderSkipped: false,
                barThickness: 20,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: isDark ? '#1f2937' : '#fff',
                    titleColor: isDark ? '#f3f4f6' : '#111827',
                    bodyColor: isDark ? '#d1d5db' : '#4b5563',
                    borderColor: isDark ? '#374151' : '#e5e7eb',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(ctx) {
                            const dept = deptData[ctx.dataIndex];
                            return '%' + dept.completion_rate + ' (' + dept.completed_enrollments + '/' + dept.total_enrollments + ')';
                        }
                    }
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
