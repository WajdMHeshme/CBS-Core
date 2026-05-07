<?php

namespace App\Http\Controllers\Lessor;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarType;
use App\Models\Amenity;

class LessorDashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $cars = Car::with(['carType', 'mainImage'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(6);

        $carTypes = CarType::all();
        $amenities = Amenity::all();

        return view('dashboard.lessor.cars.index', compact(
            'cars',
            'carTypes',
            'amenities'
        ));
    }
}
