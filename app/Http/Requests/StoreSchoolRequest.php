<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolRequest extends FormRequest
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

            'academic_grade'    => 'required|string|max:255',
            'school_name'       => 'required|string|max:255',
            'family_book_photo' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'required_amount'   => 'prohibited',
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
            'academic_grade.required' => 'The academic grade is required.',
            'academic_grade.string' => 'The academic grade must be a string.',
            'academic_grade.max' => 'The academic grade may not be greater than 255 characters.',
            'school_name.required' => 'The school name is required.',
            'school_name.string' => 'The school name must be a string.',
            'school_name.max' => 'The school name may not be greater than 255 characters.',
            'family_book_photo.required' => 'The family book photo is required.',
            'family_book_photo.file' => 'The family book photo must be a file.',
            'family_book_photo.mimes' => 'The family book photo must be a file of type: jpg, jpeg, png, pdf.',
            'family_book_photo.max' => 'The family book photo may not be greater than 5MB.',
        ];
    }   
}
