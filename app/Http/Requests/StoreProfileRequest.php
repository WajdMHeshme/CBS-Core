<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
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

            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[\pL\s\-]+$/u',
            ],

            'last_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[\pL\s\-]+$/u',
            ],

            'bio' => [
                'nullable',
                'string',
                'min:10',
                'max:1000',
            ],

            'address' => [
                'nullable',
                'string',
                'min:5',
                'max:255',
            ],

            'country' => [
                'nullable',
                'string',
                'min:2',
                'max:100',
            ],

            'city' => [
                'nullable',
                'string',
                'min:2',
                'max:100',
            ],

            'gender' => [
                'nullable',
                'in:male,female',
            ],

            'birth_date' => [
                'nullable',
                'date',
                'before:today',
                'after:1900-01-01',
            ],

            'phone'=>[
                'nullable',
                'regex:/^0[0-9]{9}$/',
                'size:10'
            ]
        ];
    }
}
