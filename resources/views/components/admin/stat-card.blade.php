@props([
    'title',
    'value',
    'color'  => 'primary',
    'icon'   => null,
    'suffix' => '',
])

<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5">
    <div class="flex items-center justify-between mb-3">
        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $title }}</span>
        @if($icon)
            <span class="w-8 h-8 rounded-xl bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 flex items-center justify-center text-{{ $color }}-600 dark:text-{{ $color }}-400">
                {!! $icon !!}
            </span>
        @endif
    </div>
    <div class="text-2xl font-bold text-gray-900 dark:text-white">
        {{ $value }}{{ $suffix }}
    </div>
    @if($slot->isNotEmpty())
        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ $slot }}</div>
    @endif
</div>
