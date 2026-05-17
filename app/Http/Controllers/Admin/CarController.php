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
use Illuminate\Http\Request;
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
        $filters = $this->resolveFilters();

        $cars = $this->carService->getAdminPaginated($filters);

        $amenities = $this->amenityService->getAll();
        $carTypes = CarType::all();

        return view('dashboard.cars.index', compact('cars', 'amenities', 'filters', 'carTypes'));
    }

    public function pending(): View
    {
        $filters = $this->resolveFilters();

        $cars = $this->carService->getPendingPaginated($filters);

        $amenities = $this->amenityService->getAll();
        $carTypes = CarType::all();

        return view('dashboard.cars.pending', compact('cars', 'amenities', 'filters', 'carTypes'));
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

        $this->notifyCarAction('created', $car);

        return redirect()->route('dashboard.admin.cars.index')
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

        $this->notifyCarAction('updated', $car);

        return redirect()->route('dashboard.admin.cars.index')
            ->with('success', 'Car updated successfully');
    }


    public function approve(Car $car): RedirectResponse
    {
        $this->carService->approve($car);

        $this->notifyCarAction('approved', $car);

        return back()->with('success', 'Car approved successfully');
    }

    public function reject(Request $request, Car $car): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $this->carService->reject($car, $validated['rejection_reason'] ?? null);

        $this->notifyCarAction('rejected', $car);

        return back()->with('success', 'Car rejected successfully');
    }

    public function destroy(Car $car): RedirectResponse
    {
        $this->notifyCarAction('deleted', $car);

        $this->carService->delete($car);

        return back()->with('success', 'Car deleted successfully');
    }

    public function show(Car $car): View
    {
        $car->load(['images']);

        return view('dashboard.cars.show', compact('car'));
    }

    private function resolveFilters(): array
    {
        $filters = collect(request()->only([
            'model',
            'car_types',
            'type',
            'min_price',
            'max_price',
            'amenity_ids',
            'sort',
            'order',
            'limit',
        ]));

        $filters = $filters
            ->when(
                $filters->get('type') && ! $filters->has('car_types'),
                fn($col) => $col->put('car_types', Arr::wrap($col->get('type')))
            )
            ->when(
                $filters->get('limit') && ! $filters->has('per_page'),
                fn($col) => $col->put('per_page', $col->get('limit'))
            )
            ->all();

        return $filters;
    }

    private function notifyCarAction(string $action, Car $car): void
    {
        $title = $car->brand . ' ' . $car->model . " (#{$car->id})";
        $by = auth()->user()?->name ?? 'System';

        $users = User::role(['admin', 'employee'])->get();

        foreach ($users as $user) {
            $user->notify(new CarActionNotification($action, $title, $by));
        }
    }
}
