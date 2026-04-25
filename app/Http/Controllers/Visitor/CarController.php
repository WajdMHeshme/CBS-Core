<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Services\CarService;
use Illuminate\Http\Request;

class CarController extends Controller
{
    protected CarService $carService;

    public function __construct(CarService $carService)
    {
        $this->carService = $carService;
    }

    /**
     * GET /api/cars
     */
    public function index(Request $request)
    {
        $cars = $this->carService->getAll($request->all());

        return CarResource::collection($cars);
    }

    /**
     * GET /api/cars/{id}
     */
    public function show($id)
    {
        $car = Car::with([
            'carType',
            'images',
            'amenities'
        ])->find($id);

        if (! $car) {
            return response()->json([
                'message' => 'Car not found',
            ], 404);
        }

        return new CarResource($car);
    }
}
