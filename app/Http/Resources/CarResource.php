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

            'type' => $this->carType?->name,

            'price_per_day' => $this->price_per_day,
            'status' => $this->status,

            'color' => $this->color,
            'plate_number' => $this->plate_number,

            'description' => $this->description,

            // main image
            'main_image' => $this->images->firstWhere('is_main', true)?->path,

            // all images
            'images' => $this->images->pluck('path'),

            // amenities (optional)
            'features' => $this->amenities->pluck('name'),

            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
