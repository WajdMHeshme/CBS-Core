<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Services\BookingService;
use App\Services\CarService;
use Illuminate\Http\Request;

class CarController extends Controller
{
    protected CarService $carService;
    protected BookingService $bookingService;

    public function __construct(
        CarService $carService,
        BookingService $bookingService
    ) {
        $this->carService = $carService;
        $this->bookingService = $bookingService;
    }

    /**
     * GET /api/cars
     */
    public function index(Request $request)
    {
        $cars = $this->carService->getPaginated($request->all());

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


    public function bookedPeriods($carId)
    {
        return response()->json(
            $this->bookingService->getBookedPeriodsByCar((int) $carId)
        );
    }
}
