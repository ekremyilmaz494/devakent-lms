<div class="space-y-4">
    {{-- Period Filter --}}
    <div class="flex items-center gap-2" role="group" aria-label="Dönem filtresi">
        @foreach(['all' => 'Tüm Zamanlar', 'month' => 'Bu Ay', 'week' => 'Bu Hafta'] as $key => $label)
            <button wire:click="$set('period', '{{ $key }}')"
                aria-pressed="{{ $period === $key ? 'true' : 'false' }}"
                class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors
                {{ $period === $key
                    ? 'bg-primary-500 text-white'
                    : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Podium (İlk 3) --}}
    @if($leaderboard->count() >= 3)
    <div class="grid grid-cols-3 gap-3">
        @foreach($leaderboard->take(3) as $i => $entry)
            @php
                $podiumColors = [
                    0 => ['bg' => 'from-amber-400 to-yellow-500', 'ring' => 'ring-amber-400', 'text' => 'text-amber-600', 'icon' => '🥇'],
                    1 => ['bg' => 'from-gray-300 to-gray-400', 'ring' => 'ring-gray-400', 'text' => 'text-gray-600', 'icon' => '🥈'],
                    2 => ['bg' => 'from-amber-600 to-amber-700', 'ring' => 'ring-amber-600', 'text' => 'text-amber-700', 'icon' => '🥉'],
                ];
                $color = $podiumColors[$i];
                $user = $entry['user'];
                $isCurrentUser = $user->id === auth()->id();
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-xl border {{ $isCurrentUser ? 'border-primary-300 dark:border-primary-600 ring-1 ring-primary-200 dark:ring-primary-700' : 'border-gray-200 dark:border-gray-700' }} p-4 text-center {{ $i === 0 ? 'order-2' : ($i === 1 ? 'order-1' : 'order-3') }}">
                <div class="text-2xl mb-2">{{ $color['icon'] }}</div>
                <div class="w-12 h-12 mx-auto rounded-full bg-gradient-to-br {{ $color['bg'] }} flex items-center justify-center ring-2 {{ $color['ring'] }} ring-offset-2 dark:ring-offset-gray-800 mb-2">
                    <span class="text-sm font-bold text-white">{{ strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}</span>
                </div>
                <p class="text-sm font-semibold text-gray-800 dark:text-white truncate">{{ $user->full_name }}</p>
                <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ $user->department->name ?? '' }}</p>
                <div class="mt-2 flex justify-center gap-3 text-[11px]">
                    <span class="{{ $color['text'] }} dark:text-primary-400 font-bold">{{ $entry['completed_count'] }} eğitim</span>
                    <span class="text-gray-500">{{ $entry['avg_score'] }} ort.</span>
                </div>
                @if($entry['badge_count'] > 0)
                    <div class="mt-1 text-[10px] text-amber-500">{{ $entry['badge_count'] }} rozet</div>
                @endif
            </div>
        @endforeach
    </div>
    @endif

    {{-- Full List --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-400 w-12">#</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-400">Personel</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-400">Eğitim</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-400">Ort. Puan</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-400">Rozet</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($leaderboard as $i => $entry)
                        @php $user = $entry['user']; $isCurrentUser = $user->id === auth()->id(); @endphp
                        <tr class="{{ $isCurrentUser ? 'bg-primary-50/50 dark:bg-primary-900/10' : '' }} hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                            <td class="px-4 py-3 text-center font-bold {{ $i < 3 ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500' }}">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center" aria-hidden="true">
                                        <span class="text-[10px] font-semibold text-white">{{ strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800 dark:text-gray-200 {{ $isCurrentUser ? 'text-primary-600 dark:text-primary-400' : '' }}">{{ $user->full_name }} @if($isCurrentUser) <span class="text-[10px]">(Sen)</span> @endif</p>
                                        <p class="text-[11px] text-gray-500">{{ $user->department->name ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-700 dark:text-gray-200">{{ $entry['completed_count'] }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="{{ $entry['avg_score'] >= 80 ? 'text-emerald-600 dark:text-emerald-400' : ($entry['avg_score'] >= 60 ? 'text-primary-600 dark:text-primary-400' : 'text-gray-600 dark:text-gray-400') }} font-medium">
                                    {{ $entry['avg_score'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($entry['badge_count'] > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                                        <svg class="w-3 h-3" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        {{ $entry['badge_count'] }}
                                    </span>
                                @else
                                    <span class="text-gray-400" aria-label="Rozet yok">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0116.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.003 6.003 0 01-3.77 1.522m0 0a6.003 6.003 0 01-3.77-1.522"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Bu dönem için liderlik tablosunda henüz veri bulunmuyor.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
