<?php

namespace App\Http\Requests;

use App\Models\CarImage;
use Illuminate\Foundation\Http\FormRequest;

class StoreCarImagesRequest extends FormRequest
{
    public const MAX_IMAGES_PER_CAR = 10;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'alt' => 'nullable|string|max:255',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            $routeCar = $this->route('car');
            $carId = is_object($routeCar) ? $routeCar->id : (int) $routeCar;

            $existingCount = CarImage::where('car_id', $carId)->count();
            $newCount = is_array($this->file('images')) ? count($this->file('images')) : 0;

            if (($existingCount + $newCount) > self::MAX_IMAGES_PER_CAR) {
                $validator->errors()->add(
                    'images',
                    'Maximum ' . self::MAX_IMAGES_PER_CAR . ' images allowed per car.'
                );
            }
        });
    }
}
