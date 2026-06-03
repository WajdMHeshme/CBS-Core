<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        Car::factory()
            ->count(10)
            ->create()
            ->each(function ($car) {

                $images = $this->carImages();

                shuffle($images);

                $selectedImages = array_slice(
                    $images,
                    0,
                    rand(3, min(5, count($images)))
                );

                foreach ($selectedImages as $index => $image) {
                    $car->images()->create([
                        'path' => $image,
                        'is_main' => $index === 0,
                        'alt' => 'Car image',
                    ]);
                }
            });
    }

    private function carImages(): array
    {
        return [
            'cars/car1.webp',
            'cars/car2.webp',
            'cars/car3.webp',
            'cars/car4.webp',
            'cars/car5.webp',
            'cars/car6.webp',
            'cars/car7.webp',
            'cars/car8.webp',
            'cars/car9.webp',
        ];
    }
}
