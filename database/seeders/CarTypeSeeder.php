<?php

namespace Database\Seeders;

use App\Models\CarType;
use Illuminate\Database\Seeder;

class CarTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Sedan',
            'SUV',
            'Hatchback',
            'Truck',
            'Van',
        ];

        foreach ($types as $type) {
            CarType::firstOrCreate(['name' => $type]);
        }
    }
}
