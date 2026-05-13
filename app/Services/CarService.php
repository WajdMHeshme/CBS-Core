<?php

namespace App\Services;

use App\Models\Car;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CarService
{
    public function getAll(): Collection
    {
        return $this->approvedQuery()
            ->with([
                'carType',
                'mainImage',
                'amenities',
            ])
            ->get();
    }

    public function getPaginated(array $filters = []): LengthAwarePaginator
    {
        $query = $this->approvedQuery()->with([
            'carType',
            'mainImage',
            'amenities',
            'images',
        ]);

        $this->applyFilters($query, $filters);

        return $query->latest()->paginate($filters['per_page'] ?? 6);
    }

    public function getAdminPaginated(array $filters = []): LengthAwarePaginator
    {
        $query = $this->baseQuery()
            ->where('approval_status', 'approved')
            ->with([
                'carType',
                'mainImage',
                'amenities',
                'images',
            ]);

        $this->applyFilters($query, $filters);

        return $query->latest()->paginate($filters['per_page'] ?? 6);
    }

    public function getPendingPaginated(array $filters = []): LengthAwarePaginator
    {
        $query = $this->baseQuery()
            ->where('approval_status', 'pending')
            ->with([
                'carType',
                'mainImage',
                'amenities',
                'images',
            ]);

        $this->applyFilters($query, $filters);

        return $query->latest()->paginate($filters['per_page'] ?? 6);
    }

    public function create(array $data): Car
    {
        $user = auth()->user();

        if ($user?->hasRole('lessor')) {
            $data['user_id'] = $user->id;
        }

        if ($user?->hasRole('admin')) {
            $data['user_id'] = $data['user_id'] ?? $user->id;
        }

        $data['approval_status'] = $user?->hasRole('admin')
            ? 'approved'
            : 'pending';

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

    public function approve(Car $car): Car
    {
        $car->update([
            'approval_status' => 'approved',
            'rejection_reason' => null,
        ]);

        return $car;
    }

    public function reject(Car $car, ?string $reason = null): Car
    {
        $car->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $reason,
        ]);

        return $car;
    }

    public function delete(Car $car): void
    {
        $car->delete();
    }

    private function baseQuery(): Builder
    {
        return Car::query();
    }

    private function approvedQuery(): Builder
    {
        return Car::approved();
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        $query->when(!empty($filters['model']), function ($q) use ($filters) {
            $q->where('model', 'like', "%{$filters['model']}%");
        });

        $query->when(!empty($filters['car_types']), function ($q) use ($filters) {
            $q->whereIn('car_type_id', (array) $filters['car_types']);
        });

        $query->when(!empty($filters['car_type']), function ($q) use ($filters) {
            $q->whereHas('carType', function ($sub) use ($filters) {
                $sub->where('name', $filters['car_type']);
            });
        });

        $query->when(isset($filters['min_price']) && $filters['min_price'] !== '', function ($q) use ($filters) {
            $q->where('price_per_day', '>=', $filters['min_price']);
        });

        $query->when(isset($filters['max_price']) && $filters['max_price'] !== '', function ($q) use ($filters) {
            $q->where('price_per_day', '<=', $filters['max_price']);
        });

        $query->when(!empty($filters['amenity_ids']), function ($q) use ($filters) {
            $q->whereHas('amenities', function ($sub) use ($filters) {
                $sub->whereIn('amenities.id', (array) $filters['amenity_ids']);
            });
        });
    }

    public function getLessorCars(int $userId)
    {
        return Car::with(['carType', 'mainImage'])
            ->where('user_id', $userId)
            ->latest()
            ->take(6)
            ->get();
    }

    public function getLessorCarsCount(int $userId)
    {
        return Car::where('user_id', $userId)->count();
    }

    public function getLessorCarsCountByStatus(int $userId, string $status)
    {
        return Car::where('user_id', $userId)
            ->where('approval_status', 'approved')
            ->where('status', $status)
            ->count();
    }

    public function findLessorCarOrFail($id, int $userId)
    {
        return Car::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();
    }
}
