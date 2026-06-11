<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_proof.required' => 'Payment proof is required',
            'payment_proof.mimes' => 'File must be jpg, png, jpeg or pdf',
            'payment_proof.max' => 'File size must not exceed 5MB',
        ];
    }
}
