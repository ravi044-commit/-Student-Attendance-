<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'date' => ['nullable', 'date'],
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
        ]);

        $attendances = Attendance::query()
            ->with(['student', 'course'])
            ->when($filters['date'] ?? null, fn ($query, $date) => $query->whereDate('date', $date))
            ->when($filters['course_id'] ?? null, fn ($query, $courseId) => $query->where('course_id', $courseId))
            ->orderByDesc('date')
            ->orderBy('student_id')
            ->paginate(15)
            ->withQueryString();

        return view('attendance.index', [
            'attendances' => $attendances,
            'courses' => Course::orderBy('name')->get(),
            'filters' => $filters,
        ]);
    }

    public function create(Request $request): View
    {
        $filters = $request->validate([
            'course_id' => ['nullable', 'integer', 'exists:courses,id'],
            'date' => ['nullable', 'date'],
        ]);
        $date = $filters['date'] ?? today()->toDateString();
        $courseId = $filters['course_id'] ?? null;
        $students = collect();

        if ($courseId !== null) {
            $students = Student::query()
                ->where('course_id', $courseId)
                ->with(['attendances' => fn ($query) => $query->whereDate('date', $date)])
                ->orderBy('roll_number')
                ->get();
        }

        return view('attendance.create', [
            'courses' => Course::orderBy('name')->get(),
            'courseId' => $courseId,
            'date' => $date,
            'students' => $students,
        ]);
    }

    public function store(StoreAttendanceRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $date = Carbon::parse($validated['date'])->toDateString();

        DB::transaction(function () use ($validated, $date): void {
            $studentIds = Student::query()
                ->where('course_id', $validated['course_id'])
                ->orderBy('id')
                ->lockForUpdate()
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();
            $submittedIds = [];
            foreach (array_keys($validated['statuses']) as $id) {
                $id = (string) $id;
                $submittedIds[] = ctype_digit($id) && (string) (int) $id === $id
                    ? (int) $id
                    : -1;
            }
            sort($submittedIds, SORT_NUMERIC);

            if ($studentIds === [] || $studentIds !== $submittedIds) {
                throw ValidationException::withMessages([
                    'statuses' => $studentIds === []
                        ? 'There are no students assigned to this course.'
                        : 'Attendance must be submitted for every student in the selected course.',
                ]);
            }

            $now = now();
            $records = [];

            foreach ($validated['statuses'] as $studentId => $status) {
                $records[] = [
                    'student_id' => (int) $studentId,
                    'course_id' => (int) $validated['course_id'],
                    'date' => $date,
                    'status' => $status,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            Attendance::upsert($records, ['student_id', 'date'], ['course_id', 'status', 'updated_at']);
        });

        return redirect()
            ->route('attendance.index', ['date' => $date, 'course_id' => $validated['course_id']])
            ->with('success', 'Attendance saved successfully.');
    }
}
