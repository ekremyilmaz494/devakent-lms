@extends('layouts.admin')
@section('title', 'Departmanlar')
@section('page-title', 'Departman Yönetimi')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Personel', 'route' => 'admin.staff.index'],
        ['label' => 'Departmanlar'],
    ]])
@endsection

@section('content')
    @livewire('admin.department-table')
@endsection
