@extends('layouts.admin')
@section('title', 'Eğitim Düzenle')
@section('page-title', 'Eğitim Düzenle')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Eğitimler', 'route' => 'admin.courses.index'],
        ['label' => 'Düzenle'],
    ]])
@endsection

@section('content')
    @livewire('admin.course-form', ['courseId' => $course->id])
@endsection
