@extends('layouts.admin')
@section('title', 'Eğitimler')
@section('page-title', 'Eğitim Yönetimi')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Eğitimler', 'route' => 'admin.courses.index'],
        ['label' => 'Eğitim Listesi'],
    ]])
@endsection

@section('content')
    @livewire('admin.course-table')
@endsection
