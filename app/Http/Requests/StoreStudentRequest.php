<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'roll_number' => ['required', 'string', 'max:50', 'unique:students,roll_number'],
            'email' => ['required', 'email', 'max:255', 'unique:students,email'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ];
    }
}
