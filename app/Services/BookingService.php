<?php

namespace App\Services;

use App\Events\BookingCreated;
use App\Models\Booking;
use App\Models\Car;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function __construct(
        protected BookingRepositoryInterface $bookings
    ) {}

    /**
     * Create booking
     */
    public function create(array $data, int $userId): Booking
    {
        return DB::transaction(function () use ($data, $userId) {

            $car = Car::lockForUpdate()->findOrFail($data['car_id']);

            $start = $data['start_date'];
            $end   = $data['end_date'];

            $this->ensureCarAvailable(
                $car->id,
                $start,
                $end
            );

            $booking = $this->bookings->create([
                'car_id'      => $car->id,
                'user_id'     => $userId,
                'employee_id' => $car->employee_id ?? null,
                'start_date'  => $start,
                'end_date'    => $end,
                'status'      => 'pending',
            ]);

            event(new BookingCreated($booking));

            return $booking;
        });
    }

    /**
     * Check availability (FIXED & RELIABLE)
     */
    private function ensureCarAvailable(int $carId, string $start, string $end, ?int $excludeId = null): void
    {
        $conflict = Booking::where('car_id', $carId)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->whereIn('status', ['approved', 'rescheduled'])
            ->where('start_date', '<', $end)
            ->where('end_date', '>', $start)
            ->exists();

        if ($conflict) {
            throw new \Exception('Car is already booked for this period');
        }
    }

    /**
     * Show booking
     */
    public function show(Booking $booking): Booking
    {
        return $this->bookings->findById($booking->id);
    }

    /**
     * Cancel booking
     */
    public function cancel(Booking $booking): Booking
    {
        if (!in_array($booking->status, ['pending', 'approved'])) {
            throw new \Exception(
                'Only pending or approved bookings can be canceled'
            );
        }

        return $this->bookings->update($booking, [
            'status' => 'canceled'
        ]);
    }

    /**
     * Get user bookings
     */
    public function getUserBookings(int $userId, ?string $status = null)
    {
        return $this->bookings->getUserBookings($userId, $status);
    }
}
