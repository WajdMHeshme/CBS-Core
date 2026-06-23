<?php

namespace App\Services;

use App\Events\BookingCreated;
use App\Models\Booking;
use App\Models\Car;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\BookingPlan;
use Carbon\Carbon;

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
            $plan = BookingPlan::findOrFail(
                $data['booking_plan_id']
            );

            $start = $data['start_date'];
            $end   = $data['end_date'];

            $days = Carbon::parse($start)
                ->diffInDays(Carbon::parse($end));

            $basePrice = $car->price_per_day * $days;

            $finalPrice = $basePrice;

            if ($plan->extra_percentage > 0) {
                $finalPrice += (
                    $basePrice *
                    $plan->extra_percentage / 100
                );
            }

            /**
             * Validate dates
             */
            $this->validateBookingDates($start, $end);

            /**
             * Check if car already booked
             */
            $this->ensureCarAvailable(
                $car->id,
                $start,
                $end
            );

            /**
             * Prevent duplicate user booking
             * ONLY if periods overlap
             */
            $this->ensureUserHasNoActiveBooking(
                $userId,
                $car->id,
                $start,
                $end
            );

            /**
             * Create booking
             */
            $booking = $this->bookings->create([
                'car_id'          => $car->id,
                'user_id'         => $userId,
                'employee_id'     => $car->employee_id ?? null,
                'booking_plan_id' => $plan->id,
                'start_date'      => $start,
                'end_date'        => $end,
                'final_price'     => $finalPrice,
                'status'          => 'pending',
            ]);

            event(new BookingCreated($booking));

            return $booking;
        });
    }

    /**
     * Validate booking dates
     */
    private function validateBookingDates(
        string $start,
        string $end
    ): void {

        if ($start >= $end) {
            throw new \Exception(
                'End date must be after start date'
            );
        }
    }

    /**
     * Prevent duplicate booking requests
     */
    private function ensureUserHasNoActiveBooking(
        int $userId,
        int $carId,
        string $start,
        string $end
    ): void {

        $exists = Booking::where('user_id', $userId)
            ->where('car_id', $carId)

            ->whereIn('status', [
                'pending',
                'approved',
                'rescheduled'
            ])

            ->where(function ($query) use ($start, $end) {

                $query
                    ->where('start_date', '<', $end)
                    ->where('end_date', '>', $start);
            })

            ->exists();

        if ($exists) {
            throw new \Exception(
                'You already have a booking for this car during this period'
            );
        }
    }

    /**
     * Check car availability
     */
    private function ensureCarAvailable(
        int $carId,
        string $start,
        string $end,
        ?int $excludeId = null
    ): void {

        $conflict = Booking::where('car_id', $carId)

            ->when(
                $excludeId,
                fn($q) => $q->where('id', '!=', $excludeId)
            )

            ->whereIn('status', [
                'approved',
                'rescheduled'
            ])

            ->where(function ($query) use ($start, $end) {

                $query
                    ->where('start_date', '<', $end)
                    ->where('end_date', '>', $start);
            })

            ->exists();

        if ($conflict) {
            throw new \Exception('CAR_BOOKED');
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
        $booking->load('bookingPlan');

        if (!in_array($booking->status, ['pending', 'approved'])) {

            if ($booking->status === 'canceled') {
                abort(422, 'This booking is already canceled');
            }

            if ($booking->status === 'completed') {
                abort(422, 'Completed bookings cannot be canceled');
            }

            abort(422, 'This booking cannot be canceled in its current status');
        }

        if (!$booking->bookingPlan->cancellation_allowed) {
            abort(422, 'Cancellation is not allowed for this booking plan');
        }


        return $this->bookings->update($booking, [
            'status' => 'canceled'
        ]);
    }

    /**
     * Get user bookings
     */
    public function getUserBookings(
        int $userId,
        ?string $status = null
    ) {
        return $this->bookings->getUserBookings(
            $userId,
            $status
        );
    }


    public function getBookedPeriodsByCar(int $carId)
    {
        return Booking::where('car_id', $carId)
            ->whereIn('status', ['pending', 'approved', 'rescheduled'])
            ->orderBy('start_date')
            ->get()
            ->map(function ($booking) {
                return [
                    'start' => $booking->start_date,
                    'end'   => $booking->end_date,
                ];
            });
    }
}
