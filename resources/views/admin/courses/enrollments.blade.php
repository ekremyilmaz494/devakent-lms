@extends('layouts.admin')
@section('title', 'Personel Atamaları — ' . $course->title)
@section('page-title', 'Personel Atamaları')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Eğitimler', 'route' => 'admin.courses.index'],
        ['label' => $course->title],
        ['label' => 'Personel Atamaları'],
    ]])
@endsection

@section('content')
<div class="space-y-6">
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-primary-600 to-primary-800 dark:from-gray-800 dark:to-gray-900 dark:border dark:border-gray-700 shadow-lg">
        <div class="relative p-6">
            <h2 class="text-xl font-bold text-white">{{ $course->title }}</h2>
            <p class="text-primary-100 dark:text-gray-400 mt-1 text-sm">Eğitime personel atayın veya mevcut kayıtları yönetin.</p>
        </div>
    </div>

    @livewire('admin.course-enrollment', ['courseId' => $course->id])
</div>
@endsection
