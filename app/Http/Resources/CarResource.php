<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'title' => $this->title,

        'brand' => $this->brand,
        'model' => $this->model,
        'year' => $this->year,

        'car_type' => [
            'id' => $this->carType?->id,
            'name' => $this->carType?->name,
        ],

        'price_per_day' => $this->price_per_day,
        'status' => $this->status,

        'color' => $this->color,
        'plate_number' => $this->plate_number,

        'description' => $this->description,

        'owner' => [
            'id' => $this->owner?->id,
            'name' => $this->owner?->name,
        ],

        'images' => [
            'main' => $this->images->firstWhere('is_main', true)?->path,
            'gallery' => $this->images->pluck('path')->values(),
        ],

        'features' => $this->amenities->map(function ($a) {
            return [
                'id' => $a->id,
                'name' => $a->name,
            ];
        }),

        'created_at' => $this->created_at?->toDateTimeString(),
    ];
}
}
