<?php

namespace App\Services;

use App\Models\Booking;
use App\Services\CommissionService;
use Carbon\Carbon;
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
     * Approve booking
     */
public function approve(Booking $booking)
{
    if (!in_array($booking->status, ['pending', 'rescheduled'])) {
        throw ValidationException::withMessages([
            'status' => 'Action not allowed. Booking must be pending or rescheduled.',
        ]);
    }

    if (is_null($booking->employee_id)) {
        $booking->employee_id = Auth::id();
        $booking->save();
    }

    if ($this->hasTimeConflict(
        $booking->car_id,
        $booking->scheduled_at,
        $booking->id
    )) {
        throw ValidationException::withMessages([
            'scheduled_at' => 'This car is already booked at this time.',
        ]);
    }

    $booking->update([
        'status' => 'approved',
        'rescheduled_at' => null,
    ]);

    //  حساب العمولة بشكل آمن
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
     * Cancel booking
     */
    public function cancel(Booking $booking)
    {
        if (!in_array($booking->status, ['pending', 'approved', 'rescheduled'])) {
            throw ValidationException::withMessages([
                'status' => 'Action not allowed',
            ]);
        }

        if (is_null($booking->employee_id)) {
            $booking->employee_id = Auth::id();
            $booking->save();
        }

        $booking->update([
            'status' => 'canceled',
        ]);

        return $booking;
    }

    /**
     * Reschedule booking
     */
    public function reschedule(Booking $booking, $scheduleAt)
    {
        if (is_null($booking->employee_id)) {
            $booking->employee_id = Auth::id();
            $booking->save();
        }

        if ($this->hasTimeConflict(
            $booking->employee_id,
            $scheduleAt,
            $booking->id
        )) {
            throw ValidationException::withMessages([
                'scheduled_at' => 'Employee already has a booking at this time',
            ]);
        }

        if (!in_array($booking->status, ['pending', 'approved', 'rescheduled'])) {
            throw ValidationException::withMessages([
                'status' => 'Action not allowed',
            ]);
        }

        $booking->update([
            'status' => 'rescheduled',
            'scheduled_at' => $scheduleAt,
            'rescheduled_at' => now(),
        ]);

        return $booking;
    }

    /**
     * Complete booking
     */
    public function complete(Booking $booking)
    {
        if (is_null($booking->employee_id)) {
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
     * Time conflict check
     */
    public function hasTimeConflict($carId, $scheduledAt, $excludeId = null)
    {
        if (empty($carId)) {
            return false;
        }

        return Booking::where('car_id', $carId)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->whereIn('status', ['pending', 'approved', 'rescheduled'])
            ->whereBetween('scheduled_at', [
                Carbon::parse($scheduledAt)->subHour(),
                Carbon::parse($scheduledAt)->addHour(),
            ])
            ->exists();
    }

    /**
     * Reject booking
     */
    public function reject(Booking $booking, $reason = null)
    {
        if (is_null($booking->employee_id)) {
            $booking->employee_id = Auth::id();
            $booking->save();
        }

        if ($booking->employee_id !== Auth::id()) {
            abort(403, 'You are not allowed to reject this booking');
        }

        if (!in_array($booking->status, ['pending', 'rescheduled'])) {
            throw ValidationException::withMessages([
                'status' => 'Action is not allowed. Booking must be pending or rescheduled.',
            ]);
        }

        $booking->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'rejected_at' => now(),
        ]);

        return $booking;
    }
}
