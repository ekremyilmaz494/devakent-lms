@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Info Banner --}}
    <div class="flex items-center gap-3 p-3.5 bg-violet-50 dark:bg-violet-900/15 border border-violet-100 dark:border-violet-800/30 rounded-xl" x-data="{ show: true }" x-show="show">
        <div class="w-8 h-8 bg-violet-100 dark:bg-violet-800/30 rounded-lg flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
        </div>
        <p class="text-[13px] text-violet-700 dark:text-violet-300 flex-1">
            <span class="font-semibold">Devakent LMS</span> — Hastane personel eğitim yönetim sistemi aktif.
        </p>
        <button @click="show = false" class="text-violet-400 hover:text-violet-600 dark:hover:text-violet-300 flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Total Courses --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-violet-50 dark:bg-violet-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-[18px] h-[18px] text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <span class="text-[13px] text-gray-500 dark:text-gray-400 font-medium">Aktif Eğitim</span>
                </div>
                <button class="text-gray-300 hover:text-gray-500 dark:text-gray-600 dark:hover:text-gray-400">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="6" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="18" r="1.5"/></svg>
                </button>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activeCourses }}</p>
            <div class="flex items-center gap-4 mt-2">
                <div class="flex items-center gap-1 text-[12px]">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                    <span class="text-emerald-600 dark:text-emerald-400 font-medium">Yayında</span>
                </div>
                <div class="flex items-end gap-0.5 ml-auto">
                    <div class="w-1 h-2 bg-violet-200 dark:bg-violet-800 rounded-sm"></div>
                    <div class="w-1 h-3 bg-violet-200 dark:bg-violet-800 rounded-sm"></div>
                    <div class="w-1 h-2.5 bg-violet-300 dark:bg-violet-700 rounded-sm"></div>
                    <div class="w-1 h-4 bg-violet-400 dark:bg-violet-600 rounded-sm"></div>
                    <div class="w-1 h-3.5 bg-violet-500 rounded-sm"></div>
                </div>
            </div>
        </div>

        {{-- Total Staff --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-blue-50 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-[18px] h-[18px] text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <span class="text-[13px] text-gray-500 dark:text-gray-400 font-medium">Toplam Personel</span>
                </div>
                <button class="text-gray-300 hover:text-gray-500 dark:text-gray-600 dark:hover:text-gray-400">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="6" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="18" r="1.5"/></svg>
                </button>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalStaff }}</p>
            <div class="flex items-center gap-4 mt-2">
                <div class="flex items-center gap-1 text-[12px]">
                    <span class="text-gray-400 dark:text-gray-500">Kayıtlı</span>
                </div>
                <div class="flex items-end gap-0.5 ml-auto">
                    <div class="w-1 h-3 bg-blue-200 dark:bg-blue-800 rounded-sm"></div>
                    <div class="w-1 h-2 bg-blue-200 dark:bg-blue-800 rounded-sm"></div>
                    <div class="w-1 h-4 bg-blue-300 dark:bg-blue-700 rounded-sm"></div>
                    <div class="w-1 h-3 bg-blue-400 dark:bg-blue-600 rounded-sm"></div>
                    <div class="w-1 h-3.5 bg-blue-500 rounded-sm"></div>
                </div>
            </div>
        </div>

        {{-- Completion Rate --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-[18px] h-[18px] text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <span class="text-[13px] text-gray-500 dark:text-gray-400 font-medium">Tamamlanma</span>
                </div>
                <button class="text-gray-300 hover:text-gray-500 dark:text-gray-600 dark:hover:text-gray-400">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="6" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="18" r="1.5"/></svg>
                </button>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">%{{ $completionRate }}</p>
            <div class="flex items-center gap-4 mt-2">
                <div class="flex items-center gap-1 text-[12px]">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                    <span class="text-emerald-600 dark:text-emerald-400 font-medium">{{ $completedEnrollments }}/{{ $totalEnrollments }}</span>
                </div>
                <div class="flex items-end gap-0.5 ml-auto">
                    <div class="w-1 h-2 bg-emerald-200 dark:bg-emerald-800 rounded-sm"></div>
                    <div class="w-1 h-3.5 bg-emerald-300 dark:bg-emerald-700 rounded-sm"></div>
                    <div class="w-1 h-2.5 bg-emerald-300 dark:bg-emerald-700 rounded-sm"></div>
                    <div class="w-1 h-4 bg-emerald-400 dark:bg-emerald-600 rounded-sm"></div>
                    <div class="w-1 h-3 bg-emerald-500 rounded-sm"></div>
                </div>
            </div>
        </div>

        {{-- Certificates --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-amber-50 dark:bg-amber-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-[18px] h-[18px] text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                    </div>
                    <span class="text-[13px] text-gray-500 dark:text-gray-400 font-medium">Sertifika</span>
                </div>
                <button class="text-gray-300 hover:text-gray-500 dark:text-gray-600 dark:hover:text-gray-400">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="6" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="18" r="1.5"/></svg>
                </button>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $certificatesIssued }}</p>
            <div class="flex items-center gap-4 mt-2">
                <div class="flex items-center gap-1 text-[12px]">
                    <span class="text-gray-400 dark:text-gray-500">Verilen</span>
                </div>
                <div class="flex items-end gap-0.5 ml-auto">
                    <div class="w-1 h-3 bg-amber-200 dark:bg-amber-800 rounded-sm"></div>
                    <div class="w-1 h-2.5 bg-amber-200 dark:bg-amber-800 rounded-sm"></div>
                    <div class="w-1 h-4 bg-amber-300 dark:bg-amber-700 rounded-sm"></div>
                    <div class="w-1 h-3 bg-amber-400 dark:bg-amber-600 rounded-sm"></div>
                    <div class="w-1 h-3.5 bg-amber-500 rounded-sm"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts + Content Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Top Performance Courses --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-violet-50 dark:bg-violet-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </div>
                    <h3 class="text-[14px] font-semibold text-gray-800 dark:text-white">Popüler Eğitimler</h3>
                </div>
                <button class="text-gray-300 hover:text-gray-500 dark:text-gray-600 dark:hover:text-gray-400">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="6" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="18" r="1.5"/></svg>
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-700/50">
                            <th class="px-5 py-3 text-left font-medium text-gray-400 dark:text-gray-500">Eğitim</th>
                            <th class="px-5 py-3 text-left font-medium text-gray-400 dark:text-gray-500">Kategori</th>
                            <th class="px-5 py-3 text-center font-medium text-gray-400 dark:text-gray-500">Kayıt</th>
                            <th class="px-5 py-3 text-center font-medium text-gray-400 dark:text-gray-500">Soru</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @forelse($topCourses as $course)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $course->title }}</span>
                                        @if($course->is_mandatory)
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-50 dark:bg-red-900/20 text-red-500">Zorunlu</span>
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
                                <td colspan="4" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500">
                                    Henüz eğitim oluşturulmamış
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Department Overview --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-blue-50 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <h3 class="text-[14px] font-semibold text-gray-800 dark:text-white">Departmanlar</h3>
                </div>
            </div>
            <div class="p-5 space-y-4">
                @forelse($departmentStats as $dept)
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[13px] font-medium text-gray-700 dark:text-gray-300">{{ $dept->name }}</span>
                            <span class="text-[12px] text-gray-400 dark:text-gray-500">{{ $dept->users_count }} kişi</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                            @php $percent = $totalStaff > 0 ? ($dept->users_count / $totalStaff * 100) : 0; @endphp
                            <div class="h-1.5 rounded-full bg-gradient-to-r from-violet-400 to-violet-600" style="width: {{ max($percent, 2) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 dark:text-gray-500 text-sm py-4">Departman verisi yok</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-[14px] font-semibold text-gray-800 dark:text-white">Son Kayıtlar</h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-gray-50 dark:border-gray-700/50">
                        <th class="px-5 py-3 text-left font-medium text-gray-400 dark:text-gray-500">Personel</th>
                        <th class="px-5 py-3 text-left font-medium text-gray-400 dark:text-gray-500">Eğitim</th>
                        <th class="px-5 py-3 text-center font-medium text-gray-400 dark:text-gray-500">Durum</th>
                        <th class="px-5 py-3 text-right font-medium text-gray-400 dark:text-gray-500">Tarih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($recentEnrollments as $enrollment)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-violet-400 to-purple-500 flex items-center justify-center flex-shrink-0">
                                        <span class="text-[10px] font-semibold text-white">{{ strtoupper(substr($enrollment->user->first_name ?? '', 0, 1) . substr($enrollment->user->last_name ?? '', 0, 1)) }}</span>
                                    </div>
                                    <span class="font-medium text-gray-800 dark:text-gray-200">{{ $enrollment->user->full_name ?? 'Bilinmeyen' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $enrollment->course->title ?? '—' }}</td>
                            <td class="px-5 py-3 text-center">
                                @php
                                    $statusMap = [
                                        'not_started' => ['label' => 'Başlanmadı', 'class' => 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400'],
                                        'in_progress' => ['label' => 'Devam Ediyor', 'class' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400'],
                                        'completed' => ['label' => 'Tamamlandı', 'class' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400'],
                                        'failed' => ['label' => 'Başarısız', 'class' => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400'],
                                    ];
                                    $status = $statusMap[$enrollment->status] ?? $statusMap['not_started'];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium {{ $status['class'] }}">{{ $status['label'] }}</span>
                            </td>
                            <td class="px-5 py-3 text-right text-gray-400 dark:text-gray-500 text-[12px]">{{ $enrollment->created_at->format('d.m.Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-gray-400 dark:text-gray-500">
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
