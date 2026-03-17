@extends('layouts.admin')
@section('title', __('lms.staff'))
@section('page-title', __('lms.staff_title'))

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Personel', 'route' => 'admin.staff.index'],
        ['label' => 'Personel Listesi'],
    ]])
@endsection

@section('content')
<div class="space-y-6">
    {{-- Hero Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-violet-600 shadow-xl shadow-indigo-500/20">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-10 -right-10 w-56 h-56 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-14 -left-10 w-72 h-72 bg-violet-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/3 w-32 h-32 bg-indigo-300/10 rounded-full blur-2xl"></div>
        </div>
        <div class="relative px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-black text-white tracking-tight">Personel Yönetimi</h1>
                    <p class="text-indigo-100 text-sm mt-0.5">Tüm çalışanları görüntüleyin ve yönetin</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Staff Table Livewire --}}
    @livewire('admin.staff-table')
</div>
@endsection
