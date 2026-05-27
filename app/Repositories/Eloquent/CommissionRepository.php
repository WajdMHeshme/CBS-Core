<?php

namespace App\Repositories\Eloquent;

use App\Models\Booking;
use App\Models\BookingCommission;
use App\Repositories\Contracts\CommissionRepositoryInterface;

class CommissionRepository implements CommissionRepositoryInterface
{
    public function createOrUpdateForBooking(
        Booking $booking,
        int $employeeId,
        float $amount
    ): BookingCommission {
        return BookingCommission::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'employee_id' => $employeeId,
                'lessor_id'   => $booking->car->user_id,
                'amount'      => round($amount, 2),
                'currency'    => 'SYP',
                'status'      => 'pending',
            ]
        );
    }

    public function find(int $id): BookingCommission
    {
        return BookingCommission::findOrFail($id);
    }
}
