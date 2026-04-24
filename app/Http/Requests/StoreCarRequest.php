<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',

            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',

            'car_type_id' => 'nullable|exists:car_types,id',
            'city' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'seats' => 'nullable|integer|min:1',
            'price_per_day' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:available,booked,rented,hidden',
            'description' => 'nullable|string',

            'amenity_ids' => 'nullable|array',
            'amenity_ids.*' => 'exists:amenities,id',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ];
    }
}
