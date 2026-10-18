@extends('layouts.app')

@section('title', 'Edit student')

@section('content')
    <div class="mb-6">
        <a href="{{ route('students.index') }}" class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">← Students</a>
        <h1 class="mt-3 text-3xl font-bold tracking-tight">Edit student</h1>
    </div>
    @include('students._form', [
        'action' => route('students.update', $student),
        'method' => 'PUT',
        'button' => 'Save changes',
        'student' => $student,
    ])
@endsection
