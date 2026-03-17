@extends('layouts.admin')
@section('title', __('lms.activity_log'))
@section('page-title', __('lms.activity_log'))

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'İşlem Geçmişi'],
    ]])
@endsection

@section('content')
<div class="space-y-6">
    {{-- Hero Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-rose-700 via-rose-600 to-pink-600 shadow-xl shadow-rose-500/20">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-10 -right-10 w-56 h-56 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-14 -left-10 w-72 h-72 bg-pink-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 w-28 h-28 bg-rose-300/10 rounded-full blur-xl"></div>
        </div>
        <div class="relative px-6 py-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/20">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-black text-white tracking-tight">İşlem Geçmişi</h1>
                <p class="text-rose-100 text-sm mt-0.5">Sistemdeki tüm kullanıcı aktivitelerini izleyin</p>
            </div>
        </div>
    </div>

    {{-- Activity Log Viewer Livewire --}}
    @livewire('admin.activity-log-viewer')
</div>
@endsection
