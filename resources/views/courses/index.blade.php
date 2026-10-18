@extends('layouts.app')

@section('title', 'Courses')

@section('content')
    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Academics</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight">Courses</h1>
        </div>
        <a href="{{ route('courses.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Add course</a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                    <tr><th class="px-5 py-3">Course name</th><th class="px-5 py-3">Code</th><th class="px-5 py-3">Students</th><th class="px-5 py-3 text-right">Actions</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($courses as $course)
                        <tr>
                            <td class="px-5 py-4 font-semibold text-slate-900">{{ $course->name }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $course->code }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $course->students_count }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <a href="{{ route('courses.edit', $course) }}" class="font-semibold text-indigo-700 hover:text-indigo-900">Edit</a>
                                <form method="POST" action="{{ route('courses.destroy', $course) }}" class="ml-3 inline" onsubmit="return confirm('Delete this course?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="font-semibold text-rose-700 hover:text-rose-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-slate-500">No courses have been added yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($courses->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">{{ $courses->links() }}</div>
        @endif
    </div>
@endsection
