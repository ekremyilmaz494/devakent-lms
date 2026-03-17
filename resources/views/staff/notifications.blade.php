@extends('layouts.staff')
@section('title', 'Bildirimler')
@section('page-title', 'Bildirimler')

@section('content')
<div class="space-y-6">

    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-600 dark:text-gray-400">
                Toplam <span class="font-semibold text-gray-800 dark:text-white">{{ $stats['total'] }}</span> bildirim
            </span>
            @if($stats['unread'] > 0)
                <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">
                    {{ $stats['unread'] }} okunmamış
                </span>
            @endif
        </div>

        @if($stats['unread'] > 0)
        <form action="{{ route('staff.notifications.readAll') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Tümünü Okundu İşaretle
            </button>
        </form>
        @endif
    </div>

    {{-- Notification List --}}
    @if($recipients->count() > 0)
    <div class="space-y-2">
        @foreach($recipients as $recipient)
        <div class="bg-white dark:bg-gray-800 rounded-xl border overflow-hidden transition-all
            {{ !$recipient->is_read ? 'border-primary-200 dark:border-primary-800 shadow-sm' : 'border-gray-200 dark:border-gray-700' }}">
            <div class="h-1.5
                @if($recipient->notification->type === 'warning') bg-gradient-to-r from-amber-400 to-amber-600
                @elseif($recipient->notification->type === 'success') bg-gradient-to-r from-emerald-400 to-emerald-600
                @else bg-gradient-to-r from-blue-400 to-blue-600
                @endif"></div>
            <div class="p-4">
                <div class="flex items-start gap-3">
                    {{-- Type Icon --}}
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5
                        @if($recipient->notification->type === 'warning') bg-amber-100 dark:bg-amber-900/30
                        @elseif($recipient->notification->type === 'success') bg-emerald-100 dark:bg-emerald-900/30
                        @else bg-blue-100 dark:bg-blue-900/30
                        @endif">
                        @if($recipient->notification->type === 'warning')
                            <svg class="w-4.5 h-4.5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        @elseif($recipient->notification->type === 'success')
                            <svg class="w-4.5 h-4.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                            <svg class="w-4.5 h-4.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <h4 class="text-sm font-semibold text-gray-800 dark:text-white {{ !$recipient->is_read ? '' : 'font-normal' }}">
                                {{ $recipient->notification->title }}
                            </h4>
                            @if(!$recipient->is_read)
                                <span class="w-2 h-2 rounded-full bg-primary-500 flex-shrink-0"></span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">{{ $recipient->notification->message }}</p>
                        <div class="flex items-center gap-3 mt-2 text-xs text-gray-400 dark:text-gray-500">
                            <span>{{ $recipient->notification->created_at->diffForHumans() }}</span>
                            @if($recipient->notification->creator)
                                <span>&middot; {{ $recipient->notification->creator->full_name }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    @if(!$recipient->is_read)
                    <form action="{{ route('staff.notifications.read', $recipient->id) }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <button type="submit" class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Okundu işaretle">
                            <svg class="w-4 h-4 text-gray-400 hover:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </form>
                    @else
                    <span class="text-xs text-gray-400 dark:text-gray-500 flex-shrink-0">Okundu</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white dark:bg-gray-800 rounded-xl p-12 border border-gray-200 dark:border-gray-700 text-center">
        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        </div>
        <p class="text-gray-600 dark:text-gray-400 font-medium">Henüz bildiriminiz bulunmuyor</p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Yeni bildirimler geldiğinde burada görünecektir.</p>
    </div>
    @endif
</div>
@endsection
