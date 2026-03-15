@extends('layouts.staff')
@section('title', $course->title)
@section('page-title', 'Eğitim Detayı')

@section('content')
    @livewire('staff.course-flow', ['courseId' => $courseId])
@endsection
