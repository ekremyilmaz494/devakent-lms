<div>
    {{-- Flash Messages --}}
    @if(session('warning'))
        <div class="mb-4 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-xl flex items-start gap-2.5 text-sm text-amber-800 dark:text-amber-300">
            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('lms.reports') }}</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Eğitim ve personel performans analizi</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            {{-- Filter Toggle --}}
            <button wire:click="$toggle('showFilters')"
                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded-lg border transition-colors
                    {{ $showFilters ? 'bg-primary-50 dark:bg-primary-900/30 border-primary-300 dark:border-primary-600 text-primary-700 dark:text-primary-300' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                {{ __('lms.filter') }}
                @if(count($activeFilters) > 0)
                    <span class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-primary-600 text-white text-[10px] font-bold leading-none">{{ count($activeFilters) }}</span>
                @endif
            </button>

            {{-- Export --}}
            <button wire:click="exportExcel"
                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-3.5 h-3.5 text-emerald-600" wire:loading.class="animate-spin" wire:target="exportExcel" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span wire:loading.remove wire:target="exportExcel">{{ __('lms.export_excel') }}</span>
                <span wire:loading wire:target="exportExcel">{{ __('lms.loading') }}</span>
            </button>
            <button wire:click="exportPdf"
                class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-3.5 h-3.5 text-red-500" wire:loading.class="animate-spin" wire:target="exportPdf" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                <span wire:loading.remove wire:target="exportPdf">{{ __('lms.export_pdf') }}</span>
                <span wire:loading wire:target="exportPdf">{{ __('lms.loading') }}</span>
            </button>
        </div>
    </div>

    {{-- Filter Panel --}}
    @if($showFilters)
    <div wire:transition.opacity.duration.200ms
        class="mb-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
        <div class="h-0.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
        <div class="p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3">
                {{-- Date From --}}
                <div>
                    <label class="block text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('lms.filter_date_from') }}</label>
                    <input type="date" wire:model.live.debounce.400ms="dateFrom"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-2.5 py-2 text-xs bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
                </div>
                {{-- Date To --}}
                <div>
                    <label class="block text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('lms.filter_date_to') }}</label>
                    <input type="date" wire:model.live.debounce.400ms="dateTo"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-2.5 py-2 text-xs bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
                </div>
                {{-- Department --}}
                <div>
                    <label class="block text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('lms.filter_department') }}</label>
                    <select wire:model.live="filterDepartment"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-2.5 py-2 text-xs bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">{{ __('lms.filter_all') }}</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Status --}}
                <div>
                    <label class="block text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('lms.filter_status') }}</label>
                    <select wire:model.live="filterStatus"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-2.5 py-2 text-xs bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">{{ __('lms.filter_all') }}</option>
                        <option value="completed">{{ __('lms.completed') }}</option>
                        <option value="in_progress">{{ __('lms.in_progress') }}</option>
                        <option value="not_started">{{ __('lms.not_started') }}</option>
                    </select>
                </div>
                {{-- Mandatory --}}
                <div>
                    <label class="block text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('lms.filter_mandatory') }}</label>
                    <select wire:model.live="filterMandatory"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-2.5 py-2 text-xs bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">{{ __('lms.filter_all') }}</option>
                        <option value="1">{{ __('lms.mandatory_label') }}</option>
                        <option value="0">{{ __('lms.optional_label') }}</option>
                    </select>
                </div>
                {{-- Reset --}}
                <div class="flex items-end">
                    <button wire:click="resetFilters"
                        class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        {{ __('lms.clear_all') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Active Filter Chips --}}
    @if(count($activeFilters) > 0)
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach($activeFilters as $chip)
                <span class="inline-flex items-center gap-1 pl-2.5 pr-1.5 py-1 rounded-full text-xs font-medium bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 border border-primary-200 dark:border-primary-700">
                    {{ $chip['label'] }}
                    <button wire:click="removeFilter('{{ $chip['key'] }}')"
                        class="w-4 h-4 rounded-full flex items-center justify-center hover:bg-primary-200 dark:hover:bg-primary-800 transition-colors">
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </span>
            @endforeach
            <button wire:click="resetFilters" class="text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 underline transition-colors">
                {{ __('lms.clear_all') }}
            </button>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl p-4 text-white shadow-lg shadow-primary-500/20">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold">{{ $stats['activeCourses'] }}</p>
            <p class="text-xs text-white/70 mt-1">{{ __('lms.active_courses_count') }}</p>
        </div>
        <div class="bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl p-4 text-white shadow-lg shadow-primary-400/20">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold">{{ $stats['totalEnrollments'] }}</p>
            <p class="text-xs text-white/70 mt-1">{{ __('lms.total_enrollments') }}</p>
        </div>
        <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-xl p-4 text-white shadow-lg shadow-primary-600/20">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            @php $compRate = $stats['totalEnrollments'] > 0 ? round($stats['completedEnrollments'] / $stats['totalEnrollments'] * 100, 1) : 0; @endphp
            <p class="text-2xl font-bold">%{{ $compRate }}</p>
            <p class="text-xs text-white/70 mt-1">{{ __('lms.completion_rate') }}</p>
        </div>
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl p-4 text-white shadow-lg shadow-amber-500/20">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold">{{ $stats['avgScore'] }}</p>
            <p class="text-xs text-white/70 mt-1">{{ __('lms.avg_exam_score') }}</p>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex gap-1 p-1 bg-gray-100 dark:bg-gray-800 rounded-xl mb-4 overflow-x-auto">
        @foreach([
            'course'     => [__('lms.course_based_tab'),    'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342'],
            'department' => [__('lms.dept_based_tab'), 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 0h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z'],
            'staff'      => [__('lms.staff_based_tab'),   'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
            'time'       => [__('lms.time_based_tab'),      'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5'],
        ] as $tab => $info)
            <button wire:click="$set('activeTab', '{{ $tab }}')"
                class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold rounded-lg whitespace-nowrap transition-all
                    {{ $activeTab === $tab ? 'bg-white dark:bg-gray-700 text-primary-700 dark:text-primary-300 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $info[1] }}" /></svg>
                {{ $info[0] }}
            </button>
        @endforeach
    </div>

    {{-- Report Table Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <div class="h-1 bg-gradient-to-r from-primary-400 to-primary-600"></div>

        {{-- ═══════════════════════════════════ COURSE TAB ═══════════════════════════════════ --}}
        @if($activeTab === 'course')
            <div class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Eğitim Detay Tablosu</h3>
                <div class="relative">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Eğitim ara..."
                        class="pl-8 pr-3 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500 w-48">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700">
                            @php
                                $thBase = 'px-4 py-3 text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider cursor-pointer select-none hover:bg-gray-100 dark:hover:bg-gray-700/60 transition-colors';
                                $sortIcon = function($field) use ($sortField, $sortDirection) {
                                    if ($sortField !== $field) return '<svg class="w-3 h-3 text-gray-300 dark:text-gray-600 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>';
                                    return $sortDirection === 'asc'
                                        ? '<svg class="w-3 h-3 text-primary-500 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>'
                                        : '<svg class="w-3 h-3 text-primary-500 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';
                                };
                            @endphp
                            <th wire:click="sortBy('title')" class="{{ $thBase }} text-left">{{ __('lms.course') }} {!! $sortIcon('title') !!}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">{{ __('lms.category') }}</th>
                            <th wire:click="sortBy('enrollments')" class="{{ $thBase }} text-center">{{ __('lms.enrollment') }} {!! $sortIcon('enrollments') !!}</th>
                            <th wire:click="sortBy('completion_rate')" class="{{ $thBase }} text-center">{{ __('lms.completion_pct') }} {!! $sortIcon('completion_rate') !!}</th>
                            <th wire:click="sortBy('pre_exam_avg')" class="{{ $thBase }} text-center">{{ __('lms.pre_exam') }} {!! $sortIcon('pre_exam_avg') !!}</th>
                            <th wire:click="sortBy('post_exam_avg')" class="{{ $thBase }} text-center">{{ __('lms.post_exam') }} {!! $sortIcon('post_exam_avg') !!}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($reportData as $row)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        @if($row['is_mandatory'])
                                            <span class="flex-shrink-0 px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">Z</span>
                                        @endif
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $row['title'] }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-xs font-medium" style="background-color: {{ $row['category_color'] }}18; color: {{ $row['category_color'] }};">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $row['category_color'] }};"></span>
                                        {{ $row['category'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ $row['enrollments'] }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-16 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $row['completion_rate'] >= 80 ? 'bg-emerald-500' : ($row['completion_rate'] >= 50 ? 'bg-amber-500' : 'bg-primary-500') }}" style="width: {{ $row['completion_rate'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold {{ $row['completion_rate'] >= 80 ? 'text-emerald-600' : ($row['completion_rate'] >= 50 ? 'text-amber-600' : 'text-primary-600') }}">%{{ $row['completion_rate'] }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">{{ $row['pre_exam_avg'] }}</td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">{{ $row['post_exam_avg'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-sm">Kriterlere uygun eğitim bulunamadı</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        {{-- ════════════════════════════════ DEPARTMENT TAB ════════════════════════════════ --}}
        @if($activeTab === 'department')
            <div class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Departman Detay Tablosu</h3>
                <div class="relative">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Departman ara..."
                        class="pl-8 pr-3 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500 w-48">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700">
                            @php
                                $thBase = 'px-4 py-3 text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider cursor-pointer select-none hover:bg-gray-100 dark:hover:bg-gray-700/60 transition-colors';
                                $sortIcon = function($field) use ($sortField, $sortDirection) {
                                    if ($sortField !== $field) return '<svg class="w-3 h-3 text-gray-300 dark:text-gray-600 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>';
                                    return $sortDirection === 'asc'
                                        ? '<svg class="w-3 h-3 text-primary-500 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>'
                                        : '<svg class="w-3 h-3 text-primary-500 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';
                                };
                            @endphp
                            <th wire:click="sortBy('name')" class="{{ $thBase }} text-left">{{ __('lms.department') }} {!! $sortIcon('name') !!}</th>
                            <th wire:click="sortBy('staff_count')" class="{{ $thBase }} text-center">{{ __('lms.staff') }} {!! $sortIcon('staff_count') !!}</th>
                            <th wire:click="sortBy('enrollments')" class="{{ $thBase }} text-center">{{ __('lms.enrollment') }} {!! $sortIcon('enrollments') !!}</th>
                            <th wire:click="sortBy('completed')" class="{{ $thBase }} text-center">{{ __('lms.completed') }} {!! $sortIcon('completed') !!}</th>
                            <th wire:click="sortBy('completion_rate')" class="{{ $thBase }} text-center">{{ __('lms.completion_pct') }} {!! $sortIcon('completion_rate') !!}</th>
                            <th wire:click="sortBy('pre_exam_avg')" class="{{ $thBase }} text-center">{{ __('lms.pre_exam') }} {!! $sortIcon('pre_exam_avg') !!}</th>
                            <th wire:click="sortBy('post_exam_avg')" class="{{ $thBase }} text-center">{{ __('lms.post_exam') }} {!! $sortIcon('post_exam_avg') !!}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($reportData as $row)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $row['name'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-semibold bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-400">{{ $row['staff_count'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ $row['enrollments'] }}</td>
                                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ $row['completed'] }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-16 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $row['completion_rate'] >= 80 ? 'bg-emerald-500' : ($row['completion_rate'] >= 50 ? 'bg-amber-500' : 'bg-primary-500') }}" style="width: {{ $row['completion_rate'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold {{ $row['completion_rate'] >= 80 ? 'text-emerald-600' : ($row['completion_rate'] >= 50 ? 'text-amber-600' : 'text-primary-600') }}">%{{ $row['completion_rate'] }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">{{ $row['pre_exam_avg'] }}</td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">{{ $row['post_exam_avg'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-sm">Kriterlere uygun departman bulunamadı</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        {{-- ══════════════════════════════════ STAFF TAB ══════════════════════════════════ --}}
        @if($activeTab === 'staff')
            <div class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Personel Detay Tablosu</h3>
                <div class="relative">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Personel ara..."
                        class="pl-8 pr-3 py-1.5 text-xs border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500 w-48">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700">
                            @php
                                $thBase = 'px-4 py-3 text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider cursor-pointer select-none hover:bg-gray-100 dark:hover:bg-gray-700/60 transition-colors';
                                $sortIcon = function($field) use ($sortField, $sortDirection) {
                                    if ($sortField !== $field) return '<svg class="w-3 h-3 text-gray-300 dark:text-gray-600 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>';
                                    return $sortDirection === 'asc'
                                        ? '<svg class="w-3 h-3 text-primary-500 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>'
                                        : '<svg class="w-3 h-3 text-primary-500 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';
                                };
                            @endphp
                            <th wire:click="sortBy('name')" class="{{ $thBase }} text-left">{{ __('lms.staff') }} {!! $sortIcon('name') !!}</th>
                            <th wire:click="sortBy('department')" class="{{ $thBase }} text-left">{{ __('lms.department') }} {!! $sortIcon('department') !!}</th>
                            <th wire:click="sortBy('enrollments')" class="{{ $thBase }} text-center">{{ __('lms.enrollment') }} {!! $sortIcon('enrollments') !!}</th>
                            <th wire:click="sortBy('completion_rate')" class="{{ $thBase }} text-center">{{ __('lms.completion_pct') }} {!! $sortIcon('completion_rate') !!}</th>
                            <th wire:click="sortBy('pre_exam_avg')" class="{{ $thBase }} text-center">{{ __('lms.pre_exam') }} {!! $sortIcon('pre_exam_avg') !!}</th>
                            <th wire:click="sortBy('post_exam_avg')" class="{{ $thBase }} text-center">{{ __('lms.post_exam') }} {!! $sortIcon('post_exam_avg') !!}</th>
                            <th wire:click="sortBy('certificates')" class="{{ $thBase }} text-center">{{ __('lms.certificates') }} {!! $sortIcon('certificates') !!}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">{{ __('lms.last_login') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($reportData as $row)
                            <tr wire:click="$dispatch('openUrl', { url: '{{ route('admin.staff.show', $row['id']) }}' })"
                                class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors cursor-pointer"
                                onclick="window.location='{{ route('admin.staff.show', $row['id']) }}'">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400">
                                    {{ $row['name'] }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 dark:bg-slate-700/60 text-slate-700 dark:text-slate-300">{{ $row['department'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ $row['enrollments'] }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-16 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $row['completion_rate'] >= 80 ? 'bg-emerald-500' : ($row['completion_rate'] >= 50 ? 'bg-amber-500' : 'bg-primary-500') }}" style="width: {{ $row['completion_rate'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold {{ $row['completion_rate'] >= 80 ? 'text-emerald-600' : ($row['completion_rate'] >= 50 ? 'text-amber-600' : 'text-primary-600') }}">%{{ $row['completion_rate'] }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">{{ $row['pre_exam_avg'] }}</td>
                                <td class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">{{ $row['post_exam_avg'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400">{{ $row['certificates'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">{{ $row['last_login'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-sm">Kriterlere uygun personel bulunamadı</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        {{-- ═══════════════════════════════════ TIME TAB ═══════════════════════════════════ --}}
        @if($activeTab === 'time')
            <div class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                    {{ $timeGrouping === 'weekly' ? __('lms.weekly_label') : __('lms.monthly_label') }} Özet
                    @if($dateFrom || $dateTo)
                        <span class="text-xs font-normal text-gray-500 dark:text-gray-400 ml-1">({{ $dateFrom ?: '...' }} — {{ $dateTo ?: '...' }})</span>
                    @else
                        <span class="text-xs font-normal text-gray-500 dark:text-gray-400 ml-1">(Son 6 ay)</span>
                    @endif
                </h3>
                {{-- Grouping Toggle --}}
                <div class="flex items-center gap-1 p-0.5 bg-gray-100 dark:bg-gray-700/60 rounded-lg">
                    <button wire:click="$set('timeGrouping', 'monthly')"
                        class="px-3 py-1.5 text-xs font-medium rounded-md transition-all {{ $timeGrouping === 'monthly' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-700' }}">
                        {{ __('lms.monthly_label') }}
                    </button>
                    <button wire:click="$set('timeGrouping', 'weekly')"
                        class="px-3 py-1.5 text-xs font-medium rounded-md transition-all {{ $timeGrouping === 'weekly' ? 'bg-white dark:bg-gray-600 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-700' }}">
                        {{ __('lms.weekly_label') }}
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700">
                            @php
                                $thBase = 'px-4 py-3 text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider cursor-pointer select-none hover:bg-gray-100 dark:hover:bg-gray-700/60 transition-colors';
                                $sortIcon = function($field) use ($sortField, $sortDirection) {
                                    if ($sortField !== $field) return '<svg class="w-3 h-3 text-gray-300 dark:text-gray-600 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>';
                                    return $sortDirection === 'asc'
                                        ? '<svg class="w-3 h-3 text-primary-500 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>'
                                        : '<svg class="w-3 h-3 text-primary-500 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';
                                };
                            @endphp
                            <th wire:click="sortBy('month')" class="{{ $thBase }} text-left">{{ __('lms.period_label') }} {!! $sortIcon('month') !!}</th>
                            <th wire:click="sortBy('total')" class="{{ $thBase }} text-center">{{ __('lms.total_enrollments') }} {!! $sortIcon('total') !!}</th>
                            <th wire:click="sortBy('completed')" class="{{ $thBase }} text-center">{{ __('lms.completed') }} {!! $sortIcon('completed') !!}</th>
                            <th wire:click="sortBy('completion_rate')" class="{{ $thBase }} text-center">{{ __('lms.completion_pct') }} {!! $sortIcon('completion_rate') !!}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($reportData as $row)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $row['month'] }}</td>
                                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ $row['total'] }}</td>
                                <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ $row['completed'] }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-20 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $row['completion_rate'] >= 80 ? 'bg-emerald-500' : ($row['completion_rate'] >= 50 ? 'bg-amber-500' : 'bg-primary-500') }}" style="width: {{ $row['completion_rate'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold {{ $row['completion_rate'] >= 80 ? 'text-emerald-600' : ($row['completion_rate'] >= 50 ? 'text-amber-600' : 'text-primary-600') }}">%{{ $row['completion_rate'] }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="text-sm">{{ __('lms.no_data_period') }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

    </div>{{-- end table card --}}
</div>
