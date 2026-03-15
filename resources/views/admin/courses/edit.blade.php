@extends('layouts.admin')
@section('title', 'Eğitim Düzenle')
@section('page-title', 'Eğitim Düzenle')

@section('content')
    @livewire('admin.course-form', ['courseId' => $course->id])
@endsection
