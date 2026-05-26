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

        $favorite = Favorite::create([
            'user_id' => Auth::id(),
            'car_id'  => $carId,
        ]);

        return response()->json([
            'message' => 'Car added to favorites successfully.',
            'data' => Favorite::with('car.images')
                ->find($favorite->id)
        ]);
    }

    public function removeFromFavorites($carId)
    {
        $favorite = Favorite::with('car.images')
            ->where('user_id', Auth::id())
            ->where('car_id', $carId)
            ->first();

        if (!$favorite) {
            throw ValidationException::withMessages([
                'car_id' => 'Car not in favorites'
            ]);
        }

        // حفظ البيانات قبل الحذف
        $favoriteData = $favorite->toArray();

        $favorite->delete();

        return response()->json([
            'message' => 'Car removed from favorites successfully.',
            'data' => $favoriteData
        ]);
    }

    public function getFavorites()
    {
        return response()->json([
            'data' => Favorite::with('car.images')
                ->where('user_id', Auth::id())
                ->get()
        ]);
    }
}
