@extends('layouts.admin')
@section('title', __('lms.departments'))
@section('page-title', __('lms.departments_title'))

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Personel', 'route' => 'admin.staff.index'],
        ['label' => 'Departmanlar'],
    ]])
@endsection

@section('content')
<div class="space-y-6">
    {{-- Hero Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-cyan-700 via-cyan-600 to-teal-600 shadow-xl shadow-cyan-500/20">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-10 -right-10 w-56 h-56 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-14 -left-10 w-72 h-72 bg-teal-400/10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-black text-white tracking-tight">Departman Yönetimi</h1>
                    <p class="text-cyan-100 text-sm mt-0.5">Organizasyon birimlerini görüntüleyin ve yönetin</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Department Table Livewire --}}
    @livewire('admin.department-table')
</div>
@endsection
