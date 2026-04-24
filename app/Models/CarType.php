<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get all cars of this type
     */
    public function cars()
    {
        return $this->hasMany(Car::class, 'car_type_id');
    }
}
