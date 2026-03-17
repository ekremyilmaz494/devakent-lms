@extends('layouts.admin')
@section('title', 'Ayarlar')
@section('page-title', 'Sistem Ayarları')

@section('breadcrumb')
    @include('layouts.partials.breadcrumb', ['items' => [
        ['label' => 'Ayarlar'],
    ]])
@endsection

@section('content')
    @livewire('admin.settings-panel')
@endsection
