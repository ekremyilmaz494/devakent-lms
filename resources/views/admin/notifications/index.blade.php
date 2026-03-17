@extends('layouts.admin')
@section('title', __('lms.notifications'))
@section('page-title', __('lms.notifications'))

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Bildirimler'],
    ]])
@endsection

@section('content')
<div class="space-y-6">
    {{-- Hero Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-sky-700 via-sky-600 to-blue-600 shadow-xl shadow-sky-500/20">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-10 -right-10 w-56 h-56 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-14 -left-10 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 right-1/3 w-24 h-24 bg-sky-300/10 rounded-full blur-xl"></div>
        </div>
        <div class="relative px-6 py-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-black text-white tracking-tight">Bildirim Yönetimi</h1>
                <p class="text-sky-100 text-sm mt-0.5">Sistem bildirimlerini gönderin ve yönetin</p>
            </div>
        </div>
    </div>

    {{-- Notification Manager Livewire --}}
    @livewire('admin.notification-manager')
</div>
@endsection
