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
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-primary-600 to-primary-800 dark:from-gray-800 dark:to-gray-900 dark:border dark:border-gray-700 shadow-lg">
        <div class="relative p-6">
            <h2 class="text-xl font-bold text-white">Liderlik Tablosu</h2>
            <p class="text-primary-100 dark:text-gray-400 mt-1 text-sm">En başarılı personelleri keşfedin ve sıralamadaki yerinizi görün.</p>
        </div>
    </div>

    @livewire('staff.leaderboard')
</div>
@endsection
