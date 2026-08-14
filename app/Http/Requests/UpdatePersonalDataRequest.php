<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonalDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Or check if user owns the data
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
            'middle_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
            'last_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s\-\.\']+$/'],
            'name_extension' => ['nullable', 'string', 'max:20', 'regex:/^[a-zA-Z\s\.]+$/'],
            'sex' => ['required', 'string', Rule::in(['Male', 'Female', 'other'])],
            'birth_date' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'civil_status' => ['required', 'string', Rule::in(['Single', 'Married', 'Divorced', 'Widowed', 'Separated'])],
            'nationality' => ['required', 'string', 'max:100'],
            'religion' => ['nullable', 'string', 'max:100'],
            'height' => ['nullable', 'numeric', 'min:50', 'max:300'],
            'weight' => ['nullable', 'numeric', 'min:10', 'max:500'],
            'telephone_no' => ['nullable', 'string', 'max:20'],
            'mobile_no' => ['nullable', 'string', 'max:20'],
            'blood_type' => ['nullable', 'string', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'], // 2MB max
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
            // First Name
            'first_name.required' => 'First name is required.',
            'first_name.regex' => 'First name should only contain letters, spaces, hyphens, periods, and apostrophes.',
            'first_name.max' => 'First name must not exceed 100 characters.',

            // Middle Name
            'middle_name.regex' => 'Middle name should only contain letters, spaces, hyphens, periods, and apostrophes.',
            'middle_name.max' => 'Middle name must not exceed 100 characters.',

            // Last Name
            'last_name.required' => 'Last name is required.',
            'last_name.regex' => 'Last name should only contain letters, spaces, hyphens, periods, and apostrophes.',
            'last_name.max' => 'Last name must not exceed 100 characters.',

            // Name Extension
            'name_extension.regex' => 'Name extension should only contain letters, spaces, and periods.',
            'name_extension.max' => 'Name extension must not exceed 20 characters.',

            // sex
            'sex.required' => 'sex is required.',
            'sex.in' => 'Invalid sex selected.',

            // Birth Date
            'birth_date.required' => 'Birth date is required.',
            'birth_date.date' => 'Invalid birth date format.',
            'birth_date.before' => 'Birth date must be in the past.',
            'birth_date.after' => 'Birth date must be after January 1, 1900.',

            // Place of Birth
            'place_of_birth.required' => 'Place of birth is required.',
            'place_of_birth.max' => 'Place of birth must not exceed 255 characters.',

            // Marital Status
            'civil_status.required' => 'Marital status is required.',
            'civil_status.in' => 'Invalid marital status selected.',

            // Nationality
            'nationality.required' => 'Nationality is required.',
            'nationality.max' => 'Nationality must not exceed 100 characters.',

            // Religion
            'religion.max' => 'Religion must not exceed 100 characters.',

            // Height
            'height.numeric' => 'Height must be a number.',
            'height.min' => 'Height must be at least 50 cm.',
            'height.max' => 'Height must not exceed 300 cm.',

            // Weight
            'weight.numeric' => 'Weight must be a number.',
            'weight.min' => 'Weight must be at least 10 kg.',
            'weight.max' => 'Weight must not exceed 500 kg.',

            // Blood Type
            'blood_type.in' => 'Invalid blood type selected.',

            // Photo
            'photo.image' => 'Uploaded file must be an image.',
            'photo.mimes' => 'Image must be of type: jpeg, png, jpg, gif, or svg.',
            'photo.max' => 'Image size must not exceed 2MB.',

            // Telephone No.
            'telephone_no.max' => 'Telephone number must not exceed 20 characters.',
            
            // Mobile No.
            'mobile_no.max' => 'Mobile number must not exceed 20 characters.',
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
            'first_name' => 'first name',
            'middle_name' => 'middle name',
            'last_name' => 'last name',
            'name_extension' => 'name extension',
            'birth_date' => 'birth date',
            'place_of_birth' => 'place of birth',
            'civil_status' => 'marital status',
            'blood_type' => 'blood type',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim whitespace from string fields
        $this->merge([
            'first_name' => trim($this->first_name ?? ''),
            'middle_name' => trim($this->middle_name ?? ''),
            'last_name' => trim($this->last_name ?? ''),
            'name_extension' => trim($this->name_extension ?? ''),
            'place_of_birth' => trim($this->place_of_birth ?? ''),
            'nationality' => trim($this->nationality ?? ''),
            'religion' => trim($this->religion ?? ''),
        ]);

        // Convert empty strings to null for optional fields
        $this->merge([
            'middle_name' => $this->middle_name ?: null,
            'name_extension' => $this->name_extension ?: null,
            'religion' => $this->religion ?: null,
            'height' => $this->height ?: null,
            'weight' => $this->weight ?: null,
            'blood_type' => $this->blood_type ?: null,
        ]);
    }

    /**
     * Get the validated data with additional formatting.
     *
     * @return array
     */
    public function validatedData(): array
    {
        $data = $this->validated();

        // Convert names to proper case
        if (isset($data['first_name'])) {
            $data['first_name'] = ucwords(strtolower($data['first_name']));
        }
        if (isset($data['middle_name'])) {
            $data['middle_name'] = ucwords(strtolower($data['middle_name']));
        }
        if (isset($data['last_name'])) {
            $data['last_name'] = ucwords(strtolower($data['last_name']));
        }
        if (isset($data['place_of_birth'])) {
            $data['place_of_birth'] = ucwords(strtolower($data['place_of_birth']));
        }

        return $data;
    }
}