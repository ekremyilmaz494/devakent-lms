@extends('layouts.admin')
@section('title', $course->title . ' — Eğitim Detayı')
@section('page-title', 'Eğitim Detayı')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Eğitimler', 'route' => 'admin.courses.index'],
        ['label' => $course->title],
        ['label' => 'Detay'],
    ]])
@endsection

@section('content')
<div class="space-y-6">
    {{-- Hero Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary-700 via-primary-600 to-teal-600 shadow-xl shadow-primary-500/20">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-10 -right-16 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-16 -left-10 w-80 h-80 bg-teal-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 right-1/4 w-32 h-32 bg-primary-300/10 rounded-full blur-2xl"></div>
        </div>
        <div class="relative px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white tracking-tight">{{ $course->title }}</h2>
                    <p class="text-primary-100 text-sm mt-0.5">
                        {{ $course->category?->name ?? 'Kategorisiz' }}
                        @if($course->is_mandatory)
                            &middot; <span class="font-bold">Zorunlu Eğitim</span>
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('admin.courses.edit', $course) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/25 text-white text-xs font-bold rounded-xl transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Düzenle
                </a>
                <a href="{{ route('admin.courses.index') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white/15 hover:bg-white/25 backdrop-blur-sm border border-white/20 text-white text-xs font-bold rounded-xl transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Eğitimlere Dön
                </a>
            </div>
        </div>
    </div>

    {{-- Detail Livewire Component --}}
    @livewire('admin.course-detail', ['courseId' => $course->id])
</div>
@endsection
