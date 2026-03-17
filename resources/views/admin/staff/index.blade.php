@extends('layouts.admin')
@section('title', 'Personel Yönetimi')
@section('page-title', 'Personel Yönetimi')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Personel', 'route' => 'admin.staff.index'],
        ['label' => 'Personel Listesi'],
    ]])
@endsection

@section('content')
    @livewire('admin.staff-table')
@endsection
