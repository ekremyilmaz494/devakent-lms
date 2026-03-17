@extends('layouts.admin')
@section('title', 'İşlem Geçmişi')
@section('page-title', 'İşlem Geçmişi')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'İşlem Geçmişi'],
    ]])
@endsection

@section('content')
    @livewire('admin.activity-log-viewer')
@endsection
