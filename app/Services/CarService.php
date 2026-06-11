<?php

namespace App\Services;

use App\Models\Car;
use App\Repositories\Contracts\CarRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CarService
{
    public function __construct(
        protected CarRepositoryInterface $cars
    ) {}

    public function getAll(): Collection
    {
        return $this->cars->approvedQuery()
            ->with(['carType', 'mainImage', 'amenities'])
            ->orderByRaw('(select is_pro from users where users.id = cars.user_id) desc')
            ->get();
    }

    public function getPaginated(array $filters = []): LengthAwarePaginator
    {
        $query = $this->cars->approvedQuery()
            ->with(['carType', 'mainImage', 'amenities', 'images'])
            ->orderByRaw('(select is_pro from users where users.id = cars.user_id) desc');

        $this->applyFilters($query, $filters);

        return $query->latest()
            ->paginate($filters['per_page'] ?? 6);
    }

    public function getAdminPaginated(array $filters = []): LengthAwarePaginator
    {
        $query = $this->cars->query()
            ->where('approval_status', 'approved')
            ->with(['carType', 'mainImage', 'amenities', 'images']);

        $this->applyFilters($query, $filters);

        return $query->latest()
            ->paginate($filters['per_page'] ?? 6);
    }

    public function getPendingPaginated(array $filters = []): LengthAwarePaginator
    {
        $query = $this->cars->query()
            ->where('approval_status', 'pending')
            ->with(['carType', 'mainImage', 'amenities', 'images']);

        $this->applyFilters($query, $filters);

        return $query->latest()
            ->paginate($filters['per_page'] ?? 6);
    }

    public function create(array $data): Car
    {
        $user = auth()->user();

        $data['user_id'] = $user->id;

        $data['approval_status'] = $user->hasRole('admin')
            ? 'approved'
            : 'pending';

        $amenities = $data['amenity_ids'] ?? [];
        $images = $data['images'] ?? [];

        unset($data['amenity_ids'], $data['images']);

        $data['year'] = $data['year'] ?? now()->year;
        $data['price_per_day'] = $data['price_per_day'] ?? 0;

        $car = $this->cars->create($data);

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

        $car = $this->cars->update($car, $data);

        if ($amenities !== null) {
            $car->amenities()->sync($amenities);
        }

        return $car;
    }

    public function approve(Car $car): Car
    {
        return $this->cars->update($car, [
            'approval_status' => 'approved',
            'rejection_reason' => null,
        ]);
    }

    public function reject(Car $car, ?string $reason = null): Car
    {
        return $this->cars->update($car, [
            'approval_status' => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    public function delete(Car $car): void
    {
        $this->cars->delete($car);
    }

    public function getLessorCars(int $userId)
    {
        return $this->cars->query()
            ->with(['carType', 'mainImage'])
            ->where('user_id', $userId)
            ->latest()
            ->take(6)
            ->get();
    }

    public function getLessorCarsCount(int $userId)
    {
        return $this->cars->query()
            ->where('user_id', $userId)
            ->count();
    }

    public function getLessorCarsCountByStatus(int $userId, string $status)
    {
        return $this->cars->query()
            ->where('user_id', $userId)
            ->where('approval_status', 'approved')
            ->where('status', $status)
            ->count();
    }

    public function findLessorCarOrFail($id, int $userId)
    {
        return $this->cars->query()
            ->where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        $query->when(
            !empty($filters['model']),
            fn($q) =>
            $q->where('model', 'like', "%{$filters['model']}%")
        );

        $query->when(
            !empty($filters['car_types']),
            fn($q) =>
            $q->whereIn('car_type_id', (array) $filters['car_types'])
        );

        $query->when(!empty($filters['car_type']), function ($q) use ($filters) {
            $q->whereHas(
                'carType',
                fn($sub) =>
                $sub->where('name', $filters['car_type'])
            );
        });

        $query->when(
            isset($filters['min_price']) && $filters['min_price'] !== '',
            fn($q) =>
            $q->where('price_per_day', '>=', $filters['min_price'])
        );

        $query->when(
            isset($filters['max_price']) && $filters['max_price'] !== '',
            fn($q) =>
            $q->where('price_per_day', '<=', $filters['max_price'])
        );

        $query->when(!empty($filters['amenity_ids']), function ($q) use ($filters) {
            $q->whereHas(
                'amenities',
                fn($sub) =>
                $sub->whereIn('amenities.id', (array) $filters['amenity_ids'])
            );
        });
    }
}
