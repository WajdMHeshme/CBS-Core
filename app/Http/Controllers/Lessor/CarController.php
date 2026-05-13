<?php

namespace App\Http\Controllers\Lessor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\CarType;
use App\Services\AmenityService;
use App\Services\CarService;
use App\Services\ImageService;

class CarController extends Controller
{
    public function __construct(
        protected CarService $carService,
        protected AmenityService $amenityService
    ) {}

    public function index()
    {
        $userId = auth()->id();

        // 🚗 Cars (approved only)
        $cars = $this->carService->getLessorCars($userId);

        // 📊 Counts (clean + correct logic)
        $carsCount = $this->carService->getLessorCarsCount($userId);

        $availableCars = $this->carService->getLessorCarsCountByStatus($userId, 'available');

        $bookedCars = $this->carService->getLessorCarsCountByStatus($userId, 'booked');

        $carTypes = CarType::all();
        $amenities = $this->amenityService->getAll();

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
            ->with('success', 'Car submitted successfully and is pending admin approval.');
    }

    public function edit($car)
    {
        $car = $this->carService->findLessorCarOrFail($car, auth()->id());

        $car->load('images');

        $amenities = $this->amenityService->getAll();
        $carTypes = CarType::all();

        return view('dashboard.lessor.cars.edit', compact('car', 'amenities', 'carTypes'));
    }

    public function update(UpdateCarRequest $request, $car)
    {
        $car = $this->carService->findLessorCarOrFail($car, auth()->id());

        $this->carService->update($car, $request->validated());

        return redirect()
            ->route('lessor.cars.index')
            ->with('success', 'Car updated successfully');
    }

    public function destroy($car)
    {
        $car = $this->carService->findLessorCarOrFail($car, auth()->id());

        $this->carService->delete($car);

        return back()->with('success', 'Car deleted successfully');
    }

    public function show($car)
    {
        $car = $this->carService->findLessorCarOrFail($car, auth()->id());

        $car->load(['images', 'amenities', 'carType']);

        return view('dashboard.lessor.cars.show', compact('car'));
    }
}
