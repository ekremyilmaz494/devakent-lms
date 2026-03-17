<div>
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        {{-- Send Form (2/5) --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center">
                            <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Bildirim Gönder</h3>
                    </div>
                </div>

                <form wire:submit="send" class="p-5 space-y-4">
                    {{-- Title --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-medium text-gray-700 dark:text-gray-400">Başlık</label>
                            <span class="text-[11px] text-gray-600">{{ strlen($title) }}/100</span>
                        </div>
                        <input wire:model="title" type="text" maxlength="100" placeholder="Bildirim başlığı"
                            class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500 placeholder-gray-400" />
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Message --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-medium text-gray-700 dark:text-gray-400">Mesaj</label>
                            <span class="text-[11px] text-gray-600">{{ strlen($message) }}/500</span>
                        </div>
                        <textarea wire:model="message" rows="4" maxlength="500" placeholder="Bildirim mesajı..."
                            class="w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500 placeholder-gray-400 resize-none"></textarea>
                        @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Type --}}
                    <div>
                        <label class="text-xs font-medium text-gray-700 dark:text-gray-400 mb-2 block">Tür</label>
                        <div class="flex gap-2">
                            @foreach(['info' => ['Bilgi', 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800'], 'warning' => ['Uyarı', 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800'], 'success' => ['Başarı', 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800']] as $key => $info)
                                <label class="flex-1 cursor-pointer">
                                    <input wire:model="type" type="radio" value="{{ $key }}" class="sr-only peer" />
                                    <div class="text-center px-3 py-2 rounded-lg border text-xs font-semibold transition-all peer-checked:ring-2 peer-checked:ring-primary-500 {{ $info[1] }}">
                                        {{ $info[0] }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Target --}}
                    <div>
                        <label class="text-xs font-medium text-gray-700 dark:text-gray-400 mb-2 block">Alıcılar</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input wire:model.live="target_type" type="radio" value="all" class="text-primary-600 focus:ring-primary-500" />
                                <span class="text-sm text-gray-700 dark:text-gray-300">Tüm Personel</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input wire:model.live="target_type" type="radio" value="department" class="text-primary-600 focus:ring-primary-500" />
                                <span class="text-sm text-gray-700 dark:text-gray-300">Departman</span>
                            </label>
                        </div>

                        @if($target_type === 'department')
                            <select wire:model="target_department_id" class="mt-2 w-full border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Departman Seçin</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            @error('target_department_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm disabled:opacity-50" wire:loading.attr="disabled" wire:target="send">
                        <span wire:loading.remove wire:target="send">Bildirim Gönder</span>
                        <span wire:loading wire:target="send" class="inline-flex items-center gap-1.5 justify-center">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Gönderiliyor...
                        </span>
                    </button>
                </form>
            </div>
        </div>

        {{-- History Table (3/5) --}}
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                <div class="h-1.5 bg-gradient-to-r from-primary-400 to-primary-600"></div>
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Gönderim Geçmişi</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50/80 dark:bg-gray-700/40 border-b border-gray-200 dark:border-gray-700">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Bildirim</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Tür</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Alıcılar</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Tarih</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-400 uppercase tracking-wider">Okunma</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($notifications as $notif)
                                @php
                                    $readRate = $notif->recipients_count > 0 ? round($notif->read_count / $notif->recipients_count * 100) : 0;
                                    $typeColors = match($notif->type) {
                                        'info' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                        'warning' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                        'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                        default => 'bg-gray-50 text-gray-700 dark:bg-gray-700 dark:text-gray-400',
                                    };
                                    $typeLabel = match($notif->type) {
                                        'info' => 'Bilgi',
                                        'warning' => 'Uyarı',
                                        'success' => 'Başarı',
                                        default => $notif->type,
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $notif->title }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $notif->message }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $typeColors }}">
                                            {{ $typeLabel }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="text-xs text-gray-600 dark:text-gray-300">
                                            @if($notif->target_type === 'all')
                                                {{ $notif->recipients_count }} kişi
                                            @else
                                                {{ $notif->targetDepartment?->name ?? '—' }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">
                                        {{ $notif->created_at->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <div class="w-14 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full bg-primary-500" style="width: {{ $readRate }}%"></div>
                                            </div>
                                            <span class="text-[11px] font-semibold text-gray-600">%{{ $readRate }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center text-sm text-gray-600">
                                        Henüz bildirim gönderilmemiş
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($notifications->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
