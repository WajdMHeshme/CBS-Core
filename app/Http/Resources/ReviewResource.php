<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'rating' => $this->rating,
            'comment' => $this->comment,

            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),

            'car' => $this->whenLoaded('car', function () {
                return [
                    'id' => $this->car->id,
                    'title' => $this->car->title,
                ];
            }),

            'booking_id' => $this->booking_id,

            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
