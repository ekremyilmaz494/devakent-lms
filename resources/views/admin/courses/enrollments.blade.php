@extends('layouts.admin')
@section('title', 'Personel Atamaları — ' . $course->title)
@section('page-title', __('lms.enrolled_staff'))

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Eğitimler', 'route' => 'admin.courses.index'],
        ['label' => $course->title],
        ['label' => 'Personel Atamaları'],
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white tracking-tight">{{ $course->title }}</h2>
                    <p class="text-primary-100 text-sm mt-0.5">Eğitime personel atayın veya mevcut kayıtları yönetin</p>
                </div>
            </div>
            <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white/15 hover:bg-white/25 backdrop-blur-sm border border-white/20 text-white text-xs font-bold rounded-xl transition-all flex-shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Eğitimlere Dön
            </a>
        </div>
    </div>

    {{-- Enrollment Livewire --}}
    @livewire('admin.course-enrollment', ['courseId' => $course->id])
</div>
@endsection
