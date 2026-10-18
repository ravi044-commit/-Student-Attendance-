@extends('layouts.app')

@section('title', 'Attendance reports')

@section('content')
    <div class="mb-7">
        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Insights</p>
        <h1 class="mt-1 text-3xl font-bold tracking-tight">Attendance reports</h1>
        <p class="mt-2 text-slate-600">Percentages use each student's or course's recorded attendance entries.</p>
    </div>

    <section class="mb-8">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-slate-900">Per-course summary</h2>
            <p class="mt-1 text-sm text-slate-500">Attendance entries grouped by the student's current course.</p>
        </div>
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr><th class="px-5 py-3">Course</th><th class="px-5 py-3">Students</th><th class="px-5 py-3">Present</th><th class="px-5 py-3">Absent</th><th class="px-5 py-3">Recorded entries</th><th class="px-5 py-3 text-right">Attendance</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($courses as $course)
                            <tr>
                                <td class="px-5 py-4 font-semibold text-slate-900">{{ $course->name }} <span class="font-normal text-slate-500">({{ $course->code }})</span></td>
                                <td class="px-5 py-4 text-slate-600">{{ $course->students_count }}</td>
                                <td class="px-5 py-4 text-emerald-700">{{ $course->present_count }}</td>
                                <td class="px-5 py-4 text-rose-700">{{ $course->absent_count }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $course->total_count }}</td>
                                <td class="px-5 py-4 text-right font-semibold text-slate-900">{{ number_format($course->attendance_percentage, 1) }}%</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-8 text-center text-slate-500">No courses available.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section>
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-slate-900">Per-student summary</h2>
            <p class="mt-1 text-sm text-slate-500">Each student's present and absent counts across all recorded dates.</p>
        </div>
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr><th class="px-5 py-3">Student</th><th class="px-5 py-3">Roll number</th><th class="px-5 py-3">Course</th><th class="px-5 py-3">Present</th><th class="px-5 py-3">Absent</th><th class="px-5 py-3">Total</th><th class="px-5 py-3 text-right">Attendance</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($students as $student)
                            <tr>
                                <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-900">{{ $student->name }}</td>
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $student->roll_number }}</td>
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $student->course->name }}</td>
                                <td class="px-5 py-4 text-emerald-700">{{ $student->present_count }}</td>
                                <td class="px-5 py-4 text-rose-700">{{ $student->attendances_count - $student->present_count }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $student->attendances_count }}</td>
                                <td class="px-5 py-4 text-right font-semibold text-slate-900">{{ number_format($student->attendance_percentage, 1) }}%</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-5 py-8 text-center text-slate-500">No students available.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
