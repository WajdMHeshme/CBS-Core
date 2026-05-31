<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_id',
        'employee_id',
        'start_date',
        'end_date',
        'status',
        'notes',
        'rejection_reason',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'start_date'   => 'date',
        'end_date'     => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function messages()
    {
        return $this->hasMany(BookingMessage::class);
    }

    public function commission()
    {
        return $this->hasOne(BookingCommission::class);
    }
    public function conversations()
    {
        return $this->hasMany(BookingConversation::class);
    }
}
