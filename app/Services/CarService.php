<?php

namespace App\Services;

use App\Models\Car;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CarService
{
    public function getAll(): Collection
    {
        return Car::with([
            'carType',
            'mainImage',
            'amenities',
        ])->get();
    }

public function getPaginated(array $filters = []): LengthAwarePaginator
{
    $query = Car::with([
        'carType',
        'mainImage',
        'amenities',
        'images',
    ]);

    // 🚗 Model
    $query->when(
        !empty($filters['model']),
        fn($q) => $q->where('model', 'like', "%{$filters['model']}%")
    );

    // 💰 Price range
    $query->when(
        isset($filters['min_price']) && $filters['min_price'] !== '',
        fn($q) => $q->where('price_per_day', '>=', $filters['min_price'])
    );

    $query->when(
        isset($filters['max_price']) && $filters['max_price'] !== '',
        fn($q) => $q->where('price_per_day', '<=', $filters['max_price'])
    );

    // 🧩 Amenities (many-to-many)
    $query->when(
        !empty($filters['amenity_ids']),
        fn($q) => $q->whereHas('amenities', function ($sub) use ($filters) {
            $sub->whereIn('amenities.id', (array) $filters['amenity_ids']);
        })
    );

    // 🚘 Car Types
    $query->when(
        !empty($filters['car_types']),
        fn($q) => $q->whereIn('car_type_id', (array) $filters['car_types'])
    );

    return $query->latest()->paginate(6);
}

public function create(array $data): Car
{
    $user = auth()->user();

    // ❗ تحديد المالك حسب الدور
    if ($user->hasRole('lessor')) {
        $data['user_id'] = $user->id;
    }

    // admin ممكن يختار user_id أو يكون system
    if ($user->hasRole('admin')) {
        $data['user_id'] = $data['user_id'] ?? $user->id;
    }

    $amenities = $data['amenity_ids'] ?? [];
    $images = $data['images'] ?? [];

    unset($data['amenity_ids'], $data['images']);

    $data['year'] = $data['year'] ?? now()->year;
    $data['price_per_day'] = $data['price_per_day'] ?? 0;

    $car = Car::create($data);

    if (!empty($amenities)) {
        $car->amenities()->sync($amenities);
    }

    foreach ($images as $i => $image) {
        $path = $image->store('cars', 'public');

        $car->images()->create([
            'path' => $path,
            'is_main' => $i === 0,
        ]);
    }

    return $car;
}

    public function update(Car $car, array $data): Car
    {
        $amenities = $data['amenity_ids'] ?? null;
        unset($data['amenity_ids']);

        $car->update($data);

        if ($amenities !== null) {
            $car->amenities()->sync($amenities);
        }

        return $car;
    }

    public function delete(Car $car): void
    {
        $car->delete();
    }
}
