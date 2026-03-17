<div class="animate-pulse space-y-6 p-6">
    {{-- Tab nav placeholder --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex gap-4">
            @for($i = 0; $i < 5; $i++)
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-24"></div>
            @endfor
        </div>
    </div>
    {{-- Stat cards placeholder --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @for($i = 0; $i < 4; $i++)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5">
                <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-16 mb-3"></div>
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
            </div>
        @endfor
    </div>
    {{-- Table placeholder --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5">
        <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-48 mb-6"></div>
        @for($i = 0; $i < 8; $i++)
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full mb-3"></div>
        @endfor
    </div>
</div>
