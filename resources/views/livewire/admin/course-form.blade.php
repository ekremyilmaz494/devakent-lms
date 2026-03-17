<div>
    <form wire:submit="save" x-data @submit="$wire.selectedPersonnel = window.selectedPersonnelState" class="space-y-6">
        {{-- Validation Error Summary --}}
        @if($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/40 flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-red-800 dark:text-red-300 mb-1">{{ __('lms.validation_errors') }}</h4>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li class="text-xs text-red-700 dark:text-red-400">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Basic Info Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('lms.basic_info') }}</h3>

            <div class="space-y-4">
                {{-- Title --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('lms.course_title_label') }}</label>
                    <input wire:model="title" type="text" required class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" placeholder="{{ __('lms.course_title_ph') }}" />
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('lms.description_label') }}</label>
                    <textarea wire:model="description" rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" placeholder="{{ __('lms.description_ph') }}"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Category --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('lms.category_label') }}</label>
                        <select wire:model="category_id" required class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
                            <option value="">{{ __('lms.select') }}</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('lms.status_label') }}</label>
                        <select wire:model="status" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">
                            <option value="draft">{{ __('lms.draft') }}</option>
                            <option value="published">{{ __('lms.published') }}</option>
                        </select>
                    </div>
                    {{-- Mandatory --}}
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input wire:model="is_mandatory" type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('lms.mandatory_check') }}</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Start Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('lms.start_date_label') }}</label>
                        <input wire:model="start_date" type="date" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                    </div>
                    {{-- End Date --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('lms.end_date_label') }}</label>
                        <input wire:model="end_date" type="date" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                        @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            </div>
        </div>

        {{-- Videos Card (Çoklu) --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('lms.videos_section') }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('lms.videos_count', ['count' => count($videos)]) }}</p>
                </div>
                <button type="button" wire:click="addVideo" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-primary-600 border border-primary-300 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    {{ __('lms.add_video') }}
                </button>
            </div>

            @if(count($videos) === 0)
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    <p>{{ __('lms.video_empty') }}</p>
                    <p class="text-sm mt-1">{{ __('lms.video_empty_sub') }}</p>
                </div>
            @endif

            <div class="space-y-4">
                @foreach($videos as $index => $video)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4" wire:key="video-{{ $index }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 text-xs font-bold">#{{ $index + 1 }}</span>
                                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Video {{ $index + 1 }}</h4>
                            </div>
                            <div class="flex items-center gap-1">
                                @if($index > 0)
                                    <button type="button" wire:click="moveVideoUp({{ $index }})" class="p-1 text-gray-400 hover:text-gray-600 rounded" title="{{ __('lms.move_up') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                    </button>
                                @endif
                                @if($index < count($videos) - 1)
                                    <button type="button" wire:click="moveVideoDown({{ $index }})" class="p-1 text-gray-400 hover:text-gray-600 rounded" title="{{ __('lms.move_down') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                @endif
                                <button type="button" wire:click="removeVideo({{ $index }})" class="p-1 text-red-400 hover:text-red-600 rounded" title="Sil">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-3">
                            {{-- Video Başlığı --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">{{ __('lms.video_title_label') }}</label>
                                <input wire:model="videos.{{ $index }}.title" type="text" placeholder="{{ __('lms.video_title_ph') }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                                @error("videos.{$index}.title") <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Mevcut Video Göstergesi --}}
                            @if(!empty($video['existing_path']))
                                <div class="flex items-center gap-2 p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    <span class="text-xs text-green-700 dark:text-green-300 truncate">{{ __('lms.video_existing') }}{{ basename($video['existing_path']) }}</span>
                                </div>
                            @endif

                            {{-- Dosya Yükleme --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                                    {{ !empty($video['existing_path']) ? __('lms.video_upload_new') : __('lms.video_upload_req') }}
                                </label>
                                <input wire:model="videoFiles.{{ $index }}" type="file" accept="video/mp4,video/avi,video/quicktime,video/x-ms-wmv"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 cursor-pointer file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-primary-100 file:text-primary-700 file:cursor-pointer dark:file:bg-primary-900/40 dark:file:text-primary-300" />
                                <div wire:loading wire:target="videoFiles.{{ $index }}" class="flex items-center gap-2 mt-1.5 text-xs text-primary-600 dark:text-primary-400">
                                    <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    {{ __('lms.video_uploading') }}
                                </div>
                                @error("videoFiles.{$index}") <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(count($videos) > 0)
                <p class="text-xs text-gray-500 mt-3">{{ __('lms.video_formats') }}</p>
            @endif
            </div>
        </div>

        {{-- Exam Settings Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('lms.exam_settings') }}</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('lms.exam_duration') }}</label>
                    <input wire:model="exam_duration_minutes" type="number" min="5" max="180" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('lms.passing_score_label') }}</label>
                    <input wire:model="passing_score" type="number" min="1" max="100" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('lms.max_attempts') }}</label>
                    <input wire:model="max_attempts" type="number" min="1" max="10" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                </div>
            </div>
            </div>
        </div>

        {{-- Personnel Assignment Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="p-6">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Personel Atama</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5" id="personnel-summary">
                            {{ count($selectedPersonnel) > 0 ? count($selectedPersonnel) . ' kişi seçildi' : 'Henüz kimse atanmadı' }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2" id="personnel-quick-actions">
                        <button type="button" onclick="personnelSelectAll()"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Tümünü Seç
                        </button>
                        <button type="button" onclick="personnelClearAll()"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Tümünü Kaldır
                        </button>
                    </div>
                </div>

                {{-- Two Column Layout --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                    {{-- ── Left Panel: Departments & Staff ── --}}
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden flex flex-col">
                        {{-- Search --}}
                        <div class="p-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/20">
                            <div class="relative">
                                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" id="dept-search" oninput="personnelSearch(this.value)" placeholder="Departman veya personel ara..."
                                    class="w-full pl-9 pr-3 py-2 text-xs border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>

                        {{-- Department List --}}
                        <div class="overflow-y-auto" style="max-height: 24rem;" id="dept-list">
                            @forelse($departments as $deptIdx => $dept)
                                @php
                                    $deptUserIds  = $dept->users->pluck('id')->toArray();
                                    $deptColors   = ['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#3b82f6','#ef4444','#06b6d4','#84cc16','#f97316'];
                                    $deptColor    = $deptColors[$dept->id % count($deptColors)];
                                    $selectedCnt  = count(array_intersect($deptUserIds, $selectedPersonnel));
                                    $allSelected  = count($deptUserIds) > 0 && $selectedCnt === count($deptUserIds);
                                    $someSelected = $selectedCnt > 0 && !$allSelected;
                                @endphp
                                <div class="dept-block border-b border-gray-100 dark:border-gray-700/60 last:border-b-0"
                                     data-dept-id="{{ $dept->id }}"
                                     data-dept-name="{{ strtolower($dept->name) }}"
                                     data-user-ids="{{ implode(',', $deptUserIds) }}">

                                    {{-- Dept Header --}}
                                    <div class="flex items-center gap-2 px-3 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                        {{-- Indeterminate-capable checkbox via Alpine --}}
                                        <div x-data="{}"
                                             x-effect="
                                                const ids = [{{ implode(',', $deptUserIds) }}];
                                                const sel = $wire.selectedPersonnel ?? [];
                                                const selCnt = ids.filter(id => sel.includes(id)).length;
                                                $el.querySelector('input').indeterminate = selCnt > 0 && selCnt < ids.length;
                                                $el.querySelector('input').checked = ids.length > 0 && selCnt === ids.length;
                                             ">
                                            <input type="checkbox"
                                                   onclick="personnelToggleDept({{ json_encode($deptUserIds) }}, this)"
                                                   class="rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500 cursor-pointer flex-shrink-0">
                                        </div>

                                        {{-- Toggle accordion --}}
                                        <button type="button"
                                                onclick="personnelToggleDeptOpen({{ $dept->id }})"
                                                class="flex-1 flex items-center justify-between text-left gap-2 min-w-0">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $deptColor }};"></span>
                                                <span class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">{{ $dept->name }}</span>
                                            </div>
                                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                                <span class="dept-sel-count text-[10px] px-1.5 py-0.5 rounded-full font-medium transition-colors {{ $selectedCnt > 0 ? 'bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}"
                                                      data-dept="{{ $dept->id }}" data-total="{{ count($deptUserIds) }}">
                                                    {{ $selectedCnt }}/{{ count($deptUserIds) }}
                                                </span>
                                                <svg class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200 dept-chevron" data-dept="{{ $dept->id }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                            </div>
                                        </button>
                                    </div>

                                    {{-- Personnel List (collapsed by default) --}}
                                    <div class="dept-users hidden bg-gray-50/50 dark:bg-gray-700/10" data-dept="{{ $dept->id }}">
                                        @forelse($dept->users as $user)
                                            @php $isPersonSelected = in_array($user->id, $selectedPersonnel); @endphp
                                            <div class="user-row flex items-center gap-2.5 pl-9 pr-3 py-2 cursor-pointer transition-colors {{ $isPersonSelected ? 'bg-primary-50 dark:bg-primary-900/20' : 'hover:bg-gray-100 dark:hover:bg-gray-700/40' }}"
                                                 data-user-id="{{ $user->id }}"
                                                 data-user-name="{{ strtolower($user->first_name . ' ' . $user->last_name) }}"
                                                 data-dept="{{ $dept->id }}"
                                                 onclick="personnelToggleUser({{ $user->id }}, {{ $dept->id }})">
                                                <input type="checkbox"
                                                       class="user-checkbox rounded border-gray-300 dark:border-gray-600 text-primary-600 focus:ring-primary-500 pointer-events-none flex-shrink-0"
                                                       data-user="{{ $user->id }}"
                                                       {{ $isPersonSelected ? 'checked' : '' }}>
                                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-semibold flex-shrink-0 transition-colors {{ $isPersonSelected ? 'bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300' : 'bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300' }}">
                                                    {{ strtoupper(mb_substr($user->first_name ?? 'A', 0, 1) . mb_substr($user->last_name ?? 'A', 0, 1)) }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-xs text-gray-800 dark:text-gray-200 truncate {{ $isPersonSelected ? 'font-semibold' : '' }}">
                                                        {{ $user->first_name }} {{ $user->last_name }}
                                                    </p>
                                                </div>
                                                @if($isPersonSelected)
                                                    <svg class="w-3.5 h-3.5 text-primary-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                @endif
                                            </div>
                                        @empty
                                            <div class="pl-9 pr-3 py-3 text-xs text-gray-400 dark:text-gray-500 italic">Bu departmanda aktif personel bulunmuyor</div>
                                        @endforelse
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-400 dark:text-gray-500">
                                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <p class="text-sm">Aktif departman bulunamadı</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- ── Right Panel: Selected Personnel ── --}}
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden flex flex-col">
                        {{-- Panel header --}}
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/20 flex-shrink-0">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Atananlar (<span id="selected-count">{{ count($selectedPersonnel) }}</span>)
                            </span>
                            <button type="button" onclick="personnelClearAll()"
                                    id="clear-all-btn"
                                    style="{{ count($selectedPersonnel) === 0 ? 'display:none' : '' }}"
                                    class="text-xs text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors">
                                Tümünü Temizle
                            </button>
                        </div>

                        {{-- Content --}}
                        <div class="overflow-y-auto flex-1" style="max-height: 24rem;" id="selected-panel">
                            {{-- Empty state --}}
                            <div id="empty-state" class="{{ count($selectedPersonnel) > 0 ? 'hidden' : '' }} flex flex-col items-center justify-center py-12 text-gray-400 dark:text-gray-500">
                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <p class="text-sm">Henüz kimse atanmadı</p>
                                <p class="text-xs mt-1 text-gray-400">Sol panelden kişi veya departman seçin</p>
                            </div>

                            {{-- Selected chips grouped by department --}}
                            <div id="selected-chips" class="{{ count($selectedPersonnel) === 0 ? 'hidden' : '' }} p-3 space-y-3">
                                @foreach($departments as $dept)
                                    @php
                                        $deptColors  = ['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#3b82f6','#ef4444','#06b6d4','#84cc16','#f97316'];
                                        $deptColor   = $deptColors[$dept->id % count($deptColors)];
                                        $deptSelUsers = $dept->users->filter(fn($u) => in_array($u->id, $selectedPersonnel));
                                    @endphp
                                    <div class="dept-chip-group {{ $deptSelUsers->isEmpty() ? 'hidden' : '' }}"
                                         data-dept="{{ $dept->id }}" id="chip-group-{{ $dept->id }}">
                                        <p class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-1 mb-1.5">{{ $dept->name }}</p>
                                        <div class="space-y-1" id="chip-list-{{ $dept->id }}">
                                            @foreach($dept->users as $user)
                                                <div class="chip-item {{ !in_array($user->id, $selectedPersonnel) ? 'hidden' : '' }} flex items-center gap-2 px-2 py-1.5 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm"
                                                     id="chip-{{ $user->id }}"
                                                     style="border-left: 3px solid {{ $deptColor }}">
                                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-semibold flex-shrink-0"
                                                         style="background-color: {{ $deptColor }}20; color: {{ $deptColor }};">
                                                        {{ strtoupper(mb_substr($user->first_name ?? 'A', 0, 1) . mb_substr($user->last_name ?? 'A', 0, 1)) }}
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-xs font-medium text-gray-800 dark:text-gray-200 truncate">{{ $user->first_name }} {{ $user->last_name }}</p>
                                                        <p class="text-[10px] text-gray-400 dark:text-gray-500 truncate">{{ $dept->name }}</p>
                                                    </div>
                                                    <button type="button"
                                                            onclick="personnelToggleUser({{ $user->id }}, {{ $dept->id }})"
                                                            class="w-5 h-5 flex items-center justify-center rounded text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors flex-shrink-0">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Summary row --}}
                <p id="assignment-summary" class="text-xs text-gray-500 dark:text-gray-400 mt-3 {{ count($selectedPersonnel) === 0 ? 'invisible' : '' }}">
                    <span id="summary-dept-count">0</span> departmandan <span id="summary-person-count">{{ count($selectedPersonnel) }}</span> kişi atandı
                </p>
            </div>
        </div>

        {{-- Questions Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
            <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('lms.questions_section') }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('lms.questions_count', ['count' => count($questions)]) }}</p>
                </div>
                <button type="button" wire:click="addQuestion" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-primary-600 border border-primary-300 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    {{ __('lms.add_question') }}
                </button>
            </div>

            @if(count($questions) === 0)
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p>{{ __('lms.question_empty') }}</p>
                    <p class="text-sm mt-1">{{ __('lms.question_empty_sub') }}</p>
                </div>
            @endif

            <div class="space-y-4">
                @foreach($questions as $index => $question)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4" wire:key="question-{{ $index }}">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('lms.question_label', ['num' => $index + 1]) }}</h4>
                            <div class="flex items-center gap-1">
                                @if($index > 0)
                                    <button type="button" wire:click="moveQuestionUp({{ $index }})" class="p-1 text-gray-400 hover:text-gray-600 rounded" title="{{ __('lms.move_up') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                    </button>
                                @endif
                                @if($index < count($questions) - 1)
                                    <button type="button" wire:click="moveQuestionDown({{ $index }})" class="p-1 text-gray-400 hover:text-gray-600 rounded" title="{{ __('lms.move_down') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </button>
                                @endif
                                <button type="button" wire:click="removeQuestion({{ $index }})" class="p-1 text-red-400 hover:text-red-600 rounded" title="Sil">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Question Text --}}
                        <div class="mb-3">
                            <input wire:model="questions.{{ $index }}.question_text" type="text" placeholder="{{ __('lms.question_text_ph') }}" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                            @error("questions.{$index}.question_text") <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Options --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $key => $label)
                                <div class="flex items-center gap-2">
                                    <label class="flex items-center gap-1.5 flex-shrink-0 cursor-pointer">
                                        <input wire:model="questions.{{ $index }}.correct_option" type="radio" value="{{ $key }}" name="correct_{{ $index }}" class="text-green-600 focus:ring-green-500" />
                                        <span class="text-xs font-bold w-5 h-5 flex items-center justify-center rounded {{ $questions[$index]['correct_option'] === $key ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $label }}</span>
                                    </label>
                                    <input wire:model="questions.{{ $index }}.option_{{ $key }}" type="text" placeholder="{{ __('lms.option_ph', ['letter' => $label]) }}" class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <a href="{{ route('admin.courses.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                {{ __('lms.cancel') }}
            </a>
            <div class="flex gap-3">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">
                        <svg class="w-4 h-4 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        {{ $courseId ? __('lms.save') : __('lms.save') }}
                    </span>
                    <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        {{ __('lms.saving') }}
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>

