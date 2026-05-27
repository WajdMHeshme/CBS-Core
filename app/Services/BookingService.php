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
        $car = Car::findOrFail($data['car_id']);

        return DB::transaction(function () use ($data, $userId, $car) {

            $car = Car::lockForUpdate()->findOrFail($car->id);

            if (!empty($data['start_date']) && !empty($data['end_date'])) {
                $this->ensureCarAvailable(
                    $car->id,
                    $data['start_date'],
                    $data['end_date']
                );
            }

            $booking = $this->bookings->create([
                'car_id'       => $car->id,
                'user_id'      => $userId,
                'employee_id'  => $car->employee_id ?? null,
                'scheduled_at' => $data['scheduled_at'] ?? null,
                'start_date'   => $data['start_date'] ?? null,
                'end_date'     => $data['end_date'] ?? null,
                'status'       => 'pending',
            ]);

            event(new BookingCreated($booking));

            return $booking;
        });
    }

    /**
     * Check availability
     */
    private function ensureCarAvailable(int $carId, string $start, string $end): void
    {
        $conflict = $this->bookings
            ->getCarBookingsInRange($carId, $start, $end);

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
