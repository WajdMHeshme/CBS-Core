<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingCommission;
use App\Models\User;

class CommissionService
{
    public function createForBooking(
        Booking $booking,
        User $employee,
        float $amount
    ): BookingCommission {

        return BookingCommission::updateOrCreate(
            [
                'booking_id' => $booking->id,
            ],
            [
                'employee_id' => $employee->id,
                'lessor_id'     => $booking->car->user_id,
                'amount'      => round($amount, 2),
                'currency'    => 'SYP',
                'status'      => 'pending',
            ]
        );
    }

    public function uploadPaymentProof(
        BookingCommission $commission,
        ?string $reference,
        ?string $imagePath
    ): BookingCommission {

        if ($commission->status !== 'pending') {
            throw new \Exception('Cannot upload payment in current status');
        }

        $commission->update([
            'payment_reference' => $reference,
            'payment_image'     => $imagePath,
            'status'            => 'payment_uploaded',
        ]);

        return $commission->fresh();
    }

    public function approve(
        BookingCommission $commission,
        User $reviewer,
        ?string $notes = null
    ): BookingCommission {

        $commission->update([
            'status'      => 'paid',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'notes'       => $notes,
        ]);

        return $commission->fresh();
    }

    public function reject(
        BookingCommission $commission,
        User $reviewer,
        ?string $notes = null
    ): BookingCommission {

        $commission->update([
            'status'      => 'rejected',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'notes'       => $notes,
        ]);

        return $commission->fresh();
    }
}
