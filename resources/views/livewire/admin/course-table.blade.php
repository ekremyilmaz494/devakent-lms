<div>
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('error') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="mb-4 flex items-center gap-2 px-4 py-3 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
            {{ session('warning') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('lms.course_list_title') }}</h2>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300">
                {{ $totalCourses }} {{ __('lms.course_count_label') }}
            </span>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <button wire:click="exportExcel" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled:opacity-50" wire:loading.attr="disabled" wire:target="exportExcel" title="{{ count($selectedCourses) > 0 ? count($selectedCourses) . ' seçili eğitim export edilecek' : 'Tüm filtrelenmiş eğitimler export edilecek' }}">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span wire:loading.remove wire:target="exportExcel">{{ __('lms.export_excel') }}{{ count($selectedCourses) > 0 ? ' (' . count($selectedCourses) . ')' : '' }}</span>
                <span wire:loading wire:target="exportExcel">{{ __('lms.loading') }}</span>
            </button>
            <button wire:click="exportPdf" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled:opacity-50" wire:loading.attr="disabled" wire:target="exportPdf">
                <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                <span wire:loading.remove wire:target="exportPdf">{{ __('lms.export_pdf') }}{{ count($selectedCourses) > 0 ? ' (' . count($selectedCourses) . ')' : '' }}</span>
                <span wire:loading wire:target="exportPdf">{{ __('lms.loading') }}</span>
            </button>
            <div class="w-px h-6 bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>
            <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                {{ __('lms.new_course_btn') }}
            </a>
        </div>
    </div>

    {{-- ═══ GRUP 2: Özet İstatistik Kartları ═══ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        {{-- Toplam Eğitim --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:shadow-primary-500/10 hover:-translate-y-0.5 transition-all duration-300">
            <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="w-9 h-9 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $totalCourses }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Toplam Eğitim</p>
            </div>
        </div>
        {{-- Toplam Kayıt --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:shadow-teal-500/10 hover:-translate-y-0.5 transition-all duration-300">
            <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-teal-400 to-teal-600"></div>
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="w-9 h-9 bg-gradient-to-br from-teal-400 to-teal-600 rounded-xl flex items-center justify-center shadow-lg shadow-teal-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $stats['totalEnrollments'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Toplam Kayıt</p>
            </div>
        </div>
        {{-- Ort. Tamamlanma --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:shadow-emerald-500/10 hover:-translate-y-0.5 transition-all duration-300">
            <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="w-9 h-9 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-gray-900 dark:text-white">%{{ $stats['avgCompletion'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Ort. Tamamlanma</p>
            </div>
        </div>
        {{-- Süresi Dolan --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:shadow-red-500/10 hover:-translate-y-0.5 transition-all duration-300">
            <div class="absolute top-0 inset-x-0 h-0.5 bg-gradient-to-r from-red-400 to-red-600"></div>
            <div class="p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="w-9 h-9 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $stats['expiredCount'] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Süresi Dolan</p>
            </div>
        </div>
    </div>

    {{-- Status Tabs + View Toggle --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-lg p-1 w-fit">
            <button wire:click="filterByStatus('')" wire:loading.attr="disabled" wire:target="filterByStatus"
                class="px-3 py-1.5 text-xs font-medium rounded-md transition-colors {{ $filterStatus === '' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                {{ __('lms.filter_all') }} <span class="ml-1 text-[10px] {{ $filterStatus === '' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400' }}">{{ $totalCourses }}</span>
            </button>
            <button wire:click="filterByStatus('published')" wire:loading.attr="disabled" wire:target="filterByStatus"
                class="px-3 py-1.5 text-xs font-medium rounded-md transition-colors {{ $filterStatus === 'published' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : ($publishedCount == 0 ? 'text-gray-400 dark:text-gray-600 opacity-50 cursor-default' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white') }}"
                @if($publishedCount == 0 && $filterStatus !== 'published') disabled @endif>
                {{ __('lms.filter_published') }} <span class="ml-1 text-[10px] {{ $filterStatus === 'published' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400' }}">{{ $publishedCount }}</span>
            </button>
            <button wire:click="filterByStatus('draft')" wire:loading.attr="disabled" wire:target="filterByStatus"
                class="px-3 py-1.5 text-xs font-medium rounded-md transition-colors {{ $filterStatus === 'draft' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : ($draftCount == 0 ? 'text-gray-400 dark:text-gray-600 opacity-50 cursor-default' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white') }}"
                @if($draftCount == 0 && $filterStatus !== 'draft') disabled @endif>
                {{ __('lms.filter_draft') }} <span class="ml-1 text-[10px] {{ $filterStatus === 'draft' ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400' }}">{{ $draftCount }}</span>
            </button>
        </div>
        {{-- View Toggle --}}
        <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
            <button wire:click="setViewMode('table')" class="p-1.5 rounded-md transition-colors {{ $viewMode === 'table' ? 'bg-white dark:bg-gray-700 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700' }}" title="Tablo görünümü">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
            </button>
            <button wire:click="setViewMode('card')" class="p-1.5 rounded-md transition-colors {{ $viewMode === 'card' ? 'bg-white dark:bg-gray-700 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700' }}" title="Kart görünümü">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
            </button>
        </div>
    </div>

    {{-- Filters Row --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <div class="relative flex-1 max-w-md">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('lms.search_course') }}"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500 placeholder-gray-400" />
        </div>
        <select wire:model.live="filterCategory" class="border border-gray-300 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 pl-3 pr-8 py-2 focus:ring-primary-500 focus:border-primary-500">
            <option value="">{{ __('lms.all_categories') }}</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterMandatory" class="border border-gray-300 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 pl-3 pr-8 py-2 focus:ring-primary-500 focus:border-primary-500">
            <option value="">{{ __('lms.all_mandatory') }}</option>
            <option value="1">{{ __('lms.filter_mandatory') }}</option>
            <option value="0">{{ __('lms.filter_optional') }}</option>
        </select>
    </div>

    {{-- Bulk Actions Bar --}}
    @if(count($selectedCourses) > 0)
        <div class="flex items-center gap-3 mb-4 px-4 py-3 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-lg">
            <span class="text-sm font-medium text-primary-700 dark:text-primary-300">{{ count($selectedCourses) }} {{ __('lms.course_selected') }}</span>
            <div class="flex items-center gap-2 ml-auto">
                <button wire:click="bulkPublish" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-900/50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    {{ __('lms.publish') }}
                </button>
                <button wire:click="bulkDraft" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                    {{ __('lms.to_draft') }}
                </button>
                <button wire:click="openBulkAssign" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-primary-700 dark:text-primary-300 bg-primary-100 dark:bg-primary-900/30 rounded-lg hover:bg-primary-200 dark:hover:bg-primary-900/50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    Departmana Ata
                </button>
                <button wire:click="confirmBulkDelete" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    {{ __('lms.delete') }}
                </button>
                <button wire:click="$set('selectedCourses', [])" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded transition-colors" title="Seçimi kaldır">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    @endif

    {{-- ═══ TABLE VIEW ═══ --}}
    @if($viewMode === 'table')
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
        <div wire:loading wire:target="filterByStatus,sortBy,search,filterCategory,filterMandatory,gotoPage,nextPage,previousPage"
            class="h-0.5 bg-gradient-to-r from-primary-400 via-primary-500 to-primary-600 animate-pulse"></div>
        <div class="overflow-x-auto" wire:loading.class="opacity-50 pointer-events-none" wire:target="filterByStatus,sortBy,filterCategory,filterMandatory,gotoPage,nextPage,previousPage">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox" wire:model.live="selectAll" class="rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500 dark:bg-gray-700">
                        </th>
                        @php
                            $thClass = 'px-4 py-3 text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-primary-600 dark:hover:text-primary-400 transition-colors';
                            $sortChevron = function($field) use ($sortField, $sortDirection) {
                                if ($sortField !== $field) return '<svg class="w-3 h-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>';
                                return $sortDirection === 'asc'
                                    ? '<svg class="w-3 h-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>'
                                    : '<svg class="w-3 h-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';
                            };
                        @endphp
                        <th class="{{ $thClass }} text-left" wire:click="sortBy('title')">
                            <div class="flex items-center gap-1">{{ __('lms.course_title') }} {!! $sortChevron('title') !!}</div>
                        </th>
                        <th class="{{ $thClass }} text-left" wire:click="sortBy('category_id')">
                            <div class="flex items-center gap-1">{{ __('lms.category') }} {!! $sortChevron('category_id') !!}</div>
                        </th>
                        <th class="{{ $thClass }} text-center" wire:click="sortBy('questions_count')">
                            <div class="flex items-center justify-center gap-1">Soru {!! $sortChevron('questions_count') !!}</div>
                        </th>
                        <th class="{{ $thClass }} text-center" wire:click="sortBy('enrollments_count')">
                            <div class="flex items-center justify-center gap-1">{{ __('lms.enrollment') }} {!! $sortChevron('enrollments_count') !!}</div>
                        </th>
                        <th class="{{ $thClass }} text-center" wire:click="sortBy('exam_duration_minutes')">
                            <div class="flex items-center justify-center gap-1">Süre {!! $sortChevron('exam_duration_minutes') !!}</div>
                        </th>
                        <th class="{{ $thClass }} text-center" wire:click="sortBy('status')">
                            <div class="flex items-center justify-center gap-1">{{ __('lms.status') }} {!! $sortChevron('status') !!}</div>
                        </th>
                        <th class="{{ $thClass }} text-center" wire:click="sortBy('updated_at')">
                            <div class="flex items-center justify-center gap-1">Güncelleme {!! $sortChevron('updated_at') !!}</div>
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">{{ __('lms.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($courses as $course)
                        @php
                            $isExpired = $course->end_date && $course->end_date->isPast();
                            $isExpiringSoon = !$isExpired && $course->end_date && $course->end_date->diffInDays(now()) <= 7 && $course->end_date->isFuture();
                            $daysLeft = $course->end_date ? (int) now()->diffInDays($course->end_date, false) : null;
                            $completionPercent = $course->enrollments_count > 0
                                ? round($course->completed_enrollments_count / $course->enrollments_count * 100) : 0;
                            $inProgressCount = $course->enrollments_count - $course->completed_enrollments_count;
                        @endphp
                        <tr wire:key="course-{{ $course->id }}"
                            wire:click="viewDetail({{ $course->id }})"
                            class="cursor-pointer hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors {{ in_array($course->id, $selectedCourses) ? 'bg-primary-50/50 dark:bg-primary-900/10' : '' }} {{ $isExpired ? 'border-l-2 border-l-red-400' : ($isExpiringSoon ? 'border-l-2 border-l-amber-400' : '') }}">
                            <td class="px-4 py-3" wire:click.stop>
                                <input type="checkbox" wire:model.live="selectedCourses" value="{{ $course->id }}" class="rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500 dark:bg-gray-700">
                            </td>
                            <td class="px-4 py-3">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $course->title }}</p>
                                    <div class="flex items-center flex-wrap gap-1 mt-1">
                                        @if($course->is_mandatory)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-semibold bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400">{{ __('lms.mandatory_label') }}</span>
                                        @endif
                                        @if($course->start_date && $course->end_date)
                                            <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $course->start_date->format('d.m') }} — {{ $course->end_date->format('d.m.Y') }}</span>
                                        @endif
                                        @if($isExpired)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400">Süresi doldu</span>
                                        @elseif($isExpiringSoon)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400">{{ $daysLeft }} gün kaldı</span>
                                        @endif
                                    </div>
                                    @if($course->departments->count() > 0)
                                        <div class="flex items-center flex-wrap gap-1 mt-1.5">
                                            @foreach($course->departments->take(3) as $dept)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300">{{ $dept->name }}</span>
                                            @endforeach
                                            @if($course->departments->count() > 3)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 dark:bg-slate-700/60 text-slate-500 dark:text-slate-400">+{{ $course->departments->count() - 3 }} daha</span>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 italic mt-1">Departman atanmamış</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($course->category)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-xs font-medium" style="background-color: {{ $course->category->color }}15; color: {{ $course->category->color }};">
                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $course->category->color }};"></span>
                                        {{ $course->category->name }}
                                    </span>
                                @else
                                    <span class="text-gray-500 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($course->questions_count === 0)
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400" title="Soru yok! Sınav çalışmayacak.">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400">
                                        {{ $course->questions_count }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col items-center gap-1 min-w-[140px]" title="{{ $course->completed_enrollments_count }} tamamladı / {{ $inProgressCount > 0 ? $inProgressCount . ' devam ediyor' : '0 devam' }}">
                                    <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400">
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $course->enrollments_count }}</span>
                                        <span class="text-gray-400 dark:text-gray-500">kayıt</span>
                                        <span class="text-gray-300 dark:text-gray-600">·</span>
                                        <span class="font-bold text-sm {{ $completionPercent >= 50 ? 'text-emerald-600 dark:text-emerald-400' : ($completionPercent > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400') }}">%{{ $completionPercent }}</span>
                                    </div>
                                    @if($course->enrollments_count > 0)
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                            <div class="h-1.5 rounded-full transition-all {{ $completionPercent >= 50 ? 'bg-emerald-500' : ($completionPercent > 0 ? 'bg-amber-500' : 'bg-gray-300') }}" style="width: {{ max($completionPercent, 2) }}%"></div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $course->exam_duration_minutes }} dk</span>
                            </td>
                            <td class="px-4 py-3 text-center" wire:click.stop>
                                <button wire:click="toggleStatus({{ $course->id }})" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold transition-colors {{ $course->status === 'published' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $course->status === 'published' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                    {{ $course->status === 'published' ? __('lms.published') : __('lms.draft') }}
                                </button>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xs text-gray-500 dark:text-gray-400" title="{{ $course->updated_at->format('d.m.Y H:i') }}">{{ $course->updated_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-4 py-3" wire:click.stop>
                                <div class="flex items-center justify-end gap-0.5">
                                    <button wire:click="viewDetail({{ $course->id }})" class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-lg transition-colors" title="{{ __('lms.view_detail') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </button>
                                    <a href="{{ route('admin.courses.edit', $course->id) }}" class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition-colors" title="{{ __('lms.edit') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                                    </a>
                                    <button wire:click="confirmDelete({{ $course->id }})" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="{{ __('lms.delete') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" /></svg>
                                    </div>
                                    @if($search || $filterCategory || $filterMandatory !== '')
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Aramanızla eşleşen eğitim bulunamadı</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Farklı filtreler deneyebilirsiniz</p>
                                        <button wire:click="$set('search', ''); $set('filterCategory', ''); $set('filterMandatory', ''); $set('filterStatus', '')"
                                            class="inline-flex items-center gap-1 mt-3 px-3 py-1.5 text-xs font-medium text-primary-600 hover:text-primary-700 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                            Filtreleri temizle
                                        </button>
                                    @else
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('lms.course_empty') }}</p>
                                        <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-1 mt-3 px-4 py-2 text-xs font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                            İlk eğitimi oluştur
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($courses->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                {{ $courses->links() }}
            </div>
        @endif
    </div>

    {{-- ═══ CARD VIEW ═══ --}}
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($courses as $course)
            @php
                $isExpired = $course->end_date && $course->end_date->isPast();
                $isExpiringSoon = !$isExpired && $course->end_date && $course->end_date->diffInDays(now()) <= 7 && $course->end_date->isFuture();
                $daysLeft = $course->end_date ? (int) now()->diffInDays($course->end_date, false) : null;
                $completionPercent = $course->enrollments_count > 0
                    ? round($course->completed_enrollments_count / $course->enrollments_count * 100) : 0;
            @endphp
            <div wire:key="card-{{ $course->id }}" wire:click="viewDetail({{ $course->id }})"
                class="group relative bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 cursor-pointer {{ $isExpired ? 'border-l-4 border-l-red-400' : ($isExpiringSoon ? 'border-l-4 border-l-amber-400' : '') }}">
                {{-- Top gradient --}}
                <div class="h-1 bg-gradient-to-r {{ $course->status === 'published' ? 'from-emerald-400 to-emerald-600' : 'from-gray-300 to-gray-400' }}"></div>
                <div class="p-4">
                    {{-- Header --}}
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div class="min-w-0 flex-1">
                            <h3 class="font-semibold text-sm text-gray-900 dark:text-white truncate">{{ $course->title }}</h3>
                            <div class="flex items-center gap-1.5 mt-1">
                                @if($course->category)
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-medium" style="background-color: {{ $course->category->color }}15; color: {{ $course->category->color }};">
                                        <span class="w-1 h-1 rounded-full" style="background-color: {{ $course->category->color }};"></span>
                                        {{ $course->category->name }}
                                    </span>
                                @endif
                                @if($course->is_mandatory)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400">{{ __('lms.mandatory_label') }}</span>
                                @endif
                            </div>
                        </div>
                        <button wire:click.stop="toggleStatus({{ $course->id }})" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold flex-shrink-0 {{ $course->status === 'published' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $course->status === 'published' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                            {{ $course->status === 'published' ? __('lms.published') : __('lms.draft') }}
                        </button>
                    </div>

                    {{-- Warnings --}}
                    @if($isExpired)
                        <div class="flex items-center gap-1.5 mb-3 px-2 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-lg">
                            <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="text-[11px] font-medium text-red-600 dark:text-red-400">Süresi doldu ({{ $course->end_date->format('d.m.Y') }})</span>
                        </div>
                    @elseif($isExpiringSoon)
                        <div class="flex items-center gap-1.5 mb-3 px-2 py-1.5 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="text-[11px] font-medium text-amber-600 dark:text-amber-400">{{ $daysLeft }} gün kaldı</span>
                        </div>
                    @endif

                    {{-- Stats --}}
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        <div class="text-center p-2 bg-gray-50 dark:bg-gray-700/40 rounded-lg">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $course->enrollments_count }}</p>
                            <p class="text-[10px] text-gray-500">Kayıt</p>
                        </div>
                        <div class="text-center p-2 bg-gray-50 dark:bg-gray-700/40 rounded-lg">
                            <p class="text-sm font-bold {{ $course->questions_count > 0 ? 'text-gray-900 dark:text-white' : 'text-red-500' }}">{{ $course->questions_count }}</p>
                            <p class="text-[10px] text-gray-500">Soru</p>
                        </div>
                        <div class="text-center p-2 bg-gray-50 dark:bg-gray-700/40 rounded-lg">
                            <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $course->exam_duration_minutes }} dk</p>
                            <p class="text-[10px] text-gray-500">Süre</p>
                        </div>
                    </div>

                    {{-- Progress --}}
                    <div class="mb-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[11px] text-gray-500 dark:text-gray-400">Tamamlanma</span>
                            <span class="text-xs font-bold {{ $completionPercent >= 50 ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-600 dark:text-gray-400' }}">%{{ $completionPercent }}</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full transition-all {{ $completionPercent >= 50 ? 'bg-emerald-500' : ($completionPercent > 0 ? 'bg-amber-500' : 'bg-gray-300') }}" style="width: {{ max($completionPercent, 2) }}%"></div>
                        </div>
                    </div>

                    {{-- Departments --}}
                    <div class="flex items-center flex-wrap gap-1 mb-3">
                        @forelse($course->departments->take(2) as $dept)
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300">{{ $dept->name }}</span>
                        @empty
                            <span class="text-[10px] text-gray-400 italic">Departman atanmamış</span>
                        @endforelse
                        @if($course->departments->count() > 2)
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 dark:bg-slate-700/60 text-slate-500">+{{ $course->departments->count() - 2 }}</span>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                        <span class="text-[11px] text-gray-400" title="{{ $course->updated_at->format('d.m.Y H:i') }}">{{ $course->updated_at->diffForHumans() }}</span>
                        <div class="flex items-center gap-0.5" wire:click.stop>
                            <a href="{{ route('admin.courses.edit', $course->id) }}" class="p-1 text-gray-400 hover:text-amber-600 rounded transition-colors" title="{{ __('lms.edit') }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                            </a>
                            <button wire:click="confirmDelete({{ $course->id }})" class="p-1 text-gray-400 hover:text-red-600 rounded transition-colors" title="{{ __('lms.delete') }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center">
                <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347" /></svg>
                </div>
                @if($search || $filterCategory || $filterMandatory !== '')
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Aramanızla eşleşen eğitim bulunamadı</p>
                    <button wire:click="$set('search', ''); $set('filterCategory', ''); $set('filterMandatory', ''); $set('filterStatus', '')"
                        class="inline-flex items-center gap-1 mt-3 px-3 py-1.5 text-xs font-medium text-primary-600 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 transition-colors">
                        Filtreleri temizle
                    </button>
                @else
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('lms.course_empty') }}</p>
                    <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-1 mt-3 px-4 py-2 text-xs font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        İlk eğitimi oluştur
                    </a>
                @endif
            </div>
        @endforelse
    </div>
    @if($courses->hasPages())
        <div class="mt-4">{{ $courses->links() }}</div>
    @endif
    @endif

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- Detail Modal                                                --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    @if($showDetailModal && $viewingCourse)
        @teleport('body')
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-start justify-center min-h-screen px-4 pt-16 pb-8">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="$set('showDetailModal', false)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl z-10 border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $viewingCourse->title }}</h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    @if($viewingCourse->is_mandatory)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">{{ __('lms.mandatory_label') }}</span>
                                    @endif
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] font-semibold {{ $viewingCourse->status === 'published' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                        <span class="w-1 h-1 rounded-full {{ $viewingCourse->status === 'published' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                        {{ $viewingCourse->status === 'published' ? __('lms.published') : __('lms.draft') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button wire:click="$set('showDetailModal', false)" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-5">
                        @if($viewingCourse->description)
                            <div>
                                <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">{{ __('lms.description_label') }}</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $viewingCourse->description }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-4 gap-3">
                            @php
                                $detailCompletion = $viewingCourse->enrollments_count > 0
                                    ? round($viewingCourse->completed_enrollments_count / $viewingCourse->enrollments_count * 100) : 0;
                            @endphp
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $viewingCourse->questions_count }}</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ __('lms.question') }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $viewingCourse->enrollments_count }}</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ __('lms.enrollment') }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ $viewingCourse->completed_enrollments_count }}</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ __('lms.completed') }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold {{ $detailCompletion >= 50 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">%{{ $detailCompletion }}</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ __('lms.completion') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('lms.category') }}</span>
                                <p class="font-medium text-gray-800 dark:text-gray-200 mt-0.5">{{ $viewingCourse->category?->name ?? '—' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('lms.video') }}</span>
                                <p class="font-medium text-gray-800 dark:text-gray-200 mt-0.5">{{ $viewingCourse->videos->count() }} video</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('lms.start_date') }} — {{ __('lms.end_date') }}</span>
                                <p class="font-medium text-gray-800 dark:text-gray-200 mt-0.5">
                                    @if($viewingCourse->start_date && $viewingCourse->end_date)
                                        {{ $viewingCourse->start_date->format('d.m.Y') }} — {{ $viewingCourse->end_date->format('d.m.Y') }}
                                    @else
                                        Belirlenmedi
                                    @endif
                                </p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('lms.departments') }}</span>
                                <p class="font-medium text-gray-800 dark:text-gray-200 mt-0.5">{{ $viewingCourse->departments->pluck('name')->join(', ') ?: 'Tüm departmanlar' }}</p>
                            </div>
                        </div>

                        @if($viewingCourse->enrollments->count() > 0)
                            <div>
                                <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">{{ __('lms.enrolled_staff') }}</h4>
                                <div class="max-h-40 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($viewingCourse->enrollments->take(10) as $enrollment)
                                        <div class="flex items-center justify-between px-3 py-2">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                                                    <span class="text-[8px] font-bold text-white">{{ strtoupper(substr($enrollment->user->first_name ?? '', 0, 1) . substr($enrollment->user->last_name ?? '', 0, 1)) }}</span>
                                                </div>
                                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $enrollment->user->full_name ?? '—' }}</span>
                                            </div>
                                            @php
                                                $enrollStatusMap = [
                                                    'not_started' => ['label' => 'Başlanmadı', 'class' => 'text-gray-500'],
                                                    'in_progress' => ['label' => 'Devam', 'class' => 'text-primary-600 dark:text-primary-400'],
                                                    'completed' => ['label' => 'Tamamlandı', 'class' => 'text-emerald-600 dark:text-emerald-400'],
                                                    'failed' => ['label' => 'Başarısız', 'class' => 'text-red-600 dark:text-red-400'],
                                                ];
                                                $es = $enrollStatusMap[$enrollment->status] ?? $enrollStatusMap['not_started'];
                                            @endphp
                                            <span class="text-[11px] font-medium {{ $es['class'] }}">{{ $es['label'] }}</span>
                                        </div>
                                    @endforeach
                                    @if($viewingCourse->enrollments->count() > 10)
                                        <div class="px-3 py-2 text-center text-xs text-gray-500 dark:text-gray-400">
                                            +{{ $viewingCourse->enrollments->count() - 10 }} kişi daha
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        <button wire:click="$set('showDetailModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            {{ __('lms.dismiss') }}
                        </button>
                        <a href="{{ route('admin.courses.edit', $viewingCourse->id) }}" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                            {{ __('lms.edit') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endteleport
    @endif

    {{-- ═══ Delete Modal ═══ --}}
    @if($showDeleteModal)
        @teleport('body')
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="$set('showDeleteModal', false)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10 border border-gray-200 dark:border-gray-700">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-red-100 dark:bg-red-900/40 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ __('lms.course_delete_title') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">{{ __('lms.course_delete_body') }}<br><span class="text-xs text-gray-500">Bu işlem geri alınamaz.</span></p>
                        <div class="flex gap-3">
                            <button wire:click="$set('showDeleteModal', false)" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">{{ __('lms.cancel') }}</button>
                            <button wire:click="delete" class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-sm disabled:opacity-50" wire:loading.attr="disabled" wire:target="delete">
                                <span wire:loading.remove wire:target="delete">{{ __('lms.yes_delete') }}</span>
                                <span wire:loading wire:target="delete" class="inline-flex items-center gap-1.5 justify-center">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    {{ __('lms.deleting') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endteleport
    @endif

    {{-- ═══ Bulk Delete Modal ═══ --}}
    @if($showBulkDeleteModal)
        @teleport('body')
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="$set('showBulkDeleteModal', false)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10 border border-gray-200 dark:border-gray-700">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-red-100 dark:bg-red-900/40 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Toplu Silme</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Seçili <strong>{{ count($selectedCourses) }}</strong> eğitimi silmek istediğinizden emin misiniz?<br><span class="text-xs text-gray-500">Kayıtlı personeli olan eğitimler atlanacaktır.</span></p>
                        <div class="flex gap-3">
                            <button wire:click="$set('showBulkDeleteModal', false)" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">{{ __('lms.cancel') }}</button>
                            <button wire:click="bulkDelete" class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-sm disabled:opacity-50" wire:loading.attr="disabled" wire:target="bulkDelete">
                                <span wire:loading.remove wire:target="bulkDelete">{{ __('lms.yes_delete') }}</span>
                                <span wire:loading wire:target="bulkDelete" class="inline-flex items-center gap-1.5 justify-center">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    {{ __('lms.deleting') }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endteleport
    @endif

    {{-- ═══ Bulk Assign Department Modal ═══ --}}
    @if($showBulkAssignModal)
        @teleport('body')
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="$set('showBulkAssignModal', false)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10 border border-gray-200 dark:border-gray-700">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-primary-100 dark:bg-primary-900/40 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Departmana Ata</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Seçili <strong>{{ count($selectedCourses) }}</strong> eğitimi bir departmana atayın.</p>
                        <select wire:model="bulkAssignDepartmentId" class="w-full mb-4 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-3 py-2.5 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">Departman seçin...</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        <div class="flex gap-3">
                            <button wire:click="$set('showBulkAssignModal', false)" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">{{ __('lms.cancel') }}</button>
                            <button wire:click="bulkAssignDepartment" class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm disabled:opacity-50" @if(!$bulkAssignDepartmentId) disabled @endif>
                                Ata
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endteleport
    @endif
</div>
