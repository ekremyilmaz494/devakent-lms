@extends('layouts.staff')
@section('title', 'Rozetlerim')
@section('page-title', 'Rozetlerim')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Rozetlerim'],
    ]])
@endsection

@section('content')
<div class="space-y-6">
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-amber-500 to-amber-700 dark:from-gray-800 dark:to-gray-900 dark:border dark:border-gray-700 shadow-lg">
        <div class="relative p-6">
            <h2 class="text-xl font-bold text-white">Rozetlerim</h2>
            <p class="text-amber-100 dark:text-gray-400 mt-1 text-sm">Kazandığınız başarı rozetlerini görüntüleyin.</p>
        </div>
    </div>

    @php
        $userBadges = auth()->user()->badges()->get();
        $allBadges = \App\Models\Badge::where('is_active', true)->get();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($allBadges as $badge)
            @php
                $earned = $userBadges->contains('id', $badge->id);
                $earnedAt = $earned ? $userBadges->firstWhere('id', $badge->id)->pivot->earned_at : null;
                $iconMap = [
                    'rocket' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>',
                    'fire' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z"/>',
                    'academic-cap' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>',
                    'star' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>',
                    'trophy' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M18.75 4.236c.982.143 1.954.317 2.916.52A6.003 6.003 0 0116.27 9.728M18.75 4.236V4.5c0 2.108-.966 3.99-2.48 5.228m0 0a6.003 6.003 0 01-3.77 1.522m0 0a6.003 6.003 0 01-3.77-1.522"/>',
                    'lightning-bolt' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>',
                    'collection' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>',
                ];
                $svg = $iconMap[$badge->icon] ?? $iconMap['star'];
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-xl border {{ $earned ? 'border-amber-200 dark:border-amber-700' : 'border-gray-200 dark:border-gray-700 opacity-50' }} shadow-sm p-5 text-center transition-all hover:shadow-md">
                <div class="w-14 h-14 mx-auto rounded-xl flex items-center justify-center mb-3 {{ $earned ? '' : 'grayscale' }}" style="background-color: {{ $badge->color }}20;">
                    <svg class="w-7 h-7" style="color: {{ $badge->color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $svg !!}</svg>
                </div>
                <h3 class="text-sm font-bold text-gray-800 dark:text-white">{{ $badge->name }}</h3>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">{{ $badge->description }}</p>
                @if($earned)
                    <span class="inline-flex items-center gap-1 mt-2 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Kazanıldı
                    </span>
                @else
                    <span class="inline-block mt-2 px-2.5 py-1 rounded-full text-[10px] font-medium bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">
                        Kilitli
                    </span>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
