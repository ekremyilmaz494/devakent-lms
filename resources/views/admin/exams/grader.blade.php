@extends('layouts.admin')
@section('title', 'Sınav Değerlendirme')
@section('page-title', 'Sınav Değerlendirme')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Sınavlar'],
        ['label' => 'Açık Uçlu Değerlendirme'],
    ]])
@endsection

@section('content')
<div class="space-y-6">

    {{-- Hero Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-amber-600 via-amber-500 to-orange-500 shadow-xl shadow-amber-500/20">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-10 -right-10 w-56 h-56 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-14 -left-10 w-72 h-72 bg-orange-400/10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-black text-white tracking-tight">Açık Uçlu Soru Değerlendirme</h1>
                    <p class="text-amber-100 text-sm mt-0.5">Manuel değerlendirme bekleyen sınavları puanlayın</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Livewire Component --}}
    @livewire('admin.exam-grader')

</div>
@endsection
