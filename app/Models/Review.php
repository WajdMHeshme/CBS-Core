<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'car_id',
        'rating',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
