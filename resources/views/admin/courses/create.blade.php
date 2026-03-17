@extends('layouts.admin')
@section('title', 'Yeni Eğitim')
@section('page-title', 'Yeni Eğitim Oluştur')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Eğitimler', 'route' => 'admin.courses.index'],
        ['label' => 'Yeni Eğitim'],
    ]])
@endsection

@section('content')
    @livewire('admin.course-form')
@endsection
