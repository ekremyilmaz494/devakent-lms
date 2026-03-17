@extends('layouts.admin')
@section('title', __('lms.courses'))
@section('page-title', __('lms.course_list_title'))

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Eğitimler', 'route' => 'admin.courses.index'],
        ['label' => 'Eğitim Listesi'],
    ]])
@endsection

@section('content')
<div class="space-y-6">
    {{-- Hero Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary-700 via-primary-600 to-teal-600 shadow-xl shadow-primary-500/20">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-10 -right-10 w-56 h-56 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-14 -left-10 w-72 h-72 bg-teal-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 w-32 h-32 bg-primary-300/10 rounded-full blur-2xl"></div>
        </div>
        <div class="relative px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-black text-white tracking-tight">Eğitim Yönetimi</h1>
                    <p class="text-primary-100 text-sm mt-0.5">Tüm eğitimleri görüntüleyin ve yönetin</p>
                </div>
            </div>
            <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/15 hover:bg-white/25 backdrop-blur-sm border border-white/20 text-white text-sm font-bold rounded-xl transition-all hover:shadow-lg flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Yeni Eğitim
            </a>
        </div>
    </div>

    {{-- Course Table Livewire --}}
    @livewire('admin.course-table')
</div>
@endsection
