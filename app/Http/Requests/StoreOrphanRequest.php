<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrphanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

   public function rules()
{
    return [
        'full_name' => 'required|string|max:255',
        'address'   => 'required|string|max:255',

        'phone' => 'nullable|regex:/^[0-9]+$/',
        'email' => 'prohibited',

        'description' => 'required|string',
        'required_amount' => 'prohibited',

        'family_booklet' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        'father_death_certificate' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ];
}

    public function messages(): array
    {
        return [
            'full_name.required' => 'The full name is required.',
            'full_name.string' => 'The full name must be a string.',
            'full_name.max' => 'The full name may not be greater than 255 characters.',
            'address.required' => 'The address is required.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than 255 characters.',
            'phone.regex' => 'The phone format is invalid.',
            'family_booklet.required' => 'The family booklet is required.',
            'family_booklet.file' => 'The family booklet must be a file.',
            'family_booklet.mimes' => 'The family booklet must be a file of type: jpg, jpeg, png, pdf.',
            'family_booklet.max' => 'The family booklet may not be greater than 5MB.',
            'father_death_certificate.required' => 'The father death certificate is required.',
            'father_death_certificate.file' => 'The father death certificate must be a file.',
            'father_death_certificate.mimes' => 'The father death certificate must be a file of type: jpg, jpeg, png, pdf.',
            'father_death_certificate.max' => 'The father death certificate may not be greater than 5MB.',
        ];
    }
}
