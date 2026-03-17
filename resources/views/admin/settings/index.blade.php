@extends('layouts.admin')
@section('title', __('lms.settings'))
@section('page-title', __('lms.settings'))

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Ayarlar'],
    ]])
@endsection

@section('content')
<div class="space-y-6">
    {{-- Hero Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-gray-800 via-gray-700 to-slate-700 dark:from-gray-900 dark:via-gray-800 dark:to-slate-800 shadow-xl shadow-gray-800/30">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-10 -right-10 w-56 h-56 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-14 -left-10 w-72 h-72 bg-slate-400/5 rounded-full blur-3xl"></div>
            <div class="absolute top-0 right-1/3 w-24 h-24 bg-gray-400/5 rounded-full blur-2xl"></div>
        </div>
        <div class="relative px-6 py-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/10">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-black text-white tracking-tight">Sistem Ayarları</h1>
                <p class="text-gray-300 text-sm mt-0.5">Sistem yapılandırmasını ve tercihlerinizi yönetin</p>
            </div>
        </div>
    </div>

    {{-- Settings Panel Livewire --}}
    @livewire('admin.settings-panel')
</div>
@endsection
