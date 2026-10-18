<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = today()->toDateString();
        $todayAttendances = Attendance::whereDate('date', $today);

        return view('dashboard', [
            'studentCount' => Student::count(),
            'courseCount' => Course::count(),
            'todayAttendanceCount' => (clone $todayAttendances)->count(),
            'presentCount' => (clone $todayAttendances)->where('status', 'present')->count(),
            'absentCount' => (clone $todayAttendances)->where('status', 'absent')->count(),
        ]);
    }
}
