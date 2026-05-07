<?php

namespace App\Http\Controllers\Lessor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Amenity;
use App\Models\Car;
use App\Models\CarType;
use App\Services\AmenityService;
use App\Services\CarService;
use App\Services\ImageService;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function __construct(
        protected CarService $carService,
        protected AmenityService $amenityService
    ) {}

    public function index()
    {
        $userId = auth()->id();

        $cars = Car::with(['carType', 'mainImage'])
            ->where('user_id', $userId)
            ->latest()
            ->take(6)
            ->get();

        $carsCount = Car::where('user_id', $userId)->count();

        $availableCars = Car::where('user_id', $userId)
            ->where('status', 'available')
            ->count();

        $bookedCars = Car::where('user_id', $userId)
            ->where('status', 'booked')
            ->count();

        $carTypes = CarType::all();
        $amenities = Amenity::all();

        return view('dashboard.lessor.cars.index', compact(
            'cars',
            'carsCount',
            'availableCars',
            'bookedCars',
            'carTypes',
            'amenities'
        ));
    }

    public function create()
    {
        $amenities = $this->amenityService->getAll();
        $carTypes = CarType::all();

        return view('dashboard.lessor.cars.create', compact('amenities', 'carTypes'));
    }

    public function store(StoreCarRequest $request, ImageService $imageService)
    {
        $data = $request->validated();

        $car = $this->carService->create($data);

        if ($request->hasFile('images')) {
            $imageService->upload(
                $car,
                $request->file('images'),
                $request->input('alt')
            );
        }

        return redirect()
            ->route('lessor.cars.index')
            ->with('success', 'Car added successfully');
    }

    public function edit(Car $car)
    {
        abort_if($car->user_id !== auth()->id(), 403);

        $car->load('images');

        $amenities = $this->amenityService->getAll();
        $carTypes = CarType::all();

        return view('dashboard.lessor.cars.edit', compact('car', 'amenities', 'carTypes'));
    }

    public function update(UpdateCarRequest $request, Car $car)
    {
        abort_if($car->user_id !== auth()->id(), 403);

        $this->carService->update($car, $request->validated());

        return redirect()
            ->route('lessor.cars.index')
            ->with('success', 'Car updated successfully');
    }

    public function destroy(Car $car)
    {
        abort_if($car->user_id !== auth()->id(), 403);

        $this->carService->delete($car);

        return back()->with('success', 'Car deleted successfully');
    }

    public function show(Car $car)
    {
        abort_if($car->user_id !== auth()->id(), 403);

        $car->load(['images', 'amenities', 'carType']);

        return view('dashboard.lessor.cars.show', compact('car'));
    }
}
