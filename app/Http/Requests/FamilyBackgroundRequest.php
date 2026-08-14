<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FamilyBackgroundRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Spouse Information
            'spouse_first_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
            'spouse_middle_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
            'spouse_last_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
            'spouse_name_extension' => ['nullable', 'string', 'max:20', 'regex:/^[a-zA-Z\s\.]+$/'],
            'spouse_occupation' => ['nullable', 'string', 'max:100'],
            'spouse_employer_business' => ['nullable', 'string', 'max:255'],
            'spouse_business_address' => ['nullable', 'string', 'max:255'],
            'spouse_telephone_no' => ['nullable', 'string', 'max:20'],

            // Father's Information
            'father_first_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
            'father_middle_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
            'father_last_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
            'father_name_extension' => ['nullable', 'string', 'max:20', 'regex:/^[a-zA-Z\s\.]+$/'],

            // Mother's Information
            'mother_first_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
            'mother_middle_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
            'mother_last_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
            'mother_maiden_last_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Spouse Messages
            'spouse_first_name.max' => 'Spouse first name must not exceed 100 characters.',
            'spouse_first_name.regex' => 'Spouse first name should only contain letters, spaces, hyphens, periods, and apostrophes.',
            'spouse_middle_name.max' => 'Spouse middle name must not exceed 100 characters.',
            'spouse_middle_name.regex' => 'Spouse middle name should only contain letters, spaces, hyphens, periods, and apostrophes.',
            'spouse_last_name.max' => 'Spouse last name must not exceed 100 characters.',
            'spouse_last_name.regex' => 'Spouse last name should only contain letters, spaces, hyphens, periods, and apostrophes.',
            'spouse_name_extension.max' => 'Spouse name extension must not exceed 20 characters.',
            'spouse_name_extension.regex' => 'Spouse name extension should only contain letters, spaces, and periods.',
            'spouse_occupation.max' => 'Spouse occupation must not exceed 100 characters.',
            'spouse_employer_business.max' => 'Spouse employer/business must not exceed 255 characters.',
            'spouse_business_address.max' => 'Spouse business address must not exceed 255 characters.',
            'spouse_telephone_no.max' => 'Spouse telephone number must not exceed 20 characters.',

            // Father Messages
            'father_first_name.max' => 'Father first name must not exceed 100 characters.',
            'father_first_name.regex' => 'Father first name should only contain letters, spaces, hyphens, periods, and apostrophes.',
            'father_middle_name.max' => 'Father middle name must not exceed 100 characters.',
            'father_middle_name.regex' => 'Father middle name should only contain letters, spaces, hyphens, periods, and apostrophes.',
            'father_last_name.max' => 'Father last name must not exceed 100 characters.',
            'father_last_name.regex' => 'Father last name should only contain letters, spaces, hyphens, periods, and apostrophes.',
            'father_name_extension.max' => 'Father name extension must not exceed 20 characters.',
            'father_name_extension.regex' => 'Father name extension should only contain letters, spaces, and periods.',

            // Mother Messages
            'mother_first_name.max' => 'Mother first name must not exceed 100 characters.',
            'mother_first_name.regex' => 'Mother first name should only contain letters, spaces, hyphens, periods, and apostrophes.',
            'mother_middle_name.max' => 'Mother middle name must not exceed 100 characters.',
            'mother_middle_name.regex' => 'Mother middle name should only contain letters, spaces, hyphens, periods, and apostrophes.',
            'mother_last_name.max' => 'Mother last name must not exceed 100 characters.',
            'mother_last_name.regex' => 'Mother last name should only contain letters, spaces, hyphens, periods, and apostrophes.',
            'mother_maiden_last_name.max' => 'Mother maiden last name must not exceed 100 characters.',
            'mother_maiden_last_name.regex' => 'Mother maiden last name should only contain letters, spaces, hyphens, periods, and apostrophes.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'spouse_first_name' => 'spouse first name',
            'spouse_middle_name' => 'spouse middle name',
            'spouse_last_name' => 'spouse last name',
            'spouse_name_extension' => 'spouse name extension',
            'spouse_occupation' => 'spouse occupation',
            'spouse_employer_business' => 'spouse employer/business',
            'spouse_business_address' => 'spouse business address',
            'spouse_telephone_no' => 'spouse telephone number',
            'father_first_name' => 'father first name',
            'father_middle_name' => 'father middle name',
            'father_last_name' => 'father last name',
            'father_name_extension' => 'father name extension',
            'mother_first_name' => 'mother first name',
            'mother_middle_name' => 'mother middle name',
            'mother_last_name' => 'mother last name',
            'mother_maiden_last_name' => 'mother maiden last name',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim whitespace from all string fields
        $this->merge([
            'spouse_first_name' => $this->trimValue($this->spouse_first_name),
            'spouse_middle_name' => $this->trimValue($this->spouse_middle_name),
            'spouse_last_name' => $this->trimValue($this->spouse_last_name),
            'spouse_name_extension' => $this->trimValue($this->spouse_name_extension),
            'spouse_occupation' => $this->trimValue($this->spouse_occupation),
            'spouse_employer_business' => $this->trimValue($this->spouse_employer_business),
            'spouse_business_address' => $this->trimValue($this->spouse_business_address),
            'spouse_telephone_no' => $this->trimValue($this->spouse_telephone_no),
            'father_first_name' => $this->trimValue($this->father_first_name),
            'father_middle_name' => $this->trimValue($this->father_middle_name),
            'father_last_name' => $this->trimValue($this->father_last_name),
            'father_name_extension' => $this->trimValue($this->father_name_extension),
            'mother_first_name' => $this->trimValue($this->mother_first_name),
            'mother_middle_name' => $this->trimValue($this->mother_middle_name),
            'mother_last_name' => $this->trimValue($this->mother_last_name),
            'mother_maiden_last_name' => $this->trimValue($this->mother_maiden_last_name),
        ]);

        // Convert empty strings to null for optional fields
        $this->merge([
            'spouse_first_name' => $this->emptyToNull($this->spouse_first_name),
            'spouse_middle_name' => $this->emptyToNull($this->spouse_middle_name),
            'spouse_last_name' => $this->emptyToNull($this->spouse_last_name),
            'spouse_name_extension' => $this->emptyToNull($this->spouse_name_extension),
            'spouse_occupation' => $this->emptyToNull($this->spouse_occupation),
            'spouse_employer_business' => $this->emptyToNull($this->spouse_employer_business),
            'spouse_business_address' => $this->emptyToNull($this->spouse_business_address),
            'spouse_telephone_no' => $this->emptyToNull($this->spouse_telephone_no),
            'father_first_name' => $this->emptyToNull($this->father_first_name),
            'father_middle_name' => $this->emptyToNull($this->father_middle_name),
            'father_last_name' => $this->emptyToNull($this->father_last_name),
            'father_name_extension' => $this->emptyToNull($this->father_name_extension),
            'mother_first_name' => $this->emptyToNull($this->mother_first_name),
            'mother_middle_name' => $this->emptyToNull($this->mother_middle_name),
            'mother_last_name' => $this->emptyToNull($this->mother_last_name),
            'mother_maiden_last_name' => $this->emptyToNull($this->mother_maiden_last_name),
        ]);
    }

    /**
     * Trim a value if it exists.
     */
    private function trimValue($value): ?string
    {
        if (is_string($value)) {
            return trim($value);
        }
        return $value;
    }

    /**
     * Convert empty string to null.
     */
    private function emptyToNull($value): ?string
    {
        if (is_string($value) && $value === '') {
            return null;
        }
        return $value;
    }

    /**
     * Get the validated data with additional formatting.
     *
     * @return array
     */
    public function validatedData(): array
    {
        $data = $this->validated();

        // Format names to proper case (exclude extensions and numbers)
        $nameFields = [
            'spouse_first_name',
            'spouse_middle_name',
            'spouse_last_name',
            'father_first_name',
            'father_middle_name',
            'father_last_name',
            'mother_first_name',
            'mother_middle_name',
            'mother_last_name',
            'mother_maiden_last_name',
        ];

        foreach ($nameFields as $field) {
            if (isset($data[$field]) && $data[$field]) {
                $data[$field] = ucwords(strtolower($data[$field]));
            }
        }

        // Format business address
        if (isset($data['spouse_business_address']) && $data['spouse_business_address']) {
            $data['spouse_business_address'] = ucwords(strtolower($data['spouse_business_address']));
        }

        // Format occupation
        if (isset($data['spouse_occupation']) && $data['spouse_occupation']) {
            $data['spouse_occupation'] = ucwords(strtolower($data['spouse_occupation']));
        }

        return $data;
    }

    /**
     * Determine if the request is for creating or updating.
     */
    public function isCreating(): bool
    {
        return $this->isMethod('POST');
    }

    /**
     * Determine if the request is for updating.
     */
    public function isUpdating(): bool
    {
        return $this->isMethod('PUT') || $this->isMethod('PATCH');
    }

    /**
     * Get the fields that should be considered as person names.
     *
     * @return array
     */
    public function personNameFields(): array
    {
        return [
            'spouse' => [
                'first_name' => 'spouse_first_name',
                'middle_name' => 'spouse_middle_name',
                'last_name' => 'spouse_last_name',
                'extension' => 'spouse_name_extension',
            ],
            'father' => [
                'first_name' => 'father_first_name',
                'middle_name' => 'father_middle_name',
                'last_name' => 'father_last_name',
                'extension' => 'father_name_extension',
            ],
            'mother' => [
                'first_name' => 'mother_first_name',
                'middle_name' => 'mother_middle_name',
                'last_name' => 'mother_last_name',
                'maiden_last_name' => 'mother_maiden_last_name',
            ],
        ];
    }

    /**
     * Get the full name of a person from the request data.
     *
     * @param string $person 'spouse', 'father', or 'mother'
     * @return string|null
     */
    public function getFullName(string $person): ?string
    {
        $fields = $this->personNameFields()[$person] ?? null;
        if (!$fields) {
            return null;
        }

        $firstName = $this->input($fields['first_name'] ?? '');
        $middleName = $this->input($fields['middle_name'] ?? '');
        $lastName = $this->input($fields['last_name'] ?? '');
        $extension = $this->input($fields['extension'] ?? '');

        if (!$firstName && !$lastName) {
            return null;
        }

        $name = $firstName;
        if ($middleName) {
            $name .= ' ' . $middleName;
        }
        if ($lastName) {
            $name .= ' ' . $lastName;
        }
        if ($extension) {
            $name .= ' ' . $extension;
        }

        return trim($name);
    }
}