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

        $query->when(
            !empty($filters['search']),
            function ($q) use ($filters) {
                $search = $filters['search'];

                $q->where(function ($sub) use ($search) {
                    $sub->where('brand', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%")
                        ->orWhere('plate_number', 'like', "%{$search}%");
                });
            }
        );

        $query->when(
            !empty($filters['car_types']),
            fn($q) => $q->whereIn('car_type_id', (array) $filters['car_types'])
        );

        $query->when(
            !empty($filters['status']),
            fn($q) => $q->where('status', $filters['status'])
        );

        $query->when(
            isset($filters['min_price_per_day']) && $filters['min_price_per_day'] !== '',
            fn($q) => $q->where('price_per_day', '>=', $filters['min_price_per_day'])
        );

        $query->when(
            isset($filters['max_price_per_day']) && $filters['max_price_per_day'] !== '',
            fn($q) => $q->where('price_per_day', '<=', $filters['max_price_per_day'])
        );

        $sortBy = in_array($filters['sort'] ?? null, ['brand', 'model', 'price_per_day', 'created_at'])
            ? $filters['sort']
            : 'created_at';

        $order = strtolower($filters['order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sortBy, $order)
            ->paginate($filters['limit'] ?? 6);
    }

    public function create(array $data): Car
    {
        $amenities = $data['amenity_ids'] ?? [];
        $images = $data['images'] ?? [];
        unset($data['amenity_ids'], $data['images']);

        $data['user_id'] = auth()->id();
        $data['year'] = $data['year'] ?? now()->year;
        $data['price_per_day'] = $data['price_per_day'] ?? 0;

        $car = Car::create($data);

        if (!empty($amenities)) {
            $car->amenities()->sync($amenities);
        }

        // ✅ الحل - أضف is_main للصورة الأولى
        foreach ($images as $i => $image) {
            $path = $image->store('cars', 'public');

            $car->images()->create([
                'path'    => $path,
                'is_main' => $i === 0, // الصورة الأولى تصير main
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
