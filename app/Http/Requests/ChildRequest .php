<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChildRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
            'date_of_birth' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'sex' => ['required', 'string', Rule::in(['male', 'female'])],
            'order' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Child\'s name is required.',
            'name.regex' => 'Name should only contain letters, spaces, hyphens, periods, and apostrophes.',
            'name.max' => 'Name must not exceed 255 characters.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Invalid date format.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'date_of_birth.after' => 'Date of birth must be after January 1, 1900.',
            'sex.required' => 'Gender is required.',
            'sex.in' => 'Invalid gender selected.',
            'order.integer' => 'Order must be a valid number.',
            'order.min' => 'Order must be at least 1.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'child\'s name',
            'date_of_birth' => 'date of birth',
            'sex' => 'gender',
            'order' => 'birth order',
        ];
    }
}