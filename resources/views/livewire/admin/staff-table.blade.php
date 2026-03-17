<div>
    {{-- Import Errors --}}
    @if(session('importErrors'))
        <div class="mb-4 p-4 bg-amber-100 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-lg">
            <p class="text-sm font-medium text-amber-800 dark:text-amber-300 mb-2">Bazı satırlar atlandı:</p>
            <ul class="text-xs text-amber-700 dark:text-amber-400 space-y-1">
                @foreach(session('importErrors') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Personel Listesi</h2>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300">
                {{ $totalStaff }} personel
            </span>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            {{-- Excel Export --}}
            <button wire:click="exportExcel" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled:opacity-50" wire:loading.attr="disabled" wire:target="exportExcel">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span wire:loading.remove wire:target="exportExcel">Excel</span>
                <span wire:loading wire:target="exportExcel">İndiriliyor...</span>
            </button>
            {{-- PDF Export --}}
            <button wire:click="exportPdf" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors disabled:opacity-50" wire:loading.attr="disabled" wire:target="exportPdf">
                <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                <span wire:loading.remove wire:target="exportPdf">PDF</span>
                <span wire:loading wire:target="exportPdf">İndiriliyor...</span>
            </button>
            {{-- Import Button --}}
            <button wire:click="openImport" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-3.5 h-3.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                Excel Aktar
            </button>
            <div class="w-px h-6 bg-gray-200 dark:bg-gray-700 hidden sm:block"></div>
            <button wire:click="openCreate" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Personel Ekle
            </button>
        </div>
    </div>

    {{-- Filters Row --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <div class="relative flex-1 max-w-md">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Ad, e-posta veya sicil no ara..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500 placeholder-gray-400" />
        </div>
        <select wire:model.live="filterDepartment" class="border border-gray-300 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 pl-3 pr-10 py-2 focus:ring-primary-500 focus:border-primary-500">
            <option value="">Tüm Departmanlar</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterStatus" class="border border-gray-300 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 pl-3 pr-10 py-2 focus:ring-primary-500 focus:border-primary-500">
            <option value="">Tüm Durumlar</option>
            <option value="1">Aktif</option>
            <option value="0">Pasif</option>
        </select>
    </div>

    {{-- Bulk Actions Bar --}}
    @if(count($selectedStaff) > 0)
        <div class="flex items-center gap-3 mb-4 px-4 py-3 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-lg">
            <span class="text-sm font-medium text-primary-700 dark:text-primary-300">{{ count($selectedStaff) }} personel seçildi</span>
            <div class="flex items-center gap-2 ml-auto">
                <button wire:click="bulkActivate" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg hover:bg-emerald-200 dark:hover:bg-emerald-900/50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Aktif Et
                </button>
                <button wire:click="bulkDeactivate" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                    Pasif Et
                </button>
                <button wire:click="confirmBulkDelete" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Sil
                </button>
                <button wire:click="$set('selectedStaff', [])" class="p-1.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded transition-colors" title="Seçimi kaldır">
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
                        <th class="w-10 px-4 py-3">
                            <input type="checkbox" wire:model.live="selectAll" class="rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500 dark:bg-gray-700" />
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-primary-600 dark:hover:text-primary-400 transition-colors" wire:click="sortBy('first_name')">
                            <div class="flex items-center gap-1">
                                Personel
                                @if($sortField === 'first_name')
                                    <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-primary-600 dark:hover:text-primary-400 transition-colors" wire:click="sortBy('registration_number')">
                            <div class="flex items-center gap-1">
                                Sicil No
                                @if($sortField === 'registration_number')
                                    <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-primary-600 dark:hover:text-primary-400 transition-colors" wire:click="sortBy('department_id')">
                            <div class="flex items-center gap-1">
                                Departman
                                @if($sortField === 'department_id')
                                    <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Ünvan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">İlerleme</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-primary-600 dark:hover:text-primary-400 transition-colors" wire:click="sortBy('last_login_at')">
                            <div class="flex items-center gap-1">
                                Son Giriş
                                @if($sortField === 'last_login_at')
                                    <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-primary-600 dark:hover:text-primary-400 transition-colors" wire:click="sortBy('is_active')">
                            <div class="flex items-center justify-center gap-1">
                                Durum
                                @if($sortField === 'is_active')
                                    <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($staff as $user)
                        @php
                            $progressPercent = $user->enrollments_count > 0
                                ? round($user->completed_enrollments_count / $user->enrollments_count * 100)
                                : 0;
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors {{ in_array($user->id, $selectedStaff) ? 'bg-primary-50/50 dark:bg-primary-900/10' : '' }}">
                            <td class="px-4 py-3">
                                <input type="checkbox" wire:model.live="selectedStaff" value="{{ $user->id }}" class="rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500 dark:bg-gray-700" />
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <span class="text-xs font-bold text-white">{{ strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white text-sm truncate">{{ $user->full_name }}</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600 dark:text-gray-300 font-mono">{{ $user->registration_number ?? '—' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($user->department)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-slate-100 dark:bg-slate-700/60 text-slate-700 dark:text-slate-300">
                                        {{ $user->department->name }}
                                    </span>
                                @else
                                    <span class="text-gray-500 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $user->title ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2 min-w-[120px]">
                                    <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500 {{ $progressPercent >= 80 ? 'bg-emerald-500' : ($progressPercent >= 50 ? 'bg-amber-500' : 'bg-primary-500') }}" style="width: {{ $progressPercent }}%"></div>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 w-8 text-right">%{{ $progressPercent }}</span>
                                </div>
                                <p class="text-[11px] text-gray-500 mt-0.5">{{ $user->completed_enrollments_count }}/{{ $user->enrollments_count }} eğitim</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Henüz giriş yok' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="toggleActive({{ $user->id }})" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold transition-colors {{ $user->is_active ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-100' : 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400 hover:bg-red-100' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    {{ $user->is_active ? 'Aktif' : 'Pasif' }}
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-0.5">
                                    <button wire:click="viewDetail({{ $user->id }})" class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-lg transition-colors" title="Detay">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </button>
                                    <button wire:click="openEdit({{ $user->id }})" class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition-colors" title="Düzenle">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $user->id }})" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Sil">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-3">
                                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Personel bulunamadı</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Arama veya filtre kriterlerinizi değiştirin</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($staff->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                {{ $staff->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if($showModal)
    @teleport('body')
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm modal-backdrop-enter" wire:click="$set('showModal', false)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg z-10 border border-gray-200 dark:border-gray-700 modal-content-enter">
                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                {{ $editingId ? 'Personel Düzenle' : 'Yeni Personel Ekle' }}
                            </h3>
                        </div>
                        <button wire:click="$set('showModal', false)" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <form wire:submit="save" class="px-6 py-5 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">Ad *</label>
                                <input wire:model="first_name" type="text" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                                @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">Soyad *</label>
                                <input wire:model="last_name" type="text" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                                @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">E-posta *</label>
                            <input wire:model="email" type="email" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">
                                Şifre {{ $editingId ? '(boş bırakılırsa değişmez)' : '*' }}
                            </label>
                            <input wire:model="password" type="password" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">Telefon</label>
                                <input wire:model="phone" type="text" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">Sicil No</label>
                                <input wire:model="registration_number" type="text" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                                @error('registration_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">Ünvan</label>
                                <input wire:model="title" type="text" placeholder="örn: Hemşire, Dr." class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">Departman *</label>
                                <select wire:model="department_id" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Seçiniz</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-400 mb-1.5">İşe Giriş Tarihi</label>
                                <input wire:model="hire_date" type="date" class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                            </div>
                            <div class="flex items-end pb-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input wire:model="is_active" type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Aktif</span>
                                </label>
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                İptal
                            </button>
                            <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm disabled:opacity-50" wire:loading.attr="disabled" wire:target="save">
                                <span wire:loading.remove wire:target="save">{{ $editingId ? 'Güncelle' : 'Oluştur' }}</span>
                                <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    Kaydediliyor...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endteleport
    @endif

    {{-- Detail Modal --}}
    @if($showDetailModal && $viewingUser)
    @teleport('body')
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm modal-backdrop-enter" wire:click="$set('showDetailModal', false)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg z-10 border border-gray-200 dark:border-gray-700 modal-content-enter">
                    {{-- Header --}}
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Personel Detayı</h3>
                            <button wire:click="$set('showDetailModal', false)" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-lg">
                                <span class="text-lg font-bold text-white">{{ strtoupper(substr($viewingUser->first_name ?? '', 0, 1) . substr($viewingUser->last_name ?? '', 0, 1)) }}</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $viewingUser->full_name }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $viewingUser->title ?? 'Ünvan belirtilmemiş' }}</p>
                            </div>
                            <div class="ml-auto">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold {{ $viewingUser->is_active ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400' : 'bg-red-100 dark:bg-red-900/40 text-red-600 dark:text-red-400' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $viewingUser->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    {{ $viewingUser->is_active ? 'Aktif' : 'Pasif' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="px-6 py-4">
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between py-1">
                                <dt class="text-gray-600 dark:text-gray-400">E-posta</dt>
                                <dd class="text-gray-900 dark:text-white font-medium">{{ $viewingUser->email }}</dd>
                            </div>
                            <div class="flex justify-between py-1">
                                <dt class="text-gray-600 dark:text-gray-400">Telefon</dt>
                                <dd class="text-gray-900 dark:text-white">{{ $viewingUser->phone ?? '—' }}</dd>
                            </div>
                            <div class="flex justify-between py-1">
                                <dt class="text-gray-600 dark:text-gray-400">Sicil No</dt>
                                <dd class="text-gray-900 dark:text-white font-mono">{{ $viewingUser->registration_number ?? '—' }}</dd>
                            </div>
                            <div class="flex justify-between py-1">
                                <dt class="text-gray-600 dark:text-gray-400">Departman</dt>
                                <dd class="text-gray-900 dark:text-white">{{ $viewingUser->department?->name ?? '—' }}</dd>
                            </div>
                            <div class="flex justify-between py-1">
                                <dt class="text-gray-600 dark:text-gray-400">İşe Giriş</dt>
                                <dd class="text-gray-900 dark:text-white">{{ $viewingUser->hire_date?->format('d.m.Y') ?? '—' }}</dd>
                            </div>
                            <div class="flex justify-between py-1">
                                <dt class="text-gray-600 dark:text-gray-400">Son Giriş</dt>
                                <dd class="text-gray-900 dark:text-white">{{ $viewingUser->last_login_at?->format('d.m.Y H:i') ?? 'Henüz giriş yapmadı' }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-4 gap-2 px-6 pb-4">
                        <div class="bg-primary-100 dark:bg-primary-900/30 rounded-lg p-3 text-center">
                            <p class="text-lg font-bold text-primary-700 dark:text-primary-300">{{ $viewingUser->enrollments_count }}</p>
                            <p class="text-[10px] text-primary-600 dark:text-primary-400 font-medium mt-0.5">Toplam</p>
                        </div>
                        <div class="bg-emerald-100 dark:bg-emerald-900/30 rounded-lg p-3 text-center">
                            <p class="text-lg font-bold text-emerald-700 dark:text-emerald-300">{{ $viewingUser->completed_count }}</p>
                            <p class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium mt-0.5">Başarılı</p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900/30 rounded-lg p-3 text-center">
                            <p class="text-lg font-bold text-red-700 dark:text-red-300">{{ $viewingUser->failed_count }}</p>
                            <p class="text-[10px] text-red-600 dark:text-red-400 font-medium mt-0.5">Başarısız</p>
                        </div>
                        <div class="bg-amber-100 dark:bg-amber-900/30 rounded-lg p-3 text-center">
                            <p class="text-lg font-bold text-amber-700 dark:text-amber-300">{{ $viewingUser->in_progress_count }}</p>
                            <p class="text-[10px] text-amber-600 dark:text-amber-400 font-medium mt-0.5">Devam Eden</p>
                        </div>
                    </div>

                    {{-- Enrollments List --}}
                    @if($viewingUser->enrollments->count() > 0)
                        <div class="px-6 pb-4">
                            <h5 class="text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider mb-2">Eğitimler</h5>
                            <div class="space-y-1.5 max-h-48 overflow-y-auto">
                                @foreach($viewingUser->enrollments as $enrollment)
                                    <div class="flex items-center justify-between py-1.5 px-2.5 rounded-lg bg-gray-50 dark:bg-gray-700/30">
                                        <span class="text-xs text-gray-800 dark:text-gray-200 truncate mr-2">{{ $enrollment->course?->title ?? '—' }}</span>
                                        @php
                                            $statusClass = match($enrollment->status) {
                                                'completed' => 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400',
                                                'failed' => 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400',
                                                'in_progress' => 'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400',
                                                default => 'bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-300',
                                            };
                                            $statusLabel = match($enrollment->status) {
                                                'completed' => 'Başarılı',
                                                'failed' => 'Başarısız',
                                                'in_progress' => 'Devam',
                                                default => 'Başlamadı',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold whitespace-nowrap {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Footer: Detail Page Link --}}
                    <div class="px-6 pb-5">
                        <a href="{{ route('admin.staff.show', $viewingUser->id) }}" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            Detaylı Sayfaya Git
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endteleport
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteModal)
    @teleport('body')
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm modal-backdrop-enter" wire:click="$set('showDeleteModal', false)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10 border border-gray-200 dark:border-gray-700 modal-content-enter">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-red-100 dark:bg-red-900/40 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Personeli Sil</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Bu personeli silmek istediğinizden emin misiniz?<br><span class="text-xs text-gray-500">Bu işlem geri alınabilir (soft delete).</span></p>
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

    {{-- Import Modal --}}
    @if($showImportModal)
    @teleport('body')
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm modal-backdrop-enter" wire:click="$set('showImportModal', false)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md z-10 border border-gray-200 dark:border-gray-700 modal-content-enter">
                    {{-- Header --}}
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Excel ile Personel Aktar</h3>
                        </div>
                        <button wire:click="$set('showImportModal', false)" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    {{-- Body --}}
                    <form wire:submit="importStaff" class="px-6 py-5 space-y-4">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <p class="text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Excel dosyanızda şu sütunlar olmalı:</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach(['ad', 'soyad', 'eposta', 'departman', 'unvan', 'telefon', 'sicil_no'] as $col)
                                    <span class="px-2 py-0.5 bg-white dark:bg-gray-600 rounded text-[11px] font-mono text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-500">{{ $col }}</span>
                                @endforeach
                            </div>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2">Şifre belirtilmezse varsayılan olarak "password" atanır.</p>
                        </div>

                        <div>
                            <input wire:model="importFile" type="file" accept=".xlsx,.xls,.csv"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-pointer file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-100 file:text-emerald-700 file:cursor-pointer file:transition-all file:duration-200 hover:file:bg-emerald-200 active:file:scale-95 dark:file:bg-emerald-900/40 dark:file:text-emerald-300" />
                            @error('importFile') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div wire:loading wire:target="importStaff" class="flex items-center gap-2 text-sm text-primary-600 dark:text-primary-400">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            İçe aktarılıyor...
                        </div>

                        {{-- Footer --}}
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="button" wire:click="$set('showImportModal', false)" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                İptal
                            </button>
                            <button type="submit" wire:loading.attr="disabled" class="px-5 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm disabled:opacity-50">
                                Aktarmayı Başlat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endteleport
    @endif

    {{-- Bulk Delete Confirmation Modal --}}
    @if($showBulkDeleteModal)
    @teleport('body')
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm modal-backdrop-enter" wire:click="$set('showBulkDeleteModal', false)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10 border border-gray-200 dark:border-gray-700 modal-content-enter">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-red-100 dark:bg-red-900/40 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Toplu Silme</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Seçili <strong>{{ count($selectedStaff) }}</strong> personeli silmek istediğinizden emin misiniz?<br><span class="text-xs text-gray-500">Eğitim kaydı bulunan personeller atlanacaktır (soft delete).</span></p>
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
