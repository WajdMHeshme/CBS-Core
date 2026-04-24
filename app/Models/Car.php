<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Amenity;
use App\Models\Booking;
use App\Models\Review;
use App\Models\CarType;
use App\Models\CarImage;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'brand',
        'model',
        'car_type_id',
        'seats',
        'price',
        'price_per_day',
        'status',
        'description',
        'year',
        'color',
        'plate_number',
        'is_furnished',
    ];
    protected $casts = [
        'seats' => 'integer',
        'price' => 'decimal:2',
        'price_per_day' => 'decimal:2',
        'is_furnished' => 'boolean',
        'year' => 'integer',
    ];

    /*
    |-------------------------
    | Relationships
    |-------------------------
    */

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function carType()
    {
        return $this->belongsTo(CarType::class, 'car_type_id');
    }

    public function images()
    {
        return $this->hasMany(CarImage::class, 'car_id');
    }

    public function mainImage()
    {
        return $this->hasOne(CarImage::class, 'car_id')
            ->where('is_main', true);
    }

    public function amenities()
    {
        return $this->belongsToMany(
            Amenity::class,
            'car_amenities',
            'car_id',
            'amenity_id'
        );
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'car_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'car_id');
    }

    /*
    |-------------------------
    | Scopes
    |-------------------------
    */

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
