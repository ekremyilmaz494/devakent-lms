@extends('layouts.admin')
@section('title', $user->full_name . ' - Personel Detayı')
@section('page-title', __('lms.staff'))

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => __('lms.staff'), 'route' => 'admin.staff.index'],
        ['label' => $user->full_name],
    ]])
@endsection

@section('content')
<div class="space-y-6">

    {{-- Profile Hero Card --}}
    <div class="relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-indigo-400 to-violet-600"></div>

        {{-- Decorative background --}}
        <div class="absolute top-0 right-0 w-64 h-32 bg-gradient-to-bl from-indigo-50 to-transparent dark:from-indigo-900/10 dark:to-transparent pointer-events-none rounded-2xl"></div>

        <div class="relative p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">

                {{-- Avatar --}}
                <div class="relative flex-shrink-0">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-700 flex items-center justify-center shadow-lg shadow-indigo-500/25">
                        <span class="text-2xl font-black text-white">{{ strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}</span>
                    </div>
                    <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white dark:border-gray-800 {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2.5 mb-1">
                        <h2 class="text-xl font-black text-gray-900 dark:text-white">{{ $user->full_name }}</h2>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-[11px] font-bold border {{ $user->is_active ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' : 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border-red-200 dark:border-red-800' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                            {{ $user->is_active ? __('lms.active') : __('lms.inactive') }}
                        </span>
                    </div>
                    <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ $user->title ?? 'Ünvan belirtilmemiş' }}</p>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 mt-2.5">
                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            {{ $user->email }}
                        </span>
                        @if($user->phone)
                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            {{ $user->phone }}
                        </span>
                        @endif
                        @if($user->department)
                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            {{ $user->department->name }}
                        </span>
                        @endif
                        @if($user->registration_number)
                        <span class="inline-flex items-center gap-1.5 text-xs font-mono text-gray-500 dark:text-gray-400">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" /></svg>
                            {{ $user->registration_number }}
                        </span>
                        @endif
                    </div>
                </div>

                {{-- Back Button --}}
                <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all flex-shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    {{ __('lms.back_to_list') }}
                </a>
            </div>

            {{-- Extra Details Strip --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-5 border-t border-gray-100 dark:border-gray-700/50">
                <div class="space-y-0.5">
                    <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wide">{{ __('lms.hire_date') }}</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->hire_date?->format('d.m.Y') ?? '—' }}</p>
                </div>
                <div class="space-y-0.5">
                    <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wide">{{ __('lms.last_login') }}</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->last_login_at?->format('d.m.Y H:i') ?? __('lms.last_login_never') }}</p>
                </div>
                <div class="space-y-0.5">
                    <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wide">{{ __('lms.certificate') }}</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->certificates_count }}</p>
                </div>
                <div class="space-y-1">
                    @php
                        $progressPercent = $user->enrollments_count > 0
                            ? round($user->completed_count / $user->enrollments_count * 100)
                            : 0;
                    @endphp
                    <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wide">{{ __('lms.progress') }}</p>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 {{ $progressPercent >= 80 ? 'bg-gradient-to-r from-emerald-400 to-emerald-500' : ($progressPercent >= 50 ? 'bg-gradient-to-r from-amber-400 to-amber-500' : 'bg-gradient-to-r from-indigo-400 to-violet-500') }}" style="width: {{ $progressPercent }}%"></div>
                        </div>
                        <span class="text-xs font-black text-gray-700 dark:text-gray-300">%{{ $progressPercent }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        {{-- Total Enrollments --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 text-center hover:-translate-y-0.5 hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-200 cursor-default overflow-hidden">
            <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-indigo-400 to-violet-500"></div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-100 to-violet-200 dark:from-indigo-900/40 dark:to-violet-900/40 flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
            <p class="text-3xl font-black text-indigo-700 dark:text-indigo-300">{{ $user->enrollments_count }}</p>
            <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wide mt-1">{{ __('lms.total_enrollments') }}</p>
        </div>

        {{-- Completed --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 text-center hover:-translate-y-0.5 hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-200 cursor-default overflow-hidden">
            <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-emerald-400 to-teal-500"></div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-100 to-teal-200 dark:from-emerald-900/40 dark:to-teal-900/40 flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400">{{ $user->completed_count }}</p>
            <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wide mt-1">{{ __('lms.completed') }}</p>
        </div>

        {{-- Failed --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 text-center hover:-translate-y-0.5 hover:shadow-xl hover:shadow-red-500/10 transition-all duration-200 cursor-default overflow-hidden">
            <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-red-400 to-rose-500"></div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-100 to-rose-200 dark:from-red-900/40 dark:to-rose-900/40 flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <p class="text-3xl font-black text-red-600 dark:text-red-400">{{ $user->failed_count }}</p>
            <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wide mt-1">{{ __('lms.failed') }}</p>
        </div>

        {{-- In Progress --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 text-center hover:-translate-y-0.5 hover:shadow-xl hover:shadow-amber-500/10 transition-all duration-200 cursor-default overflow-hidden">
            <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-amber-400 to-orange-500"></div>
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-100 to-orange-200 dark:from-amber-900/40 dark:to-orange-900/40 flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <p class="text-3xl font-black text-amber-600 dark:text-amber-400">{{ $user->in_progress_count }}</p>
            <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wide mt-1">{{ __('lms.in_progress') }}</p>
        </div>
    </div>

    {{-- Enrollments Table --}}
    <div class="relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-indigo-400 to-violet-600"></div>
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700/50 flex items-center gap-3">
            <div class="w-9 h-9 bg-gradient-to-br from-indigo-100 to-violet-200 dark:from-indigo-900/40 dark:to-violet-900/40 rounded-xl flex items-center justify-center">
                <svg class="w-4.5 h-4.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            </div>
            <div>
                <h3 class="text-sm font-black text-gray-900 dark:text-white">{{ __('lms.courses') }}</h3>
                <p class="text-[11px] text-gray-500 dark:text-gray-400">Personelin tüm eğitim kayıtları</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-700/20">
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wide text-gray-400">{{ __('lms.course') }}</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wide text-gray-400">{{ __('lms.category') }}</th>
                        <th class="px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wide text-gray-400">{{ __('lms.pre_exam') }}</th>
                        <th class="px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wide text-gray-400">{{ __('lms.post_exam') }}</th>
                        <th class="px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wide text-gray-400">{{ __('lms.attempt') }}</th>
                        <th class="px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wide text-gray-400">{{ __('lms.status') }}</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wide text-gray-400">{{ __('lms.date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @forelse($user->enrollments as $enrollment)
                        @php
                            $statusClass = match($enrollment->status) {
                                'completed' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                'failed' => 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border-red-200 dark:border-red-800',
                                'in_progress' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800',
                                default => 'bg-gray-50 dark:bg-gray-700/30 text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-600',
                            };
                            $statusLabel = match($enrollment->status) {
                                'completed' => __('lms.completed'),
                                'failed' => __('lms.failed'),
                                'in_progress' => __('lms.in_progress'),
                                default => __('lms.not_started'),
                            };
                            $preExam = $enrollment->examAttempts->where('exam_type', 'pre_exam')->where('attempt_number', 1)->whereNotNull('finished_at')->first();
                            $postExam = $enrollment->examAttempts->where('exam_type', 'post_exam')->whereNotNull('finished_at')->sortByDesc('attempt_number')->first();
                            $currentAttempt = $enrollment->current_attempt ?: 1;
                            $maxAttempts = $enrollment->course?->max_attempts ?? 3;
                        @endphp
                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors">
                            <td class="px-5 py-3.5">
                                <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $enrollment->course?->title ?? '—' }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($enrollment->course?->category)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[11px] font-bold bg-slate-100 dark:bg-slate-700/60 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                                        {{ $enrollment->course->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($preExam)
                                    <span class="text-sm font-black text-gray-700 dark:text-gray-300">{{ $preExam->score }}</span>
                                    <span class="text-[10px] text-gray-400">/100</span>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($postExam)
                                    <span class="text-sm font-black {{ $postExam->is_passed ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">{{ $postExam->score }}</span>
                                    <span class="text-[10px] text-gray-400">/100</span>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[11px] font-bold bg-gray-50 dark:bg-gray-700/30 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-600">{{ $currentAttempt }}/{{ $maxAttempts }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[11px] font-bold border {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    {{ $enrollment->completed_at?->format('d.m.Y') ?? '—' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center mb-4">
                                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-500 dark:text-gray-400">{{ __('lms.no_enrollment_data') }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Bu personele henüz eğitim atanmamış</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Certificates --}}
    @if($user->certificates->count() > 0)
    <div class="relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-amber-400 to-orange-500"></div>
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700/50 flex items-center gap-3">
            <div class="w-9 h-9 bg-gradient-to-br from-amber-100 to-orange-200 dark:from-amber-900/40 dark:to-orange-900/40 rounded-xl flex items-center justify-center">
                <svg class="w-4.5 h-4.5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
            </div>
            <div>
                <h3 class="text-sm font-black text-gray-900 dark:text-white">{{ __('lms.certificates') }}</h3>
                <p class="text-[11px] text-gray-500 dark:text-gray-400">Kazanılan başarı belgeleri</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-700/20">
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wide text-gray-400">Sertifika No</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wide text-gray-400">{{ __('lms.course') }}</th>
                        <th class="px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wide text-gray-400">{{ __('lms.score') }}</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wide text-gray-400">Veriliş Tarihi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50">
                    @foreach($user->certificates as $cert)
                        <tr class="hover:bg-amber-50/30 dark:hover:bg-amber-900/10 transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="text-sm font-mono font-bold text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 px-2.5 py-1 rounded-xl border border-indigo-100 dark:border-indigo-800">{{ $cert->certificate_number }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $cert->course?->title ?? '—' }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[11px] font-black bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">{{ $cert->final_score }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $cert->issued_at?->format('d.m.Y H:i') ?? '—' }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
