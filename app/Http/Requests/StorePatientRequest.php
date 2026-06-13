<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePatientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Beneficiary
            'full_name'   => 'required|string|max:255',
            'address'     => 'required|string|max:255',
            'email' => [
                'nullable',
                'required_without:phone',
                'email',
                Rule::unique('users', 'email')->whereNotNull('email'),
            ],
            'phone' => [
                'nullable',
                'required_without:email',
                'regex:/^[0-9]+$/',
                Rule::unique('users', 'phone')->whereNotNull('phone'),
            ],
            // Request
            'description' => 'required|string',

            // Patient
            'required_amount'    => 'required|numeric|min:1',
            'medical_report'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
    }
     public function messages(): array
    {
        return [
            'full_name.required' => 'The full name is required.',
            'full_name.string' => 'The full name must be a string.',
            'full_name.max' => 'The full name may not be greater than 255 characters.',
            'national_id.required' => 'The national ID is required.',
            'national_id.string' => 'The national ID must be a string.',
            'national_id.max' => 'The national ID may not be greater than 100 characters.',
            'address.required' => 'The address is required.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than 255 characters.',
            'email.required_without' => 'The email is required when phone is not present.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'phone.required_without' => 'The phone is required when email is not present.',
            'phone.regex' => 'The phone format is invalid.',
            'phone.unique' => 'The phone has already been taken.',
            'required_amount.required' => 'The required amount is required.',
            'required_amount.numeric' => 'The required amount must be a number.',
            'required_amount.min' => 'The required amount must be at least 1.',
            'medical_report.required' => 'The medical report is required.',
            'medical_report.file' => 'The medical report must be a file.',
            'medical_report.mimes' => 'The medical report must be a file of type: jpg, jpeg, png, pdf.',
            'medical_report.max' => 'The medical report may not be greater than 5MB.',

        ];
    }
}
