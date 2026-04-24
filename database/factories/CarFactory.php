<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\User;
use App\Models\CarType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition(): array
    {
        $brands = [
            'Toyota' => ['Camry', 'Corolla', 'RAV4', 'Land Cruiser'],
            'BMW'    => ['X5', 'X3', '320i', '520i'],
            'Audi'   => ['A4', 'A6', 'Q5', 'Q7'],
            'Kia'    => ['Sportage', 'Sorento', 'Cerato'],
            'Hyundai' => ['Elantra', 'Tucson', 'Santa Fe'],
            'Ford'   => ['Mustang', 'Explorer', 'F-150'],
        ];

        $brand = $this->faker->randomElement(array_keys($brands));
        $model = $this->faker->randomElement($brands[$brand]);

        return [
            'user_id'       => User::inRandomOrder()->first()?->id,
            'title' => "$brand $model",
            'car_type_id'   => CarType::inRandomOrder()->first()?->id,
            'brand'         => $brand,
            'model'         => $model,
            'year'          => $this->faker->numberBetween(2018, 2024),
            'color'         => $this->faker->randomElement(['White', 'Black', 'Silver', 'Red', 'Blue', 'Gray']),
            'plate_number'  => strtoupper($this->faker->bothify('??-####')),
            'price_per_day' => $this->faker->numberBetween(50, 300),
            'status'        => $this->faker->randomElement(['available', 'booked', 'maintenance']),
            'description'   => $this->faker->text(),
        ];
    }
}
