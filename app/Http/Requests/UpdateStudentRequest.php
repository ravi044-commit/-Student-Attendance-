<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'roll_number' => ['required', 'string', 'max:50', Rule::unique('students', 'roll_number')->ignore($this->route('student'))],
            'email' => ['required', 'email', 'max:255', Rule::unique('students', 'email')->ignore($this->route('student'))],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ];
    }
}
