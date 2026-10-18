@extends('layouts.app')

@section('title', 'Attendance records')

@section('content')
    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Attendance</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight">Records</h1>
        </div>
        <a href="{{ route('attendance.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Mark attendance</a>
    </div>

    <form method="GET" action="{{ route('attendance.index') }}" class="mb-5 grid gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-[1fr_1fr_auto_auto] sm:items-end">
        <div>
            <label for="filter_date" class="mb-1 block text-sm font-semibold text-slate-700">Date</label>
            <input id="filter_date" name="date" type="date" value="{{ $filters['date'] ?? '' }}" class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
            <label for="filter_course" class="mb-1 block text-sm font-semibold text-slate-700">Course</label>
            <select id="filter_course" name="course_id" class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All courses</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected((string) ($filters['course_id'] ?? '') === (string) $course->id)>{{ $course->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Apply filters</button>
        <a href="{{ route('attendance.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Clear</a>
    </form>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                    <tr><th class="px-5 py-3">Date</th><th class="px-5 py-3">Student</th><th class="px-5 py-3">Roll number</th><th class="px-5 py-3">Course</th><th class="px-5 py-3">Status</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($attendances as $attendance)
                        <tr>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $attendance->date->format('M j, Y') }}</td>
                            <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-900">{{ $attendance->student->name }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $attendance->student->roll_number }}</td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $attendance->course->name }}</td>
                            <td class="px-5 py-4">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize',
                                    'bg-emerald-100 text-emerald-800' => $attendance->status === 'present',
                                    'bg-rose-100 text-rose-800' => $attendance->status === 'absent',
                                ])>{{ $attendance->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-10 text-center text-slate-500">No attendance records match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($attendances->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">{{ $attendances->links() }}</div>
        @endif
    </div>
@endsection
