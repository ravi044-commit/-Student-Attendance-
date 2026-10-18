<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('courses', 'name')) {
            Schema::table('courses', fn (Blueprint $table) => $table->string('name')->default(''));
        }
        if (! Schema::hasColumn('courses', 'code')) {
            Schema::table('courses', fn (Blueprint $table) => $table->string('code')->default('')->unique());
        }
        if (! Schema::hasColumn('students', 'name')) {
            Schema::table('students', fn (Blueprint $table) => $table->string('name')->default(''));
        }
        if (! Schema::hasColumn('students', 'roll_number')) {
            Schema::table('students', fn (Blueprint $table) => $table->string('roll_number')->default('')->unique());
        }
        if (! Schema::hasColumn('students', 'email')) {
            Schema::table('students', fn (Blueprint $table) => $table->string('email')->default('')->unique());
        }
        if (! Schema::hasColumn('students', 'course_id')) {
            Schema::table('students', fn (Blueprint $table) => $table->foreignId('course_id')->nullable()->constrained()->restrictOnDelete());
        }
        if (! Schema::hasColumn('attendances', 'student_id')) {
            Schema::table('attendances', fn (Blueprint $table) => $table->foreignId('student_id')->nullable()->constrained()->cascadeOnDelete());
        }
        if (! Schema::hasColumn('attendances', 'course_id')) {
            Schema::table('attendances', fn (Blueprint $table) => $table->foreignId('course_id')->nullable()->constrained()->restrictOnDelete());
        }
        if (! Schema::hasColumn('attendances', 'date')) {
            Schema::table('attendances', function (Blueprint $table): void {
                $table->date('date')->default(now()->toDateString())->index();
            });
        }
        if (! Schema::hasColumn('attendances', 'status')) {
            Schema::table('attendances', fn (Blueprint $table) => $table->string('status', 8)->default('absent'));
        }
        if (! Schema::hasIndex('attendances', 'attendances_student_id_date_unique')) {
            Schema::table('attendances', fn (Blueprint $table) => $table->unique(['student_id', 'date']));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table): void {
            $table->dropUnique('attendances_student_id_date_unique');
            $table->dropIndex('attendances_date_index');
            $table->dropForeign('attendances_course_id_foreign');
            $table->dropForeign('attendances_student_id_foreign');
            $table->dropColumn(['student_id', 'course_id', 'date', 'status']);
        });
        Schema::table('students', function (Blueprint $table): void {
            $table->dropForeign('students_course_id_foreign');
            $table->dropUnique('students_roll_number_unique');
            $table->dropUnique('students_email_unique');
            $table->dropColumn(['name', 'roll_number', 'email', 'course_id']);
        });
        Schema::table('courses', function (Blueprint $table): void {
            $table->dropUnique('courses_code_unique');
            $table->dropColumn(['name', 'code']);
        });
    }
};
