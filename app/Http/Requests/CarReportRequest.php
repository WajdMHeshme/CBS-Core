<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'       => 'nullable|in:available,booked,maintenance',
            'car_type_id'  => 'nullable|exists:car_types,id',
            'from'         => 'nullable|date',
            'to'           => 'nullable|date|after_or_equal:from',
        ];
    }

    public function attributes(): array
    {
        return [
            'status'      => 'car status',
            'car_type_id' => 'car type',
            'from'        => 'from date',
            'to'          => 'to date',
        ];
    }
}
