<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'car_id',
        'path',
        'is_main',
        'alt'
    ];

    protected $casts = [
        'is_main' => 'boolean'
    ];

    /**
     * Image belongs to a Car
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
