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

    {{-- Hero Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 shadow-xl shadow-amber-500/20">
        <div class="absolute -right-8 -top-8 w-40 h-40 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute right-16 bottom-0 w-28 h-28 rounded-full bg-white/10 blur-xl"></div>
        <div class="relative px-6 py-7">
            @php
                $userBadges = auth()->user()->badges()->get();
                $allBadges = \App\Models\Badge::where('is_active', true)->get();
                $earnedCount = $userBadges->count();
                $totalCount = $allBadges->count();
            @endphp
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 mb-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-sm">
                        <svg class="w-3.5 h-3.5 text-amber-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="text-[11px] font-bold text-white/90 uppercase tracking-wide">Başarı Rozetleri</span>
                    </div>
                    <h2 class="text-2xl font-black text-white">{{ $earnedCount }} / {{ $totalCount }} rozet kazanıldı</h2>
                    <p class="text-amber-100 mt-1 text-sm">Eğitimleri tamamlayarak yeni rozetler kazanın.</p>
                </div>
                <div class="flex-shrink-0 text-right">
                    @php $badgePct = $totalCount > 0 ? round($earnedCount / $totalCount * 100) : 0; @endphp
                    <span class="text-4xl font-black text-white">%{{ $badgePct }}</span>
                    <p class="text-xs text-amber-100 mt-0.5">Tamamlanma oranı</p>
                </div>
            </div>
            @if($totalCount > 0)
            <div class="mt-5">
                <div class="w-full h-2 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full transition-all duration-700" style="width: {{ $badgePct }}%"></div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Badge Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">
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
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border transition-all duration-300
                {{ $earned
                    ? 'border-amber-200 dark:border-amber-700/50 shadow-md shadow-amber-500/10 hover:shadow-xl hover:shadow-amber-500/15 hover:-translate-y-0.5'
                    : 'border-gray-100 dark:border-gray-700 opacity-50 hover:opacity-60' }}
                p-5 text-center">

                {{-- Earned glow --}}
                @if($earned)
                <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-amber-400/5 to-orange-400/5 pointer-events-none"></div>
                @endif

                {{-- Icon --}}
                <div class="relative w-16 h-16 mx-auto mb-3">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center {{ $earned ? '' : 'grayscale' }}"
                         style="background: linear-gradient(135deg, {{ $badge->color }}22, {{ $badge->color }}44);">
                        <svg class="w-8 h-8" style="color: {{ $badge->color }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $svg !!}
                        </svg>
                    </div>
                    @if($earned)
                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-amber-400 rounded-full flex items-center justify-center shadow-md shadow-amber-400/50">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    @endif
                </div>

                <h3 class="text-sm font-bold text-gray-800 dark:text-white leading-snug">{{ $badge->name }}</h3>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 leading-relaxed line-clamp-2">{{ $badge->description }}</p>

                @if($earned)
                    <div class="mt-3 pt-3 border-t border-amber-100 dark:border-amber-800/30">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-600 dark:text-amber-400">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            Kazanıldı
                            @if($earnedAt)
                                &middot; {{ \Carbon\Carbon::parse($earnedAt)->format('d.m.Y') }}
                            @endif
                        </span>
                    </div>
                @else
                    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700/50">
                        <span class="inline-flex items-center gap-1 text-[10px] font-medium text-gray-400 dark:text-gray-500">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Kilitli
                        </span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

</div>
@endsection
