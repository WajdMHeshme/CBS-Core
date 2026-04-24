<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * The cars that belong to the amenity.
     */
    public function cars()
    {
        return $this->belongsToMany(
            Car::class,
            'car_amenity',
            'amenity_id',
            'car_id'
        );
    }
}
