@extends('layouts.admin')
@section('title', __('lms.new_course_btn'))
@section('page-title', __('lms.create_course'))

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Eğitimler', 'route' => 'admin.courses.index'],
        ['label' => 'Yeni Eğitim'],
    ]])
@endsection

@section('content')
<div class="space-y-6">
    {{-- Hero Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary-700 via-primary-600 to-teal-600 shadow-xl shadow-primary-500/20">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-10 -right-10 w-56 h-56 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-14 -left-10 w-72 h-72 bg-teal-400/10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-black text-white tracking-tight">{{ __('lms.create_course') }}</h1>
                    <p class="text-primary-100 text-sm mt-0.5">Eğitim bilgilerini doldurun ve kaydedin</p>
                </div>
            </div>
            <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white/15 hover:bg-white/25 backdrop-blur-sm border border-white/20 text-white text-xs font-bold rounded-xl transition-all">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                {{ __('lms.back_to_list') }}
            </a>
        </div>
    </div>

    {{-- Course Form Livewire --}}
    @livewire('admin.course-form')
</div>
@endsection
