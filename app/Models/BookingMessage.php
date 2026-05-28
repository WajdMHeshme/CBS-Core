<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use App\Models\User;

class BookingMessage extends Model
{
    protected $fillable = [
        'booking_id',
        'sender_id',
        'message',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id'); // ← sender_id هون
    }
}
