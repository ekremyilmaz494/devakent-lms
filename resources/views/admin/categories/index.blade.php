@extends('layouts.admin')
@section('title', 'Kategoriler')
@section('page-title', 'Kategori Yönetimi')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Eğitimler', 'route' => 'admin.courses.index'],
        ['label' => 'Kategoriler'],
    ]])
@endsection

@section('content')
    @livewire('admin.category-table')
@endsection
