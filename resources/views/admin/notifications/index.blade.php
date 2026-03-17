@extends('layouts.admin')
@section('title', 'Bildirimler')
@section('page-title', 'Bildirim Yönetimi')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Bildirimler'],
    ]])
@endsection

@section('content')
    @livewire('admin.notification-manager')
@endsection
