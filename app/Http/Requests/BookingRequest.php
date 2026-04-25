<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'car_id' => 'required|exists:cars,id',

            'scheduled_at' => 'required|date|after:now',

            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',

            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'car_id.required' => 'Car is required',
            'car_id.exists' => 'Selected car does not exist',

            'scheduled_at.after' => 'The booking date must be in the future',
            'scheduled_at.date' => 'Invalid date format',

            'start_date.after_or_equal' => 'Start date must be today or later',
            'end_date.after' => 'End date must be after start date',
        ];
    }
}
