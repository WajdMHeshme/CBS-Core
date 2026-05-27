<?php


namespace App\Repositories\Contracts;

use App\Models\Car;
use Illuminate\Database\Eloquent\Builder;

interface CarRepositoryInterface
{
    public function query(): Builder;

    public function approvedQuery(): Builder;

    public function create(array $data): Car;

    public function update(Car $car, array $data): Car;

    public function delete(Car $car): void;

    public function findOrFail(int $id): Car;
}
