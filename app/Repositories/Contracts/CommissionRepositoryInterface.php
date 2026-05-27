<?php

namespace App\Repositories\Contracts;

use App\Models\Booking;
use App\Models\BookingCommission;

interface CommissionRepositoryInterface
{
    public function createOrUpdateForBooking(
        Booking $booking,
        int $employeeId,
        float $amount
    ): BookingCommission;

    public function find(int $id): BookingCommission;
}
