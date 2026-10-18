<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_pages_require_authentication_and_root_redirects_to_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('students.index'))->assertRedirect(route('login'));
        $this->get(route('courses.index'))->assertRedirect(route('login'));
        $this->get(route('attendance.index'))->assertRedirect(route('login'));
        $this->get(route('attendance.create'))->assertRedirect(route('login'));
        $this->get(route('reports.index'))->assertRedirect(route('login'));
        $this->get(route('login'))->assertOk();
    }

    public function test_attendance_is_upserted_once_per_student_and_date_and_reports_are_calculated(): void
    {
        $this->actingAs(User::factory()->create());
        $course = Course::create(['name' => 'Computer Science', 'code' => 'CS101']);
        $firstStudent = Student::create([
            'name' => 'Aarav Sharma',
            'roll_number' => 'CS-001',
            'email' => 'aarav@example.com',
            'course_id' => $course->id,
        ]);
        $secondStudent = Student::create([
            'name' => 'Diya Patel',
            'roll_number' => 'CS-002',
            'email' => 'diya@example.com',
            'course_id' => $course->id,
        ]);

        $payload = [
            'course_id' => $course->id,
            'date' => '2026-10-18',
            'statuses' => [
                $firstStudent->id => 'present',
                $secondStudent->id => 'absent',
            ],
        ];

        $this->post(route('attendance.store'), $payload)
            ->assertRedirect(route('attendance.index', ['date' => '2026-10-18', 'course_id' => $course->id]));
        $this->assertDatabaseCount('attendances', 2);

        $payload['statuses'][$firstStudent->id] = 'absent';
        $payload['statuses'][$secondStudent->id] = 'present';

        $this->post(route('attendance.store'), $payload)->assertRedirect();

        $this->assertDatabaseCount('attendances', 2);
        $this->assertDatabaseHas('attendances', [
            'student_id' => $firstStudent->id,
            'course_id' => $course->id,
            'date' => '2026-10-18',
            'status' => 'absent',
        ]);
        $this->assertDatabaseHas('attendances', [
            'student_id' => $secondStudent->id,
            'date' => '2026-10-18',
            'status' => 'present',
        ]);

        $this->get(route('reports.index'))->assertOk()->assertSee('50.0%');
        $this->get(route('attendance.index', ['date' => '2026-10-18', 'course_id' => $course->id]))
            ->assertOk()
            ->assertSee('Aarav Sharma')
            ->assertSee('Diya Patel');
    }

    public function test_attendance_submission_must_include_each_student_in_the_selected_course(): void
    {
        $this->actingAs(User::factory()->create());
        $course = Course::create(['name' => 'Mathematics', 'code' => 'MATH101']);
        $student = Student::create([
            'name' => 'Meera Rao',
            'roll_number' => 'MATH-001',
            'email' => 'meera@example.com',
            'course_id' => $course->id,
        ]);
        Student::create([
            'name' => 'Kabir Singh',
            'roll_number' => 'MATH-002',
            'email' => 'kabir@example.com',
            'course_id' => $course->id,
        ]);

        $this->from(route('attendance.create', ['course_id' => $course->id, 'date' => '2026-10-18']))
            ->post(route('attendance.store'), [
                'course_id' => $course->id,
                'date' => '2026-10-18',
                'statuses' => [$student->id => 'present'],
            ])
            ->assertSessionHasErrors('statuses');

        $this->assertSame(0, Attendance::count());
    }
}
