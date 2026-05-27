<?php

namespace App\Repositories\Contracts;

use App\Models\Booking;

interface BookingRepositoryInterface
{
    public function findById(int $id): ?Booking;

    public function create(array $data): Booking;

    public function update(Booking $booking, array $data): Booking;

    public function delete(Booking $booking): bool;

    public function getCarBookingsInRange(int $carId, string $start, string $end): bool;

    public function getUserBookings(int $userId, ?string $status = null);
}
