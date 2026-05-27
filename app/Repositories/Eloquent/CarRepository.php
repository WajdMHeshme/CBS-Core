<?php

namespace App\Repositories\Eloquent;
use App\Models\Car;
use App\Repositories\Contracts\CarRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class CarRepository implements CarRepositoryInterface
{
    public function query(): Builder
    {
        return Car::query();
    }

    public function approvedQuery(): Builder
    {
        return Car::where('approval_status', 'approved');
    }

    public function create(array $data): Car
    {
        return Car::create($data);
    }

    public function update(Car $car, array $data): Car
    {
        $car->update($data);
        return $car;
    }

    public function delete(Car $car): void
    {
        $car->delete();
    }

    public function findOrFail(int $id): Car
    {
        return Car::findOrFail($id);
    }
}
