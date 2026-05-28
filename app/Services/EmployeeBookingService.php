<?php

namespace App\Services;

use App\Models\Booking;
use App\Services\CommissionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EmployeeBookingService
{
    public function __construct(
        protected CommissionService $commissionService
    ) {}

    /**
     * Get bookings for employee + unassigned pending bookings
     */
    public function getEmployeeBooking($employeeId)
    {
        return Booking::with(['user', 'car'])
            ->where(function ($q) use ($employeeId) {
                $q->where('employee_id', $employeeId)
                    ->orWhere(function ($q2) {
                        $q2->whereNull('employee_id')
                            ->where('status', 'pending');
                    });
            })
            ->latest()
            ->paginate(10);
    }

    /**
     * APPROVE
     */
    public function approve(Booking $booking)
    {
        if (!in_array($booking->status, ['pending', 'rescheduled'])) {
            throw ValidationException::withMessages([
                'status' => 'Action not allowed',
            ]);
        }

        if (!$booking->employee_id) {
            $booking->employee_id = Auth::id();
            $booking->save();
        }

        $this->assertNoCarConflict(
            $booking->car_id,
            $booking->start_date,
            $booking->end_date,
            $booking->id
        );

        $booking->update([
            'status' => 'approved',
            'rescheduled_at' => null,
        ]);

        if (!$booking->commission()->exists()) {

            $price = $booking->car->price_per_day;
            $commissionAmount = round($price * 0.05, 2);

            $this->commissionService->createForBooking(
                $booking,
                Auth::user(),
                $commissionAmount
            );
        }

        return $booking;
    }

    /**
     * CANCEL
     */
    public function cancel(Booking $booking)
    {
        if (!in_array($booking->status, ['pending', 'approved', 'rescheduled'])) {
            throw ValidationException::withMessages([
                'status' => 'Action not allowed',
            ]);
        }

        if (!$booking->employee_id) {
            $booking->employee_id = Auth::id();
            $booking->save();
        }

        $booking->update([
            'status' => 'canceled',
        ]);

        return $booking;
    }

    /**
     * RESCHEDULE
     */
    public function reschedule(Booking $booking, $start, $end)
    {
        if (!$booking->employee_id) {
            $booking->employee_id = Auth::id();
            $booking->save();
        }

        if (!in_array($booking->status, ['pending', 'approved', 'rescheduled'])) {
            throw ValidationException::withMessages([
                'status' => 'Action not allowed',
            ]);
        }

        $this->assertNoCarConflict(
            $booking->car_id,
            $start,
            $end,
            $booking->id
        );

        $booking->update([
            'status' => 'rescheduled',
            'start_date' => $start,
            'end_date' => $end,
            'rescheduled_at' => now(),
        ]);

        return $booking;
    }

    /**
     * COMPLETE
     */
    public function complete(Booking $booking)
    {
        if (!$booking->employee_id) {
            $booking->employee_id = Auth::id();
            $booking->save();
        }

        if ($booking->employee_id !== Auth::id()) {
            abort(403, 'Forbidden');
        }

        if (!in_array($booking->status, ['approved', 'rescheduled'])) {
            throw ValidationException::withMessages([
                'status' => 'Action not allowed',
            ]);
        }

        $booking->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return $booking;
    }

    /**
     * REJECT
     */
    public function reject(Booking $booking, $reason = null)
    {
        if (!$booking->employee_id) {
            $booking->employee_id = Auth::id();
            $booking->save();
        }

        if ($booking->employee_id !== Auth::id()) {
            abort(403, 'Forbidden');
        }

        if (!in_array($booking->status, ['pending', 'rescheduled'])) {
            throw ValidationException::withMessages([
                'status' => 'Action is not allowed',
            ]);
        }

        $booking->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'rejected_at' => now(),
        ]);

        return $booking;
    }

    /**
     * UNIFIED CONFLICT CHECK (FIXED)
     */
    private function assertNoCarConflict($carId, $start, $end, $excludeId = null)
    {
        $conflict = Booking::where('car_id', $carId)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->whereIn('status', ['approved', 'rescheduled'])
            ->where('start_date', '<', $end)
            ->where('end_date', '>', $start)
            ->exists();

        if ($conflict) {
            throw ValidationException::withMessages([
                'date' => 'Car already booked for this period',
            ]);
        }
    }
}
