@extends('layouts.staff')
@section('title', 'Ana Sayfa')
@section('page-title', 'Ana Sayfa')

@section('content')
<div class="space-y-6">
    {{-- Hoşgeldin Banner --}}
    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl p-6 text-white">
        <h2 class="text-2xl font-bold">Hosgeldin, {{ $user->first_name }}!</h2>
        <p class="mt-1 text-emerald-100">{{ $user->title ?? 'Personel' }} &middot; {{ $user->department?->name ?? 'Departman atanmamis' }}</p>
    </div>

    {{-- Özet Kartları --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Tamamlanan</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $enrollments->where('status', 'completed')->count() }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Devam Eden</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $enrollments->where('status', 'in_progress')->count() }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Baslanmamis</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $enrollments->where('status', 'not_started')->count() }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Toplam Egitim</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $enrollments->count() }}</p>
        </div>
    </div>

    {{-- Eğitim Kartları --}}
    @if($enrollments->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($enrollments->take(4) as $enrollment)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="h-1 {{ $enrollment->course->category?->color ? '' : 'bg-blue-500' }}" style="background-color: {{ $enrollment->course->category?->color ?? '#3B82F6' }}"></div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">{{ $enrollment->course->category?->name ?? 'Genel' }}</span>
                    <span class="text-xs font-medium px-2 py-1 rounded-full
                        @if($enrollment->status === 'completed') bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-300
                        @elseif($enrollment->status === 'in_progress') bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300
                        @elseif($enrollment->status === 'failed') bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-300
                        @else bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300
                        @endif">
                        @if($enrollment->status === 'completed') Tamamlandi
                        @elseif($enrollment->status === 'in_progress') Devam Ediyor
                        @elseif($enrollment->status === 'failed') Basarisiz
                        @else Baslanmadi
                        @endif
                    </span>
                </div>
                <h3 class="font-semibold text-gray-800 dark:text-white">{{ $enrollment->course->title }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Son tarih: {{ $enrollment->course->end_date->format('d.m.Y') }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white dark:bg-gray-800 rounded-xl p-12 border border-gray-200 dark:border-gray-700 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
        <p class="mt-4 text-gray-500 dark:text-gray-400">Henuz atanmis bir egitim bulunmuyor.</p>
    </div>
    @endif
</div>
@endsection
