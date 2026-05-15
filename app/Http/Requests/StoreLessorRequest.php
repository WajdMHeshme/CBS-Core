<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'business_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['nullable', 'string', 'max:2000'],


            'identity_front_image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120'
            ],

            'identity_back_image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120'
            ],
        ];
    }
}
