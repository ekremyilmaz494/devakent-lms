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

    {{-- Page Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Eğitim Listesi</h2>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300">
                {{ $totalCourses }} eğitim
            </span>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <button wire:click="exportExcel" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled:opacity-50" wire:loading.attr="disabled" wire:target="exportExcel">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span wire:loading.remove wire:target="exportExcel">Excel</span>
                <span wire:loading wire:target="exportExcel">İndiriliyor...</span>
            </button>
            <button wire:click="exportPdf" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled:opacity-50" wire:loading.attr="disabled" wire:target="exportPdf">
                <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                <span wire:loading.remove wire:target="exportPdf">PDF</span>
                <span wire:loading wire:target="exportPdf">İndiriliyor...</span>
            </button>
            <div class="w-px h-6 bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>
            <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Yeni Eğitim
            </a>
        </div>
    </div>

    {{-- Status Tabs --}}
    <div class="flex items-center gap-1 mb-4 bg-gray-100 dark:bg-gray-800 rounded-lg p-1 w-fit">
        <button wire:click="$set('filterStatus', '')" class="px-3 py-1.5 text-xs font-medium rounded-md transition-colors {{ $filterStatus === '' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
            Tümü <span class="ml-1 text-[10px] {{ $filterStatus === '' ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400' }}">{{ $totalCourses }}</span>
        </button>
        <button wire:click="$set('filterStatus', 'published')" class="px-3 py-1.5 text-xs font-medium rounded-md transition-colors {{ $filterStatus === 'published' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
            Yayında <span class="ml-1 text-[10px] {{ $filterStatus === 'published' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400' }}">{{ $publishedCount }}</span>
        </button>
        <button wire:click="$set('filterStatus', 'draft')" class="px-3 py-1.5 text-xs font-medium rounded-md transition-colors {{ $filterStatus === 'draft' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
            Taslak <span class="ml-1 text-[10px] {{ $filterStatus === 'draft' ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400' }}">{{ $draftCount }}</span>
        </button>
    </div>

    {{-- Filters Row --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <div class="relative flex-1 max-w-md">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Eğitim adı ile ara..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500 placeholder-gray-400" />
        </div>
        <select wire:model.live="filterCategory" class="border border-gray-300 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
            <option value="">Tüm Kategoriler</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterMandatory" class="border border-gray-300 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
            <option value="">Tümü (Zorunlu/Ops.)</option>
            <option value="1">Zorunlu</option>
            <option value="0">Opsiyonel</option>
        </select>
    </div>

    {{-- Bulk Actions Bar --}}
    @if(count($selectedCourses) > 0)
        <div class="flex items-center gap-3 mb-4 px-4 py-3 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-lg">
            <span class="text-sm font-medium text-primary-700 dark:text-primary-300">{{ count($selectedCourses) }} eğitim seçildi</span>
            <div class="flex items-center gap-2 ml-auto">
                <button wire:click="bulkPublish" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-900/50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Yayınla
                </button>
                <button wire:click="bulkDraft" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                    Taslağa Al
                </button>
                <button wire:click="confirmBulkDelete" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Sil
                </button>
                <button wire:click="$set('selectedCourses', [])" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded transition-colors" title="Seçimi kaldır">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
        <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox" wire:model.live="selectAll" class="rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500 dark:bg-gray-700">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-primary-600 dark:hover:text-primary-400 transition-colors" wire:click="sortBy('title')">
                            <div class="flex items-center gap-1">
                                Eğitim
                                @if($sortField === 'title')
                                    <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-primary-600 dark:hover:text-primary-400 transition-colors" wire:click="sortBy('questions_count')">
                            <div class="flex items-center justify-center gap-1">
                                Soru
                                @if($sortField === 'questions_count')
                                    <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-primary-600 dark:hover:text-primary-400 transition-colors" wire:click="sortBy('enrollments_count')">
                            <div class="flex items-center justify-center gap-1">
                                Kayıt / Tamamlanma
                                @if($sortField === 'enrollments_count')
                                    <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-primary-600 dark:hover:text-primary-400 transition-colors" wire:click="sortBy('created_at')">
                            <div class="flex items-center justify-center gap-1">
                                Durum
                                @if($sortField === 'created_at')
                                    <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($courses as $course)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors {{ in_array($course->id, $selectedCourses) ? 'bg-primary-50/50 dark:bg-primary-900/10' : '' }}">
                            <td class="px-4 py-3">
                                <input type="checkbox" wire:model.live="selectedCourses" value="{{ $course->id }}" class="rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500 dark:bg-gray-700">
                            </td>
                            <td class="px-4 py-3">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $course->title }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        @if($course->is_mandatory)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-semibold bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400">Zorunlu</span>
                                        @endif
                                        @if($course->departments->count() > 0)
                                            <span class="text-[11px] text-gray-500">{{ $course->departments->count() }} departman</span>
                                        @endif
                                        @if($course->start_date && $course->end_date)
                                            <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $course->start_date->format('d.m') }} — {{ $course->end_date->format('d.m.Y') }}</span>
                                        @endif
                                    </div>
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
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-semibold {{ $course->questions_count > 0 ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400' : 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400' }}">
                                    {{ $course->questions_count }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $completionPercent = $course->enrollments_count > 0
                                        ? round($course->completed_enrollments_count / $course->enrollments_count * 100)
                                        : 0;
                                @endphp
                                <div class="flex flex-col items-center gap-1 min-w-[100px]">
                                    <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">{{ $course->enrollments_count }}</span>
                                        <span class="text-gray-400 dark:text-gray-500">kayıt</span>
                                        <span class="text-gray-300 dark:text-gray-600">·</span>
                                        <span class="font-medium {{ $completionPercent >= 50 ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-600 dark:text-gray-400' }}">%{{ $completionPercent }}</span>
                                    </div>
                                    @if($course->enrollments_count > 0)
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1">
                                            <div class="h-1 rounded-full transition-all {{ $completionPercent >= 50 ? 'bg-emerald-500' : ($completionPercent > 0 ? 'bg-amber-500' : 'bg-gray-300') }}" style="width: {{ max($completionPercent, 2) }}%"></div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="toggleStatus({{ $course->id }})" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold transition-colors {{ $course->status === 'published' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $course->status === 'published' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                    {{ $course->status === 'published' ? 'Yayında' : 'Taslak' }}
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-0.5">
                                    <button wire:click="viewDetail({{ $course->id }})" class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-lg transition-colors" title="Detay">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </button>
                                    <a href="{{ route('admin.courses.edit', $course->id) }}" class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition-colors" title="Düzenle">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                                    </a>
                                    <button wire:click="confirmDelete({{ $course->id }})" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Sil">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-3">
                                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" /></svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Henüz eğitim oluşturulmamış</p>
                                    <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-1 mt-2 text-xs font-medium text-primary-600 hover:text-primary-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                        İlk eğitimi oluştur
                                    </a>
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

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- Detail Modal                                                --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    @if($showDetailModal && $viewingCourse)
        @teleport('body')
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-start justify-center min-h-screen px-4 pt-16 pb-8">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="$set('showDetailModal', false)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl z-10 border border-gray-200 dark:border-gray-700 overflow-hidden">
                    {{-- Header --}}
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
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400">Zorunlu</span>
                                    @endif
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[10px] font-semibold {{ $viewingCourse->status === 'published' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                                        <span class="w-1 h-1 rounded-full {{ $viewingCourse->status === 'published' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                        {{ $viewingCourse->status === 'published' ? 'Yayında' : 'Taslak' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <button wire:click="$set('showDetailModal', false)" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-5">
                        {{-- Description --}}
                        @if($viewingCourse->description)
                            <div>
                                <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Açıklama</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $viewingCourse->description }}</p>
                            </div>
                        @endif

                        {{-- Stats Grid --}}
                        <div class="grid grid-cols-4 gap-3">
                            @php
                                $detailCompletion = $viewingCourse->enrollments_count > 0
                                    ? round($viewingCourse->completed_enrollments_count / $viewingCourse->enrollments_count * 100)
                                    : 0;
                            @endphp
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $viewingCourse->questions_count }}</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">Soru</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $viewingCourse->enrollments_count }}</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">Kayıt</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ $viewingCourse->completed_enrollments_count }}</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">Tamamlayan</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-center">
                                <p class="text-lg font-bold {{ $detailCompletion >= 50 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">%{{ $detailCompletion }}</p>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">Tamamlanma</p>
                            </div>
                        </div>

                        {{-- Info Grid --}}
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Kategori</span>
                                <p class="font-medium text-gray-800 dark:text-gray-200 mt-0.5">{{ $viewingCourse->category?->name ?? '—' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Video</span>
                                <p class="font-medium text-gray-800 dark:text-gray-200 mt-0.5">{{ $viewingCourse->videos->count() }} video</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Tarih Aralığı</span>
                                <p class="font-medium text-gray-800 dark:text-gray-200 mt-0.5">
                                    @if($viewingCourse->start_date && $viewingCourse->end_date)
                                        {{ $viewingCourse->start_date->format('d.m.Y') }} — {{ $viewingCourse->end_date->format('d.m.Y') }}
                                    @else
                                        Belirlenmedi
                                    @endif
                                </p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Departmanlar</span>
                                <p class="font-medium text-gray-800 dark:text-gray-200 mt-0.5">{{ $viewingCourse->departments->pluck('name')->join(', ') ?: 'Tüm departmanlar' }}</p>
                            </div>
                        </div>

                        {{-- Enrolled Staff List --}}
                        @if($viewingCourse->enrollments->count() > 0)
                            <div>
                                <h4 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Kayıtlı Personel</h4>
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

                    {{-- Footer --}}
                    <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        <button wire:click="$set('showDetailModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            Kapat
                        </button>
                        <a href="{{ route('admin.courses.edit', $viewingCourse->id) }}" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                            Düzenle
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endteleport
    @endif

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- Delete Modal                                                --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
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
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Eğitimi Sil</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Bu eğitimi ve tüm sorularını silmek istediğinizden emin misiniz?<br><span class="text-xs text-gray-500">Bu işlem geri alınamaz.</span></p>
                        <div class="flex gap-3">
                            <button wire:click="$set('showDeleteModal', false)" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                İptal
                            </button>
                            <button wire:click="delete" class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-sm disabled:opacity-50" wire:loading.attr="disabled" wire:target="delete">
                                <span wire:loading.remove wire:target="delete">Evet, Sil</span>
                                <span wire:loading wire:target="delete" class="inline-flex items-center gap-1.5 justify-center">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    Siliniyor...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endteleport
    @endif

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- Bulk Delete Modal                                           --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
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
                            <button wire:click="$set('showBulkDeleteModal', false)" class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                İptal
                            </button>
                            <button wire:click="bulkDelete" class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-sm disabled:opacity-50" wire:loading.attr="disabled" wire:target="bulkDelete">
                                <span wire:loading.remove wire:target="bulkDelete">Evet, Sil</span>
                                <span wire:loading wire:target="bulkDelete" class="inline-flex items-center gap-1.5 justify-center">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    Siliniyor...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endteleport
    @endif
</div>
