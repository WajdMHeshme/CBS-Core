<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingPlan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'cancellation_allowed',
        'cancellation_hours_before',
        'extra_percentage',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
