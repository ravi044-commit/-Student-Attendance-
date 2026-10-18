<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $students = Student::query()
            ->with('course')
            ->withCount('attendances')
            ->withCount(['attendances as present_count' => fn ($query) => $query->where('status', 'present')])
            ->orderBy('name')
            ->get()
            ->map(function (Student $student): Student {
                $student->attendance_percentage = $student->attendances_count === 0
                    ? 0
                    : round($student->present_count / $student->attendances_count * 100, 1);

                return $student;
            });

        $attendanceByCourse = DB::table('attendances')
            ->select('attendances.course_id')
            ->selectRaw("SUM(CASE WHEN attendances.status = 'present' THEN 1 ELSE 0 END) as present_count")
            ->selectRaw("SUM(CASE WHEN attendances.status = 'absent' THEN 1 ELSE 0 END) as absent_count")
            ->selectRaw('COUNT(*) as total_count')
            ->groupBy('attendances.course_id')
            ->get()
            ->keyBy('course_id');

        $courses = Course::query()
            ->withCount('students')
            ->orderBy('name')
            ->get()
            ->map(function (Course $course) use ($attendanceByCourse): Course {
                $summary = $attendanceByCourse->get($course->id);
                $course->present_count = (int) ($summary->present_count ?? 0);
                $course->absent_count = (int) ($summary->absent_count ?? 0);
                $course->total_count = (int) ($summary->total_count ?? 0);
                $course->attendance_percentage = $course->total_count === 0
                    ? 0
                    : round($course->present_count / $course->total_count * 100, 1);

                return $course;
            });

        return view('reports.index', compact('students', 'courses'));
    }
}
