@extends('layouts.staff')
@section('title', 'Liderlik Tablosu')
@section('page-title', 'Liderlik Tablosu')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Liderlik Tablosu'],
    ]])
@endsection

@section('content')
<div class="space-y-6">

    {{-- Hero Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary-700 via-primary-600 to-teal-600 shadow-xl shadow-primary-500/20">
        <div class="absolute -right-10 -top-10 w-48 h-48 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute left-1/3 bottom-0 w-32 h-32 rounded-full bg-white/5 blur-xl"></div>

        <div class="relative px-6 py-7">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 mb-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-sm">
                        <svg class="w-3.5 h-3.5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/></svg>
                        <span class="text-[11px] font-bold text-white/90 uppercase tracking-wide">Canlı Sıralama</span>
                    </div>
                    <h2 class="text-2xl font-black text-white">Liderlik Tablosu</h2>
                    <p class="text-primary-100 mt-1 text-sm">En başarılı personelleri keşfedin ve sıralamadaki yerinizi görün.</p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <div class="flex items-center gap-1.5 bg-white/10 rounded-xl px-4 py-2.5 backdrop-blur-sm" role="status" aria-label="Puan tablosu aktif">
                        <svg class="w-4 h-4 text-yellow-300" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm2.5 3a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                        <span class="text-xs font-bold text-white">Puan Tablosu</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Livewire component --}}
    @livewire('staff.leaderboard')

</div>
@endsection
