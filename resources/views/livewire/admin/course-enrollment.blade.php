<div>
    {{-- Mevcut Kayıtlar --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white">{{ __('lms.enrolled_staff') }}</h3>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400">{{ $course->enrollments_count }}</span>
            </div>
            <button wire:click="openModal" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold bg-gradient-to-r from-primary-500 to-primary-700 text-white rounded-lg hover:from-primary-600 hover:to-primary-800 transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                {{ __('lms.assign_staff') }}
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-5 py-3 text-left font-semibold text-gray-700 dark:text-gray-400">{{ __('lms.enrolled_staff') }}</th>
                        <th class="px-5 py-3 text-left font-semibold text-gray-700 dark:text-gray-400">{{ __('lms.target_department') }}</th>
                        <th class="px-5 py-3 text-center font-semibold text-gray-700 dark:text-gray-400">{{ __('lms.loading') }}</th>
                        <th class="px-5 py-3 text-center font-semibold text-gray-700 dark:text-gray-400">{{ __('lms.enrollment_date') }}</th>
                        <th class="px-5 py-3 text-center font-semibold text-gray-700 dark:text-gray-400">{{ __('lms.remove_enrollment') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($enrolledUsers as $enrollment)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                                        <span class="text-[10px] font-semibold text-white">{{ strtoupper(substr($enrollment->user->first_name ?? '', 0, 1) . substr($enrollment->user->last_name ?? '', 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-gray-200">{{ $enrollment->user->full_name ?? '—' }}</p>
                                        <p class="text-[11px] text-gray-500">{{ $enrollment->user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $enrollment->user->department->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-center">
                                @php
                                    $map = [
                                        'not_started' => ['Başlanmadı', 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'],
                                        'in_progress' => ['Devam Ediyor', 'bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400'],
                                        'completed' => ['Tamamlandı', 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400'],
                                        'failed' => ['Başarısız', 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400'],
                                    ];
                                    $s = $map[$enrollment->status] ?? $map['not_started'];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium {{ $s[1] }}">{{ $s[0] }}</span>
                            </td>
                            <td class="px-5 py-3 text-center text-gray-500 text-[12px]">{{ $enrollment->created_at->format('d.m.Y') }}</td>
                            <td class="px-5 py-3 text-center">
                                @if($enrollment->status === 'not_started')
                                    <button wire:click="removeEnrollment({{ $enrollment->id }})" wire:confirm="{{ __('lms.enrollment_remove_confirm') }}" class="text-red-500 hover:text-red-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @else
                                    <span class="text-gray-300 dark:text-gray-600">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400">{{ __('lms.enrollment_empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Personel Atama Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data x-init="$el.focus()">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[80vh] flex flex-col">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ __('lms.assign_staff') }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">{{ $course->title }}</p>
            </div>

            <div class="px-6 py-3 border-b border-gray-200 dark:border-gray-700">
                <div class="flex gap-3">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('lms.search_staff_ph') }}" class="flex-1 text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    <select wire:model.live="filterDepartment" class="text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                        <option value="">{{ __('lms.all_departments') }}</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-3">
                {{-- Departman Bazlı Toplu Atama --}}
                <div class="mb-3 flex flex-wrap gap-2">
                    @foreach($departments as $dept)
                        <button wire:click="enrollDepartment({{ $dept->id }})" wire:confirm="{{ __('lms.assign_dept_confirm') }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-[11px] font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $dept->name }}
                        </button>
                    @endforeach
                </div>

                <div class="space-y-2">
                    @forelse($availableUsers as $user)
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors">
                            <input type="checkbox" wire:model="selectedUsers" value="{{ $user->id }}" class="rounded border-gray-300 dark:border-gray-600 text-primary-500 focus:ring-primary-500">
                            <div class="flex items-center gap-2 flex-1">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                                    <span class="text-[10px] font-semibold text-white">{{ strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $user->full_name }}</p>
                                    <p class="text-[11px] text-gray-500">{{ $user->department->name ?? '—' }} · {{ $user->email }}</p>
                                </div>
                            </div>
                        </label>
                    @empty
                        <p class="text-center text-sm text-gray-500 py-4">{{ __('lms.no_assignable_staff') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <span class="text-xs text-gray-500">{{ count($selectedUsers) }} {{ __('lms.selected_staff_count') }}</span>
                <div class="flex gap-3">
                    <button wire:click="$set('showModal', false)" class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">{{ __('lms.cancel') }}</button>
                    <button wire:click="enrollSelected" class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-primary-500 to-primary-700 rounded-lg hover:from-primary-600 hover:to-primary-800 transition-all" @if(empty($selectedUsers)) disabled @endif>
                        {{ __('lms.assign_selected') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
