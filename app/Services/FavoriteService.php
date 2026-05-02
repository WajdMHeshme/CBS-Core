<?php

namespace App\Services;

use App\Models\Favorite;
use App\Models\Car;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class FavoriteService
{
    public function addToFavorites($carId)
    {
        $car = Car::find($carId);

        if (!$car) {
            throw ValidationException::withMessages([
                'car_id' => 'Car not found'
            ]);
        }

        $exists = Favorite::where('user_id', Auth::id())
            ->where('car_id', $carId)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'car_id' => 'Car is already in favorites'
            ]);
        }

        return Favorite::create([
            'user_id' => Auth::id(),
            'car_id' => $carId,
        ]);
    }

    public function removeFromFavorites($carId)
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('car_id', $carId)
            ->first();

        if (!$favorite) {
            throw ValidationException::withMessages([
                'car_id' => 'Car not in favorites'
            ]);
        }

        return $favorite->delete();
    }

    public function getFavorites()
    {
        return Favorite::with('car')
            ->where('user_id', Auth::id())
            ->get();
    }
}
