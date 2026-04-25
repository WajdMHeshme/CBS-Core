<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'scheduled_at' => $this->scheduled_at?->format('Y-m-d H:i'),
            'notes' => $this->notes,
            'rejection_reason' => $this->rejection_reason,

            // 🚗 Car instead of property
            'car' => $this->car ? [
                'id' => $this->car->id,
                'title' => $this->car->title,
                'brand' => $this->car->brand,
                'model' => $this->car->model,
                'price_per_day' => $this->car->price_per_day,
            ] : null,

            // 👤 Customer (user)
            'customer' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ] : null,

            // 👷 Employee
            'employee' => $this->employee ? [
                'id' => $this->employee->id,
                'name' => $this->employee->name,
            ] : null,
        ];
    }

    public function with($request)
    {
        return [
            'meta' => [
                'created_at' => $this->created_at?->diffForHumans(),
                'updated_at' => $this->updated_at?->diffForHumans(),
            ],
        ];
    }
}
