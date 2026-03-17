@extends('layouts.staff')
@section('title', 'Bildirimler')
@section('page-title', 'Bildirimler')

@section('content')
<div class="space-y-5">

    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    Toplam <span class="font-bold text-gray-800 dark:text-white">{{ $stats['total'] }}</span> bildirim
                </span>
            </div>
            @if($stats['unread'] > 0)
                <span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-1 rounded-full bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                    {{ $stats['unread'] }} okunmamış
                </span>
            @endif
        </div>

        @if($stats['unread'] > 0)
        <form action="{{ route('staff.notifications.readAll') }}" method="POST">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 text-white shadow-md shadow-primary-500/25 hover:shadow-lg hover:shadow-primary-500/35 transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                Tümünü Okundu İşaretle
            </button>
        </form>
        @endif
    </div>

    {{-- Notification List --}}
    @if($recipients->count() > 0)
    <div class="space-y-2">
        @foreach($recipients as $recipient)
        @php
            $typeColor = match($recipient->notification->type) {
                'warning' => ['bar' => 'from-amber-400 to-amber-600', 'icon_bg' => 'bg-amber-100 dark:bg-amber-900/30', 'icon_text' => 'text-amber-600 dark:text-amber-400'],
                'success' => ['bar' => 'from-emerald-400 to-emerald-600', 'icon_bg' => 'bg-emerald-100 dark:bg-emerald-900/30', 'icon_text' => 'text-emerald-600 dark:text-emerald-400'],
                default   => ['bar' => 'from-blue-400 to-blue-600', 'icon_bg' => 'bg-blue-100 dark:bg-blue-900/30', 'icon_text' => 'text-blue-600 dark:text-blue-400'],
            };
        @endphp
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border overflow-hidden transition-all duration-200
            {{ !$recipient->is_read
                ? 'border-primary-200 dark:border-primary-800 shadow-sm shadow-primary-500/5'
                : 'border-gray-100 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-600' }}">

            {{-- Left accent --}}
            @if(!$recipient->is_read)
            <div class="absolute left-0 top-0 bottom-0 w-0.5 bg-gradient-to-b {{ $typeColor['bar'] }}"></div>
            @endif

            <div class="p-4 sm:p-5 {{ !$recipient->is_read ? 'pl-5 sm:pl-6' : '' }}">
                <div class="flex items-start gap-3.5">

                    {{-- Type icon --}}
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $typeColor['icon_bg'] }}">
                        @if($recipient->notification->type === 'warning')
                            <svg class="w-5 h-5 {{ $typeColor['icon_text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        @elseif($recipient->notification->type === 'success')
                            <svg class="w-5 h-5 {{ $typeColor['icon_text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                            <svg class="w-5 h-5 {{ $typeColor['icon_text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <h4 class="text-sm font-{{ !$recipient->is_read ? 'bold' : 'semibold' }} text-gray-800 dark:text-white truncate">
                                {{ $recipient->notification->title }}
                            </h4>
                            @if(!$recipient->is_read)
                                <span class="flex-shrink-0 w-2 h-2 rounded-full bg-primary-500"></span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed">
                            {{ $recipient->notification->message }}
                        </p>
                        <div class="flex items-center gap-2 mt-2 text-xs text-gray-400 dark:text-gray-500">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $recipient->notification->created_at->diffForHumans() }}</span>
                            @if($recipient->notification->creator)
                                <span class="text-gray-300 dark:text-gray-600">&middot;</span>
                                <span>{{ $recipient->notification->creator->full_name }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Action --}}
                    @if(!$recipient->is_read)
                    <form action="{{ route('staff.notifications.read', $recipient->id) }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <button type="submit"
                                title="Okundu işaretle"
                                class="p-2 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-xl transition-colors group/btn">
                            <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 group-hover/btn:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </form>
                    @else
                    <span class="flex-shrink-0 flex items-center gap-1 text-[11px] text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700/50 px-2 py-1 rounded-lg">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Okundu
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 p-14 text-center">
        <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-700/50 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        </div>
        <p class="font-bold text-gray-700 dark:text-gray-300">Bildirim yok</p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Yeni bildirimler geldiğinde burada görünecek.</p>
    </div>
    @endif

</div>
@endsection
