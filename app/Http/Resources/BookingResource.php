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
            'start_date' => $this->start_date?->format('Y-m-d H:i'),
            'end_date' => $this->end_date?->format('Y-m-d H:i'),
            'booking_plan' => $this->bookingPlan?->name,
            'final_price'  => $this->final_price,
            'notes' => $this->notes,
            'rejection_reason' => $this->rejection_reason,

            // Car instead of car
            'car' => $this->car ? [
                'id' => $this->car->id,
                'title' => $this->car->title,
                'brand' => $this->car->brand,
                'model' => $this->car->model,
                'price_per_day' => $this->car->price_per_day,
                'images' => $this->car->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'url' => asset('storage/' . $image->path),
                    ];
                }),
            ] : null,

            // Customer (user)
            'customer' => $this->user ? [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ] : null,

            // Employee
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
