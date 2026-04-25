<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use App\Models\CarType;
use App\Models\User;
use App\Notifications\CarActionNotification;
use App\Services\AmenityService;
use App\Services\CarService;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class CarController extends Controller
{
    protected CarService $carService;
    protected AmenityService $amenityService;

    public function __construct(CarService $carService, AmenityService $amenityService)
    {
        $this->carService = $carService;
        $this->amenityService = $amenityService;
    }

    public function index(): View
    {
        $filters = collect(request()->only([
            'car_type_ids',
            'type',
            'city',
            'min_price',
            'max_price',
            'sort',
            'order',
            'limit',
        ]));

        $filters = $filters
            ->when(
                $filters->get('type') && ! $filters->has('car_type_ids'),
                fn($col) => $col->put('car_type_ids', Arr::wrap($col->get('type')))
            )
            ->all();

        $cars = $this->carService->getPaginated($filters);

        $amenities = $this->amenityService->getAll();
        $carTypes = CarType::all();

        return view('dashboard.cars.index', compact('cars', 'amenities', 'filters', 'carTypes'));
    }

    public function create(): View
    {
        $amenities = $this->amenityService->getAll();
        $carTypes = CarType::all();

        return view('dashboard.cars.create', compact('amenities', 'carTypes'));
    }

    public function store(StoreCarRequest $request, ImageService $imageService): RedirectResponse
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

        $title = $car->brand . ' ' . $car->model . " (#{$car->id})";
        $by = auth()->user()?->name ?? 'System';

        $users = User::role(['admin', 'employee'])->get();
        foreach ($users as $user) {
            $user->notify(new CarActionNotification('created', $title, $by));
        }

        return redirect()->route('dashboard.cars.index')
            ->with('success', 'Car added successfully');
    }
    public function edit(Car $car): View
    {
        $car->load('images');
        $amenities = $this->amenityService->getAll();
        $carTypes = CarType::all();

        return view('dashboard.cars.edit', compact('car', 'amenities', 'carTypes'));
    }

    public function update(UpdateCarRequest $request, Car $car): RedirectResponse
    {
        $data = $request->validated();

        $this->carService->update($car, $data);

        $car->refresh();

        $title = $car->brand . ' ' . $car->model . " (#{$car->id})";
        $by = auth()->user()?->name ?? 'System';

        $users = User::role(['admin', 'employee'])->get();
        foreach ($users as $user) {
            $user->notify(new CarActionNotification('updated', $title, $by));
        }

        return redirect()->route('dashboard.cars.index')
            ->with('success', 'Car updated successfully');
    }

    public function destroy(Car $car): RedirectResponse
    {
        $title = $car->brand . ' ' . $car->model . " (#{$car->id})";
        $by = auth()->user()?->name ?? 'System';

        $this->carService->delete($car);

        $users = User::role(['admin', 'employee'])->get();
        foreach ($users as $user) {
            $user->notify(new CarActionNotification('deleted', $title, $by));
        }

        return back()->with('success', 'Car deleted successfully');
    }

    public function show(Car $car): View
    {
        $car->load(['images']);

        return view('dashboard.cars.show', compact('car'));
    }
}