@script
<script>
    // ── State ──
    window.selectedPersonnelState = @json($selectedPersonnel);

    // ── Toggle individual user ──
    window.personnelToggleUser = function(userId, deptId) {
        const idx = window.selectedPersonnelState.indexOf(userId);
        if (idx === -1) {
            window.selectedPersonnelState.push(userId);
        } else {
            window.selectedPersonnelState.splice(idx, 1);
        }
        _pSyncUserRow(userId);
        _pSyncDeptBadge(deptId);
        _pSyncDeptChipGroup(deptId);
        _pSyncPanel();
    };

    // ── Toggle entire department ──
    window.personnelToggleDept = function(userIds, checkboxEl) {
        const block = checkboxEl.closest('.dept-block');
        const deptId = parseInt(block.dataset.deptId);
        const allSelected = userIds.every(id => window.selectedPersonnelState.includes(id));

        if (allSelected) {
            userIds.forEach(id => {
                const idx = window.selectedPersonnelState.indexOf(id);
                if (idx !== -1) window.selectedPersonnelState.splice(idx, 1);
            });
        } else {
            userIds.forEach(id => {
                if (!window.selectedPersonnelState.includes(id)) window.selectedPersonnelState.push(id);
            });
        }

        userIds.forEach(id => _pSyncUserRow(id));
        _pSyncDeptBadge(deptId);
        _pSyncDeptChipGroup(deptId);
        _pSyncPanel();
    };

    // ── Accordion toggle ──
    window.personnelToggleDeptOpen = function(deptId) {
        const usersEl = document.querySelector(`.dept-users[data-dept="${deptId}"]`);
        const chevron = document.querySelector(`.dept-chevron[data-dept="${deptId}"]`);
        if (!usersEl) return;
        const isHidden = usersEl.classList.contains('hidden');
        usersEl.classList.toggle('hidden', !isHidden);
        if (chevron) chevron.style.transform = isHidden ? 'rotate(180deg)' : '';
    };

    // ── Search ──
    window.personnelSearch = function(query) {
        const q = query.toLowerCase().trim();
        document.querySelectorAll('.dept-block').forEach(block => {
            const deptName = block.dataset.deptName || '';
            const usersEl  = block.querySelector('.dept-users');
            const userRows = block.querySelectorAll('.user-row');
            const deptId   = block.dataset.deptId;
            const chevron  = document.querySelector(`.dept-chevron[data-dept="${deptId}"]`);

            if (!q) {
                block.classList.remove('hidden');
                userRows.forEach(row => row.classList.remove('hidden'));
                if (usersEl) usersEl.classList.add('hidden');
                if (chevron) chevron.style.transform = '';
                return;
            }

            if (deptName.includes(q)) {
                block.classList.remove('hidden');
                userRows.forEach(row => row.classList.remove('hidden'));
                if (usersEl) usersEl.classList.remove('hidden');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
                return;
            }

            let anyMatch = false;
            userRows.forEach(row => {
                const match = (row.dataset.userName || '').includes(q);
                row.classList.toggle('hidden', !match);
                if (match) anyMatch = true;
            });

            block.classList.toggle('hidden', !anyMatch);
            if (anyMatch) {
                if (usersEl) usersEl.classList.remove('hidden');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            }
        });
    };

    // ── Select all ──
    window.personnelSelectAll = function() {
        const affectedDepts = new Set();
        document.querySelectorAll('.dept-block').forEach(block => {
            const deptId  = parseInt(block.dataset.deptId);
            const userIds = (block.dataset.userIds || '').split(',').filter(Boolean).map(Number);
            userIds.forEach(id => {
                if (!window.selectedPersonnelState.includes(id)) window.selectedPersonnelState.push(id);
                _pSyncUserRow(id);
            });
            affectedDepts.add(deptId);
        });
        affectedDepts.forEach(deptId => { _pSyncDeptBadge(deptId); _pSyncDeptChipGroup(deptId); });
        _pSyncPanel();
    };

    // ── Clear all ──
    window.personnelClearAll = function() {
        const affectedDepts = new Set();
        document.querySelectorAll('.user-row').forEach(row => {
            if (window.selectedPersonnelState.includes(parseInt(row.dataset.userId))) {
                affectedDepts.add(parseInt(row.dataset.dept));
            }
        });
        window.selectedPersonnelState = [];
        document.querySelectorAll('.user-row').forEach(row => _pSyncUserRow(parseInt(row.dataset.userId)));
        affectedDepts.forEach(deptId => { _pSyncDeptBadge(deptId); _pSyncDeptChipGroup(deptId); });
        _pSyncPanel();
    };

    // ── Helpers ──

    function _pSyncUserRow(userId) {
        const selected = window.selectedPersonnelState.includes(userId);

        const row = document.querySelector(`.user-row[data-user-id="${userId}"]`);
        if (row) {
            const checkbox = row.querySelector('.user-checkbox');
            const avatar   = row.querySelector('.w-7');
            const nameEl   = row.querySelector('p.text-xs');

            row.classList.toggle('bg-primary-50', selected);
            row.classList.toggle('hover:bg-gray-100', !selected);
            if (checkbox) checkbox.checked = selected;
            if (avatar) {
                avatar.classList.toggle('bg-primary-100', selected);
                avatar.classList.toggle('text-primary-700', selected);
                avatar.classList.toggle('bg-gray-200', !selected);
                avatar.classList.toggle('text-gray-600', !selected);
            }
            if (nameEl) nameEl.classList.toggle('font-semibold', selected);
        }

        const chip = document.getElementById(`chip-${userId}`);
        if (chip) chip.classList.toggle('hidden', !selected);
    }

    function _pSyncDeptBadge(deptId) {
        const block = document.querySelector(`.dept-block[data-dept-id="${deptId}"]`);
        if (!block) return;

        const userIds = (block.dataset.userIds || '').split(',').filter(Boolean).map(Number);
        const total   = userIds.length;
        const selCnt  = userIds.filter(id => window.selectedPersonnelState.includes(id)).length;

        const badge = document.querySelector(`.dept-sel-count[data-dept="${deptId}"]`);
        if (badge) {
            badge.textContent = `${selCnt}/${total}`;
            badge.classList.toggle('bg-primary-100', selCnt > 0);
            badge.classList.toggle('text-primary-700', selCnt > 0);
            badge.classList.toggle('bg-gray-100', selCnt === 0);
            badge.classList.toggle('text-gray-500', selCnt === 0);
        }

        const deptCheckbox = block.querySelector('input[type="checkbox"]');
        if (deptCheckbox && total > 0) {
            deptCheckbox.checked       = selCnt === total;
            deptCheckbox.indeterminate = selCnt > 0 && selCnt < total;
        }
    }

    function _pSyncDeptChipGroup(deptId) {
        const group = document.getElementById(`chip-group-${deptId}`);
        if (!group) return;
        const anyVisible = group.querySelectorAll('.chip-item:not(.hidden)').length > 0;
        group.classList.toggle('hidden', !anyVisible);
    }

    function _pSyncPanel() {
        const count = window.selectedPersonnelState.length;

        const countEl = document.getElementById('selected-count');
        if (countEl) countEl.textContent = count;

        const emptyState      = document.getElementById('empty-state');
        const chipsContainer  = document.getElementById('selected-chips');
        if (emptyState)     emptyState.classList.toggle('hidden', count > 0);
        if (chipsContainer) chipsContainer.classList.toggle('hidden', count === 0);

        const clearBtn = document.getElementById('clear-all-btn');
        if (clearBtn) clearBtn.style.display = count > 0 ? '' : 'none';

        const summary = document.getElementById('assignment-summary');
        if (summary) summary.classList.toggle('invisible', count === 0);

        const personCount = document.getElementById('summary-person-count');
        if (personCount) personCount.textContent = count;

        const deptCount = document.getElementById('summary-dept-count');
        if (deptCount) {
            const deptSet = new Set();
            document.querySelectorAll('.user-row').forEach(row => {
                if (window.selectedPersonnelState.includes(parseInt(row.dataset.userId))) {
                    deptSet.add(row.dataset.dept);
                }
            });
            deptCount.textContent = deptSet.size;
        }

        const summaryEl = document.getElementById('personnel-summary');
        if (summaryEl) summaryEl.textContent = count > 0 ? `${count} kişi seçildi` : 'Henüz kimse atanmadı';
    }
</script>
@endscript
