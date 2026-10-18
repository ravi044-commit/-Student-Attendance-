@extends('layouts.app')

@section('title', 'Edit course')

@section('content')
    <div class="mb-6">
        <a href="{{ route('courses.index') }}" class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">← Courses</a>
        <h1 class="mt-3 text-3xl font-bold tracking-tight">Edit course</h1>
    </div>
    @include('courses._form', ['action' => route('courses.update', $course), 'method' => 'PUT', 'button' => 'Save changes', 'course' => $course])
@endsection
