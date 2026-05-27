<?php

namespace App\Repositories\Eloquent;

use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;

class BookingRepository implements BookingRepositoryInterface
{
    public function findById(int $id): ?Booking
    {
        return Booking::with(['car', 'user', 'employee'])
            ->find($id);
    }

    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    public function update(Booking $booking, array $data): Booking
    {
        $booking->update($data);

        return $booking->fresh();
    }

    public function delete(Booking $booking): bool
    {
        return $booking->delete();
    }

    /**
     * Check overlapping bookings
     */
    public function getCarBookingsInRange(int $carId, string $start, string $end): bool
    {
        return Booking::where('car_id', $carId)
            ->whereNotIn('status', ['canceled', 'rejected'])
            ->where('start_date', '<', $end)
            ->where('end_date', '>', $start)
            ->exists();
    }

    /**
     * Get user bookings
     */
    public function getUserBookings(int $userId, ?string $status = null)
    {
        return Booking::with(['car', 'user', 'employee'])
            ->where('user_id', $userId)
            ->when($status, fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10);
    }
}
