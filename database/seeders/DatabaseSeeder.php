<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => 'password123',
                'email_verified_at' => now(),
            ],
        );

        $courses = collect([
            ['name' => 'Computer Science', 'code' => 'CS101'],
            ['name' => 'Mathematics', 'code' => 'MATH101'],
            ['name' => 'Physics', 'code' => 'PHY101'],
        ])->mapWithKeys(fn (array $attributes) => [
            $attributes['code'] => Course::firstOrCreate(
                ['code' => $attributes['code']],
                ['name' => $attributes['name']],
            ),
        ]);

        $students = [
            ['name' => 'Aarav Sharma', 'roll_number' => 'CS-001', 'email' => 'aarav@example.com', 'course' => 'CS101'],
            ['name' => 'Diya Patel', 'roll_number' => 'CS-002', 'email' => 'diya@example.com', 'course' => 'CS101'],
            ['name' => 'Kabir Singh', 'roll_number' => 'MATH-001', 'email' => 'kabir@example.com', 'course' => 'MATH101'],
            ['name' => 'Meera Rao', 'roll_number' => 'MATH-002', 'email' => 'meera@example.com', 'course' => 'MATH101'],
            ['name' => 'Arjun Kumar', 'roll_number' => 'PHY-001', 'email' => 'arjun@example.com', 'course' => 'PHY101'],
            ['name' => 'Anaya Joshi', 'roll_number' => 'PHY-002', 'email' => 'anaya@example.com', 'course' => 'PHY101'],
        ];

        foreach ($students as $student) {
            Student::firstOrCreate(
                ['roll_number' => $student['roll_number']],
                [
                    'name' => $student['name'],
                    'email' => $student['email'],
                    'course_id' => $courses[$student['course']]->id,
                ],
            );
        }
    }
}
