<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="relative w-full sm:w-72">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Kullanıcı veya işlem ara..."
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
            <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </div>
        <div class="flex items-center gap-3">
            <select wire:model.live="eventFilter" class="pl-3 pr-8 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 min-w-[140px]">
                <option value="">Tüm İşlemler</option>
                <option value="created">Oluşturma</option>
                <option value="updated">Güncelleme</option>
                <option value="deleted">Silme</option>
            </select>
            <select wire:model.live="subjectFilter" class="pl-3 pr-8 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 min-w-[140px]">
                <option value="">Tüm Kategoriler</option>
                @foreach($subjectTypes as $type => $label)
                    <option value="{{ $type }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Activity List --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($activities as $activity)
                @php
                    $eventConfig = match($activity->event) {
                        'created' => ['bg' => 'bg-emerald-100 dark:bg-emerald-900/40', 'text' => 'text-emerald-700 dark:text-emerald-300', 'icon' => 'M12 4v16m8-8H4', 'label' => 'Oluşturuldu'],
                        'updated' => ['bg' => 'bg-amber-100 dark:bg-amber-900/40', 'text' => 'text-amber-700 dark:text-amber-300', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'label' => 'Güncellendi'],
                        'deleted' => ['bg' => 'bg-red-100 dark:bg-red-900/40', 'text' => 'text-red-700 dark:text-red-300', 'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16', 'label' => 'Silindi'],
                        default => ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-700 dark:text-gray-300', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => $activity->event ?? 'İşlem'],
                    };
                    $subjectLabel = $activity->subject_type ? class_basename($activity->subject_type) : '-';
                    $modelLabels = [
                        'User' => 'Personel',
                        'Department' => 'Departman',
                        'Category' => 'Kategori',
                        'Course' => 'Eğitim',
                    ];
                    $subjectLabel = $modelLabels[$subjectLabel] ?? $subjectLabel;
                @endphp
                <div class="px-5 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors" x-data="{ open: false }">
                    <div class="flex items-start gap-4">
                        {{-- Event Icon --}}
                        <div class="flex-shrink-0 w-9 h-9 rounded-full {{ $eventConfig['bg'] }} flex items-center justify-center mt-0.5">
                            <svg class="w-4 h-4 {{ $eventConfig['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $eventConfig['icon'] }}" />
                            </svg>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-medium text-gray-800 dark:text-white">
                                    {{ $activity->causer ? ($activity->causer->full_name ?? $activity->causer->email) : 'Sistem' }}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium {{ $eventConfig['bg'] }} {{ $eventConfig['text'] }}">
                                    {{ $eventConfig['label'] }}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300">
                                    {{ $subjectLabel }}
                                </span>
                                @if($activity->subject)
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        — {{ $activity->subject->name ?? $activity->subject->title ?? $activity->subject->full_name ?? '#'.$activity->subject_id }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                {{ $activity->created_at->diffForHumans() }} · {{ $activity->created_at->format('d.m.Y H:i') }}
                            </p>

                            {{-- Expandable Properties --}}
                            @if($activity->properties && $activity->properties->count() > 0)
                                <button @click="open = !open" class="mt-2 text-xs text-primary-600 dark:text-primary-400 hover:underline flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    Detayları Göster
                                </button>
                                <div x-show="open" x-collapse class="mt-2">
                                    @if($activity->properties->has('old') && $activity->properties->has('attributes'))
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            {{-- Old values --}}
                                            <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-3">
                                                <p class="text-[11px] font-semibold text-red-600 dark:text-red-400 mb-1.5 uppercase">Eski Değerler</p>
                                                @foreach($activity->properties['old'] as $key => $val)
                                                    <div class="flex justify-between text-xs py-0.5">
                                                        <span class="text-gray-500 dark:text-gray-400">{{ $key }}</span>
                                                        <span class="text-gray-700 dark:text-gray-300 font-mono truncate ml-2 max-w-[200px]">{{ is_array($val) ? json_encode($val) : ($val ?? '—') }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                            {{-- New values --}}
                                            <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-lg p-3">
                                                <p class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 mb-1.5 uppercase">Yeni Değerler</p>
                                                @foreach($activity->properties['attributes'] as $key => $val)
                                                    <div class="flex justify-between text-xs py-0.5">
                                                        <span class="text-gray-500 dark:text-gray-400">{{ $key }}</span>
                                                        <span class="text-gray-700 dark:text-gray-300 font-mono truncate ml-2 max-w-[200px]">{{ is_array($val) ? json_encode($val) : ($val ?? '—') }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @elseif($activity->properties->has('attributes'))
                                        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-lg p-3">
                                            <p class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 mb-1.5 uppercase">Değerler</p>
                                            @foreach($activity->properties['attributes'] as $key => $val)
                                                <div class="flex justify-between text-xs py-0.5">
                                                    <span class="text-gray-500 dark:text-gray-400">{{ $key }}</span>
                                                    <span class="text-gray-700 dark:text-gray-300 font-mono truncate ml-2 max-w-[200px]">{{ is_array($val) ? json_encode($val) : ($val ?? '—') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Timestamp --}}
                        <div class="hidden lg:block flex-shrink-0 text-right">
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $activity->created_at->format('H:i') }}</p>
                            <p class="text-[11px] text-gray-300 dark:text-gray-600">{{ $activity->created_at->format('d.m.Y') }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-16 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-sm text-gray-400 dark:text-gray-500">Henüz işlem geçmişi bulunmuyor.</p>
                </div>
            @endforelse
        </div>

        @if($activities->hasPages())
        <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
            {{ $activities->links() }}
        </div>
        @endif
    </div>
</div>
