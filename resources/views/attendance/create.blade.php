@extends('layouts.app')

@section('title', 'Mark attendance')

@section('content')
    <div class="mb-6">
        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Attendance</p>
        <h1 class="mt-1 text-3xl font-bold tracking-tight">Mark attendance</h1>
        <p class="mt-2 text-slate-600">Choose a course and date to record each student's status.</p>
    </div>

    <form method="GET" action="{{ route('attendance.create') }}" class="mb-6 grid gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:grid-cols-[1fr_1fr_auto] sm:items-end">
        <div>
            <label for="course_id" class="mb-1 block text-sm font-semibold text-slate-700">Course</label>
            <select id="course_id" name="course_id" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Select a course</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected((string) $courseId === (string) $course->id)>{{ $course->name }} ({{ $course->code }})</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="date" class="mb-1 block text-sm font-semibold text-slate-700">Attendance date</label>
            <input id="date" name="date" type="date" value="{{ $date }}" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">Load students</button>
    </form>

    @if ($courseId !== null)
        @if ($errors->has('statuses'))
            <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" role="alert">{{ $errors->first('statuses') }}</div>
        @endif

        @if ($students->isEmpty())
            <div class="rounded-xl border border-slate-200 bg-white p-8 text-center shadow-sm">
                <p class="font-semibold text-slate-800">No students are assigned to this course yet.</p>
                <a href="{{ route('students.create') }}" class="mt-3 inline-block text-sm font-semibold text-indigo-700 hover:text-indigo-900">Add a student</a>
            </div>
        @else
            <form method="POST" action="{{ route('attendance.store') }}" class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                @csrf
                <input type="hidden" name="course_id" value="{{ $courseId }}">
                <input type="hidden" name="date" value="{{ $date }}">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                    <div>
                        <h2 class="font-semibold text-slate-900">{{ $students->count() }} students</h2>
                        <p class="mt-1 text-sm text-slate-500">Date: {{ \Illuminate\Support\Carbon::parse($date)->format('F j, Y') }}</p>
                    </div>
                    <a href="{{ route('attendance.index', ['date' => $date, 'course_id' => $courseId]) }}" class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">View records</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                            <tr><th class="px-5 py-3">Student</th><th class="px-5 py-3">Roll number</th><th class="px-5 py-3">Email</th><th class="px-5 py-3">Status</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($students as $student)
                                @php($currentStatus = old('statuses.'.$student->id, $student->attendances->first()?->status))
                                <tr>
                                    <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-900">{{ $student->name }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $student->roll_number }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $student->email }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex gap-4">
                                            <label class="inline-flex items-center gap-2 text-emerald-800">
                                                <input type="radio" name="statuses[{{ $student->id }}]" value="present" required @checked($currentStatus === 'present') class="border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                                Present
                                            </label>
                                            <label class="inline-flex items-center gap-2 text-rose-800">
                                                <input type="radio" name="statuses[{{ $student->id }}]" value="absent" required @checked($currentStatus === 'absent') class="border-slate-300 text-rose-600 focus:ring-rose-500">
                                                Absent
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end border-t border-slate-200 px-5 py-4">
                    <button class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Save attendance</button>
                </div>
            </form>
        @endif
    @elseif ($courses->isEmpty())
        <div class="rounded-xl border border-slate-200 bg-white p-8 text-center shadow-sm">
            <p class="font-semibold text-slate-800">Add a course before recording attendance.</p>
            <a href="{{ route('courses.create') }}" class="mt-3 inline-block text-sm font-semibold text-indigo-700 hover:text-indigo-900">Add a course</a>
        </div>
    @endif
@endsection
