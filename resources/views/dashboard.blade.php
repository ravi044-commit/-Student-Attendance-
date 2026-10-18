@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Overview</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">Dashboard</h1>
            <p class="mt-2 text-slate-600">{{ today()->format('l, F j, Y') }}</p>
        </div>
        <a href="{{ route('attendance.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
            Mark attendance
        </a>
    </div>

    <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ([
            ['label' => 'Total students', 'value' => $studentCount, 'detail' => 'Enrolled across all courses'],
            ['label' => 'Total courses', 'value' => $courseCount, 'detail' => 'Courses in the system'],
            ['label' => "Today's attendance", 'value' => $todayAttendanceCount, 'detail' => 'Student records for today'],
        ] as $stat)
            <article class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</p>
                <p class="mt-3 text-4xl font-bold tracking-tight text-slate-900">{{ $stat['value'] }}</p>
                <p class="mt-2 text-sm text-slate-500">{{ $stat['detail'] }}</p>
            </article>
        @endforeach
    </section>

    <section class="mt-8 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Today's attendance summary</h2>
                <p class="mt-1 text-sm text-slate-500">Present and absent counts from today's records.</p>
            </div>
            <a href="{{ route('attendance.index', ['date' => today()->toDateString()]) }}" class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">
                View records
            </a>
        </div>
        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <div class="rounded-lg bg-emerald-50 p-5">
                <p class="text-sm font-medium text-emerald-800">Present</p>
                <p class="mt-2 text-3xl font-bold text-emerald-900">{{ $presentCount }}</p>
            </div>
            <div class="rounded-lg bg-rose-50 p-5">
                <p class="text-sm font-medium text-rose-800">Absent</p>
                <p class="mt-2 text-3xl font-bold text-rose-900">{{ $absentCount }}</p>
            </div>
        </div>
    </section>
@endsection
