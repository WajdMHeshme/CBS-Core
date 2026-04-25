<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Create booking (transaction)
     */
    public function create(array $data): Booking
    {

        $car = Car::find($data['car_id'] ?? null);

        if (! $car) {
            throw new \Exception('Car not found');
        }


        $userId = auth('sanctum')->id();

        if (! $userId) {
            throw new \Exception('Unauthenticated');
        }

        if (!empty($data['start_date']) && !empty($data['end_date'])) {

            $isBooked = Booking::where('car_id', $car->id)
                ->where(function ($q) use ($data) {
                    $q->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                      ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']]);
                })
                ->exists();

            if ($isBooked) {
                throw new \Exception('Car is already booked for this period');
            }
        }

        return DB::transaction(function () use ($data, $userId, $car) {

            $booking = Booking::create([
                'car_id' => $car->id,
                'user_id' => $userId,

                'employee_id' => $car->employee_id ?? null,

                'scheduled_at' => $data['scheduled_at'] ?? null,
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,

                'status' => 'pending',
            ]);

            return $booking->load(['car', 'employee', 'user']);
        });
    }

    /**
     * Show booking details
     */
    public function show(Booking $booking)
    {
        return $booking->load([
            'car',
            'employee',
            'user',
        ]);
    }

    /**
     * Cancel booking
     */
    public function cancel(Booking $booking)
    {
        $booking->update([
            'status' => 'canceled',
        ]);

        return $booking;
    }
}
