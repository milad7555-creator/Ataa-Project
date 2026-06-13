<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUniversityRequest extends FormRequest
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

        // ممنوع يضيف رقم
        'phone' => 'prohibited',

        // ممنوع يضيف إيميل
        'email' => 'prohibited',

        'description' => 'required|string',

        'academic_year'       => 'required|string|max:255',
        'support_type'        => 'required|string|in:laptopsupport,tuitionassistance',
        'university_id_photo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        'required_amount'     => 'prohibited',
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
            'description.required' => 'The description is required.',
            'description.string' => 'The description must be a string.',
            'academic_year.required' => 'The academic year is required.',
            'academic_year.string' => 'The academic year must be a string.',
            'academic_year.max' => 'The academic year may not be greater than 255 characters.',
            'support_type.required' => 'The support type is required.',
            'support_type.string' => 'The support type must be a string.',
            'support_type.in' => 'The support type must be either laptopsupport or tuitionassistance.',
            'university_id_photo.required' => 'The university ID photo is required.',
            'university_id_photo.file' => 'The university ID photo must be a file.',
            'university_id_photo.mimes' => 'The university ID photo must be a file of type: jpg, jpeg, png, pdf.',
            'university_id_photo.max' => 'The university ID photo may not be greater than 5MB.',
        ];
    }
}
