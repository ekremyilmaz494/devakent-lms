@extends('layouts.admin')
@section('title', 'Raporlar')
@section('page-title', 'Raporlar')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Raporlar'],
    ]])
@endsection

@section('content')
    @livewire('admin.report-dashboard')
@endsection
