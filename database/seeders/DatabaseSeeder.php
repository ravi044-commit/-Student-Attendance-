<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /** Keep only the columns that really exist in the table. */
    private function only(string $table, array $data): array
    {
        return array_intersect_key($data, array_flip(Schema::getColumnListing($table)));
    }

    public function run(): void
    {
        $now = now();

        // 1. Admin login
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin', 'password' => Hash::make('Admin@123')]
        );

        // 2. Five courses
        if (Schema::hasTable('courses') && DB::table('courses')->count() === 0) {
            $courses = [
                ['Computer Science', 'CS101'],
                ['Information Technology', 'IT102'],
                ['Business Administration', 'BA103'],
                ['Electronics', 'EC104'],
                ['Mechanical Engineering', 'ME105'],
            ];
            foreach ($courses as [$name, $code]) {
                DB::table('courses')->insert($this->only('courses', [
                    'name' => $name,
                    'title' => $name,
                    'course_name' => $name,
                    'code' => $code,
                    'course_code' => $code,
                    'description' => $name . ' course',
                    'duration' => '3 years',
                    'credits' => 4,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }

        // 3. Ten students
        if (Schema::hasTable('students') && DB::table('students')->count() === 0) {
            $courseIds = Schema::hasTable('courses') ? DB::table('courses')->pluck('id')->all() : [];
            $names = [
                ['Aarav', 'Patel'], ['Diya', 'Shah'], ['Vihaan', 'Mehta'], ['Ananya', 'Joshi'],
                ['Rohan', 'Desai'], ['Isha', 'Trivedi'], ['Kabir', 'Parmar'], ['Meera', 'Gandhi'],
                ['Arjun', 'Solanki'], ['Priya', 'Thakkar'],
            ];
            foreach ($names as $i => [$first, $last]) {
                $n = $i + 1;
                DB::table('students')->insert($this->only('students', [
                    'name' => "$first $last",
                    'student_name' => "$first $last",
                    'first_name' => $first,
                    'last_name' => $last,
                    'email' => strtolower("$first.$last") . '@example.com',
                    'roll_number' => sprintf('STU%03d', $n),
                    'roll_no' => sprintf('STU%03d', $n),
                    'phone' => '98765' . sprintf('%05d', $n),
                    'gender' => $i % 2 === 0 ? 'Male' : 'Female',
                    'address' => 'Vadodara, Gujarat',
                    'course_id' => $courseIds ? $courseIds[$i % count($courseIds)] : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }

        // 4. Attendance for the last 14 days (weekdays only)
        $table = collect(['attendances', 'attendance'])->first(fn ($t) => Schema::hasTable($t));
        if ($table && DB::table($table)->count() === 0 && Schema::hasTable('students')) {
            $statusType = Schema::hasColumn($table, 'status') ? Schema::getColumnType($table, 'status') : null;
            $numeric = in_array($statusType, ['boolean', 'bool', 'tinyint', 'integer', 'int', 'smallint', 'bigint']);

            $students = DB::table('students')->get();
            for ($d = 13; $d >= 0; $d--) {
                $date = now()->subDays($d);
                if ($date->isWeekend()) {
                    continue;
                }
                foreach ($students as $student) {
                    $present = mt_rand(1, 100) <= 85;
                    DB::table($table)->insert($this->only($table, [
                        'student_id' => $student->id,
                        'course_id' => $student->course_id ?? null,
                        'date' => $date->toDateString(),
                        'attendance_date' => $date->toDateString(),
                        'status' => $numeric ? (int) $present : ($present ? 'present' : 'absent'),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]));
                }
            }
        }
    }
}