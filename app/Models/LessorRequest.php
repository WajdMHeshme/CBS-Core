<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessorRequest extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'phone',
        'message',
        'status',
        'identity_front_image',
        'identity_back_image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
