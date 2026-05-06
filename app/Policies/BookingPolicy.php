<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Booking $booking): bool
    {
        return
            $user->hasRole('admin') ||
            $booking->user_id === $user->id ||
            ($user->hasRole('employee') &&
                ($booking->employee_id == $user->id || is_null($booking->employee_id)));
    }

    public function approve(User $user, Booking $booking): bool
    {
        if ($user->hasRole('admin')) return true;

        return
            $user->hasRole('employee') &&
            ($booking->employee_id == $user->id || is_null($booking->employee_id)) &&
            in_array($booking->status, ['pending', 'rescheduled']);
    }

    public function reject(User $user, Booking $booking): bool
    {
        if ($user->hasRole('admin')) return true;

        return
            $user->hasRole('employee') &&
            ($booking->employee_id == $user->id || is_null($booking->employee_id)) &&
            in_array($booking->status, ['pending', 'rescheduled']);
    }

    public function employeeCancel(User $user, Booking $booking): bool
    {
        if ($user->hasRole('admin')) return true;

        return
            $user->hasRole('employee') &&
            $booking->employee_id === $user->id &&
            in_array($booking->status, ['pending', 'approved', 'rescheduled']);
    }

    public function reschedule(User $user, Booking $booking): bool
    {
        if ($user->hasRole('admin')) return true;

        return
            $user->hasRole('employee') &&
            $booking->employee_id === $user->id &&
            in_array($booking->status, ['pending', 'approved', 'rescheduled']);
    }

    public function complete(User $user, Booking $booking): bool
    {
        if ($user->hasRole('admin')) return true;

        return
            $user->hasRole('employee') &&
            $booking->employee_id === $user->id &&
            $booking->status === 'approved';
    }
}
