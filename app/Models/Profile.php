<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'bio',
        'avatar',
        'address',
        'country',
        'gender',
        'birth_date',
        'phone',
        'city'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
