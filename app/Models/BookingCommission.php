<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingCommission extends Model
{
    protected $fillable = [
        'booking_id',
        'employee_id',
        'lessor_id',
        'amount',
        'currency',
        'status',
        'payment_reference',
        'payment_image',
        'reviewed_at',
        'reviewed_by',
        'notes',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function lessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lessor_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaymentUploaded(): bool
    {
        return $this->status === 'payment_uploaded';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
