@extends('layouts.app')

@section('title', 'Students')

@section('content')
    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Directory</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight">Students</h1>
        </div>
        <a href="{{ route('students.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Add student</a>
    </div>

    <div class="mb-5 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('students.index') }}" class="flex flex-wrap gap-3">
            <label for="search" class="sr-only">Search students</label>
            <input id="search" name="search" type="search" value="{{ $search }}" placeholder="Search name, roll number, or email"
                   class="min-w-64 flex-1 rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Search</button>
            @if ($search !== '')
                <a href="{{ route('students.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Clear</a>
            @endif
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Student</th>
                        <th class="px-5 py-3">Roll number</th>
                        <th class="px-5 py-3">Course</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($students as $student)
                        <tr>
                            <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-900">{{ $student->name }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $student->roll_number }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $student->course->name }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $student->email }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-right">
                                <a href="{{ route('students.edit', $student) }}" class="font-semibold text-indigo-700 hover:text-indigo-900">Edit</a>
                                <form method="POST" action="{{ route('students.destroy', $student) }}" class="ml-3 inline" onsubmit="return confirm('Delete this student and their attendance history?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="font-semibold text-rose-700 hover:text-rose-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500">No students found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($students->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">{{ $students->links() }}</div>
        @endif
    </div>
@endsection
